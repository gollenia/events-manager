<?php

namespace Contexis\Events\Blocks;

class Booking {

	public array $args;
	
	public $blockname = 'details';
    
    public static function init(Assets $assets) {
        
        $instance = new self;
        $instance->args = $assets->args;
		
		add_action('init', [$instance, 'register_block']);
        
        add_action( 'wp_enqueue_scripts', [$instance, 'enqueue_scripts'] );
		//add_action( 'admin_enqueue_scripts', [$instance, 'admin_enqueue_scripts'] );
        
    }

	public function get_block_meta() {
		
		$filename = EM_DIR . "/blocks/src/booking/block.json";
		
		if(!file_exists($filename)) {    
			return false;
		}
		$string = file_get_contents($filename);
		
		return array_merge(json_decode($string, true), $this->args);
		
	}

	function register_block() {	
		$meta = $this->get_block_meta();
		$meta['render_callback'] = [$this,'render'];
		register_block_type($meta['name'], $meta);
	}     

    /**
     * Register Scripts for frontend and add the event data  for booking
     *
     * @return void
     */
    public function enqueue_scripts() {
        global $post;
        $event = em_get_event($post->id, 'post_id');
        
        if (!$event->event_id ) return;

		$script_asset = require( EM_DIR . "/includes/booking.asset.php" );
		wp_enqueue_script( 
			'booking', 
			plugins_url( '/../includes/booking.js', __FILE__ ),
			$script_asset['dependencies'],
			$script_asset['version'],
			true 
		);
		wp_localize_script( 'booking', 'bookingAppData',
		array( 
			'booking_nonce' => wp_create_nonce('booking_add'),
			'rest_nonce' => wp_create_nonce( 'booking_rest' ),
			'rest_url' => get_rest_url(),
			'booking_url' => admin_url('admin-ajax.php'),
			'wp_debug' => WP_DEBUG,
			'event_id' => $event->id,
			'event' => \EM_Events::get_rest(['event' => $event->id])[0],
			'coupons' => [
				'available' => \EM_Coupons::event_has_coupons($event),
				'nonce' => wp_create_nonce('emp_checkout'),
			],
			'fields' => \EM_Booking_Form::get_fields($event),
			'attendee_fields' => \EM_Attendees_Form::get_fields($event),
			'tickets' => $event->get_tickets_rest(),
			'gateways' => \EM_Gateways::get_gateways_rest(),
			'strings' => [ 
				"consent" => function_exists('get_the_privacy_policy_link') ? sprintf(get_option("dbem_data_privacy_consent_text"), get_the_privacy_policy_link()) : get_option("dbem_data_privacy_consent_text"), 
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
		wp_set_script_translations( 'booking', 'events', EM_DIR  . '/languages' );
    
    }

    public function can_book($request) {
        if (WP_DEBUG) {
            return true;
         }
        return wp_verify_nonce($request->get_param('rest_nonce'), 'booking_rest');
    }
   
    /**
     * Return the DOM Element that the booking app mounts on - or not
     *
     * @param EM_Event or Int $EM_Event
     * @return void
     */
    public static function render($event = 0) {
		global $post;
		
        $EM_Event = em_get_event($post->id, 'post_id');
		
        if (count($EM_Event->get_bookings()->get_available_tickets()) == 0) return false;

        return "<div id='booking_app'></div>";
    }

}