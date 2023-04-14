<?php

namespace Contexis\Events;


class Assets {

	static array $args = [
		'style'         => 'event-manager-blocks-block-style',
		'editor_script' => 'events-block-editor',
		'editor_style'  => 'events-block-style',
	];

	public function frontend_script() {
		$script_asset_path = \Events::DIR . "/includes/frontend.asset.php";
		if ( ! file_exists( $script_asset_path ) ) {
			throw new \Error(
					'You need to run `npm start` or `npm run build` for the events-blocks first.'
			);
		}
		$index_js     = '../includes/frontend.js';
		$script_asset = require( $script_asset_path );
		wp_enqueue_script(
			'events-block-frontend',
			plugins_url( $index_js, __FILE__ ),
			$script_asset['dependencies'],
			$script_asset['version']
		);
		wp_set_script_translations( 'events-block-frontend', 'events', plugin_dir_path( __FILE__ ) . '../languages' );

		wp_localize_script('events-block-frontend', 'eventBlocksLocalization', [
			'locale' => str_replace('_', '-',get_locale()),
			'rest_url' => get_rest_url(null, 'events/v2/events'),
			'current_id' => get_the_ID(),
			'post_type' => self::get_post_type()
		]);
	}

	public function backend_script() {
		
		$script_asset_path = \Events::DIR . "/includes/backend.asset.php";
		if ( ! file_exists( $script_asset_path ) ) {
			throw new \WP_Error(
				'You need to run `npm start` or `npm run build` for the "create-block/ctx-blocks" block first.'
			);
		}
		$index_js = '../includes/backend.js';
		$script_asset = require( $script_asset_path );
		wp_register_script(
			'events-block-editor',
			plugins_url( $index_js, __FILE__ ),
			$script_asset['dependencies'],
			$script_asset['version']
		);
		wp_set_script_translations( 'events-block-editor', 'events', plugin_dir_path( __FILE__ ) . '../languages' );

		wp_localize_script('events-block-editor', 'eventBlocksLocalization', [
			'locale' => str_replace('_', '-',get_locale()),
			'rest_url' => get_rest_url(null, 'events/v2/events'),
			'current_id' => get_the_ID(),
			'post_type' => self::get_post_type(),
			'countries' => \Contexis\Events\Intl\Countries::get(),
			'default_country' => get_option('dbem_location_default_country'),
		]);
		
		$editor_css = '../includes/backend.css';

		wp_register_style(
			'events-block-style',
			plugins_url( $editor_css, __FILE__ ),
			array(),
			$script_asset['version']
		);
	}

	public static function get_post_type () {

		if(get_post_type()) return get_post_type();

		if ( isset( $_REQUEST['post_type'] ) && post_type_exists( $_REQUEST['post_type'] ) ) {
			return $_REQUEST['post_type'];
		};

		if(!function_exists('get_current_screen')) return 'post';

		$screen = get_current_screen();
		if(isset($screen->post_type)) return $screen->post_type;

		
		return '';
	}

	public static function output_to_script_tag($output, $variable_name) {
		return sprintf('<script>%s = %s;</script>', $variable_name, json_encode($output));
	}

	public static function init(){
		$instance = new self;
		if( !is_admin()) add_action('wp_enqueue_scripts', [$instance, 'frontend_script']);
		add_action('admin_enqueue_scripts', [$instance, 'backend_script']);
		add_action('admin_enqueue_scripts', [$instance,'admin_enqueue']);
		return $instance;
	}
	
	public function admin_enqueue( $hook_suffix = false ){
		wp_enqueue_script('events-manager', plugins_url('../includes/events-manager.js',__FILE__), array('jquery', 'jquery-ui-core','jquery-ui-widget','jquery-ui-position','jquery-ui-sortable','jquery-ui-datepicker','jquery-ui-autocomplete','jquery-ui-dialog','wp-color-picker'), \Events::VERSION);		
		wp_enqueue_script('events-manager-admin-script', plugins_url('../includes/admin.js',__FILE__), array('jquery'), \Events::VERSION);		
		wp_enqueue_style('events-manager-admin', plugins_url('../includes/admin.css',__FILE__), array(), \Events::VERSION);
		//wp_enqueue_style('events-manager-pro-admin', plugins_url('includes/events-manager-pro.css',__FILE__), array(), \Events::VERSION);
		$this->localize_admin_script();
	}

