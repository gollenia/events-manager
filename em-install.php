<?php

function em_uninstall() {
	global $wpdb;
	//delete EM posts
	remove_action('before_delete_post',array('EM_Location_Post_Admin','before_delete_post'),10,1);
	remove_action('before_delete_post',array('EM_Event_Post_Admin','before_delete_post'),10,1);
	remove_action('before_delete_post',array('EM_Event_Recurring_Post_Admin','before_delete_post'),10,1);
	$post_ids = $wpdb->get_col('SELECT ID FROM '.$wpdb->posts." WHERE post_type IN ('".EM_Event::POST_TYPE."','".EM_POST_TYPE_LOCATION."','event-recurring')");
	foreach($post_ids as $post_id){
		wp_delete_post($post_id);
	}
	//delete categories
	$cat_terms = get_terms(EM_TAXONOMY_CATEGORY, array('hide_empty'=>false));
	foreach($cat_terms as $cat_term){
		wp_delete_term($cat_term->term_id, EM_TAXONOMY_CATEGORY);
	}
	$tag_terms = get_terms(EM_TAXONOMY_TAG, array('hide_empty'=>false));
	foreach($tag_terms as $tag_term){
		wp_delete_term($tag_term->term_id, EM_TAXONOMY_TAG);
	}
	//delete EM tables
	$wpdb->query('DROP TABLE '.EM_EVENTS_TABLE);
	$wpdb->query('DROP TABLE '.EM_BOOKINGS_TABLE);
	$wpdb->query('DROP TABLE '.EM_LOCATIONS_TABLE);
	$wpdb->query('DROP TABLE '.EM_TICKETS_TABLE);
	$wpdb->query('DROP TABLE '.EM_TICKETS_BOOKINGS_TABLE);
	$wpdb->query('DROP TABLE '.EM_RECURRENCE_TABLE);
	$wpdb->query('DROP TABLE '.EM_META_TABLE);
	
	//delete options
	$wpdb->query('DELETE FROM '.$wpdb->options.' WHERE option_name LIKE \'em_%\' OR option_name LIKE \'dbem_%\'');
	//deactivate and go!
	deactivate_plugins(array('events-manager/events-manager.php','events-manager-pro/events-manager-pro.php'), true);
	wp_safe_redirect(admin_url('plugins.php?deactivate=true'));
	exit();
}

function em_install() {
	global $wp_rewrite;
	$wp_rewrite->flush_rules();
	$old_version = get_option('dbem_version');	
   	
	if( Events::VERSION > $old_version || $old_version == '' ){
		if( get_option('dbem_upgrade_throttle') <= time() || !get_option('dbem_upgrade_throttle') ){
		 	// Creates the events table if necessary
			em_create_events_table();
			em_create_events_meta_table();
			em_create_locations_table();
			em_create_bookings_table();
			em_create_tickets_table();
			em_create_tickets_bookings_table();
			em_create_transactions_table();
			em_create_coupons_table(); 
			em_create_reminders_table();
			em_create_bookings_relationships_table();
			
			
			if( empty($old_version) ){
				update_option('dbem_hello_to_user',1);
			}
					
			//set caps and options
			em_set_capabilities();
			em_add_options();
			em_upgrade_current_installation();
			do_action('events_manager_updated', $old_version );
			//Update Version
		  	update_option('dbem_version', Events::VERSION);
			delete_option('dbem_upgrade_throttle');
			delete_option('dbem_upgrade_throttle_time');
			
			global $wp_rewrite;
			$wp_rewrite->flush_rules();
			
			update_option('dbem_flush_needed',1);
			add_action ( 'admin_notices', function() {
				echo '<div class="updated"><p>'.__('Events has been updated to version ',  'events-manager'). Events::VERSION . '</p></div>';
			});
		}else{
			function em_upgrading_in_progress_notification(){
				?><div class="error"><p>Events Manager upgrade still in progress. Please be patient, this message should disappear once the upgrade is complete.</p></div><?php
			}
			add_action ( 'admin_notices', 'em_upgrading_in_progress_notification' );
			add_action ( 'network_admin_notices', 'em_upgrading_in_progress_notification' );
			return;
		}
	}
	restore_previous_locale(); //now that we're done, switch back to current language (if applicable)
}

/**
 * Magic function that takes a table name and cleans all non-unique keys not present in the $clean_keys array. if no array is supplied, all but the primary key is removed.
 * @param string $table_name
 * @param array $clean_keys
 */
function em_sort_out_table_nu_keys($table_name, $clean_keys = array()){
	global $wpdb;
	//sort out the keys
	$new_keys = $clean_keys;
	$table_key_changes = array();
	$table_keys = $wpdb->get_results("SHOW KEYS FROM $table_name WHERE Key_name != 'PRIMARY'", ARRAY_A);
	foreach($table_keys as $table_key_row){
		if( !in_array($table_key_row['Key_name'], $clean_keys) ){
			$table_key_changes[] = "ALTER TABLE $table_name DROP INDEX ".$table_key_row['Key_name'];
		}elseif( in_array($table_key_row['Key_name'], $clean_keys) ){
			foreach($clean_keys as $key => $clean_key){
				if($table_key_row['Key_name'] == $clean_key){
					unset($new_keys[$key]);
				}
			}
		}
	}
	//delete duplicates
	foreach($table_key_changes as $sql){
		$wpdb->query($sql);
	}
	//add new keys
	foreach($new_keys as $key){
		if( preg_match('/\(/', $key) ){
			$wpdb->query("ALTER TABLE $table_name ADD INDEX $key");
		}else{
			$wpdb->query("ALTER TABLE $table_name ADD INDEX ($key)");
		}
	}
}

/**
 * Since WP 4.2 tables are created with utf8mb4 collation. This creates problems when storing content in previous utf8 tables such as when using emojis. 
 * This function checks whether the table in WP was changed 
 * @return boolean
 */
function em_check_utf8mb4_tables(){
		global $wpdb, $em_check_utf8mb4_tables;
		
		if( $em_check_utf8mb4_tables || $em_check_utf8mb4_tables === false ) return $em_check_utf8mb4_tables;
		
		$column = $wpdb->get_row( "SHOW FULL COLUMNS FROM {$wpdb->posts} WHERE Field='post_content';" );
		if ( ! $column ) {
			return false;
		}
		
		//if this doesn't become true further down, that means we couldn't find a correctly converted utf8mb4 posts table 
		$em_check_utf8mb4_tables = false;
		
		if ( $column->Collation ) {
			list( $charset ) = explode( '_', $column->Collation );
			$em_check_utf8mb4_tables = ( 'utf8mb4' === strtolower( $charset ) );
		}
		return $em_check_utf8mb4_tables;
		
}

