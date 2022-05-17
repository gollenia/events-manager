<?php
/*
Plugin Name: Events
Version: 6.0
Plugin URI: https://github.com/gollenia/events-manager
Description: Event registration and booking management for WordPress. Recurring events, locations, webinars, google maps, rss, ical, booking registration and more!
Author: Marcus Sykes, Thomas Gollenia
Author URI: https://github.com/gollenia/events-manager
Text Domain: events
*/

/*
Copyright (c) 2021, Marcus Sykes

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

// Setting constants

define('EM_VERSION', 6.3); //self expanatory, although version currently may not correspond directly with published version number
define('EM_DIR', dirname( __FILE__ )); //an absolute path to this directory
define('EM_DIR_URI', trailingslashit(plugins_url('',__FILE__))); //an absolute path to this directory
define('EM_MS_GLOBAL',false);

//temporarily disable AJAX by default, future updates will eventually have this turned on as we work out some kinks
if( !defined('EM_AJAX') ){
	define( 'EM_AJAX', get_option('dbem_events_page_ajax', (defined('EM_AJAX_SEARCH') && EM_AJAX_SEARCH)) );
}

require_once( plugin_dir_path( __FILE__ ) . '/vendor/autoload.php');

require_once('classes/em-twig.php');

$EM_Twig = EM_Twig::init();


add_filter( 'timber/locations', function($paths) use ($EM_Twig) {
	$paths[] = $EM_Twig->locations;
	return $paths;
});

add_action('init', function() {
	register_rest_field( 'event', 'meta', array(
		'get_callback' => function ( $data ) {
			return get_post_meta( $data['id'], '', '' );
		}, ));
});




// INCLUDES
//Base classes
require_once('polyfill.php');
require_once('classes/em-options.php');
require_once('classes/em-object.php');
require_once('classes/em-datetime.php');
require_once('classes/em-datetimezone.php');
require_once('classes/em-taxonomy-term.php');
require_once('classes/em-taxonomy-terms.php');
require_once('classes/em-taxonomy-frontend.php');
//set up events as posts
require_once("em-posts.php");
//Template Tags & Template Logic
require_once("em-actions.php");
//require_once("em-emails.php");
require_once("em-functions.php");
require_once("em-ical.php");
require_once("em-data-privacy.php");
require_once("multilingual/em-ml.php");

//Classes
require_once('classes/em-booking.php');
require_once('classes/em-bookings.php');
require_once("classes/em-bookings-table.php") ;
//require_once('classes/em-calendar.php');
require_once('classes/em-category.php');
require_once('classes/em-categories.php');
require_once('classes/em-categories-frontend.php');
require_once('classes/Events/Event.php');
require_once('classes/Locations/EventLocations.php');
require_once('classes/Events/EventPost.php');
require_once('classes/Events/Events.php');
require_once('classes/Locations/Location.php');
require_once('classes/Locations/LocationPost.php');
require_once('classes/Locations/Locations.php');
require_once("classes/em-mailer.php") ;
require_once('classes/em-notices.php');
require_once('classes/em-people.php');
require_once('classes/em-person.php');
require_once('classes/em-permalinks.php');
require_once('classes/em-speakers.php');

require_once('classes/em-tag.php');
require_once('classes/em-tags.php');
require_once('classes/em-tags-frontend.php');
require_once('classes/em-ticket-booking.php');
require_once('classes/em-ticket.php');
require_once('classes/em-tickets-bookings.php');
require_once('classes/em-tickets.php');
//Admin Files
if( is_admin() ){
	require_once('admin/em-admin.php');
	require_once('admin/em-bookings.php');
	require_once('admin/em-docs.php');
	require_once('admin/em-help.php');
	require_once('admin/em-options.php');
	require_once('admin/em-data-privacy.php');

	//post/taxonomy controllers
	require_once('classes/Events/EventPostAdmin.php');
	require_once('classes/Events/EventPostsAdmin.php');
	require_once('classes/Locations/LocationPostAdmin.php');
	require_once('classes/Locations/LocationPostsAdmin.php');
	require_once('classes/em-taxonomy-admin.php');
	require_once('classes/em-categories-admin.php');
	require_once('classes/em-tags-admin.php');
	//bookings folder
	require_once('admin/bookings/em-cancelled.php');
	require_once('admin/bookings/em-confirmed.php');
	require_once('admin/bookings/em-events.php');
	require_once('admin/bookings/em-rejected.php');
	require_once('admin/bookings/em-pending.php');
	require_once('admin/bookings/em-person.php');
}

// new namespaced classes
require_once('classes/speaker.php');
require_once('add-ons/export/Export.php');

require_once('emp-forms.php'); //form editor
		
//require_once('emp-ml.php');


//booking-specific features
require_once('add-ons/gateways/gateways.php'); 
require_once('add-ons/bookings-form/bookings-form.php');

require_once('add-ons/coupons/coupons.php');
require_once('add-ons/emails/emails.php');
require_once('add-ons/user-fields.php');

//This should come into a "Migration" class
//Table names
global $wpdb;
$prefix = $wpdb->prefix;
define('EM_EVENTS_TABLE',$prefix.'em_events'); //TABLE NAME
define('EM_TICKETS_TABLE', $prefix.'em_tickets'); //TABLE NAME
define('EM_TICKETS_BOOKINGS_TABLE', $prefix.'em_tickets_bookings'); //TABLE NAME
define('EM_META_TABLE',$prefix.'em_meta'); //TABLE NAME
define('EM_RECURRENCE_TABLE',$prefix.'dbem_recurrence'); //TABLE NAME
define('EM_LOCATIONS_TABLE',$prefix.'em_locations'); //TABLE NAME
define('EM_BOOKINGS_TABLE',$prefix.'em_bookings'); //TABLE NAME
define('EM_TRANSACTIONS_TABLE', $wpdb->prefix.'em_transactions'); //TABLE NAME
define('EM_EMAIL_QUEUE_TABLE', $wpdb->prefix.'em_email_queue'); //TABLE NAME
define('EM_COUPONS_TABLE', $wpdb->prefix.'em_coupons'); //TABLE NAME
define('EM_BOOKINGS_RELATIONSHIPS_TABLE', $wpdb->prefix.'em_bookings_relationships'); //TABLE NAME

/**
 * @author marcus
 * Contains functions for loading styles on both admin and public sides.
 */
