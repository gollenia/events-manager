<?php
/**
 * Performs actions on init. This works for both ajax and normal requests, the return results depends if an em_ajax flag is passed via POST or GET.
 * 
 * @todo This whole file must be split up and the wp_ajax_ functions should be replaced with the REST API where possible
 */
function em_init_actions() {
	global $wpdb,$EM_Notices,$EM_Event; 
	if( defined('DOING_AJAX') && DOING_AJAX ) $_REQUEST['em_ajax'] = true;
	
	//Event Actions
	if( !empty($_REQUEST['action']) && substr($_REQUEST['action'],0,5) == 'event' ){
		//Load the event object, with saved event if requested
		if( !empty($_REQUEST['event_id']) ){
			$EM_Event = new EM_Event( absint($_REQUEST['event_id']) );
		}else{
			$EM_Event = new EM_Event();
		}
		
		if ( $_REQUEST['action'] == 'event_duplicate' && wp_verify_nonce($_REQUEST['_wpnonce'],'event_duplicate_'.$EM_Event->event_id) ) {
			$event = $EM_Event->duplicate();
			if( $event === false ){
				$EM_Notices->add_error($EM_Event->errors, true);
				wp_safe_redirect( wp_validate_redirect(wp_get_raw_referer(), false ) );
			}else{
				$EM_Notices->add_confirm($event->feedback_message, true);
				wp_safe_redirect( $event->get_edit_url() );
			}
			exit();
		}
		if ( $_REQUEST['action'] == 'event_delete' && wp_verify_nonce($_REQUEST['_wpnonce'],'event_delete_'.$EM_Event->event_id) ) { 
			//DELETE action
			$selectedEvents = !empty($_REQUEST['events']) ? $_REQUEST['events']:'';
			if( is_array($selectedEvents) && array_is_list($selectedEvents) && !empty($selectedEvents) ){
				$events_result = EM_Events::delete( $selectedEvents );
			}elseif( is_object($EM_Event) ){
				$events_result = $EM_Event->delete();
			}		
			$plural = (count($selectedEvents) > 1) ? __('Events','events'):__('Event','events');
			if($events_result){
				$message = ( !empty($EM_Event->feedback_message) ) ? $EM_Event->feedback_message : sprintf(__('%s successfully deleted.','events'),$plural);
				$EM_Notices->add_confirm( $message, true );
			}else{
				$message = ( !empty($EM_Event->errors) ) ? $EM_Event->errors : sprintf(__('%s could not be deleted.','events'),$plural);
				$EM_Notices->add_error( $message, true );		
			}
			wp_safe_redirect( wp_validate_redirect(wp_get_raw_referer(), false ) );
			exit();
		}elseif( $_REQUEST['action'] == 'event_detach' && wp_verify_nonce($_REQUEST['_wpnonce'],'event_detach_'.get_current_user_id().'_'.$EM_Event->event_id) ){ 
			//Detach event and move on
			if($EM_Event->detach()){
				$EM_Notices->add_confirm( $EM_Event->feedback_message, true );
			}else{
				$EM_Notices->add_error( $EM_Event->errors, true );			
			}
			wp_safe_redirect(wp_validate_redirect(wp_get_raw_referer(), false ));
			exit();
		}elseif( $_REQUEST['action'] == 'event_attach' && !empty($_REQUEST['undo_id']) && wp_verify_nonce($_REQUEST['_wpnonce'],'event_attach_'.get_current_user_id().'_'.$EM_Event->event_id) ){ 
			//Detach event and move on
			if( $EM_Event->attach( absint($_REQUEST['undo_id']) ) ){
				$EM_Notices->add_confirm( $EM_Event->feedback_message, true );
			}else{
				$EM_Notices->add_error( $EM_Event->errors, true );
			}
			wp_safe_redirect(wp_validate_redirect(wp_get_raw_referer(), false ));
			exit();
		}
		
		//AJAX Exit
		if( isset($events_result) && !empty($_REQUEST['em_ajax']) ){
			if( $events_result ){
				$return = array('result'=>true, 'message'=>$EM_Event->feedback_message);
			}else{		
				$return = array('result'=>false, 'message'=>$EM_Event->feedback_message, 'errors'=>$EM_Event->errors);
			}
			echo json_encode($return);
			exit();
		}
	}

	
	//Booking Actions
	if( !empty($_REQUEST['action']) && substr($_REQUEST['action'],0,7) == 'booking' && (is_user_logged_in() || ($_REQUEST['action'] == 'booking_add')) ){
		
		global $EM_Event, $EM_Booking;
		//Load the booking object, with saved booking if requested
		$EM_Booking = ( !empty($_REQUEST['booking_id']) ) ? EM_Booking::find($_REQUEST['booking_id']) : EM_Booking::find();
		if( !empty($EM_Booking->event_id) ){
			//Load the event object, with saved event if requested
			$EM_Event = $EM_Booking->get_event();
		}elseif( !empty($_REQUEST['event_id']) ){
			$EM_Event = new EM_Event( absint($_REQUEST['event_id']) );
		}
		$allowed_actions = array('bookings_approve'=>'approve','bookings_reject'=>'reject','bookings_unapprove'=>'unapprove', 'bookings_delete'=>'delete', 'bookings_cancel'=>'cancel');
		$result = false;
		$feedback = '';
		
		if( $_REQUEST['action'] == 'booking_resend_email' ){
			
			if( $EM_Booking->can_manage('manage_bookings','manage_others_bookings') ){
				if( $EM_Booking->email(false, true) ){
				    if( $EM_Booking->mails_sent > 0 ) {
				        $EM_Notices->add_confirm( __('Email Sent.','events'), true );
				    }else{
				        $EM_Notices->add_confirm( _x('No emails to send for this booking.', 'bookings', 'events'), true );
				    }
					$redirect = !empty($_REQUEST['redirect_to']) ? $_REQUEST['redirect_to'] : wp_validate_redirect(wp_get_raw_referer(), false );
					wp_safe_redirect( $redirect );
					exit();
				}else{
					$result = false;
					$EM_Notices->add_error( __('ERROR : Email Not Sent.','events') );			
					$feedback = $EM_Booking->feedback_message;
				}	
			}
		}

		//header( 'Content-Type: application/javascript; charset=UTF-8', true );
		$return = array('result'=>$result, 'message'=>$feedback, 'error'=>$EM_Booking->get_errors());
		var_dump($return);
		//wp_die();
	}

		
	//EM Ajax requests require this flag.
	if( is_user_logged_in() ){
		//Admin operations
		//Specific Oject Ajax
		if( !empty($_REQUEST['em_obj']) && $_REQUEST['em_obj'] == 'em_bookings_events_table' ){
			include_once('admin/bookings/em-events.php');
			em_bookings_events_table();
			exit();
		}
	}
	//Export CSV - WIP
	if( !empty($_REQUEST['action']) && $_REQUEST['action'] == 'export_bookings_csv' && wp_verify_nonce($_REQUEST['_wpnonce'], 'export_bookings_csv')){
		if( !empty($_REQUEST['event_id']) ){
			$EM_Event = EM_Event::find( absint($_REQUEST['event_id']) );
		}
		//sort out cols
		if( !empty($_REQUEST['cols']) && is_array($_REQUEST['cols']) ){
			$cols = array();
			foreach($_REQUEST['cols'] as $col => $active){
				if( $active ){ $cols[] = $col; }
			}
			$_REQUEST['cols'] = $cols;
		}
		$_REQUEST['limit'] = 0;
		
		//generate bookings export according to search request
		$show_tickets = !empty($_REQUEST['show_tickets']);
		$EM_Bookings_Table = new EM_Bookings_Table($show_tickets);
	
	
		
		$EM_Bookings_Table->limit = 350; //if you're having server memory issues, try messing with this number
		$EM_Bookings = $EM_Bookings_Table->get_bookings();
		
		$excel_sheet = [$EM_Bookings_Table->get_headers(true)];
		
		while( !empty($EM_Bookings->bookings) ){
			foreach( $EM_Bookings->bookings as $EM_Booking ) { /* @var EM_Booking $EM_Booking */
				//Display all values
				if( $show_tickets ){
					foreach($EM_Booking->get_tickets_bookings()->tickets_bookings as $ticket_booking){ 
						$row = $EM_Bookings_Table->get_row_csv($ticket_booking);
						array_push($excel_sheet, $row);
					}
				}else{
					$row = $EM_Bookings_Table->get_row_csv($EM_Booking);
					array_push($excel_sheet, $row);
				}
			}
			//reiterate loop
			$EM_Bookings_Table->offset += $EM_Bookings_Table->limit;
			$EM_Bookings = $EM_Bookings_Table->get_bookings();
		}
		$xlsx = Shuchkin\SimpleXLSXGen::fromArray( $excel_sheet );
		$xlsx->downloadAs($EM_Event->event_slug . '-bookings.xlsx');
		
		exit();
	}
}
add_action('init','em_init_actions',11);