function em_create_events_table() {
	global  $wpdb;
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

	$table_name = $wpdb->prefix.'em_events';
	$sql = "CREATE TABLE ".$table_name." (
		event_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		post_id bigint(20) unsigned NOT NULL,
		event_parent bigint(20) unsigned NULL DEFAULT NULL,
		event_slug VARCHAR( 200 ) NULL DEFAULT NULL,
		event_owner bigint(20) unsigned DEFAULT NULL,
		event_status tinyint(1) NULL DEFAULT NULL,
		event_name text NULL DEFAULT NULL,
		event_start_date date NULL DEFAULT NULL,
		event_end_date date NULL DEFAULT NULL,
		event_start_time time NULL DEFAULT NULL,
		event_end_time time NULL DEFAULT NULL,
 		event_all_day tinyint(1) unsigned NULL DEFAULT NULL,
		event_start datetime NULL DEFAULT NULL,
		event_end datetime NULL DEFAULT NULL,
		event_timezone tinytext NULL DEFAULT NULL,
		post_content longtext NULL DEFAULT NULL,
		event_rsvp tinyint(1) unsigned NOT NULL DEFAULT 0,
		event_rsvp_end datetime NULL DEFAULT NULL,
		event_rsvp_start datetime NULL DEFAULT NULL,
		event_speaker_id bigint(20) unsigned NULL DEFAULT NULL,
		event_audience text NULL DEFAULT NULL,
		event_rsvp_spaces int(5) NULL DEFAULT NULL,
		event_spaces int(5) NULL DEFAULT 0,
		event_private tinyint(1) unsigned NOT NULL DEFAULT 0,
		location_id bigint(20) unsigned NULL DEFAULT NULL,
		event_location_type VARCHAR(15) NULL DEFAULT NULL,
		recurrence_id bigint(20) unsigned NULL DEFAULT NULL,
  		event_date_created datetime NULL DEFAULT NULL,
  		event_date_modified datetime NULL DEFAULT NULL,
		recurrence tinyint(1) unsigned NOT NULL DEFAULT 0,
		recurrence_interval int(4) NULL DEFAULT NULL,
		recurrence_freq tinytext NULL DEFAULT NULL,
		recurrence_byday tinytext NULL DEFAULT NULL,
		recurrence_byweekno int(4) NULL DEFAULT NULL,
		recurrence_days int(4) NULL DEFAULT NULL,
		recurrence_rsvp_days int(3) NULL DEFAULT NULL,
		blog_id bigint(20) unsigned NULL DEFAULT NULL,
		group_id bigint(20) unsigned NULL DEFAULT NULL,
		event_language varchar(14) NULL DEFAULT NULL,
		event_translation tinyint(1) unsigned NOT NULL DEFAULT 0,
		PRIMARY KEY  (event_id)
		) DEFAULT CHARSET=utf8 ;";

	if( $wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name ){
		dbDelta($sql);
	}elseif( get_option('dbem_version') != '' ){
		if( get_option('dbem_version') < 5.984 ){
			// change the recurrence flag to a required field defaulting to 0, to avoid missing recurrences in EM_Events::get() due to wayward null values
			$wpdb->query("UPDATE $table_name SET recurrence = 0 WHERE recurrence IS NULL");
			$wpdb->query("ALTER TABLE $table_name CHANGE `recurrence` `recurrence` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0'");
			$wpdb->query("ALTER TABLE $table_name CHANGE `event_status` `event_status` TINYINT(1) NULL DEFAULT NULL;");
		}
		dbDelta($sql);
	}
	em_sort_out_table_nu_keys($table_name, array('event_status','post_id','blog_id','group_id','location_id','event_start', 'event_end', 'event_start_date', 'event_end_date'));
	if( em_check_utf8mb4_tables() ) maybe_convert_table_to_utf8mb4( $table_name );
}

function em_create_events_meta_table(){
	global  $wpdb, $user_level;
	$table_name = $wpdb->prefix.'em_meta';

	// Creating the events table
	$sql = "CREATE TABLE ".$table_name." (
		meta_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		object_id bigint(20) unsigned NOT NULL,
		meta_key varchar(255) DEFAULT NULL,
		meta_value longtext,
		meta_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY  (meta_id)
		) DEFAULT CHARSET=utf8 ";

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

	dbDelta($sql);
	em_sort_out_table_nu_keys($table_name, array('object_id','meta_key'));
	if( em_check_utf8mb4_tables() ) maybe_convert_table_to_utf8mb4( $table_name );
}

function em_create_locations_table() {

	global  $wpdb, $user_level;
	$table_name = $wpdb->prefix.'em_locations';

	// Creating the events table
	$sql = "CREATE TABLE ".$table_name." (
		location_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		post_id bigint(20) unsigned NOT NULL,
		blog_id bigint(20) unsigned NULL DEFAULT NULL,
		location_parent bigint(20) unsigned NULL DEFAULT NULL,
		location_slug VARCHAR( 200 ) NULL DEFAULT NULL,
		location_name text NULL DEFAULT NULL,
		location_owner bigint(20) unsigned NOT NULL DEFAULT 0,
		location_address VARCHAR( 200 ) NULL DEFAULT NULL,
		location_town VARCHAR( 200 ) NULL DEFAULT NULL,
		location_state VARCHAR( 200 ) NULL DEFAULT NULL, 
		location_postcode VARCHAR( 10 ) NULL DEFAULT NULL,
		location_region VARCHAR( 200 ) NULL DEFAULT NULL,
		location_country CHAR( 2 ) NULL DEFAULT NULL,
		location_latitude DECIMAL( 9, 6 ) NULL DEFAULT NULL,
		location_longitude DECIMAL( 9, 6 ) NULL DEFAULT NULL,
		location_url VARCHAR( 400 ) NULL DEFAULT NULL,
		post_content longtext NULL DEFAULT NULL,
		location_status int(1) NULL DEFAULT NULL,
		location_private tinyint(1) unsigned NOT NULL DEFAULT 0,
		location_language varchar(14) NULL DEFAULT NULL,
		location_translation tinyint(1) unsigned NOT NULL DEFAULT 0,
		PRIMARY KEY  (location_id)
		) DEFAULT CHARSET=utf8 ;";

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

	if( $wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name ) {
		dbDelta($sql);
		/*
		DEPRICATED - kept here as an example for how migrations from the wp_em_events table is fairly easy
		//Add default values
		$wpdb->query("INSERT INTO ".$table_name." (location_name, location_address, location_town, location_state, location_country, location_latitude, location_longitude, location_slug, location_owner, location_status, post_id) VALUES ('Arts Millenium Building', 'Newcastle Road','Galway','Galway','IE', 53.275, -9.06532, 'arts-millenium-building','".get_current_user_id()."', 1,0)");
		$wpdb->query("INSERT INTO ".$table_name." (location_name, location_address, location_town, location_state, location_country, location_latitude, location_longitude, location_slug, location_owner, location_status, post_id) VALUES ('The Crane Bar', '2, Sea Road','Galway','Galway','IE', 53.2692, -9.06151, 'the-crane-bar','".get_current_user_id()."', 1, 0)");
		$wpdb->query("INSERT INTO ".$table_name." (location_name, location_address, location_town, location_state, location_country, location_latitude, location_longitude, location_slug, location_owner, location_status, post_id) VALUES ('Taaffes Bar', '19 Shop Street','Galway','Galway','IE', 53.2725, -9.05321, 'taffes-bar','".get_current_user_id()."', 1, 0)");
		em_migrate_locations($wpdb->get_results('SELECT * FROM '.$table_name, ARRAY_A));
		*/
	}else{
		if( get_option('dbem_version') != '' && get_option('dbem_version') < 4.938 ){
			$wpdb->query("ALTER TABLE $table_name CHANGE location_description post_content longtext NULL DEFAULT NULL");
		}
		dbDelta($sql);
		if( get_option('dbem_version') != '' && get_option('dbem_version') < 4.93 ){
			//if updating from version 4 (4.93 is beta v5) then set all statuses to 1 since it's new
			$wpdb->query("UPDATE ".$table_name." SET location_status=1");
		}
	}
	if( em_check_utf8mb4_tables() ){
		maybe_convert_table_to_utf8mb4( $table_name );
		em_sort_out_table_nu_keys($table_name, array('location_state (location_state(191))','location_region (location_region(191))','location_country','post_id','blog_id'));
	}else{
		em_sort_out_table_nu_keys($table_name, array('location_state','location_region','location_country','post_id','blog_id'));
	}
}

function em_create_bookings_table() {

	global  $wpdb, $user_level;
	$table_name = $wpdb->prefix.'em_bookings';

	$sql = "CREATE TABLE ".$table_name." (
		booking_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		event_id bigint(20) unsigned NULL,
		person_id bigint(20) unsigned NOT NULL,
		booking_spaces int(5) NOT NULL,
		booking_comment text DEFAULT NULL,
		booking_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
		booking_status bool NOT NULL DEFAULT 1,
 		booking_price decimal(14,4) unsigned NOT NULL DEFAULT 0,
 		booking_donation bool NOT NULL DEFAULT 0,
		booking_meta LONGTEXT NULL,
		PRIMARY KEY  (booking_id)
		) DEFAULT CHARSET=utf8 ;";
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);
	em_sort_out_table_nu_keys($table_name, array('event_id','person_id','booking_status'));
	if( em_check_utf8mb4_tables() ) maybe_convert_table_to_utf8mb4( $table_name );
}


//Add the categories table
function em_create_tickets_table() {

	global  $wpdb, $user_level;
	$table_name = $wpdb->prefix.'em_tickets';

	// Creating the events table
	$sql = "CREATE TABLE {$table_name} (
		ticket_id BIGINT( 20 ) UNSIGNED NOT NULL AUTO_INCREMENT,
		event_id BIGINT( 20 ) UNSIGNED NOT NULL ,
		ticket_name TINYTEXT NOT NULL ,
		ticket_description TEXT NULL ,
		ticket_price DECIMAL( 14 , 4 ) NULL ,
		ticket_start DATETIME NULL ,
		ticket_end DATETIME NULL ,
		ticket_min INT( 10 ) NULL ,
		ticket_max INT( 10 ) NULL ,
		ticket_spaces INT NULL ,
		ticket_members INT( 1 ) NULL ,
		ticket_members_roles LONGTEXT NULL,
		ticket_guests INT( 1 ) NULL ,
		ticket_required INT( 1 ) NULL ,
		ticket_order INT( 2 ) UNSIGNED NULL,
		ticket_meta LONGTEXT NULL,
		PRIMARY KEY  (ticket_id)
		) DEFAULT CHARSET=utf8 ;";

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);
	em_sort_out_table_nu_keys($table_name, array('event_id'));
	if( em_check_utf8mb4_tables() ) maybe_convert_table_to_utf8mb4( $table_name );
}