class EM_Scripts_and_Styles {
	public static function init(){
		if( is_admin() ){
			//Scripts and Styles
			add_action('admin_enqueue_scripts', array('EM_Scripts_and_Styles','admin_enqueue'));
		}
	}
	
	public static function admin_enqueue( $hook_suffix = false ){
		if( $hook_suffix == 'post.php' || (!empty($_GET['page']) && substr($_GET['page'],0,14) == 'events-manager') || (!empty($_GET['post_type']) && in_array($_GET['post_type'], array(EM_POST_TYPE_EVENT,EM_POST_TYPE_LOCATION,'event-recurring'))) ){
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script('events-manager', plugins_url('includes/events-manager.js',__FILE__), array('jquery', 'jquery-ui-core','jquery-ui-widget','jquery-ui-position','jquery-ui-sortable','jquery-ui-datepicker','jquery-ui-autocomplete','jquery-ui-dialog','wp-color-picker'), EM_VERSION);
		    global $pagenow;
			if( !empty($_REQUEST['page']) && ($_REQUEST['page'] == 'events-manager-forms-editor' || ($_REQUEST['page'] == 'events-manager-bookings' && !empty($_REQUEST['action']) && $_REQUEST['action'] == 'manual_booking')) ){
				wp_enqueue_script('events-manager-pro', plugins_url('includes/events-manager-pro.js',__FILE__), array('jquery', 'jquery-ui-core','jquery-ui-widget','jquery-ui-position')); //jQuery will load as dependency
				do_action('em_enqueue_admin_scripts');
			}
			if( $pagenow == 'user-edit.php' ){
				//need to include the em script for dates
				EM_Scripts_and_Styles::admin_enqueue();
			}
			wp_enqueue_style('events-manager-admin', plugins_url('includes/admin-settings.css',__FILE__), array(), EM_VERSION);
			wp_enqueue_style('events-manager-pro', plugins_url('includes/events-manager-pro.css',__FILE__), array(), EM_VERSION);
			do_action('em_enqueue_admin_styles');
			wp_enqueue_style('events-manager-pro-admin', plugins_url('includes/events-manager-pro.css',__FILE__), array(), EM_VERSION);
			self::localize_script();
		}
	}

