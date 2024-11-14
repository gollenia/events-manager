<?php
/**
 * Performs actions on init. This works for both ajax and normal requests, the return results depends if an em_ajax flag is passed via POST or GET.
 * 
 * TODO: This whole file must be split up and the wp_ajax_ functions should be replaced with the REST API
 */
function em_init_actions() {
	global $wpdb,$EM_Notices,$EM_Event; 
	if( defined('DOING_AJAX') && DOING_AJAX ) $_REQUEST['em_ajax'] = true;
	
	//NOTE - No EM objects are globalized at this point, as we're hitting early init mode.
	//TODO Clean this up.... use a uniformed way of calling EM Ajax actions
	if( !empty($_REQUEST['em_ajax']) || !empty($_REQUEST['em_ajax_action']) ){
		if(isset($_REQUEST['em_ajax_action']) && $_REQUEST['em_ajax_action'] == 'get_location') {
			if(isset($_REQUEST['id'])){
				$EM_Location = new EM_Location( absint($_REQUEST['id']), 'location_id' );
				$location_array = $EM_Location->to_array();
		     	echo json_encode($location_array);
			}
			die();
		}   
		if(isset($_REQUEST['query']) && $_REQUEST['query'] == 'GlobalMapData') {
			$EM_Locations = EM_Locations::get( $_REQUEST );
			$json_locations = array();
			foreach($EM_Locations as $location_key => $EM_Location) {
				$json_locations[$location_key] = $EM_Location->to_array();
			}
			echo json_encode($json_locations);
		 	die();
	 	}
		if(isset($_REQUEST['query']) && $_REQUEST['query'] == 'GlobalEventsMapData') {
			$_REQUEST['has_location'] = true; //we're looking for locations in this context, so locations necessary
			$_REQUEST['groupby'] = 'location_id'; //grouping will generally produce much faster processing
			$EM_Events = EM_Events::get( $_REQUEST );
			$json_locations = array();
			$locations = array();
			foreach($EM_Events as $EM_Event) {
				$EM_Location = $EM_Event->get_location();
				$location_array = $EM_Event->get_location()->to_array();
				$json_locations[] = $location_array;
			}
			echo json_encode($json_locations);
		 	die();   
	 	}
	}
	
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
		$allowed_actions = array('bookings_approve'=>'approve','bookings_reject'=>'reject','bookings_unapprove'=>'unapprove', 'bookings_delete'=>'delete');
		$result = false;
		$feedback = '';
		
		//TODO user action shouldn't check permission, booking object should.
	  	if( array_key_exists($_REQUEST['action'], $allowed_actions) && $EM_Event->can_manage('manage_bookings','manage_others_bookings') ){
	  		//Event Admin only actions
			$action = $allowed_actions[$_REQUEST['action']];
			//Just do it here, since we may be deleting bookings of different events.
			if( !empty($_REQUEST['bookings']) && is_array($_REQUEST['bookings']) && array_is_list($_REQUEST['bookings'])){
				$results = array();
				foreach($_REQUEST['bookings'] as $booking_id){
					$EM_Booking = EM_Booking::find($booking_id);
					$result = $EM_Booking->$action();
					$results[] = $result;
					if( !in_array(false, $results) && !$result ){
						$feedback = $EM_Booking->feedback_message;
					}
				}
				$result = !in_array(false,$results);
			}elseif( is_object($EM_Booking) ){
				$result = $EM_Booking->$action();
				$feedback = $EM_Booking->feedback_message;
			}
			//FIXME not adhereing to object's feedback or error message, like other bits in this file.
			//TODO multiple deletion won't work in ajax
			if( !empty($_REQUEST['em_ajax']) ){
				if( $result ){
					echo $feedback;
				}else{
					echo '<span style="color:red">'.$feedback.'</span>';
				}	
				die();
			}else{
			    if( $result ){
			        $EM_Notices->add_confirm($feedback);
			    }else{
			        $EM_Notices->add_error($feedback);
			    }
			}
		}elseif( $_REQUEST['action'] == 'booking_set_status' ){
			
			if( $EM_Booking->can_manage('manage_bookings','manage_others_bookings') && $_REQUEST['booking_status'] != $EM_Booking->booking_status ){
				if ( $EM_Booking->set_status($_REQUEST['booking_status'], false, true) ){
					if( !empty($_REQUEST['send_email']) ){
						if( $EM_Booking->email() ){
						    if( $EM_Booking->mails_sent > 0 ) {
						        $EM_Booking->feedback_message .= " ".__('Email Sent.','events');
						    }else{
						        $EM_Booking->feedback_message .= " "._x('No emails to send for this booking.', 'bookings', 'events');
						    }
						}else{
							$EM_Booking->feedback_message .= ' <span style="color:red">'.__('ERROR : Email Not Sent.','events').'</span>';
						}
					}
					$EM_Notices->add_confirm( $EM_Booking->feedback_message, true );
					$redirect = !empty($_REQUEST['redirect_to']) ? $_REQUEST['redirect_to'] : wp_validate_redirect(wp_get_raw_referer(), false );
					wp_safe_redirect( $redirect );
					exit();
				}else{
					$result = false;
					$EM_Notices->add_error( $EM_Booking->get_errors() );
					$feedback = $EM_Booking->feedback_message;	
				}	
			}
		}elseif( $_REQUEST['action'] == 'booking_resend_email' ){
			
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

		header( 'Content-Type: application/javascript; charset=UTF-8', true );
		$return = array('result'=>$result, 'message'=>$feedback, 'error'=>$EM_Booking->get_errors());
		echo json_encode(apply_filters('em_action_'.$_REQUEST['action'], $return, $EM_Booking));
		wp_die();
		
	}
	
	//AJAX call for searches
	if( !empty($_REQUEST['action']) && substr($_REQUEST['action'],0,6) == 'search' ){
		//default search arts
		if( $_REQUEST['action'] == 'search_states' ){
			$results = array();
			$conds = array();
			if( !empty($_REQUEST['country']) ){
				$conds[] = $wpdb->prepare("(location_country = '%s' OR location_country IS NULL )", $_REQUEST['country']);
			}
			if( !empty($_REQUEST['region']) ){
				$conds[] = $wpdb->prepare("( location_region = '%s' )", $_REQUEST['region']);
			}
			$cond = (count($conds) > 0) ? "AND ".implode(' AND ', $conds):'';
			$results = $wpdb->get_col("SELECT DISTINCT location_state FROM " . EM_LOCATIONS_TABLE ." WHERE location_state IS NOT NULL AND location_state != '' $cond ORDER BY location_state");
			if( $_REQUEST['return_html'] ) {
				//quick shortcut for quick html form manipulation
				ob_start();
				?>
				<option value=''><?php echo get_option('dbem_search_form_states_label') ?></option>
				<?php
				foreach( $results as $result ){
					echo "<option>{$result}</option>";
				}
				$return = ob_get_clean();
				echo apply_filters('em_ajax_search_states', $return);
				exit();
			}else{
				echo json_encode($results);
				exit();
			}
		}
		if( $_REQUEST['action'] == 'search_towns' ){
			$results = array();
			$conds = array();
			if( !empty($_REQUEST['country']) ){
				$conds[] = $wpdb->prepare("(location_country = '%s' OR location_country IS NULL )", $_REQUEST['country']);
			}
			if( !empty($_REQUEST['region']) ){
				$conds[] = $wpdb->prepare("( location_region = '%s' )", $_REQUEST['region']);
			}
			if( !empty($_REQUEST['state']) ){
				$conds[] = $wpdb->prepare("(location_state = '%s' )", $_REQUEST['state']);
			}
			$cond = (count($conds) > 0) ? "AND ".implode(' AND ', $conds):'';
			$results = $wpdb->get_col("SELECT DISTINCT location_town FROM " . EM_LOCATIONS_TABLE ." WHERE location_town IS NOT NULL AND location_town != '' $cond  ORDER BY location_town");
			if( $_REQUEST['return_html'] ) {
				//quick shortcut for quick html form manipulation
				ob_start();
				?>
				<option value=''><?php echo get_option('dbem_search_form_towns_label'); ?></option>
				<?php			
				foreach( $results as $result ){
					echo "<option>$result</option>";
				}
				$return = ob_get_clean();
				echo apply_filters('em_ajax_search_towns', $return);
				exit();
			}else{
				echo json_encode($results);
				exit();
			}
		}
		if( $_REQUEST['action'] == 'search_regions' ){
			$results = array();
			if( !empty($_REQUEST['country']) ){
				$conds[] = $wpdb->prepare("(location_country = '%s' )", $_REQUEST['country']);
			}
			$cond = (count($conds) > 0) ? "AND ".implode(' AND ', $conds):'';
			$results = $wpdb->get_results("SELECT DISTINCT location_region AS value FROM " . EM_LOCATIONS_TABLE ." WHERE location_region IS NOT NULL AND location_region != '' $cond  ORDER BY location_region");
			if( $_REQUEST['return_html'] ) {
				//quick shortcut for quick html form manipulation
				ob_start();
				?>
				<option value=''><?php echo get_option('dbem_search_form_regions_label'); ?></option>
				<?php	
				foreach( $results as $result ){
					echo "<option>{$result->value}</option>";
				}
				$return = ob_get_clean();
				echo apply_filters('em_ajax_search_regions', $return);
				exit();
			}else{
				echo json_encode($results);
				exit();
			}
		}
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