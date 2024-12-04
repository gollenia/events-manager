<?php
/*
Plugin Name: Events
Version: 6.8.2
Plugin URI: https://github.com/gollenia/events
Description: Event registration and booking management for WordPress. Recurring events, locations, webinars, ical, booking registration and more!
Author: Marcus Sykes, Thomas Gollenia
Author URI: https://github.com/gollenia/events
Text Domain: events
*/

/*

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
	const VERSION = '6.82';
	const DIR = __DIR__;
}

function em_load_textdomain() {
	load_plugin_textdomain('events', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
}

add_action( 'plugin_loaded', 'em_load_textdomain', 10 );




require_once( plugin_dir_path( __FILE__ ) . '/vendor/autoload.php');

// INCLUDES
//Base classes
require_once __DIR__ . '/polyfill.php';
require_once __DIR__ . '/Assets.php';
require_once __DIR__ . '/classes/Options.php';
require_once __DIR__ . '/classes/Object.php';
require_once __DIR__ . '/classes/DateTime.php';
require_once __DIR__ . '/classes/DateTimeZone.php';
require_once __DIR__ . '/classes/Taxonomies/TaxonomyTerm.php';
require_once __DIR__ . '/classes/Taxonomies/TaxonomyTerms.php';

require_once __DIR__ . '/classes/Forms/FormPost.php';
require_once __DIR__ . '/em-posts.php';
require_once __DIR__ . '/em-actions.php';
require_once __DIR__ . '/em-ical.php';

require_once __DIR__ . '/classes/Bookings/Booking.php';

require_once __DIR__ . '/classes/Bookings/Bookings.php';
require_once __DIR__ . '/classes/Bookings/BookingsTable.php';
require_once __DIR__ . '/classes/Bookings/BookingsRest.php';
require_once __DIR__ . '/classes/Bookings/BookingExport.php';
require_once __DIR__ . '/classes/Categories/Category.php';
require_once __DIR__ . '/classes/Categories/Categories.php';

require_once __DIR__ . '/classes/Events/Event.php';
require_once __DIR__ . '/classes/Events/EventPost.php';
require_once __DIR__ . '/classes/Events/Events.php';
require_once __DIR__ . '/classes/Locations/Location.php';

require_once __DIR__ . '/classes/Locations/LocationPost.php';
require_once __DIR__ . '/classes/Locations/Locations.php';
require_once __DIR__ . '/classes/Emails/Mailer.php';
require_once __DIR__ . '/classes/Notices.php';
require_once __DIR__ . '/classes/People/People.php';
require_once __DIR__ . '/classes/People/Person.php';
require_once __DIR__ . '/classes/Permalinks.php';
require_once __DIR__ . '/classes/Speaker/Speakers.php';

require_once __DIR__ . '/classes/Tags/Tag.php';
require_once __DIR__ . '/classes/Tags/Tags.php';
require_once __DIR__ . '/classes/Tickets/TicketBooking.php';
require_once __DIR__ . '/classes/Tickets/Ticket.php';
require_once __DIR__ . '/classes/Tickets/TicketsBookings.php';
require_once __DIR__ . '/classes/Tickets/Tickets.php';
require_once __DIR__ . '/classes/Tickets/TicketsController.php';
//Admin Files
if( is_admin() ){
	require_once __DIR__ . '/classes/Forms/FormPostAdmin.php';
	require_once __DIR__ . '/admin/em-admin.php';
	require_once __DIR__ . '/admin/em-bookings.php';
	require_once __DIR__ . '/admin/em-docs.php';
	require_once __DIR__ . '/admin/em-help.php';
	require_once __DIR__ . '/admin/em-options.php';
	require_once __DIR__ . '/admin/em-data-privacy.php';

	require_once __DIR__ . '/classes/Events/EventPostAdmin.php';
	require_once __DIR__ . '/classes/Events/EventPostsAdmin.php';
	require_once __DIR__ . '/classes/Locations/LocationPostAdmin.php';
	require_once __DIR__ . '/classes/Locations/LocationPostsAdmin.php';
	require_once __DIR__ . '/classes/Taxonomies/TaxonomyAdmin.php';
	require_once __DIR__ . '/classes/Categories/CategoriesAdmin.php';
	require_once __DIR__ . '/classes/Tags/TagsAdmin.php';
	require_once __DIR__ . '/admin/bookings/em-events.php';
	/*
	require_once __DIR__ . '/admin/bookings/em-cancelled.php';
	require_once __DIR__ . '/admin/bookings/em-confirmed.php';
	
	require_once __DIR__ . '/admin/bookings/em-rejected.php';
	require_once __DIR__ . '/admin/bookings/em-pending.php';
	require_once __DIR__ . '/admin/bookings/em-person.php';
	*/
}

require_once __DIR__ . '/classes/Speaker/Speaker.php';

require_once __DIR__ . '/classes/Export/Export.php';

require_once __DIR__ . '/classes/Forms/Forms.php';
require_once __DIR__ . '/classes/Gateways/Gateways.php';
require_once __DIR__ . '/classes/Forms/BookingsForm.php';

require_once __DIR__ . '/classes/Coupons/Coupons.php';
require_once __DIR__ . '/classes/Emails/Emails.php';
require_once __DIR__ . '/classes/Forms/UserFields.php';

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
		$rss_url = add_query_arg(['post_type'=>EM_POST_TYPE_EVENT, 'feed'=>'rss2'], home_url());
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
	locate_template('plugins/events/functions.php', true);
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
	global $EM_Event, $EM_Recurrences, $EM_Location, $EM_Person, $EM_Booking, $EM_Category;
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
	$located = locate_template(array('plugins/events/'.$template_name));
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


register_activation_hook( __FILE__,function() {
	update_option('dbem_flush_needed',1);
});

register_deactivation_hook( __FILE__,function() {
	global $wp_rewrite;
   	$wp_rewrite->flush_rules();
});



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




function em_register_blocks()
{
	
	$blocks = [
		'upcoming',
		'details',
		'details-audience',
		'details-date',
		'details-location',
		'details-price',
		'details-shutdown',
		'details-spaces',
		'details-time',
		'details-speaker',
		'booking'
	];

	foreach ($blocks as $block) {
		register_block_type(__DIR__ . '/build/blocks/' . $block);
	}
}

add_action('init', 'em_register_blocks');