//Add the categories table
function em_create_tickets_bookings_table() {
	global  $wpdb, $user_level;
	$table_name = $wpdb->prefix.'em_tickets_bookings';

	// Creating the events table
	$sql = "CREATE TABLE {$table_name} (
		  ticket_booking_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		  booking_id bigint(20) unsigned NOT NULL,
		  ticket_id bigint(20) unsigned NOT NULL,
		  ticket_booking_spaces int(6) NOT NULL,
		  ticket_booking_price decimal(14,4) NOT NULL,
		  PRIMARY KEY  (ticket_booking_id)
		) DEFAULT CHARSET=utf8 ;";

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);
	em_sort_out_table_nu_keys($table_name, array('booking_id','ticket_id'));
	if( em_check_utf8mb4_tables() ) maybe_convert_table_to_utf8mb4( $table_name );
}

function em_create_transactions_table() {
	global  $wpdb;
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	$table_name = $wpdb->prefix.'em_transactions'; 
	$sql = "CREATE TABLE ".$table_name." (
		  transaction_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		  booking_id bigint(20) unsigned NOT NULL DEFAULT '0',
		  transaction_gateway_id varchar(30) DEFAULT NULL,
		  transaction_payment_type varchar(20) DEFAULT NULL,
		  transaction_timestamp datetime NOT NULL,
		  transaction_total_amount decimal(14,2) DEFAULT NULL,
		  transaction_currency varchar(35) DEFAULT NULL,
		  transaction_status varchar(35) DEFAULT NULL,
		  transaction_duedate date DEFAULT NULL,
		  transaction_gateway varchar(50) DEFAULT NULL,
		  transaction_note text,
		  transaction_expires datetime DEFAULT NULL,
		  PRIMARY KEY  (transaction_id)
		) DEFAULT CHARSET=utf8 ;";
	
	dbDelta($sql);
	em_sort_out_table_nu_keys($table_name,array('transaction_gateway','booking_id'));
	if( em_check_utf8mb4_tables() ) maybe_convert_table_to_utf8mb4( $table_name );
}

function em_create_coupons_table() {
	global  $wpdb;
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php'); 
	$table_name = $wpdb->prefix.'em_coupons'; 
	$sql = "CREATE TABLE ".$table_name." (
		  coupon_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		  coupon_owner bigint(20) unsigned NOT NULL,
		  blog_id bigint(20) unsigned DEFAULT NULL,
		  coupon_code varchar(20) NOT NULL,
		  coupon_name text NOT NULL,
		  coupon_description text NULL,
		  coupon_max int(10) NULL,
		  coupon_start datetime DEFAULT NULL,
		  coupon_end datetime DEFAULT NULL,
		  coupon_type varchar(20) DEFAULT NULL,
		  coupon_discount decimal(14,2) NOT NULL,
		  coupon_eventwide bool NOT NULL DEFAULT 0,
		  coupon_private bool NOT NULL DEFAULT 0,
		  PRIMARY KEY  (coupon_id)
		) DEFAULT CHARSET=utf8 ;";
	dbDelta($sql);
	$array = array('coupon_owner','coupon_code');
	em_sort_out_table_nu_keys($table_name,$array);
	if( em_check_utf8mb4_tables() ) maybe_convert_table_to_utf8mb4( $table_name );
}

function em_create_reminders_table(){
	global  $wpdb;
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php'); 
    $table_name = $wpdb->prefix.'em_email_queue';
	$sql = "CREATE TABLE ".$table_name." (
		  queue_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		  event_id bigint(20) unsigned DEFAULT NULL,
		  booking_id bigint(20) unsigned DEFAULT NULL,
		  email text NOT NULL,
		  subject text NOT NULL,
		  body text NOT NULL,
		  attachment text NOT NULL,
		  PRIMARY KEY  (queue_id)
		) DEFAULT CHARSET=utf8 ;";
	dbDelta($sql);
	em_sort_out_table_nu_keys($table_name,array('event_id','booking_id'));
	if( em_check_utf8mb4_tables() ) maybe_convert_table_to_utf8mb4( $table_name );
}

function em_create_bookings_relationships_table(){
	global  $wpdb;
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php'); 
    $table_name = $wpdb->prefix.'em_bookings_relationships';
	$sql = "CREATE TABLE ".$table_name." (
		  booking_relationship_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		  event_id bigint(20) unsigned DEFAULT NULL,
		  booking_id bigint(20) unsigned DEFAULT NULL,
		  booking_main_id bigint(20) unsigned DEFAULT NULL,
		  PRIMARY KEY  (booking_relationship_id)
		) DEFAULT CHARSET=utf8 ;";
	dbDelta($sql);
	em_sort_out_table_nu_keys($table_name,array('event_id','booking_id','booking_main_id'));
	if( em_check_utf8mb4_tables() ) maybe_convert_table_to_utf8mb4( $table_name );
}

