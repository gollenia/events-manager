<?php

function emp_install() {
	$old_version = get_option('em_pro_version');
	if( $old_version == '' ){

		emp_create_transactions_table();
		emp_create_coupons_table(); 
		emp_create_reminders_table();
		emp_create_bookings_relationships_table();
		delete_option('emp_ms_global_install'); //in case for some reason the user changed global settings
	 	
		emp_add_options();
		//trigger update action
		do_action('events_manager_pro_updated');
		//Update Version	
	  	update_option('em_pro_version', 'installed');
	  	//flush tables
	  	global $wp_rewrite;
	  	$wp_rewrite->flush_rules(true);
	}
}

/**
 * Since WP 4.2 tables are created with utf8mb4 collation. This creates problems when storing content in previous utf8 tables such as when using emojis. 
 * This function checks whether the table in WP was changed 
 * @return boolean
 */
function emp_check_utf8mb4_tables(){
		global $wpdb, $emp_check_utf8mb4_tables;
		
		if( $emp_check_utf8mb4_tables || $emp_check_utf8mb4_tables === false ) return $emp_check_utf8mb4_tables;
		
		$column = $wpdb->get_row( "SHOW FULL COLUMNS FROM {$wpdb->posts} WHERE Field='post_content';" );
		if ( ! $column ) {
			return false;
		}
		
		//if this doesn't become true further down, that means we couldn't find a correctly converted utf8mb4 posts table 
		$emp_check_utf8mb4_tables = false;
		
		if ( $column->Collation ) {
			list( $charset ) = explode( '_', $column->Collation );
			$emp_check_utf8mb4_tables = ( 'utf8mb4' === strtolower( $charset ) );
		}
		return $emp_check_utf8mb4_tables;
		
}


/**
 * Magic function that takes a table name and cleans all non-unique keys not present in the $clean_keys array. if no array is supplied, all but the primary key is removed.
 * @param string $table_name
 * @param array $clean_keys
 */
function emp_sort_out_table_nu_keys($table_name, $clean_keys = array()){
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
		$wpdb->query("ALTER TABLE $table_name ADD INDEX ($key)");
	}
}

function emp_create_transactions_table() {
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
	emp_sort_out_table_nu_keys($table_name,array('transaction_gateway','booking_id'));
	if( emp_check_utf8mb4_tables() ) maybe_convert_table_to_utf8mb4( $table_name );
}

function emp_create_coupons_table() {
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
		  coupon_tax varchar(4) DEFAULT NULL,
		  coupon_discount decimal(14,2) NOT NULL,
		  coupon_eventwide bool NOT NULL DEFAULT 0,
		  coupon_sitewide bool NOT NULL DEFAULT 0,
		  coupon_private bool NOT NULL DEFAULT 0,
		  PRIMARY KEY  (coupon_id)
		) DEFAULT CHARSET=utf8 ;";
	dbDelta($sql);
	$array = array('coupon_owner','coupon_code');
	emp_sort_out_table_nu_keys($table_name,$array);
	if( emp_check_utf8mb4_tables() ) maybe_convert_table_to_utf8mb4( $table_name );
}

function emp_create_reminders_table(){
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
	emp_sort_out_table_nu_keys($table_name,array('event_id','booking_id'));
	if( emp_check_utf8mb4_tables() ) maybe_convert_table_to_utf8mb4( $table_name );
}

function emp_create_bookings_relationships_table(){
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
	emp_sort_out_table_nu_keys($table_name,array('event_id','booking_id','booking_main_id'));
	if( emp_check_utf8mb4_tables() ) maybe_convert_table_to_utf8mb4( $table_name );
}

