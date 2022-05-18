<?php

namespace Contexis\Events\Blocks;

class Booking {

	public array $args;
	
	public $blockname = 'details';
    
    public static function init(Assets $assets) {
        
        $instance = new self;
        $instance->args = $assets->args;
		
		add_action('init', [$instance, 'register_block']);
        
        add_action( 'wp_enqueue_scripts', [$instance, 'register_scripts'] );
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
    public function register_scripts() {
        
		$script_asset = require( EM_DIR . "/includes/booking.asset.php" );
		wp_enqueue_script( 
			'booking', 
			plugins_url( '/../includes/booking.js', __FILE__ ),
			$script_asset['dependencies'],
			$script_asset['version'],
			true 
		);
		
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
     * @param array $attribures
     * @return string HTML
	 */
    public static function render($attributes) {
		global $post;
		
		$event = \EM_Event::find($post->id, 'post_id');
		
		add_action( 'wp_enqueue_scripts', function() {
			wp_enqueue_script('booking');
		});

        
		$priceFormatter = new \Contexis\Events\Intl\Price(0);

		$data = [
			'attributes' => $attributes,
			'_nonce' => wp_create_nonce('booking_add'),
			'rest_url' => get_rest_url(),
			'booking_url' => admin_url('admin-ajax.php'),
			'event' => \EM_Events::get_rest(['event' => $event->id])[0],
			'registration_fields' => \EM_Booking_Form::get_fields($event),
			'attendee_fields' => \EM_Attendees_Form::get_fields($event),
			'available_tickets' => $event->get_tickets_rest(),
			'available_gateways' => \EM_Gateways::get_rest(),
			'l10n' => [ 
				"consent" => get_option("dbem_privacy_message"), 
				"currency" => $priceFormatter->get_currency_code(),
				"locale" => str_replace('_', '-',get_locale()),
			]
		];

		$result = Assets::output_to_script_tag($data, 'booking_data');
		$result .= "<div id='booking_app'></div>";
		
        return $result;
    }

}