	/**
	 * Localize the script vars that require PHP intervention, removing the need for inline JS.
	 */
	public function localize_admin_script(){
		global $em_localized_js;
		$locale_code = substr ( get_locale(), 0, 2 );
		//Localize
		$em_localized_js = array(
			'ajaxurl' => admin_url('admin-ajax.php'),
			'locationajaxurl' => admin_url('admin-ajax.php?action=locations_search'),
			'firstDay' => get_option('start_of_week'),
			'locale' => $locale_code,
			'ui_css' => plugins_url('includes/jquery-ui.min.css', __FILE__),
			'is_ssl' => is_ssl(),
		);

		//booking-specific stuff
		if( get_option('dbem_rsvp_enabled') ){
		    $em_localized_js = array_merge($em_localized_js, array(
				'bookingInProgress' => __('Please wait while the booking is being submitted.','events-manager'),
				'tickets_save' => __('Save Ticket','events-manager'),
				'bookings_export_save' => __('Export Bookings','events-manager'),
				'bookings_settings_save' => __('Save Settings','events-manager'),
				'booking_delete' => __("Are you sure you want to delete?",'events-manager'),
		    	'booking_offset' => 30,
			));		
		}
		$em_localized_js['cache'] = defined('WP_CACHE') && WP_CACHE;
		$em_localized_js['txt_search'] = get_option('dbem_search_form_text_label',__('Search','events-manager'));
		$em_localized_js['txt_searching'] = __('Searching...','events-manager');
		$em_localized_js['txt_loading'] = __('Loading...','events-manager');
		
		//logged in messages that visitors shouldn't need to see
		if( is_user_logged_in() ){
		    if( get_option('dbem_recurrence_enabled') ){
		    	if( !empty($_REQUEST['action']) && ($_REQUEST['action'] == 'edit' || $_REQUEST['action'] == 'event_save') && !empty($_REQUEST['event_id']) ){
					$em_localized_js['event_reschedule_warning'] = __('Are you sure you want to continue?', 'events-manager') .PHP_EOL;
					$em_localized_js['event_reschedule_warning'] .= __('Modifications to event times will cause all recurrences of this event to be deleted and recreated, previous bookings will be deleted.', 'events-manager');
					$em_localized_js['event_recurrence_overwrite'] = __('Are you sure you want to continue?', 'events-manager') .PHP_EOL;
					$em_localized_js['event_recurrence_overwrite'] .= __( 'Modifications to recurring events will be applied to all recurrences and will overwrite any changes made to those individual event recurrences.', 'events-manager') .PHP_EOL;
					$em_localized_js['event_recurrence_overwrite'] .= __( 'Bookings to individual event recurrences will be preserved if event times and ticket settings are not modified.', 'events-manager');
					$em_localized_js['event_recurrence_bookings'] = __('Are you sure you want to continue?', 'events-manager') .PHP_EOL;
					$em_localized_js['event_recurrence_bookings'] .= __('Modifications to event tickets will cause all bookings to individual recurrences of this event to be deleted.', 'events-manager');
		    	}
				$em_localized_js['event_detach_warning'] = __('Are you sure you want to detach this event? By doing so, this event will be independent of the recurring set of events.', 'events-manager');
				$delete_text = ( !EMPTY_TRASH_DAYS ) ? __('This cannot be undone.','events-manager'):__('All events will be moved to trash.','events-manager');
				$em_localized_js['delete_recurrence_warning'] = __('Are you sure you want to delete all recurrences of this event?', 'events-manager').' '.$delete_text;
		    }
			if( get_option('dbem_rsvp_enabled') ){
				$em_localized_js['disable_bookings_warning'] = __('Are you sure you want to disable bookings? If you do this and save, you will lose all previous bookings. If you wish to prevent further bookings, reduce the number of spaces available to the amount of bookings you currently have', 'events-manager');
			}
		}
		//load admin/public only vars
		if( is_admin() ){
			$em_localized_js['event_post_type'] = EM_POST_TYPE_EVENT;
			$em_localized_js['location_post_type'] = EM_POST_TYPE_LOCATION;
			if( !empty($_GET['page']) && $_GET['page'] == 'events-manager-options' ){
			    $em_localized_js['close_text'] = __('Collapse All','events-manager');
			    $em_localized_js['open_text'] = __('Expand All','events-manager');
			}
		}		
		
		wp_localize_script('events-manager','EM', apply_filters('em_wp_localize_script', $em_localized_js));
	}
}
Assets::init();

