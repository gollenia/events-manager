<?php

/**
 * Register REST-Route for React Booking form.
 * added by Thomas Gollenia
 */
class EM_Booking_Api {
    
    public static function init() {
        
        $instance = new self;
        add_action('rest_api_init', [$instance, 'register_rest_route']);
        add_action('rest_error', [$instance, 'register_rest_error']);
        add_action( 'the_post', [$instance, 'post_object_created'] );
        
    }

    /**
     * We need a moment when the Post Object is created, but wp_enqueu_scripts is not executed.
     *
     * @return void
     */
    public function post_object_created() {
        global $post;
        $event = em_get_event($post->id, 'post_id');
        
        if (!$event->event_id ) return;

        add_action( 'wp_enqueue_scripts', function () use ($event){
            $script_asset = require( __DIR__ . "/../../includes/booking-form.asset.php" );
            wp_enqueue_script( 
                'booking_app', 
                plugins_url( '../../includes/booking-form.js', __FILE__ ),
                $script_asset['dependencies'],
                $script_asset['version'],
                true 
            );
            wp_localize_script( 'booking_app', 'bookingAppData',
            array( 
                'booking_nonce' => wp_create_nonce('booking_add'),
                'rest_nonce' => wp_create_nonce( 'booking_rest' ),
                'rest_url' => get_rest_url(),
                'booking_url' => admin_url('admin-ajax.php'),
                'wp_debug' => WP_DEBUG,
                'event_id' => $event->id,
                'event' => array_filter((array)$event, [$this, 'filter_event'], ARRAY_FILTER_USE_KEY),
                'coupons' => [
                    'available' => EM_Coupons::event_has_coupons($event),
                    'nonce' => wp_create_nonce('emp_checkout'),
                ],
                'fields' => $this->filter_fields(EM_Booking_Form::get_form($event)->form_fields),
                'attendee_fields' => $this->filter_fields(EM_Attendees_Form::get_form($event)->form_fields),
                'tickets' => $this->filter_tickets((array)$event->get_bookings()->get_available_tickets()->tickets),
                'tickets_raw' => $event->get_bookings()->get_available_tickets()->tickets,
                'gateways' => $this->get_gateways(),
                'strings' => [ 
                    "consent" => function_exists('get_the_privacy_policy_link') ? sprintf(get_option("dbem_data_privacy_consent_text"), get_the_privacy_policy_link()) : get_option("dbem_data_privacy_consent_text"), 
                    "date_format" => get_option("dbem_date_format"),
                    "pay_with" => get_option('dbem_gateway_label'),
                    "book_now" => get_option("dbem_bookings_submit_button"),
                    "time_format" => get_option("dbem_time_format"),
                    "allday" => get_option("dbem_event_all_day_message"),
                    "currency" => em_get_currency_symbol(true,get_option("dbem_bookings_currency")),
                    "modal_button" => get_option("dbem_booking_button_msg_book"),
                    "loading" => get_option("dbem_booking_button_msg_booking"),
                    "dont_close" => get_option("dbem_booking_button_msg_booked")
                ]
            ));
            wp_set_script_translations( 'booking_app', 'em-pro', plugin_dir_path( __FILE__ ) . '../../languages' );
        }, 10 );
    }

    public function register_rest_route() {
         register_rest_route('events-manager/v2', '/booking', ['method' => 'GET', 'callback' => [$this, 'get_booking_data'], 'permission_callback' => [$this, 'can_book']]);
    }

    public function register_rest_error() {
        register_rest_route('events-manager/v2', '/error', ['method' => 'GET', 'callback' => [$this, 'send_error'], 'permission_callback' => function() {return true;}]);
   }


    /**
     * Get all required data for booking. This can be used later for displaying the event with React aswell
     *
     * @param WP_REST_Request $request
     * @return array JSON Response
     */
    public function get_booking_data(WP_REST_Request $request) {
        
        $EM_Event = em_get_event($request->get_param( 'event_id' ));
        return [
            'event' => array_filter((array)$EM_Event, [$this, 'filter_event'], ARRAY_FILTER_USE_KEY),
            'coupons' => [
                'available' => EM_Coupons::event_has_coupons($EM_Event),
                'nonce' => wp_create_nonce('emp_checkout'),
            ],
            'fields' => $this->filter_fields(EM_Booking_Form::get_form($EM_Event)->form_fields),
            'attendee_fields' => $this->filter_fields(EM_Attendees_Form::get_form($EM_Event)->form_fields),
            'tickets' => $this->filter_tickets((array)$EM_Event->get_bookings()->get_available_tickets()->tickets),
            'tickets_raw' => $EM_Event->get_bookings()->get_available_tickets()->tickets,
            'gateways' => $this->get_gateways(),
            'strings' => [ 
                "consent" => function_exists('get_the_privacy_policy_link') ? sprintf(get_option("dbem_data_privacy_consent_text"), get_the_privacy_policy_link()) : get_option("dbem_data_privacy_consent_text"), 
                "date_format" => get_option("dbem_date_format"),
                "pay_with" => get_option('dbem_gateway_label'),
                "book_now" => get_option("dbem_bookings_submit_button"),
                "time_format" => get_option("dbem_time_format"),
                "allday" => get_option("dbem_event_all_day_message"),
                "currency" => em_get_currency_symbol(true,get_option("dbem_bookings_currency")),
                "modal_button" => get_option("dbem_booking_button_msg_book"),
                "loading" => get_option("dbem_booking_button_msg_booking"),
                "dont_close" => get_option("dbem_booking_button_msg_booked")
            ]
        ];  
    }

