<?php





define('EM_MIN_VERSION', 6);
define('EM_MIN_VERSION_CRITICAL', 2.377);
define('EMP_SLUG', plugin_basename( __FILE__ ));


class EM_Pro {

	/**
	 * em_pro_data option
	 * @var array
	 */
	var $data;

	/**
	 * Actions to take upon initial action hook
	 */
	public static function init(){
		global $wpdb;
		
		define('EM_TRANSACTIONS_TABLE', $wpdb->prefix.'em_transactions'); //TABLE NAME
		define('EM_EMAIL_QUEUE_TABLE', $wpdb->prefix.'em_email_queue'); //TABLE NAME
		define('EM_COUPONS_TABLE', $wpdb->prefix.'em_coupons'); //TABLE NAME
		define('EM_BOOKINGS_RELATIONSHIPS_TABLE', $wpdb->prefix.'em_bookings_relationships'); //TABLE NAME
		//check that EM is installed
		

		if( is_admin() ){ //although activate_plugins would be beter here, superusers don't visit every single site on MS
			add_action('init', 'EM_Pro::install',2);
		}
		
		add_action('em_enqueue_admin_styles', 'EM_Pro::em_enqueue_admin_styles', 1); //added only when EM adds its own scripts
		add_action('em_enqueue_scripts', 'EM_Pro::em_enqueue_scripts', 1); //added only when EM adds its own scripts
		add_action('em_enqueue_admin_scripts', 'EM_Pro::em_enqueue_scripts', 1); //added only when EM adds its own scripts
		add_action('admin_init', 'EM_Pro::enqueue_admin_script', 1); //specific pages in admin that EMP deals with
	    add_filter('em_wp_localize_script', 'EM_Pro::em_wp_localize_script',10,1);
		
		//booking-specific features - this one may change in the future
		include('emp-forms.php'); //form editor
		
		include('emp-ml.php');
		include('emp-update.php');

		//booking-specific features
		include('add-ons/gateways/gateways.php'); //this may change in the future too e.g. for pay-per-post
		include('add-ons/bookings-form/bookings-form.php');
		
		include('add-ons/coupons/coupons.php');
		include('add-ons/emails/emails.php');
		include('add-ons/user-fields.php');
			
		
		
		
		
		do_action('em_pro_loaded');
	}

	/**
	 * Enqueue Pro CSS file when action em_enqueue_admin_styles is fired.
	 */
	public static function em_enqueue_admin_styles(){
	    wp_enqueue_style('events-manager-pro-admin', plugins_url('includes/events-manager-pro.css',__FILE__), array(), 5);
	}
	
	public static function install(){
	    if( current_user_can('list_users') ){
	    	$old_version = get_option('em_pro_version');
	    	if( $old_version == '' ) {
	    		require_once('emp-install.php');
	    		emp_install();
	    	}
	    }
	}

	
	
	
	/**
	 * Enqueue scripts when fired by em_enqueue_scripts action.
	 */
	public static function em_enqueue_scripts(){
		wp_enqueue_script('events-manager-pro', plugins_url('includes/events-manager-pro.js',__FILE__), array('jquery'), 5); //jQuery will load as dependency
	}

	/**
	 * Add admin scripts for specific pages handled by EM Pro. Fired by admin_init
	 */
	public static function enqueue_admin_script(){
	    global $pagenow;
	    if( !empty($_REQUEST['page']) && ($_REQUEST['page'] == 'events-manager-forms-editor' || ($_REQUEST['page'] == 'events-manager-bookings' && !empty($_REQUEST['action']) && $_REQUEST['action'] == 'manual_booking')) ){
			wp_enqueue_script('events-manager-pro', plugins_url('includes/events-manager-pro.js',__FILE__), array('jquery', 'jquery-ui-core','jquery-ui-widget','jquery-ui-position')); //jQuery will load as dependency
			do_action('em_enqueue_admin_scripts');
	    }
	    if( $pagenow == 'user-edit.php' ){
	        //need to include the em script for dates
	        EM_Scripts_and_Styles::admin_enqueue();
	    }
	}
	
	
	
	/**
	 * Add extra localized JS options to the em_wp_localize_script filter.
	 * @param array $vars
	 * @return array
	 */
	public static function em_wp_localize_script( $vars ){
	    $vars['cache'] = defined('WP_CACHE') && WP_CACHE;
	    return $vars;
	}

	/**
	 * Enqueues the CSS required by Pro features. Fired by action em_enqueue_styles which is when EM enqueues it's stylesheet, if it doesn't then this shouldn't either 
	 */
	public static function em_enqueue_styles(){
	    wp_enqueue_style('events-manager-pro', plugins_url('includes/events-manager-pro.css',__FILE__), array(), 5);
	}
	
}

add_action( 'plugins_loaded', 'EM_Pro::init' );


//Add translation
function emp_load_plugin_textdomain() {
    load_plugin_textdomain('em-pro', false, dirname( plugin_basename( __FILE__ ) ).'/languages');
}
add_action('plugins_loaded', 'emp_load_plugin_textdomain');

/* Creating the wp_events table to store event data*/
function emp_activate() {
	global $wp_rewrite;
   	$wp_rewrite->flush_rules();
}
register_activation_hook( __FILE__,'emp_activate');


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



add_action( 'init', ['\\Schedule\\Addons\\Assets', 'register'] );
$args = \Schedule\Addons\Assets::register();

add_filter( 'timber/twig', ["\\Schedule\\Addons\\TwigExtend", "add_to_twig"] );
// Add Twig functions
// @todo Check if needed
//add_filter( 'timber/twig', ["EMB\\Utils\\TwigExtend", "add_to_twig"] );

$upcoming_block = new \Schedule\Addons\Upcoming($args);
$upcoming_block->register();