function em_add_options() {
	global $wp_locale, $wpdb;
	$decimal_point = !empty($wp_locale->number_format['decimal_point']) ? $wp_locale->number_format['decimal_point']:'.';
	$thousands_sep = !empty($wp_locale->number_format['thousands_sep']) ? $wp_locale->number_format['thousands_sep']:',';
	$email_footer = '<br/><br/>-------------------------------<br/>Powered by Events Manager - http://wp-events-plugin.com';
	$respondent_email_body_localizable = __("Dear #_BOOKINGNAME, <br/>You have successfully reserved #_BOOKINGSPACES space/spaces for #_EVENTNAME.<br/>When : #_EVENTDATES @ #_EVENTTIMES<br/>Where : #_LOCATIONNAME - #_LOCATIONFULLLINE<br/>Yours faithfully,<br/>#_CONTACTNAME",'events-manager').$email_footer;
	$respondent_email_pending_body_localizable = __("Dear #_BOOKINGNAME, <br/>You have requested #_BOOKINGSPACES space/spaces for #_EVENTNAME.<br/>When : #_EVENTDATES @ #_EVENTTIMES<br/>Where : #_LOCATIONNAME - #_LOCATIONFULLLINE<br/>Your booking is currently pending approval by our administrators. Once approved you will receive an automatic confirmation.<br/>Yours faithfully,<br/>#_CONTACTNAME",'events-manager').$email_footer;
	$respondent_email_rejected_body_localizable = __("Dear #_BOOKINGNAME, <br/>Your requested booking for #_BOOKINGSPACES spaces at #_EVENTNAME on #_EVENTDATES has been rejected.<br/>Yours faithfully,<br/>#_CONTACTNAME",'events-manager').$email_footer;
	$respondent_email_cancelled_body_localizable = __("Dear #_BOOKINGNAME, <br/>Your requested booking for #_BOOKINGSPACES spaces at #_EVENTNAME on #_EVENTDATES has been cancelled.<br/>Yours faithfully,<br/>#_CONTACTNAME",'events-manager').$email_footer;
	
	$event_submitted_email_body = __("A new event has been submitted by #_CONTACTNAME.<br/>Name : #_EVENTNAME <br/>Date : #_EVENTDATES <br/>Time : #_EVENTTIMES <br/>Please visit #_EDITEVENTURL to review this event for approval.",'events-manager').$email_footer;
	$event_submitted_email_body = str_replace('#_EDITEVENTURL', admin_url().'post.php?action=edit&post=#_EVENTPOSTID', $event_submitted_email_body);
	$event_published_email_body = __("A new event has been published by #_CONTACTNAME.<br/>Name : #_EVENTNAME <br/>Date : #_EVENTDATES <br/>Time : #_EVENTTIMES <br/>Edit this event - #_EDITEVENTURL <br/> View this event - #_EVENTURL",'events-manager').$email_footer;
	$event_published_email_body = str_replace('#_EDITEVENTURL', admin_url().'post.php?action=edit&post=#_EVENTPOSTID', $event_published_email_body);
	$event_resubmitted_email_body = __("A previously published event has been modified by #_CONTACTNAME, and this event is now unpublished and pending your approval.<br/>Name : #_EVENTNAME <br/>Date : #_EVENTDATES <br/>Time : #_EVENTTIMES <br/>Please visit #_EDITEVENTURL to review this event for approval.",'events-manager').$email_footer;
	$event_resubmitted_email_body = str_replace('#_EDITEVENTURL', admin_url().'post.php?action=edit&post=#_EVENTPOSTID', $event_resubmitted_email_body);

	//event admin emails - new format to the above, standard format plus one unique line per booking status at the top of the body and subject line
	$contact_person_email_body_template = '#_EVENTNAME - #_EVENTDATES @ #_EVENTTIMES'.'<br/>'
 		    .__('Now there are #_BOOKEDSPACES spaces reserved, #_AVAILABLESPACES are still available.','events-manager').'<br/>'.
 		    strtoupper(__('Booking Details','events-manager')).'<br/>'.
 	 		__('Name','events-manager').' : #_BOOKINGNAME'.'<br/>'.
 		    __('Email','events-manager').' : #_BOOKINGEMAIL'.'<br/>'.
 		    '#_BOOKINGSUMMARY'.'<br/>'.
 		    '<br/>Powered by Events Manager - http://wp-events-plugin.com';
	$contact_person_emails['confirmed'] = sprintf(__('The following booking is %s :','events-manager'),strtolower(__('Confirmed','events-manager'))).'<br/>'.$contact_person_email_body_template;
	$contact_person_emails['pending'] = sprintf(__('The following booking is %s :','events-manager'),strtolower(__('Pending','events-manager'))).'<br/>'.$contact_person_email_body_template;
	$contact_person_emails['cancelled'] = sprintf(__('The following booking is %s :','events-manager'),strtolower(__('Cancelled','events-manager'))).'<br/>'.$contact_person_email_body_template;
	$contact_person_emails['rejected'] = sprintf(__('The following booking is %s :','events-manager'),strtolower(__('Rejected','events-manager'))).'<br/>'.$contact_person_email_body_template;
	//registration email content
	$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
	$booking_registration_email_subject = sprintf(__('[%s] Your username and password', 'events-manager'), $blogname);
	$booking_registration_email_body = "";
	$respondent_email_body_localizable = __("Dear #_BOOKINGNAME, <br />This is a reminder about your #_BOOKINGSPACES space/spaces reserved for #_EVENTNAME.<br />When : #_EVENTDATES @ #_EVENTTIMES<br />Where : #_LOCATIONNAME - #_LOCATIONFULLLINE<br />We look forward to seeing you there!<br />Yours faithfully,<br />#_CONTACTNAME",'events-manager').$email_footer;
	//all the options
	$dbem_options = array(
		
		//defaults
		'dbem_default_location'=>0,
		//Event List Options
		'dbem_events_default_orderby' => 'event_start_date,event_start_time,event_name',
		'dbem_events_default_order' => 'ASC',
		
		//Event Search Options
		'dbem_search_form_submit' => __('Search','events-manager'),
		'dbem_search_form_advanced' => 1,
		'dbem_search_form_advanced_hidden' => 1,
		'dbem_search_form_advanced_show' => __('Show Advanced Search','events-manager'),
		'dbem_search_form_advanced_hide' => __('Hide Advanced Search','events-manager'),
		'dbem_search_form_text' => 1,
		'dbem_search_form_text_label' => __('Search','events-manager'),
		'dbem_search_form_geo' => 1,
		'dbem_search_form_geo_label' => __('Near...','events-manager'),
		'dbem_search_form_geo_units' => 1,
		'dbem_search_form_geo_units_label' => __('Within','events-manager'),
		'dbem_search_form_geo_unit_default' => 'mi',
		'dbem_search_form_geo_distance_default' => 25,
	    'dbem_search_form_geo_distance_options' => '5,10,25,50,100',
		'dbem_search_form_dates' => 1,
		'dbem_search_form_dates_label' => __('Dates','events-manager'),
		'dbem_search_form_dates_separator' => __('and','events-manager'),
		'dbem_search_form_categories' => 1,
		'dbem_search_form_categories_label' => __('All Categories','events-manager'),
		'dbem_search_form_category_label' => __('Category','events-manager'),
		'dbem_search_form_countries' => 1,
		'dbem_search_form_default_country' => get_option('dbem_location_default_country',''),
		'dbem_search_form_countries_label' => __('All Countries','events-manager'),
		'dbem_search_form_country_label' => __('Country','events-manager'),
		'dbem_search_form_regions' => 1,
		'dbem_search_form_regions_label' => __('All Regions','events-manager'),
		'dbem_search_form_region_label' => __('Region','events-manager'),
		'dbem_search_form_states' => 1,
		'dbem_search_form_states_label' => __('All States','events-manager'),
		'dbem_search_form_state_label' => __('State/County','events-manager'),
		'dbem_search_form_towns' => 0,
		'dbem_search_form_towns_label' => __('All Cities/Towns','events-manager'),
		'dbem_search_form_town_label' => __('City/Town','events-manager'),
		'dbem_no_events_message' => sprintf(__( 'No %s', 'events-manager'),__('Events','events-manager')),
		//Location Formatting
		'dbem_no_locations_message' => sprintf(__( 'No %s', 'events-manager'),__('Locations','events-manager')),
		'dbem_location_default_country' => '',

		'dbem_location_event_single_format' => '#_EVENTLINK - #_EVENTDATES - #_EVENTTIMES',
		'dbem_location_no_event_message' => __('No events in this location', 'events-manager'),

		//Email Config
		'dbem_email_disable_registration' => 0,
		
		'dbem_smtp_html' => 1,
		'dbem_smtp_html_br' => 1,
		'dbem_smtp_encryption' => 'tls',
		'dbem_smtp_autotls' => true,

		//General Settings
		'dbem_locations_enabled' => 1,
		'dbem_recurrence_enabled'=> 1,
		'dbem_rsvp_enabled'=> 1,
		
		//Bookings
		'dbem_bookings_approval' => 1, //approval is on by default
		'dbem_bookings_approval_reserved' => 0, //overbooking before approval?
		'dbem_bookings_approval_overbooking' => 0, //overbooking possible when approving?
		
		'dbem_bookings_currency' => 'USD',
		
			//Form Options
		'dbem_bookings_submit_button' => __('Send your booking', 'events-manager'),	
		'dbem_bookings_form_max' => 20,
		'dbem_booking_feedback_pending' =>__('Booking successful, pending confirmation (you will also receive an email once confirmed).', 'events-manager'),
		'dbem_booking_feedback' => __('Booking successful.', 'events-manager'),
		'dbem_booking_feedback_full' => __('Booking cannot be made, not enough spaces available!', 'events-manager'),
		'dbem_booking_feedback_new_user' => __('A new user account has been created for you. Please check your email for access details.','events-manager'),
		'dbem_booking_feedback_reg_error' => __('There was a problem creating a user account, please contact a website administrator.','events-manager'),
		//Emails
		'dbem_bookings_notify_admin' => 0,
		'dbem_bookings_contact_email' => 1,
		'dbem_bookings_contact_email_pending_subject' => __("Booking Pending",'events-manager'),
		'dbem_bookings_contact_email_pending_body' => str_replace("<br/>", "\n\r", $contact_person_emails['pending']),
		'dbem_bookings_contact_email_confirmed_subject' => __('Booking Confirmed','events-manager'),
		'dbem_bookings_contact_email_confirmed_body' => str_replace("<br/>", "\n\r", $contact_person_emails['confirmed']),
		'dbem_bookings_contact_email_rejected_subject' => __("Booking Rejected",'events-manager'),
		'dbem_bookings_contact_email_rejected_body' => str_replace("<br/>", "\n\r", $contact_person_emails['rejected']),
		'dbem_bookings_contact_email_cancelled_subject' => __("Booking Cancelled",'events-manager'),
		'dbem_bookings_contact_email_cancelled_body' => str_replace("<br/>", "\n\r", $contact_person_emails['cancelled']),
		'dbem_bookings_email_pending_subject' => __("Booking Pending",'events-manager'),
		'dbem_bookings_email_pending_body' => str_replace("<br/>", "\n\r", $respondent_email_pending_body_localizable),
		'dbem_bookings_email_rejected_subject' => __("Booking Rejected",'events-manager'),
		'dbem_bookings_email_rejected_body' => str_replace("<br/>", "\n\r", $respondent_email_rejected_body_localizable),
		'dbem_bookings_email_confirmed_subject' => __('Booking Confirmed','events-manager'),
		'dbem_bookings_email_confirmed_body' => str_replace("<br/>", "\n\r", $respondent_email_body_localizable),
		'dbem_bookings_email_cancelled_subject' => __('Booking Cancelled','events-manager'),
		'dbem_bookings_email_cancelled_body' => str_replace("<br/>", "\n\r", $respondent_email_cancelled_body_localizable),
		//Registration Email
		//Ticket Specific Options
		'dbem_bookings_tickets_ordering' => 1,
		'dbem_bookings_tickets_orderby' => 'ticket_price DESC, ticket_name ASC',
		'dbem_bookings_tickets_priority' => 0,

		//My Bookings Page
		'dbem_bookings_my_title_format' => __('My Bookings','events-manager'),
		//Flags
		'dbem_hello_to_user' => 1,
		'dbem_cp_events_slug' => 'events',
		//event cp options
		'dbem_events_default_archive_order' => 'ASC',
	    'dbem_cp_events_excerpt_formats' => 1,
		'dbem_cp_events_search_results' => 0,
	    //feedback reminder
	    'dbem_feedback_reminder' => time(),
	    'dbem_conditional_recursions' => 1,
        //data privacy/protection
        'dbem_data_privacy_consent_text' => esc_html__('I consent to my submitted data being collected and stored as outlined by the site %s.','events-manager'),
        'dbem_data_privacy_consent_remember' => 1,
		'dbem_data_privacy_consent_events' => 1,
		'dbem_data_privacy_consent_locations' => 1,
		'dbem_data_privacy_consent_bookings' => 1,
		'dbem_data_privacy_export_events' => 1,
		'dbem_data_privacy_export_locations' => 1,
		'dbem_data_privacy_export_bookings' => 1,
		'dbem_data_privacy_erase_events' => 1,
		'dbem_data_privacy_erase_locations' => 1,
		'dbem_data_privacy_erase_bookings' => 1,

		'dbem_custom_emails' => 0,
		'dbem_custom_emails_events' => 1,
		'dbem_custom_emails_events_admins' => 1,
		'dbem_custom_emails_gateways' => 1,
		'dbem_custom_emails_gateways_admins' => 1,

		'dbem_bookings_ical_attachments' => 1,
		'dbem_multiple_bookings_ical_attachments' => 1,
		//email reminders
		'dbem_cron_emails' => 0,
		'dbem_cron_emails_limit' => get_option('emp_cron_emails_limit', 100),
		'dbem_emp_emails_reminder_subject' => __('Reminder','events-manager').' - #_EVENTNAME',
		'dbem_emp_emails_reminder_body' => str_replace("<br />", "\n\r", $respondent_email_body_localizable),
		'dbem_emp_emails_reminder_time' => '12:00 AM',
		'dbem_emp_emails_reminder_days' => 1,
		'dbem_emp_emails_reminder_ical' => 1,
		//offline
		'em_offline_option_name' => __('Pay Offline', 'events-manager'),
		'em_offline_booking_feedback' => __('Booking successful.', 'events-manager'),
		'em_offline_button' => __('Pay Offline', 'events-manager'),
		'emp_gateway_customer_fields' => ['address' => 'dbem_address','address_2' => 'dbem_address_2','city' => 'dbem_city','state' => 'dbem_state','zip' => 'dbem_zip','country' => 'dbem_country','phone' => 'dbem_phone','fax' => 'dbem_fax','company' => 'dbem_company'],
		'em_user_fields' => [
			'dbem_address' => array ( 'label' => __('Address','events-manager'), 'type' => 'text', 'fieldid'=>'dbem_address', 'required'=>1 ),
			'dbem_address_2' => array ( 'label' => __('Address Line 2','events-manager'), 'type' => 'text', 'fieldid'=>'dbem_address_2' ),
			'dbem_city' => array ( 'label' => __('City/Town','events-manager'), 'type' => 'text', 'fieldid'=>'dbem_city', 'required'=>1 ),
			'dbem_state' => array ( 'label' => __('State/County','events-manager'), 'type' => 'text', 'fieldid'=>'dbem_state', 'required'=>1 ),
			'dbem_zip' => array ( 'label' => __('Zip/Post Code','events-manager'), 'type' => 'text', 'fieldid'=>'dbem_zip', 'required'=>1 ),
			'dbem_country' => array ( 'label' => __('Country','events-manager'), 'type' => 'country', 'fieldid'=>'dbem_country', 'required'=>1 ),
			'dbem_phone' => array ( 'label' => __('Phone','events-manager'), 'type' => 'text', 'fieldid'=>'dbem_phone' ),
			'dbem_fax' => array ( 'label' => __('Fax','events-manager'), 'type' => 'text', 'fieldid'=>'dbem_fax' ),
			'dbem_company' => array ( 'label' => __('Company','events-manager'), 'type' => 'text', 'fieldid'=>'dbem_company' ),
		]
		
	);
	
	//do date js according to locale:
	$locale_code = substr ( get_locale (), 0, 2 );
	
	//add new options
	foreach($dbem_options as $key => $value){
		add_option($key, $value);
	}

	$booking_form_data = array( 'name'=> __('Default','events-manager'), 'form'=> array (
		'name' => array ( 'label' => __('Name','events-manager'), 'type' => 'name', 'fieldid'=>'user_name', 'required'=>1 ),
		'user_email' => array ( 'label' => __('Email','events-manager'), 'type' => 'user_email', 'fieldid'=>'user_email', 'required'=>1 ),
		  'dbem_address' => array ( 'label' => __('Address','events-manager'), 'type' => 'dbem_address', 'fieldid'=>'dbem_address', 'required'=>1 ),
		  'dbem_city' => array ( 'label' => __('City/Town','events-manager'), 'type' => 'dbem_city', 'fieldid'=>'dbem_city', 'required'=>1 ),
		  'dbem_state' => array ( 'label' => __('State/County','events-manager'), 'type' => 'dbem_state', 'fieldid'=>'dbem_state', 'required'=>1 ),
		  'dbem_zip' => array ( 'label' => __('Zip/Post Code','events-manager'), 'type' => 'dbem_zip', 'fieldid'=>'dbem_zip', 'required'=>1 ),
		  'dbem_country' => array ( 'label' => __('Country','events-manager'), 'type' => 'dbem_country', 'fieldid'=>'dbem_country', 'required'=>1 ),
		  'dbem_phone' => array ( 'label' => __('Phone','events-manager'), 'type' => 'dbem_phone', 'fieldid'=>'dbem_phone' ),
		  'booking_comment' => array ( 'label' => __('Comment','events-manager'), 'type' => 'textarea', 'fieldid'=>'booking_comment' ),
	  ));
  
	  //Booking form stuff only run on install
	$wpdb->insert(EM_META_TABLE, array('meta_value'=>serialize($booking_form_data), 'meta_key'=>'booking-form','object_id'=>0));
	add_option('em_booking_form_fields', $wpdb->insert_id);
		
	
}

