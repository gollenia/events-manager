<?php

namespace Contexis\Events;


class Assets {

	public static function init(){
		$instance = new self;
		add_action('init', [$instance, 'frontend_script']);
		add_action('init', [$instance, 'booking_script']);
		add_action('init', [$instance, 'editor_script']);
		add_action('admin_enqueue_scripts', [$instance,'admin_enqueue']);
		return $instance;
	}

	/*
	 * Enqueues script for Upcoming and Featured Blocks
	 */
	public function frontend_script() {

		$script_asset_path = \Events::DIR . "/build/frontend.asset.php";
		$booking_asset_path = \Events::DIR . "/build/booking.asset.php";
		if ( ! file_exists( $script_asset_path ) || ! file_exists( $booking_asset_path ) ) {
			return;
		}
		
		$script_asset = require( $script_asset_path );
		wp_enqueue_script(
			'events-block-frontend',
			plugins_url( '/build/frontend.js', __FILE__ ),
			$script_asset['dependencies'],
			$script_asset['version']
		);

		wp_enqueue_style(
			'events-frontend-style',
			plugins_url( '/build/style-frontend.css', __FILE__ ),
			[],
			$script_asset['version'],
			'all'
		);

		wp_set_script_translations( 'events-block-frontend', 'events', plugin_dir_path( __FILE__ ) . '/languages' );

		wp_localize_script('events-block-frontend', 'eventBlocksLocalization', [
			'locale' => str_replace('_', '-', get_locale()),
			'rest_url' => get_rest_url(null, 'events/v2/events'),
			'current_id' => get_the_ID(),
		]);
	
	}


	/*
	 * Enqueues script for Booking Block
	 */
	public function booking_script() {

		$script_asset_path = \Events::DIR . "/build/booking.asset.php";
		
		if ( ! file_exists( $script_asset_path ) ) return;
		
		$script_asset = require( $script_asset_path );

		wp_register_script(
			'booking-view',
			plugins_url('/build/booking.js', __FILE__),
			$script_asset['dependencies'],
			$script_asset['version'],
			true
		);

		wp_set_script_translations('booking-view', 'events', \Events::DIR  . '/languages');

		wp_register_style(
			'booking-style',
			plugins_url('/build/style-booking.css', __FILE__),
			[],
			$script_asset['version'],
			'all'
		);
	}


	/*
	 * Enqueues script for Editor
	 */
	public function editor_script() {
		
		$script_asset_path = \Events::DIR . "/build/index.asset.php";
		if ( ! file_exists( $script_asset_path ) ) return;
		
		$script_asset = require( $script_asset_path );

		wp_enqueue_script(
			'events-block-editor',
			plugins_url( '/build/index.js', __FILE__ ),
			$script_asset['dependencies'],
			$script_asset['version']
		);

		wp_set_script_translations( 'events-block-editor', 'events', plugin_dir_path( __FILE__ ) . '/languages' );

		wp_localize_script('events-block-editor', 'eventBlocksLocalization', [
			'locale' => str_replace('_', '-',get_locale()),
			'rest_url' => get_rest_url(null, 'events/v2/events'),
			'countries' => \Contexis\Events\Intl\Countries::get(),
			'default_country' => get_option('dbem_location_default_country'),
			'currency' => get_option('dbem_bookings_currency'),
			'bookings_enabled' => \EM_Booking::booking_enabled(),
		]);

		wp_register_style(
			'events-block-style',
			plugins_url( '/build/style-index.css', __FILE__ ),
			array(),
			$script_asset['version']
		);

		wp_register_style(
			'events-block-editor-style',
			plugins_url( '/build/index.css', __FILE__ ),
			array(),
			$script_asset['version']
		);
	}


	
	
	public function admin_enqueue( ){
		wp_enqueue_script('events-manager', plugins_url('/build/events-manager.js',__FILE__), array('jquery', 'jquery-ui-core','jquery-ui-widget','jquery-ui-position','jquery-ui-sortable','jquery-ui-datepicker','jquery-ui-autocomplete','jquery-ui-dialog','wp-color-picker'), \Events::VERSION);		
		wp_enqueue_script('events-admin-script', plugins_url('/build/admin.js',__FILE__), array('jquery', 'wp-api', 'wp-i18n', 'wp-components', 'wp-element' ), \Events::VERSION);		
		wp_enqueue_style('events-admin', plugins_url('/build/admin.css',__FILE__), array('wp-components'), \Events::VERSION);
		wp_enqueue_style('events-admin-booking', plugins_url('/build/style-admin.css',__FILE__), array(), \Events::VERSION);
		$this->localize_admin_script();
		wp_set_script_translations( 'events-admin-script', 'events', plugin_dir_path( __FILE__ ) . '/languages' );
	}