/**
 * Handles AJAX Bookings admin table filtering, view changes and pagination
 */
function em_ajax_bookings_table(){
    check_admin_referer('em_bookings_table');
	$EM_Bookings_Table = new EM_Bookings_Table();
	$EM_Bookings_Table->output_table();
	exit();
}
add_action('wp_ajax_em_bookings_table','em_ajax_bookings_table');

/**
 * Handles AJAX Searching and Pagination for events, locations, tags and categories
 */
function em_ajax_search_and_pagination(){
	$args = array( 'owner' => false, 'pagination' => 1, 'ajax' => true);
	echo '<div class="em-search-ajax">';
	ob_start();
	if( $_REQUEST['action'] == 'search_events' ){
		$args['scope'] = 'future';
		$args = EM_Events::get_post_search($args);
		$args['limit'] = !empty($args['limit']) ? $args['limit'] : 0;
	}elseif( $_REQUEST['action'] == 'search_events_grouped' && defined('DOING_AJAX') ){
		$args['scope'] = 'future';
		$args = EM_Events::get_post_search($args);
		$args['limit'] = !empty($args['limit']) ? $args['limit'] : 0;
		em_locate_template('templates/events-list-grouped.php', true, array('args'=>$args)); //if successful, this template overrides the settings and defaults, including search
	}elseif( $_REQUEST['action'] == 'search_locations' && defined('DOING_AJAX') ){
		$args = EM_Locations::get_post_search($args);
		$args['limit'] = !empty($args['limit']) ? $args['limit'] : 20;
		em_locate_template('templates/locations-list.php', true, array('args'=>$args)); //if successful, this template overrides the settings and defaults, including search
	}elseif( $_REQUEST['action'] == 'search_tags' && defined('DOING_AJAX') ){
		$args = EM_Tags::get_post_search($args);
		$args['limit'] = !empty($args['limit']) ? $args['limit'] : 20;
		em_locate_template('templates/tags-list.php', true, array('args'=>$args)); //if successful, this template overrides the settings and defaults, including search
	}elseif( $_REQUEST['action'] == 'search_cats' && defined('DOING_AJAX') ){
		$args = EM_Categories::get_post_search($args);
		$args['limit'] = !empty($args['limit']) ? $args['limit'] : 20;
		em_locate_template('templates/categories-list.php', true, array('args'=>$args)); //if successful, this template overrides the settings and defaults, including search
	}
	echo '</div>';
	echo apply_filters('em_ajax_'.$_REQUEST['action'], ob_get_clean(), $args);
	exit();
}
add_action('wp_ajax_nopriv_search_events','em_ajax_search_and_pagination');
add_action('wp_ajax_search_events','em_ajax_search_and_pagination');
add_action('wp_ajax_nopriv_search_events_grouped','em_ajax_search_and_pagination');
add_action('wp_ajax_search_events_grouped','em_ajax_search_and_pagination');
add_action('wp_ajax_nopriv_search_locations','em_ajax_search_and_pagination');
add_action('wp_ajax_search_locations','em_ajax_search_and_pagination');
add_action('wp_ajax_nopriv_search_tags','em_ajax_search_and_pagination');
add_action('wp_ajax_search_tags','em_ajax_search_and_pagination');
add_action('wp_ajax_nopriv_search_cats','em_ajax_search_and_pagination');
add_action('wp_ajax_search_cats','em_ajax_search_and_pagination');


?>