function emp_add_options() {
	global $wpdb;
	add_option('em_pro_data', array());
	add_option('dbem_disable_css',false); //TODO - remove this or create dependency in admin settings
	//Form Stuff
	$booking_form_data = array( 'name'=> __('Default','em-pro'), 'form'=> array (
	  'name' => array ( 'label' => __('Name','events-manager'), 'type' => 'name', 'fieldid'=>'user_name', 'required'=>1 ),
	  'user_email' => array ( 'label' => __('Email','events-manager'), 'type' => 'user_email', 'fieldid'=>'user_email', 'required'=>1 ),
    	'dbem_address' => array ( 'label' => __('Address','events-manager'), 'type' => 'dbem_address', 'fieldid'=>'dbem_address', 'required'=>1 ),
    	'dbem_city' => array ( 'label' => __('City/Town','events-manager'), 'type' => 'dbem_city', 'fieldid'=>'dbem_city', 'required'=>1 ),
    	'dbem_state' => array ( 'label' => __('State/County','events-manager'), 'type' => 'dbem_state', 'fieldid'=>'dbem_state', 'required'=>1 ),
    	'dbem_zip' => array ( 'label' => __('Zip/Post Code','em-pro'), 'type' => 'dbem_zip', 'fieldid'=>'dbem_zip', 'required'=>1 ),
    	'dbem_country' => array ( 'label' => __('Country','events-manager'), 'type' => 'dbem_country', 'fieldid'=>'dbem_country', 'required'=>1 ),
    	'dbem_phone' => array ( 'label' => __('Phone','events-manager'), 'type' => 'dbem_phone', 'fieldid'=>'dbem_phone' ),
	  	'booking_comment' => array ( 'label' => __('Comment','events-manager'), 'type' => 'textarea', 'fieldid'=>'booking_comment' ),
	));
	add_option('dbem_emp_booking_form_error_required', __('Please fill in the field: %s','em-pro'));
    $new_fields = array(
    	'dbem_address' => array ( 'label' => __('Address','events-manager'), 'type' => 'text', 'fieldid'=>'dbem_address', 'required'=>1 ),
    	'dbem_address_2' => array ( 'label' => __('Address Line 2','events-manager'), 'type' => 'text', 'fieldid'=>'dbem_address_2' ),
    	'dbem_city' => array ( 'label' => __('City/Town','events-manager'), 'type' => 'text', 'fieldid'=>'dbem_city', 'required'=>1 ),
    	'dbem_state' => array ( 'label' => __('State/County','events-manager'), 'type' => 'text', 'fieldid'=>'dbem_state', 'required'=>1 ),
    	'dbem_zip' => array ( 'label' => __('Zip/Post Code','em-pro'), 'type' => 'text', 'fieldid'=>'dbem_zip', 'required'=>1 ),
    	'dbem_country' => array ( 'label' => __('Country','events-manager'), 'type' => 'country', 'fieldid'=>'dbem_country', 'required'=>1 ),
    	'dbem_phone' => array ( 'label' => __('Phone','events-manager'), 'type' => 'text', 'fieldid'=>'dbem_phone' ),
    	'dbem_fax' => array ( 'label' => __('Fax','em-pro'), 'type' => 'text', 'fieldid'=>'dbem_fax' ),
    	'dbem_company' => array ( 'label' => __('Company','em-pro'), 'type' => 'text', 'fieldid'=>'dbem_company' ),
    );
	add_option('em_user_fields', $new_fields);
	$customer_fields = array('address' => 'dbem_address','address_2' => 'dbem_address_2','city' => 'dbem_city','state' => 'dbem_state','zip' => 'dbem_zip','country' => 'dbem_country','phone' => 'dbem_phone','fax' => 'dbem_fax','company' => 'dbem_company');
    add_option('emp_gateway_customer_fields', $customer_fields);
    add_option('em_attendee_fields_enabled', defined('EM_ATTENDEES') && EM_ATTENDEES );
	//Gateway Stuff
    
    add_option('dbem_emp_booking_form_reg_show', 1);
	add_option('dbem_gateway_use_buttons', 0);
	add_option('dbem_gateway_label', __('Pay With','em-pro'));
	
	//offline
	add_option('em_offline_option_name', __('Pay Offline', 'em-pro'));
	add_option('em_offline_booking_feedback', __('Booking successful.', 'events-manager'));
	add_option('em_offline_button', __('Pay Offline', 'em-pro'));
	//authorize.net
	add_option('em_authorize_aim_option_name', __('Credit Card', 'em-pro'));
	add_option('em_authorize_aim_booking_feedback', __('Booking successful.', 'events-manager'));
	add_option('em_authorize_aim_booking_feedback_free', __('Booking successful. You have not been charged for this booking.', 'em-pro'));
	//ical attachments
	$ical_attachments = get_option('em_pro_version') !== false ? 0:1;
	add_option('dbem_bookings_ical_attachments', $ical_attachments);
	add_option('dbem_multiple_bookings_ical_attachments', $ical_attachments);
	//email reminders
	add_option('dbem_cron_emails', 0);
	add_option('dbem_cron_emails_limit', get_option('emp_cron_emails_limit', 100));
	add_option('dbem_emp_emails_reminder_subject', __('Reminder','em-pro').' - #_EVENTNAME');
	$email_footer = '<br /><br />-------------------------------<br />Powered by Events Manager - http://wp-events-plugin.com';
	$respondent_email_body_localizable = __("Dear #_BOOKINGNAME, <br />This is a reminder about your #_BOOKINGSPACES space/spaces reserved for #_EVENTNAME.<br />When : #_EVENTDATES @ #_EVENTTIMES<br />Where : #_LOCATIONNAME - #_LOCATIONFULLLINE<br />We look forward to seeing you there!<br />Yours faithfully,<br />#_CONTACTNAME",'em-pro').$email_footer;
	add_option('dbem_emp_emails_reminder_body', str_replace("<br />", "\n\r", $respondent_email_body_localizable));
	add_option('dbem_emp_emails_reminder_time', '12:00 AM');
	add_option('dbem_emp_emails_reminder_days', 1);	
	add_option('dbem_emp_emails_reminder_ical', 1);
	//custom emails
	add_option('dbem_custom_emails', 0);
	add_option('dbem_custom_emails_events', 1);	
	add_option('dbem_custom_emails_events_admins', 1);
	add_option('dbem_custom_emails_gateways', 1);
	add_option('dbem_custom_emails_gateways_admins', 1);	
	//multiple bookings
	add_option('dbem_multiple_bookings_feedback_added', __('Your booking was added to your shopping cart.','em-pro'));
	add_option('dbem_multiple_bookings_feedback_already_added', __('You have already booked a spot at this eventin your cart, please modify or delete your current booking.','em-pro'));
	add_option('dbem_multiple_bookings_feedback_no_bookings', __('You have not booked any events yet. Your cart is empty.','em-pro'));
	add_option('dbem_multiple_bookings_feedback_loading_cart', __('Loading Cart Contents...','em-pro'));
	add_option('dbem_multiple_bookings_feedback_empty_cart', __('Are you sure you want to empty your cart?','em-pro'));
	add_option('dbem_multiple_bookings_submit_button', __('Place Order','em_pro'));
	//multiple bookings - emails
	$contact_person_email_body_template = strtoupper(__('Booking Details'))."\n\r".
		__('Name','events-manager').' : #_BOOKINGNAME'."\n\r".
		__('Email','events-manager').' : #_BOOKINGEMAIL'."\n\r".
		'#_BOOKINGSUMMARY';
		$contact_person_emails['confirmed'] = sprintf(__('The following booking is %s :'),strtolower(__('Confirmed')))."\n\r".$contact_person_email_body_template;
		$contact_person_emails['pending'] = sprintf(__('The following booking is %s :'),strtolower(__('Pending')))."\n\r".$contact_person_email_body_template;
		$contact_person_emails['cancelled'] = sprintf(__('The following booking is %s :'),strtolower(__('Cancelled')))."\n\r".$contact_person_email_body_template;
	
		add_option('dbem_multiple_bookings_contact_email_confirmed_subject', __("Booking Confirmed"));
	$respondent_email_body_localizable = sprintf(__('The following booking is %s :'),strtolower(__('Confirmed')))."\n\r".$contact_person_email_body_template;
	add_option('dbem_multiple_bookings_contact_email_confirmed_body', $respondent_email_body_localizable);
	
	add_option('dbem_multiple_bookings_contact_email_pending_subject', __("Booking Pending"));
	$respondent_email_body_localizable = sprintf(__('The following booking is %s :'),strtolower(__('Pending')))."\n\r".$contact_person_email_body_template;
	add_option('dbem_multiple_bookings_contact_email_pending_body', $respondent_email_body_localizable);
	
	add_option('dbem_multiple_bookings_contact_email_cancelled_subject', __('Booking Cancelled','em-pro'));
	$respondent_email_body_localizable = sprintf(__('The following booking is %s :'),strtolower(__('Cancelled')))."\n\r".$contact_person_email_body_template;
	add_option('dbem_multiple_bookings_contact_email_cancelled_body', $respondent_email_body_localizable);
	
	add_option('dbem_multiple_bookings_email_confirmed_subject', __('Booking Confirmed','em-pro'));
	$respondent_email_body_localizable = __("Dear #_BOOKINGNAME, <br />Your booking has been confirmed. <br />Below is a summary of your booking: <br />#_BOOKINGSUMMARY <br />We look forward to seeing you there!",'em-pro').$email_footer;
	add_option('dbem_multiple_bookings_email_confirmed_body', str_replace("<br />", "\n\r", $respondent_email_body_localizable));
	
	add_option('dbem_multiple_bookings_email_pending_subject', __('Booking Pending','em-pro'));
	$respondent_email_body_localizable = __("Dear #_BOOKINGNAME, <br />Your booking is currently pending approval by our administrators. Once approved you will receive another confirmation email. <br />Below is a summary of your booking: <br />#_BOOKINGSUMMARY",'em-pro').$email_footer;
	add_option('dbem_multiple_bookings_email_pending_body', str_replace("<br />", "\n\r", $respondent_email_body_localizable));
	
	add_option('dbem_multiple_bookings_email_rejected_subject', __('Booking Rejected','em-pro'));
	$respondent_email_body_localizable = __("Dear #_BOOKINGNAME, <br />Your requested booking has been rejected. <br />Below is a summary of your booking: <br />#_BOOKINGSUMMARY",'em-pro').$email_footer;
	add_option('dbem_multiple_bookings_email_rejected_body', str_replace("<br />", "\n\r", $respondent_email_body_localizable));
	
	add_option('dbem_multiple_bookings_email_cancelled_subject', __('Booking Cancelled','em-pro'));
	$respondent_email_body_localizable = __("Dear #_BOOKINGNAME, <br />Your requested booking has been cancelled. <br />Below is a summary of your booking: <br />#_BOOKINGSUMMARY",'em-pro').$email_footer;
	add_option('dbem_multiple_bookings_email_cancelled_body', str_replace("<br />", "\n\r", $respondent_email_body_localizable));
	
	//Version updates
	
		
		
		
		
		if( get_option('em_pro_version') < 2.643 ){ //transition into new license, but don't deactivate their site immediately.
			
			
		}
		else{
			//Booking form stuff only run on install
			$wpdb->insert(EM_META_TABLE, array('meta_value'=>serialize($booking_form_data), 'meta_key'=>'booking-form','object_id'=>0));
			add_option('em_booking_form_fields', $wpdb->insert_id);
		}
}     
?>