	/**
	 * Localize the script vars that require PHP intervention, removing the need for inline JS.
	 */
	public static function localize_script(){
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
			$offset = defined('EM_BOOKING_MSG_JS_OFFSET') ? EM_BOOKING_MSG_JS_OFFSET : 30;
		    $em_localized_js = array_merge($em_localized_js, array(
				'bookingInProgress' => __('Please wait while the booking is being submitted.','events-manager'),
				'tickets_save' => __('Save Ticket','events-manager'),
				'bookings_export_save' => __('Export Bookings','events-manager'),
				'bookings_settings_save' => __('Save Settings','events-manager'),
				'booking_delete' => __("Are you sure you want to delete?",'events-manager'),
		    	'booking_offset' => $offset,
				//booking button
				

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
EM_Scripts_and_Styles::init();


/**
 * Perform plugins_loaded actions
 */
function em_plugins_loaded(){
	//Capabilities
	global $em_capabilities_array;
	$em_capabilities_array = apply_filters('em_capabilities_array', array(
		/* Booking Capabilities */
		'manage_others_bookings' => sprintf(__('You do not have permission to manage others %s','events-manager'),__('bookings','events-manager')),
		'manage_bookings' => sprintf(__('You do not have permission to manage %s','events-manager'),__('bookings','events-manager')),
		/* Event Capabilities */
		'publish_events' => sprintf(__('You do not have permission to publish %s','events-manager'),__('events','events-manager')),
		'delete_others_events' => sprintf(__('You do not have permission to delete others %s','events-manager'),__('events','events-manager')),
		'delete_events' => sprintf(__('You do not have permission to delete %s','events-manager'),__('events','events-manager')),
		'edit_others_events' => sprintf(__('You do not have permission to edit others %s','events-manager'),__('events','events-manager')),
		'edit_events' => sprintf(__('You do not have permission to edit %s','events-manager'),__('events','events-manager')),
		'read_private_events' => sprintf(__('You cannot read private %s','events-manager'),__('events','events-manager')),
		/*'read_events' => sprintf(__('You cannot view %s','events-manager'),__('events','events-manager')),*/
		/* Recurring Event Capabilties */
		'publish_recurring_events' => sprintf(__('You do not have permission to publish %s','events-manager'),__('recurring events','events-manager')),
		'delete_others_recurring_events' => sprintf(__('You do not have permission to delete others %s','events-manager'),__('recurring events','events-manager')),
		'delete_recurring_events' => sprintf(__('You do not have permission to delete %s','events-manager'),__('recurring events','events-manager')),
		'edit_others_recurring_events' => sprintf(__('You do not have permission to edit others %s','events-manager'),__('recurring events','events-manager')),
		'edit_recurring_events' => sprintf(__('You do not have permission to edit %s','events-manager'),__('recurring events','events-manager')),
		/* Location Capabilities */
		'publish_locations' => sprintf(__('You do not have permission to publish %s','events-manager'),__('locations','events-manager')),
		'delete_others_locations' => sprintf(__('You do not have permission to delete others %s','events-manager'),__('locations','events-manager')),
		'delete_locations' => sprintf(__('You do not have permission to delete %s','events-manager'),__('locations','events-manager')),
		'edit_others_locations' => sprintf(__('You do not have permission to edit others %s','events-manager'),__('locations','events-manager')),
		'edit_locations' => sprintf(__('You do not have permission to edit %s','events-manager'),__('locations','events-manager')),
		'read_private_locations' => sprintf(__('You cannot read private %s','events-manager'),__('locations','events-manager')),
		'read_others_locations' => sprintf(__('You cannot view others %s','events-manager'),__('locations','events-manager')),
		/*'read_locations' => sprintf(__('You cannot view %s','events-manager'),__('locations','events-manager')),*/
		/* Category Capabilities */
		'delete_event_categories' => sprintf(__('You do not have permission to delete %s','events-manager'),__('categories','events-manager')),
		'edit_event_categories' => sprintf(__('You do not have permission to edit %s','events-manager'),__('categories','events-manager')),
		/* Upload Capabilities */
		'upload_event_images' => __('You do not have permission to upload images','events-manager')
	));

}
add_filter('plugins_loaded','em_plugins_loaded');

/**
 * Perform init actions
 */
function em_init(){
	//Hard Links
	global $EM_Mailer, $wp_rewrite;
	
	if( $wp_rewrite->using_permalinks() ){
		define('EM_URI', trailingslashit(home_url()). EM_POST_TYPE_EVENT_SLUG.'/'); //PAGE URI OF EM
	}else{
		define('EM_URI', trailingslashit(home_url()).'?post_type='.EM_POST_TYPE_EVENT); //PAGE URI OF EM
	}
	
	if( $wp_rewrite->using_permalinks() ){
		$rss_url = trailingslashit(home_url()). EM_POST_TYPE_EVENT_SLUG.'/feed/';
		define('EM_RSS_URI', $rss_url); //RSS PAGE URI via CPT archives page
	}else{
		$rss_url = em_add_get_params(home_url(), array('post_type'=>EM_POST_TYPE_EVENT, 'feed'=>'rss2'));
		define('EM_RSS_URI', $rss_url); //RSS PAGE URI
	}
	$EM_Mailer = new EM_Mailer();
	//Upgrade/Install Routine
	if( is_admin() && current_user_can('manage_options') ){
		if( EM_VERSION > get_option('dbem_version', 0) || (is_multisite() && !EM_MS_GLOBAL && get_option('em_ms_global_install')) ){
			require_once( dirname(__FILE__).'/em-install.php');
			em_install();
		}
	}
	//add custom functions.php file
	locate_template('plugins/events-manager/functions.php', true);
	//fire a loaded hook, most plugins should consider going through here to load anything EM related
	do_action('events_manager_loaded');
}
add_filter('init','em_init',1);

/**
 * This function will load an event into the global $EM_Event variable during page initialization, provided an event_id is given in the url via GET or POST.
 * global $EM_Recurrences also holds global array of recurrence objects when loaded in this instance for performance
 * All functions (admin and public) can now work off this object rather than it around via arguments.
 * @return null
 */
function em_load_event(){
	global $EM_Event, $EM_Recurrences, $EM_Location, $EM_Person, $EM_Booking, $EM_Category, $EM_Ticket, $current_user;
	if( !defined('EM_LOADED') ){
		$EM_Recurrences = array();
		if( isset( $_REQUEST['event_id'] ) && is_numeric($_REQUEST['event_id']) && !is_object($EM_Event) ){
			$EM_Event = new EM_Event( absint($_REQUEST['event_id']) );
		}elseif( isset($_REQUEST['post']) && (get_post_type($_REQUEST['post']) == 'event' || get_post_type($_REQUEST['post']) == 'event-recurring') ){
			$EM_Event = EM_Event::find($_REQUEST['post'], 'post_id');
		}elseif ( !empty($_REQUEST['event_slug']) && EM_MS_GLOBAL && is_main_site() && !get_site_option('dbem_ms_global_events_links')) {
			// single event page for a subsite event being shown on the main blog
			global $wpdb;
			$matches = array();
			if( preg_match('/\-([0-9]+)$/', $_REQUEST['event_slug'], $matches) ){
				$event_id = $matches[1];
			}else{
				$query = $wpdb->prepare('SELECT event_id FROM '.EM_EVENTS_TABLE.' WHERE event_slug = %s AND blog_id != %d', $_REQUEST['event_slug'], get_current_blog_id());
				$event_id = $wpdb->get_var($query);
			}
			$EM_Event = EM_Event::find($event_id);
		}
		if( isset($_REQUEST['location_id']) && is_numeric($_REQUEST['location_id']) && !is_object($EM_Location) ){
			$EM_Location = new EM_Location( absint($_REQUEST['location_id']) );
		}elseif( isset($_REQUEST['post']) && get_post_type($_REQUEST['post']) == 'location' ){
			$EM_Location = em_get_location($_REQUEST['post'], 'post_id');
		}elseif ( !empty($_REQUEST['location_slug']) && EM_MS_GLOBAL && is_main_site() && !get_site_option('dbem_ms_global_locations_links')) {
			// single event page for a subsite event being shown on the main blog
			global $wpdb;
			$matches = array();
			if( preg_match('/\-([0-9]+)$/', $_REQUEST['location_slug'], $matches) ){
				$location_id = $matches[1];
			}else{
				$query = $wpdb->prepare('SELECT location_id FROM '.EM_LOCATIONS_TABLE." WHERE location_slug = %s AND blog_id != %d", $_REQUEST['location_slug'], get_current_blog_id());
				$location_id = $wpdb->get_var($query);
			}
			$EM_Location = em_get_location($location_id);
		}
		if( is_user_logged_in() || (!empty($_REQUEST['person_id']) && is_numeric($_REQUEST['person_id'])) ){
			//make the request id take priority, this shouldn't make it into unwanted objects if they use theobj::get_person().
			if( !empty($_REQUEST['person_id']) ){
				$EM_Person = new EM_Person( absint($_REQUEST['person_id']) );
			}else{
				$EM_Person = new EM_Person( get_current_user_id() );
			}
		}
		if( isset($_REQUEST['booking_id']) && is_numeric($_REQUEST['booking_id']) && !is_object($_REQUEST['booking_id']) ){
			$EM_Booking = EM_Booking::find( absint($_REQUEST['booking_id']) );
		}
		if( isset($_REQUEST['category_id']) && is_numeric($_REQUEST['category_id']) && !is_object($_REQUEST['category_id']) ){
			$EM_Category = new EM_Category( absint($_REQUEST['category_id']) );
		}elseif( isset($_REQUEST['category_slug']) && !is_object($EM_Category) ){
			$EM_Category = new EM_Category( $_REQUEST['category_slug'] );
		}
		if( isset($_REQUEST['ticket_id']) && is_numeric($_REQUEST['ticket_id']) && !is_object($_REQUEST['ticket_id']) ){
			$EM_Ticket = new EM_Ticket( absint($_REQUEST['ticket_id']) );
		}
		define('EM_LOADED',true);
	}
}
add_action('template_redirect', 'em_load_event', 1);
if(is_admin()){ add_action('init', 'em_load_event', 2); }


/**
 * Works much like <a href="http://codex.wordpress.org/Function_Reference/locate_template" target="_blank">locate_template</a>, except it takes a string instead of an array of templates, we only need to load one.
 * @param string $template_name
 * @param boolean $load
 * @uses locate_template()
 * @return string
 */
function em_locate_template( $template_name, $load=false, $the_args = array() ) {
	//First we check if there are overriding tempates in the child or parent theme
	$located = locate_template(array('plugins/events-manager/'.$template_name));
	if( !$located ){
		$located = apply_filters('em_locate_template_default', $located, $template_name, $load, $the_args);
		if ( !$located && file_exists(EM_DIR.'/templates/'.$template_name) ) {
			$located = EM_DIR.'/templates/'.$template_name;
		}
	}
	$located = apply_filters('em_locate_template', $located, $template_name, $load, $the_args);
	if( $located && $load ){
		$the_args = apply_filters('em_locate_template_args_'.$template_name, $the_args, $located);
		if( is_array($the_args) ) extract($the_args);
		require_once($located);
	}
	return $located;
}

/**
 * Quick class to dynamically catch wp_options that are EM formats and need replacing with template files.
 * Since the options filter doesn't have a catchall filter, we send all filters to the __call function and figure out the option that way.
 */
class EM_Formats {
	function __construct(){ add_action( 'template_redirect', array(&$this, 'add_filters')); }
	function add_filters(){
		//you can hook into this filter and activate the format options you want to override by supplying the wp option names in an array, just like in the database.
		$formats = apply_filters('em_formats_filter', array());
		foreach( $formats as $format_name ){
			add_filter('pre_option_'.$format_name, array(&$this, $format_name), 1,1);
		}
	}
	function __call( $name, $value ){
		$format = em_locate_template( 'formats/'.substr($name, 5).'.php' );
		if( $format ){
			ob_start();
			require_once($format);
			$value[0] = ob_get_clean();
		}
		return $value[0];
	}
}
global $EM_Formats;
$EM_Formats = new EM_Formats();


/**
 * Monitors event saves and changes the rss pubdate and a last modified option so it's current
 * @param boolean $result
 * @return boolean
 */
function em_modified_monitor($result){
	if($result){
	    update_option('em_last_modified', time());
	}
	return $result;
}
add_filter('em_event_save', 'em_modified_monitor', 10,1);
add_filter('em_location_save', 'em_modified_monitor', 10,1);



function em_activate() {
	update_option('dbem_flush_needed',1);
}
register_activation_hook( __FILE__,'em_activate');

/* Creating the wp_events table to store event data*/
function em_deactivate() {
	global $wp_rewrite;
   	$wp_rewrite->flush_rules();
}
register_deactivation_hook( __FILE__,'em_deactivate');




/**
 * Load plugin textdomain.
 */
function wpdocs_load_textdomain() {
	load_plugin_textdomain('events-manager', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
	load_plugin_textdomain('em-pro', false, dirname( plugin_basename( __FILE__ ) ).'/languages');
}
add_action( 'plugins_loaded', 'wpdocs_load_textdomain' );



//cron functions - ran here since functions aren't loaded, scheduling done by gateways and other modules
/**
 * Adds a schedule according to EM
 * @param array $shcehules
 * @return array
 */
function emp_cron_schedules($schedules){
	$schedules['em_minute'] = array(
		'interval' => 60,
		'display' => 'Every Minute'
	);
	return $schedules;
}
add_filter('cron_schedules','emp_cron_schedules',10,1);

require_once('blocks/Block.php');
