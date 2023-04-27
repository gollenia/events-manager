<?php
/*
Plugin Name: Events
Version: 6.4
Plugin URI: https://github.com/gollenia/events-manager
Description: Event registration and booking management for WordPress. Recurring events, locations, webinars, ical, booking registration and more!
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

class Events {
	const VERSION = '6.4';
	const DIR = __DIR__;
}

//temporarily disable AJAX by default, future updates will eventually have this turned on as we work out some kinks
if( !defined('EM_AJAX') ){
	define( 'EM_AJAX', true );
}

require_once( plugin_dir_path( __FILE__ ) . '/vendor/autoload.php');

require_once('classes/Twig.php');
$EM_Twig = \EM_Twig::init();

add_filter( 'timber/locations', function($paths) use ($EM_Twig) {
	$paths[] = $EM_Twig->locations;
	return $paths;
});


define('EM_LOGS_DIR' , '/var/www/vhosts/kids-team.internal/log/');


// INCLUDES
//Base classes
require_once('polyfill.php');
require_once('classes/Assets.php');
require_once('classes/Options.php');
require_once('classes/Object.php');
require_once('classes/Datetime.php');
require_once('classes/DatetimeZone.php');
require_once('classes/Taxonomies/TaxonomyTerm.php');
require_once('classes/Taxonomies/TaxonomyTerms.php');
//require_once('classes/Taxonomies/TaxonomyFrontend.php');
//set up events as posts
require_once('classes/Forms/FormPost.php');
require_once("em-posts.php");
//Template Tags & Template Logic
require_once("em-actions.php");
require_once("em-functions.php");
require_once("em-ical.php");
//require_once("em-data-privacy.php");
require_once("multilingual/em-ml.php");

//Classes
require_once('classes/Bookings/Booking.php');
require_once('classes/Bookings/Bookings.php');
require_once("classes/Bookings/BookingsTable.php") ;

require_once('classes/Categories/Category.php');
require_once('classes/Categories/Categories.php');
//require_once('classes/Categories/CategoriesFrontend.php');
require_once('classes/Events/Event.php');
require_once('classes/Locations/EventLocations.php');
require_once('classes/Events/EventPost.php');
require_once('classes/Events/Events.php');
require_once('classes/Locations/Location.php');
require_once('classes/Locations/LocationPost.php');
require_once('classes/Locations/Locations.php');
require_once("classes/Emails/Mailer.php") ;
require_once('classes/Notices.php');
require_once('classes/People/People.php');
require_once('classes/People/Person.php');
require_once('classes/Permalinks.php');
require_once('classes/Speaker/Speakers.php');

require_once('classes/Tags/Tag.php');
require_once('classes/Tags/Tags.php');
//require_once('classes/Tags/TagsFrontend.php');
require_once('classes/Tickets/TicketBooking.php');
require_once('classes/Tickets/Ticket.php');
require_once('classes/Tickets/TicketsBookings.php');
require_once('classes/Tickets/Tickets.php');
//Admin Files
if( is_admin() ){
	
	require_once('classes/Forms/FormPostAdmin.php');
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
	require_once('classes/Taxonomies/TaxonomyAdmin.php');
	require_once('classes/Categories/CategoriesAdmin.php');
	require_once('classes/Tags/TagsAdmin.php');
	//bookings folder
	require_once('admin/bookings/em-cancelled.php');
	require_once('admin/bookings/em-confirmed.php');
	require_once('admin/bookings/em-events.php');
	require_once('admin/bookings/em-rejected.php');
	require_once('admin/bookings/em-pending.php');
	require_once('admin/bookings/em-person.php');
}

require_once('classes/Speaker/Speaker.php');
require_once('classes/Export/Export.php');

require_once('classes/Forms/Forms.php');

//booking-specific features
require_once('classes/Gateways/Gateways.php'); 
require_once('classes/Forms/BookingsForm.php');

require_once('classes/Coupons/Coupons.php');
require_once('classes/Emails/Emails.php');
require_once('classes/Forms/UserFields.php');

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
	$EM_Mailer = new \EM_Mailer();
	//Upgrade/Install Routine
	if( is_admin() && current_user_can('manage_options') ){
		if( Events::VERSION > get_option('dbem_version', 0) ){
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
	if (defined('EM_LOADED')) return;
	
	$EM_Recurrences = array();

	if( isset( $_REQUEST['event_id'] ) && is_numeric($_REQUEST['event_id']) && !is_object($EM_Event) ){
		$EM_Event = new \EM_Event( absint($_REQUEST['event_id']) );
	}elseif( isset($_REQUEST['post']) && (get_post_type($_REQUEST['post']) == 'event' || get_post_type($_REQUEST['post']) == 'event-recurring') ){
		$EM_Event = \EM_Event::find($_REQUEST['post'], 'post_id');
	}

	if( isset($_REQUEST['location_id']) && is_numeric($_REQUEST['location_id']) && !is_object($EM_Location) ){
		$EM_Location = new \EM_Location( absint($_REQUEST['location_id']) );
	}elseif( isset($_REQUEST['post']) && get_post_type($_REQUEST['post']) == 'location' ){
		$EM_Location = EM_Location::get($_REQUEST['post'], 'post_id');
	}

	if( is_user_logged_in() || (!empty($_REQUEST['person_id']) && is_numeric($_REQUEST['person_id'])) ){
		//make the request id take priority, this shouldn't make it into unwanted objects if they use theobj::get_person().
		if( !empty($_REQUEST['person_id']) ){
			$EM_Person = new \EM_Person( absint($_REQUEST['person_id']) );
		}else{
			$EM_Person = new \EM_Person( get_current_user_id() );
		}
	}

	if( isset($_REQUEST['booking_id']) && is_numeric($_REQUEST['booking_id']) && !is_object($_REQUEST['booking_id']) ){
		$EM_Booking = \EM_Booking::find( absint($_REQUEST['booking_id']) );
	}

	if( isset($_REQUEST['category_id']) && is_numeric($_REQUEST['category_id']) && !is_object($_REQUEST['category_id']) ){
		$EM_Category = new \EM_Category( absint($_REQUEST['category_id']) );
	}elseif( isset($_REQUEST['category_slug']) && !is_object($EM_Category) ){
		$EM_Category = new \EM_Category( $_REQUEST['category_slug'] );
	}

	if( isset($_REQUEST['ticket_id']) && is_numeric($_REQUEST['ticket_id']) && !is_object($_REQUEST['ticket_id']) ){
		$EM_Ticket = new \EM_Ticket( absint($_REQUEST['ticket_id']) );
	}

	define('EM_LOADED',true);
	
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
		if ( !$located && file_exists(Events::DIR.'/templates/'.$template_name) ) {
			$located = Events::DIR.'/templates/'.$template_name;
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


register_uninstall_hook(__FILE__, 'em_uninstall');


//cron functions - ran here since functions aren't loaded, scheduling done by gateways and other modules
/**
 * Adds a schedule according to EM
 * @param array $shcehules
 * @return array
 */
function em_cron_schedules($schedules){
	$schedules['em_minute'] = array(
		'interval' => 60,
		'display' => 'Every Minute'
	);
	return $schedules;
}
add_filter('cron_schedules','em_cron_schedules',10,1);

require_once('classes/Blocks/Block.php');