	/**
	 * Localize the script vars that require PHP intervention, removing the need for inline JS.
	 */
	public function localize_admin_script(){
		global $em_localized_js;
		$locale_code = explode( '_', get_locale() );
		//Localize
		$em_localized_js = array(
			'firstDay' => get_option('start_of_week'),
			'_wpnonce' => wp_create_nonce('events'),
			'locale' => $locale_code[0],
			'country' => $locale_code[1],
			'ui_css' => plugins_url('build/jquery-ui.min.css', __FILE__),
			'is_ssl' => is_ssl(),
		);

		//booking-specific stuff
		if( get_option('dbem_rsvp_enabled') ){
		    $em_localized_js = array_merge($em_localized_js, array(
				'bookingInProgress' => __('Please wait while the booking is being submitted.','events'),
				'tickets_save' => __('Save Ticket','events'),
				'bookings_export_save' => __('Export Bookings','events'),
				'bookings_settings_save' => __('Save Settings','events'),
				'booking_delete' => __("Are you sure you want to delete?",'events'),
		    	'booking_offset' => 30,
			));		
		}
		$em_localized_js['cache'] = defined('WP_CACHE') && WP_CACHE;
		$em_localized_js['txt_search'] = get_option('dbem_search_form_text_label',__('Search','events'));
		$em_localized_js['txt_searching'] = __('Searching...','events');
		$em_localized_js['txt_loading'] = __('Loading...','events');
		
		//logged in messages that visitors shouldn't need to see
		if( is_user_logged_in() ){
		    if( get_option('dbem_recurrence_enabled') ){
		    	if( !empty($_REQUEST['action']) && ($_REQUEST['action'] == 'edit' || $_REQUEST['action'] == 'event_save') && !empty($_REQUEST['event_id']) ){
					$em_localized_js['event_reschedule_warning'] = __('Are you sure you want to continue?', 'events') .PHP_EOL;
					$em_localized_js['event_reschedule_warning'] .= __('Modifications to event times will cause all recurrences of this event to be deleted and recreated, previous bookings will be deleted.', 'events');
					$em_localized_js['event_recurrence_overwrite'] = __('Are you sure you want to continue?', 'events') .PHP_EOL;
					$em_localized_js['event_recurrence_overwrite'] .= __( 'Modifications to recurring events will be applied to all recurrences and will overwrite any changes made to those individual event recurrences.', 'events') .PHP_EOL;
					$em_localized_js['event_recurrence_overwrite'] .= __( 'Bookings to individual event recurrences will be preserved if event times and ticket settings are not modified.', 'events');
					$em_localized_js['event_recurrence_bookings'] = __('Are you sure you want to continue?', 'events') .PHP_EOL;
					$em_localized_js['event_recurrence_bookings'] .= __('Modifications to event tickets will cause all bookings to individual recurrences of this event to be deleted.', 'events');
		    	}
				$em_localized_js['event_detach_warning'] = __('Are you sure you want to detach this event? By doing so, this event will be independent of the recurring set of events.', 'events');
				$delete_text = ( !EMPTY_TRASH_DAYS ) ? __('This cannot be undone.','events'):__('All events will be moved to trash.','events');
				$em_localized_js['delete_recurrence_warning'] = __('Are you sure you want to delete all recurrences of this event?', 'events').' '.$delete_text;
		    }
			if( get_option('dbem_rsvp_enabled') ){
				$em_localized_js['disable_bookings_warning'] = __('Are you sure you want to disable bookings? If you do this and save, you will lose all previous bookings. If you wish to prevent further bookings, reduce the number of spaces available to the amount of bookings you currently have', 'events');
			}
		}
		//load admin/public only vars
		if( is_admin() ){
			$em_localized_js['event_post_type'] = EM_POST_TYPE_EVENT;
			$em_localized_js['location_post_type'] = EM_POST_TYPE_LOCATION;
			if( !empty($_GET['page']) && $_GET['page'] == 'events-options' ){
			    $em_localized_js['close_text'] = __('Collapse All','events');
			    $em_localized_js['open_text'] = __('Expand All','events');
			}
		}		
		
		wp_localize_script('events-manager','EM', apply_filters('em_wp_localize_script', $em_localized_js));
	}
}
Assets::init();