    public function send_error(WP_REST_Request $request) {
        wp_mail(get_option('admin_email'), "An error occured in the event booking form", $request->get_param('error'));
    }

    public function filter_event(string $key) {
        return in_array($key, [
            'event_id', 'post_id', 'event_slug', 'event_name', 'start_date', 'end_date'
        ]);
    }

    public function filter_tickets(array $tickets) {
        $ticket_collection = [];
        foreach($tickets as $id => $ticket) {
            array_push($ticket_collection, [
                "id" => $id,
                "event_id" => $ticket->event_id,
                "is_available" => $ticket->is_available,
                "max" => intval($ticket->ticket_max ? min( $ticket->ticket_spaces, $ticket->ticket_max ) : $ticket->ticket_spaces),
                "price" => floatval($ticket->ticket_price),
                "min" => $ticket->ticket_min ?: 0,
                "name" => $ticket->ticket_name,
                "description" => $ticket->ticket_description,
                "fields" => []
            ]);
        }
        return $ticket_collection;
    }

    public function can_book($request) {
        if (WP_DEBUG) {
            return true;
         }
        return wp_verify_nonce($request->get_param('rest_nonce'), 'booking_rest');
    }

    public function filter_fields(array $fields) {
        $fieldset = [];
        foreach ($fields as $key => $field) {
            $input_field = [
                "name" => $field['fieldid'],
                "type" => $field['type'],
                "label" => $field['label'],
                "value" => "",
                "min" => false,
                "max" => false,
                "regex" => array_key_exists('options_text_regex', $field) ? $field['options_text_regex'] : "",
                "required" => array_key_exists('required', $field) && $field['required'] == 1 ? true : false,
                "error" => array_key_exists('options_text_error', $field) ? $field['options_text_error'] : "",
                "tip" => array_key_exists('options_text_tip', $field) ? $field['options_text_tip'] : "",
                "select_hint" => array_key_exists('options_select_default_text', $field) ? $field['options_select_default_text'] : "",
                "options" => [],
                "_full" => $field
            ];
            // This is very unfancy.
            switch ($field['type']) {
				case 'html':
					$input_field['value'] = $field['options_html_content'];
					break;
                case 'checkbox':
                    $input_field['value'] = $field['options_checkbox_checked'];
                    $input_field['error'] = $field['options_checkbox_error'];
                    $input_field['tip'] = $field['options_checkbox_tip'];
                    break;
                case 'date':
                    $input_field['value'] = $field['options_checkbox_checked'];
                    //$input_field['error'] = $field['options_date_min_error'] ?: $field['options_date_max_error'];
                    $input_field['tip'] = $field['options_date_tip'];
                    $input_field['min'] = $field['options_date_min'];
                    $input_field['max'] = $field['options_date_max'];
                    break;
                case 'select':
                    $input_field['value'] = $field['options_select_default'];
                    $input_field['error'] = $field['options_select_error'];
                    $input_field['tip'] = $field['options_select_tip'];
                    $input_field['options'] = explode("\r\n", $field['options_select_values']);
                    break;
                case 'radio':
                    $input_field['error'] = $field['options_selection_error'];
                    $input_field['tip'] = $field['options_selection_tip'];
                    $input_field['options'] = explode("\r\n", $field['options_selection_values']);
                    break;
                case 'user_email':
                    $input_field['type'] = "email";
                    break;
                case 'country':
                    $input_field['type'] = "select";
                    $input_field['options'] = em_get_countries();
                    $input_field['value'] = substr(get_locale(), -2);

                }

            array_push($fieldset, $input_field);
        }
        return $fieldset;
    }

    public function get_gateways() {
        global $EM_Gateways;
		$gateways = array();
		foreach($EM_Gateways as $EM_Gateway){
			if($EM_Gateway->is_active()){
				array_push($gateways, [
                    "name" => $EM_Gateway->title,
                    "id" => $EM_Gateway->gateway,
                    "title" => get_option('em_'.$EM_Gateway->gateway.'_option_name'),
                    "html" => get_option('em_'.$EM_Gateway->gateway.'_form'),
                    "methods" => $EM_Gateway->gateway == "mollie" ? get_option('mollie_activated_methods') : []
                ]);
			}
		}
		return $gateways; 
    }

    /**
     * Return the DOM Element that the booking app mounts on - or not
     *
     * @param EM_Event or Int $EM_Event
     * @return void
     */
    public static function get_booking_form($event = 0) {

        if (!$event) return false;

        if(!is_object($event)) $event = em_get_event($event);

        if (count($event->get_bookings()->get_available_tickets()) == 0) return false;

        return "<div id='booking_app'></div>";
    }

}

EM_Booking_Api::init();