function em_upgrade_current_installation(){
	
	if( get_option('dbem_version') != '' && get_option('dbem_version') < 6.4 ) {
		
		delete_option('dbem_multiple_bookings_email_cancelled_body');
		delete_option('dbem_multiple_bookings_email_cancelled_subject');
		delete_option('dbem_multiple_bookings_email_rejected_body');
		delete_option('dbem_multiple_bookings_email_rejected_subject');
		delete_option('dbem_multiple_bookings_email_pending_body');
		delete_option('dbem_multiple_bookings_email_pending_subject');
		delete_option('dbem_multiple_bookings_email_confirmed_body');
		delete_option('dbem_multiple_bookings_email_confirmed_subject');
		delete_option('dbem_multiple_bookings_contact_email_cancelled_body');
		delete_option('dbem_multiple_bookings_contact_email_cancelled_subject');
		delete_option('dbem_multiple_bookings_contact_email_pending_body');
		delete_option('dbem_multiple_bookings_contact_email_pending_subject');
		delete_option('dbem_multiple_bookings_contact_email_confirmed_body');
		delete_option('dbem_multiple_bookings_contact_email_confirmed_subject');
		delete_option('dbem_multiple_bookings_feedback_added_body');
		delete_option('dbem_multiple_bookings_feedback_added');
		delete_option('dbem_multiple_bookings_feedback_already_added');
		delete_option('dbem_multiple_bookings_feedback_no_bookings');
		delete_option('dbem_multiple_bookings_feedback_loading_cart');
		delete_option('dbem_multiple_bookings_feedback_empty_cart');
		delete_option('dbem_multiple_bookings_submit_button');
		delete_option('dbem_multiple_bookings_ical_attachments');
		delete_option('em_authorize_aim_option_name');
		delete_option('em_authorize_aim_booking_feedback');
		delete_option('em_authorize_aim_booking_feedback_free');
		delete_option('em_pro_data');
		delete_option('dbem_disable_css');
		delete_option('dbem_bookings_form_msg_few_spaces');
		delete_option('dbem_bookings_form_msg_disabled');
		delete_option('dbem_bookings_form_msg_closed');
		delete_option('dbem_bookings_form_msg_full');
		delete_option('dbem_bookings_form_msg_few_spaces');
		delete_option('dbem_time_format');
		delete_option('dbem_dates_separator');
		delete_option('dbem_times_separator');
		delete_option('dbem_event_list_item_format_header');
		delete_option('dbem_event_list_item_format_footer');
		delete_option('dbem_event_list_groupby');
		delete_option('dbem_event_list_groupby_format');
		delete_option('dbem_event_list_groupby_header_format');
		delete_option('dbem_event_excerpt_format');
	    delete_option('dbem_event_excerpt_alt_format');
		delete_option('dbem_locations_default_orderby');
		delete_option('dbem_locations_default_order');
		delete_option('dbem_locations_page_title');
		delete_option('dbem_locations_page_search_form');
		delete_option('dbem_location_list_item_format_header');
		delete_option('dbem_location_list_item_format');
		delete_option('dbem_location_list_item_format_footer');
		delete_option('dbem_location_page_title_format');
		delete_option('dbem_location_event_list_item_header_format');
		delete_option('dbem_location_event_list_item_format');
		delete_option('dbem_location_event_list_item_footer_format');
		delete_option('dbem_single_location_format');
		delete_option('dbem_location_excerpt_format');
	    delete_option('dbem_location_excerpt_alt_format');
		delete_option('dbem_location_event_list_item_header_format');
		delete_option('dbem_location_event_list_item_format');
		delete_option('dbem_location_event_list_item_footer_format');
		delete_option('dbem_location_event_list_limit');
		delete_option('dbem_location_event_list_orderby');
		delete_option('dbem_location_event_list_order');
		delete_option('dbem_location_no_events_message');
		delete_option('dbem_image_max_width');
		delete_option('dbem_image_max_height');
		delete_option('dbem_image_min_width');
		delete_option('dbem_image_min_height');
		delete_option('dbem_image_max_size');
		delete_option('dbem_category_event_list_item_header_format');
		delete_option('dbem_category_event_list_item_format');
		delete_option('dbem_category_event_list_item_footer_format');
		delete_option('dbem_category_event_list_orderby');
		delete_option('dbem_google_maps_browser_key');
		delete_option('dbem_map_default_width');
		delete_option('dbem_map_default_height');
		delete_option('dbem_location_baloon_format');
		delete_option('dbem_map_text_format');
		delete_option('dbem_placeholders_custom');
		delete_option('dbem_bp_events_list_format_header');
		delete_option('dbem_bp_events_list_format');
		delete_option('dbem_bp_events_list_format_footer');
		delete_option('dbem_bp_events_list_none_format');
		delete_option('dbem_list_date_title');
		delete_option('dbem_css_editors');
		delete_option('dbem_css_rsvp');
		delete_option('dbem_css_rsvpadmin');
		delete_option('dbem_css_evlist');
		delete_option('dbem_css_search');
		delete_option('dbem_css_loclist');
		delete_option('dbem_css_catlist');
		delete_option('dbem_css_taglist');
		delete_option('dbem_cp_locations_body_class');
		delete_option('dbem_cp_locations_post_class');
		delete_option('dbem_tags_default_limit');
		delete_option('dbem_tags_default_orderby');
		delete_option('dbem_tags_default_order');
		delete_option('dbem_tags_list_item_format_header');
		delete_option('dbem_tags_list_item_format');
		delete_option('dbem_tags_list_item_format_footer');
		delete_option('dbem_no_tags_message');
		delete_option('dbem_tag_page_title_format');
		delete_option('dbem_tag_page_format');
		delete_option('dbem_tag_no_events_message');
		delete_option('dbem_tag_event_list_item_header_format');
		delete_option('dbem_tag_event_list_item_format');
		delete_option('dbem_tag_event_list_item_footer_format');
		delete_option('dbem_tag_event_single_format');
		delete_option('dbem_tag_no_event_message');
		delete_option('dbem_tag_event_list_limit');
		delete_option('dbem_tag_event_list_orderby');
		delete_option('dbem_tag_event_list_order');
		delete_option('dbem_tag_default_color');
		delete_option('dbem_tags_default_archive_orderby');
		delete_option('dbem_tags_default_archive_order');
		delete_option('dbem_categories_default_limit');
		delete_option('dbem_categories_default_orderby');
		delete_option('dbem_categories_default_order');
		delete_option('dbem_categories_list_item_format_header');
		delete_option('dbem_categories_list_item_format');
		delete_option('dbem_categories_list_item_format_footer');
		delete_option('dbem_no_categories_message');
		delete_option('dbem_category_page_title_format');
		delete_option('dbem_category_page_format');
		delete_option('dbem_category_no_events_message');
		delete_option('dbem_category_event_list_limit');
		delete_option('dbem_category_event_list_order');
		delete_option('dbem_category_event_single_format');
		delete_option('dbem_category_no_event_message');
		delete_option('dbem_category_default_color');
		delete_option('dbem_categories_default_archive_orderby');
		delete_option('dbem_categories_default_archive_order');
		delete_option('dbem_cp_categories_formats');
		delete_option('dbem_cp_tags_formats');
		delete_option('dbem_disable_thumbnails');
		delete_option('dbem_bookings_currency_format');
		delete_option('dbem_event_all_day_message');
		delete_option("dbem_gateway_label");
		delete_option('dbem_booking_feedback_log_in');
		delete_option('dbem_booking_feedback_already_booked');
		delete_option('dbem_booking_button_msg_book');
		delete_option('dbem_booking_button_msg_booking');
		delete_option('dbem_booking_button_msg_booked');
		delete_option('dbem_booking_feedback_cancelled');
		delete_option('dbem_booking_warning_cancel');
		delete_option('dbem_booking_button_msg_error');
		delete_option('dbem_booking_button_msg_full');
		delete_option('dbem_booking_button_msg_closed');
		delete_option('dbem_booking_feedback_spaces_limit');
		delete_option('dbem_thumbnails_enabled');
		delete_option('dbem_emp_booking_form_error_required');
		delete_option('dbem_emp_booking_form_reg_show');
		delete_option('dbem_booking_feedback_email_exists');
		delete_option('dbem_bookings_currency_decimal_point');
		delete_option('dbem_bookings_currency_thousands_sep');
		delete_option('dbem_booking_feedback_min_space');
		delete_option('dbem_booking_feedback_nomail');
		delete_option('dbem_booking_feedback_error');
		delete_option('dbem_rss_main_title');
		delete_option('dbem_rss_main_description');
		delete_option('dbem_rss_limit');
		delete_option('dbem_rss_description_format');
		delete_option('dbem_rss_title_format');
		delete_option('dbem_rss_order');
		delete_option('dbem_rss_scope');
		delete_option('dbem_rss_orderby');
		delete_option('em_rss_pubdate');
		delete_option('dbem_cp_locations_slug');
		delete_option('dbem_taxonomy_category_slug');
		delete_option('dbem_taxonomy_tag_slug');
		delete_option('dbem_ical_description_format');
		delete_option('dbem_ical_real_description_format');
		delete_option('dbem_ical_location_format');
		delete_option('dbem_ical_limit');
		delete_option('dbem_ical_scope');
		delete_option('dbem_cp_locations_formats');
		delete_option('dbem_cp_locations_comments');
		delete_option('dbem_cp_locations_archive_formats');
	    delete_option('dbem_cp_locations_excerpt_formats');
		delete_option('dbem_cp_locations_search_results');
		delete_option('dbem_cp_locations_template');
		delete_option('dbem_cp_locations_has_archive');
		delete_option('dbem_locations_default_archive_orderby');
		delete_option('dbem_locations_default_archive_order');
		delete_option('dbem_locations_default_limit');
		delete_option('dbem_data');
		delete_option('dbem_bookings_email_registration_subject');
		delete_option('dbem_bookings_email_registration_body');
		delete_option('dbem_rsvp_mail_port');
		delete_option('dbem_smtp_host');
		delete_option('dbem_mail_sender_name');
		delete_option('dbem_rsvp_mail_send_method');
		delete_option('dbem_rsvp_mail_SMTPAuth');
		
	}

	//update version
	if( get_option('dbem_version') != '' && get_option('dbem_version') < 6.7 ) {
		global $wpdb;
		$wpdb->query('ALTER TABLE '.EM_EVENTS_TABLE.' ADD COLUMN event_rsvp_end datetime NULL DEFAULT NULL');
		$wpdb->query('ALTER TABLE '.EM_EVENTS_TABLE.' ADD COLUMN event_rsvp_start datetime NULL DEFAULT NULL');
		$wpdb->query('ALTER TABLE '.EM_EVENTS_TABLE.' ADD COLUMN event_speaker_id bigint(20) unsigned NULL DEFAULT NULL');
		$wpdb->query('ALTER TABLE '.EM_EVENTS_TABLE.' ADD COLUMN event_audience text NULL DEFAULT NULL');
		$wpdb->query('ALTER TABLE '.EM_EVENTS_TABLE.' DROP COLUMN event_rsvp_date');
		$wpdb->query('ALTER TABLE '.EM_EVENTS_TABLE.' DROP COLUMN event_rsvp_time');
	}

	if( get_option('dbem_version') != '' && get_option('dbem_version') < 6.8 ) {
		global $wpdb;
		$wpdb->query('ALTER TABLE '.EM_BOOKINGS_TABLE.' ADD COLUMN booking_donation decimal(10,2) NULL DEFAULT NULL');
		$wpdb->query('ALTER TABLE '.EM_BOOKINGS_TABLE.' DROP COLUMN booking_tax');
		$wpdb->query('ALTER TABLE '.EM_BOOKINGS_TABLE.' DROP COLUMN booking_tax_rate');
		$wpdb->query('ALTER TABLE '.EM_COUPONS_TABLE.' DROP COLUMN coupon_tax');
		$wpdb->query('ALTER TABLE '.EM_TICKETS_TABLE.' DROP COLUMN ticket_parent');
		
		delete_option('dbem_bookings_tax');
		delete_option('dbem_bookings_tax_auto_add');
}

function em_set_mass_caps( $roles, $caps ){
	global $wp_roles;
	foreach( $roles as $user_role ){
		foreach($caps as $cap){
			$wp_roles->add_cap($user_role, $cap);
		}
	}
}

function em_set_capabilities(){
	//Get default roles
	global $wp_roles;
	if( get_option('dbem_version') == '' ){
		//Assign caps in groups, as we go down, permissions are "looser"
		$caps = array(
			/* Event Capabilities */
			'publish_events', 'delete_others_events', 'edit_others_events', 'manage_others_bookings',
			/* Recurring Event Capabilties */
			'publish_recurring_events', 'delete_others_recurring_events', 'edit_others_recurring_events',
			/* Location Capabilities */
			'publish_locations', 'delete_others_locations',	'delete_locations', 'edit_others_locations',
			/* Category Capabilities */
			'delete_event_categories', 'edit_event_categories'
		);
		em_set_mass_caps( array('administrator','editor'), $caps );

		//Add all the open caps
		$loose_caps = array(
			'manage_bookings', 'upload_event_images',
			/* Event Capabilities */
			'delete_events', 'edit_events', 'read_private_events',
			/* Recurring Event Capabilties */
			'delete_recurring_events', 'edit_recurring_events',
			/* Location Capabilities */
			'edit_locations', 'read_private_locations', 'read_others_locations',
		);
		em_set_mass_caps( array('administrator','editor','contributor','author'), $loose_caps);
		
		//subscribers can read private stuff, nothing else
		$wp_roles->add_cap('subscriber', 'read_private_locations');
		$wp_roles->add_cap('subscriber', 'read_private_events');
	}
	if( get_option('dbem_version')  && get_option('dbem_version') < 5 ){
		//Add new caps that are similar to old ones
		$conditional_caps = array(
			'publish_events' => 'publish_locations,publish_recurring_events',
			'edit_others_events' => 'edit_others_recurring_events',
			'delete_others_events' => 'delete_others_recurring_events',
			'edit_categories' => 'edit_event_categories,delete_event_categories',
			'edit_recurrences' => 'edit_recurring_events,delete_recurring_events',
			'edit_events' => 'upload_event_images'
		);
		$default_caps = array( 'read_private_events', 'read_private_locations' );
		foreach($conditional_caps as $cond_cap => $new_caps){
			foreach( $wp_roles->role_objects as $role_name => $role ){
				if($role->has_cap($cond_cap)){
					foreach(explode(',', $new_caps) as $new_cap){
						$role->add_cap($new_cap);
					}
				}
			}
		}
		em_set_mass_caps( array('administrator','editor','contributor','author','subscriber'), $default_caps);
	}
}

function em_migrate_locations($locations){
	global $wpdb;
	//disable actions
	remove_action('save_post',array('EM_Location_Post_Admin','save_post'));
	$location_metas = array(); //restart metas
	$post_fields = array('post_id','location_slug','location_name','post_content','location_owner');
	foreach($locations as $location){
		//new post info
		$post_array = array();
		$post_array['post_type'] = EM_POST_TYPE_LOCATION;
		$post_array['post_title'] = $location['location_name'];
		$post_array['post_content'] = $location['post_content'];
		$post_array['post_status'] = 'publish';
		$post_array['post_author'] = $location['location_owner'];
		//Save post, register post id in index
		$post_id = wp_insert_post($post_array);
		if( is_wp_error($post_id) || $post_id == 0 ){ $post_id = 999999999999999999; }//hopefully nobody blogs that much... if you do, and you're reading this, maybe you should be hiring me for the upgrade ;) }
		$wpdb->query('UPDATE '.EM_LOCATIONS_TABLE." SET post_id='$post_id' WHERE location_id='{$location['location_id']}'");
		//meta
 		foreach($location as $meta_key => $meta_val){
 			if( !in_array($meta_key, $post_fields) ){
	 			$location_metas[] = $wpdb->prepare("(%d, '%s', '%s')", array($post_id, '_'.$meta_key, $meta_val));
 			}
 		}
	}
 	//insert the metas in one go, faster than one by one
 	if( count($location_metas) > 0 ){
	 	$result = $wpdb->query("INSERT INTO ".$wpdb->postmeta." (post_id,meta_key,meta_value) VALUES ".implode(',',$location_metas));
 	}
}


function em_migrate_datetime_timezones( $reset_new_fields = true, $migrate_date_fields = true, $timezone = false ){
	global $wpdb;
	//Table names
	$db = $wpdb->prefix;
	//create AND and WHERE conditions for blog IDs if we're in Multisite Glboal Mode
	$blog_id_where = $blog_id_and = '';
	
	//reset all the data for these purposes
	if( $reset_new_fields || $migrate_date_fields ) $wpdb->query('UPDATE '. $db.'em_events' .' SET event_start = NULL, event_end = NULL, event_timezone = NULL'.$blog_id_where);
	if( !$migrate_date_fields ) return true;
	
	//start migration of old date formats to new datetime formats in local and UTC mode along with a declared timezone
	$migration_results = $migration_meta_results = $migration_errors = array();
	//firstly, we do a query for all-day events and reset the times, so that UTC times are correct relative to the local time
	$migration_result = $wpdb->query('UPDATE '. $db.'em_events'." SET event_start_time = '00:00:00', event_end_time = '23:59:59' WHERE event_all_day = 1".$blog_id_and);
	if( $migration_result === false ) $migration_errors[] = array('Local datetime allday event times modification errors', $wpdb->last_error);
	
	//migration procedure depends on whether we have an actual timezone or just a manual offset of hours in the WP settings page
	if( empty($timezone) ){
		$timezone = get_option('timezone_string');
		if( empty($timezone) ){ 
			$timezone = get_option('gmt_offset');
			$timezone = preg_match('/[+\-]/', $timezone) ? 'UTC'.$timezone : 'UTC+'.$timezone;
		}
	}
	if( !preg_match('/^UTC/', $timezone) ){
		//we'll get the minimum start/end dates in our events, and get the transitions for this range
		$transitions = em_migrate_get_tz_transitions($timezone, $blog_id_where);
		//now, build the SQL statements with the transitions
		$query_data = array();
		$where_start = $where_end = array();
		//go through each transition and add it to the right offset array key
		foreach( $transitions as $t ){
			//format start/end transitions for mysql DATETIME format IN CORRECT TIMEZONE
			$start = $t['start'] ? date('Y-m-d H:i:s', $t['start'] + $t['offset']) : false;
			$end = $t['end'] ? date('Y-m-d H:i:s', $t['end'] + $t['offset']) : false;
			//set up SQL statement for offset if it doesn't exist, but without the WHERE clause, which we'll add later
			if( empty($query_data[$t['offset']]) ){
				$query_data[$t['offset']] = array(
						'start' => array(
								'sql' => $wpdb->prepare('UPDATE '. $db.'em_events'. ' SET event_start = DATE_SUB(TIMESTAMP(event_start_date,event_start_time), INTERVAL %d SECOND)', $t['offset']),
								'where' => array()
						),
						'end' => array(
								'sql' => $wpdb->prepare('UPDATE '. $db.'em_events'. ' SET event_end = DATE_SUB(TIMESTAMP(event_end_date, event_end_time), INTERVAL %d SECOND)', $t['offset']),
								'where' => array()
						)
				);
			}
			//create array of conditions, which we'll join into single statements for each unique offset amount
			if( $start && $end ){
				$query_data[$t['offset']]['start']['where'][] = $wpdb->prepare("(TIMESTAMP(event_start_date,event_start_time) BETWEEN %s AND %s)", $start, $end);
				$query_data[$t['offset']]['end']['where'][] = $wpdb->prepare("(TIMESTAMP(event_end_date, event_end_time) BETWEEN %s AND %s)", $start, $end);
			}elseif( $start ){
				$query_data[$t['offset']]['start']['where'][] = $wpdb->prepare("(TIMESTAMP(event_start_date,event_start_time) > %s)", $start);
				$query_data[$t['offset']]['end']['where'][] = $wpdb->prepare("(TIMESTAMP(event_end_date, event_end_time) > %s)", $start);
			}elseif( $end ){
				$query_data[$t['offset']]['start']['where'][] = $wpdb->prepare("(TIMESTAMP(event_start_date,event_start_time) < %s)", $end);
				$query_data[$t['offset']]['end']['where'][] = $wpdb->prepare("(TIMESTAMP(event_end_date, event_end_time) < %s)", $end);
			}
		}
		//glue the where clauses together with SQLs and create the minimum required statements to run this update
		$sql_array = array();
		foreach( $query_data as $offset => $statements ){
			$migration_result = $wpdb->query($statements['start']['sql'] .' WHERE event_start IS NULL AND ('. implode(' OR ', $statements['start']['where']).')'.$blog_id_and);
			if( $migration_result === false ) $migration_errors[] = array('Event start UTC transition',$wpdb->last_error);
			$migration_result = $wpdb->query($statements['end']['sql'] ." WHERE event_end IS NULL AND (". implode(' OR ', $statements['end']['where']).')'.$blog_id_and);
			if( $migration_result === false ) $migration_errors[] = array('Event end UTC transation', $wpdb->last_error);
		}
	}else{
		//This gets very easy... just do a single query that copies over all the times to right columns with relevant offset
		$EM_DateTimeZone = EM_DateTimeZone::create($timezone);
		$offset = $timezone == 'UTC' ? 0 : $EM_DateTimeZone->manual_offset / MINUTE_IN_SECONDS;
		$timezone = $EM_DateTimeZone->getName();
		$migration_result = $wpdb->query($wpdb->prepare('UPDATE '. $db.'em_events'. ' SET event_start = DATE_SUB(TIMESTAMP(event_start_date,event_start_time), INTERVAL %d MINUTE), event_end = DATE_SUB(TIMESTAMP(event_end_date, event_end_time), INTERVAL %d MINUTE) WHERE event_end IS NULL '.$blog_id_and, $offset, $offset));
		if( $migration_result === false ) $migration_errors[] = array('Event start/end UTC offset', $wpdb->last_error);
	}
	
	//set the timezone (on initial migration all events have same timezone of blog)
	$migration_result = $wpdb->query($wpdb->prepare('UPDATE '. $db.'em_events' .' SET event_timezone = %s WHERE event_timezone IS NULL'.$blog_id_and, $timezone));
	if( $migration_result === false ) $migration_errors[] = array('Event timezone setting', $wpdb->last_error);
	
	//reave meta data - at this point once we've copied over all of the dates, so we do 5 queries to postmeta, one for each field we've created above start/end times in local/utc and timezone
	if( empty($migration_errors) ){
		//delete all previously added fields, in case they were added before
		$sql = 'DELETE FROM '.$wpdb->postmeta." WHERE meta_key IN ('_event_start','_event_end','_event_timezone', '_event_start_local', '_event_end_local') AND post_id IN (SELECT ID FROM ".$wpdb->posts." WHERE post_type='".EM_Event::POST_TYPE."' OR post_type='event-recurring')";
		$migration_result = $wpdb->query($sql);
		if( $migration_result === false ) $migration_errors[] = array('Previous meta deletion', $wpdb->last_error);
		foreach( array('event_start', 'event_end', 'event_timezone', 'start', 'end') as $field ){
			if( $field == 'start' || $field == 'end' ){
				//create a timestamp combining two given fields, which we'll now use 
				$sql = 'INSERT INTO '.$wpdb->postmeta." (post_id, meta_key, meta_value) SELECT post_id, '_event_{$field}_local', TIMESTAMP(event_{$field}_date, event_{$field}_time) FROM ".$db . 'em_events'. $blog_id_where;
				$field = "event_".$field."_local";
			}else{
				$sql = 'INSERT INTO '.$wpdb->postmeta." (post_id, meta_key, meta_value) SELECT post_id, '_{$field}', {$field} FROM ". $db.'em_events'. $blog_id_where;
			}
			$migration_result = $wpdb->query($sql);
			if( $migration_result === false ) $migration_errors[] = array('Adding new meta data key <em>_'.$field.'</em>', $wpdb->last_error);
		}
	}
	
	//return the result of this migration, either true for no errors, or a string of errors.
	if( !empty($migration_errors) ){
		$string = __('There was an error whilst migrating your times to our new timezone-aware formats. Below is a list of errors:', 'events-manager');
		$string .= '<ul>';
		foreach( $migration_errors as $err ){
			$string .= '<li><strong>'. $err[0] .': </strong>'. $err[1] .'</li>';
		}
		$string .= '</ul>';
		return $string;
	}
	return true;
}

function em_migrate_get_tz_transitions( $timezone, $blog_id_where = '' ){
	global $wpdb;
	$db = $wpdb->prefix;
	$minmax_dates = $wpdb->get_row('SELECT MIN(event_start_date) AS mindate, MAX(event_end_date) AS maxdate FROM '. $db.'em_events' .$blog_id_where);
	$DTZ = new EM_DateTimeZone( $timezone );
	$start = strtotime($minmax_dates->mindate, current_time('timestamp')) - 60*60*24;
	$end = strtotime($minmax_dates->maxdate, current_time('timestamp')) + 60*60*24; //we add a day just to get the most comprehensive range possible
	$DTZ_Transitions = $DTZ->getTransitions($start, $end);
	//get first and next transitions to create a range, if there's only one transition we create a fake transition
	$transitions = array();
	if( count($DTZ_Transitions) == 1 ){
		$current_transition = current($DTZ_Transitions);
		$dst_offset = $current_transition['isdst'] ? 60*60 : 0;
		$transitions[] = array(
			'start' => $start,
			'end' => false,
			'offset' => $current_transition['offset']
		);
	}else{
		do{
			$current_transition = current($DTZ_Transitions);
			$transition_key = key($DTZ_Transitions);
			$next_transition = next($DTZ_Transitions);
			if( $current_transition['ts'] < $start ) continue;
			$dst_offset = $current_transition['isdst'] ? 60*60 : 0;
			if( $transition_key == 0 ){
				//add the final transition and break this loop
				$transitions[] = array(
				'start' => false,
				'end' => $next_transition['ts'] - 1 - $dst_offset,
				'offset' => $current_transition['offset']
				);
			}else{
				if( empty($next_transition) ){
					//add the final transition and break this loop
					$transitions[] = array(
					'start' => $current_transition['ts'] - $dst_offset,
					'end' => false,
					'offset' => $current_transition['offset']
					);
					break;
				}else{
					$transitions[] = array(
							'start' => $current_transition['ts'] - $dst_offset,
							'end' => $next_transition['ts'] - 1 - $dst_offset,
							'offset' => $current_transition['offset']
					);
				}
			}
			if( $current_transition['ts'] > $end ) break;
		} while( $next_transition !== false );
	}
	return $transitions;
}
?>