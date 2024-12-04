<?php
include('AttendeeForm.php');
class EM_Attendees_Form {
	static $validate;
	/**
	 * @var EM_Attendee_Form
	 */
	static $form;
	static $form_id;
	static $form_name;
	static $form_template;
	
	public static function init(){
		//Exporting
		add_action('init', array('EM_Attendees_Form', 'intercept_csv_export'),10); //show booking form and ticket summary
		add_action('em_bookings_table_export_options', array('EM_Attendees_Form', 'em_bookings_table_export_options')); //show booking form and ticket summary
		
		//Booking interception - will not trigger on multi-booking checkout
		add_filter('em_booking_get_post', array('EM_Attendees_Form', 'em_booking_get_post'), 2, 2); //get post data + validate
		add_filter('em_booking_validate', array('EM_Attendees_Form', 'em_booking_validate'), 2, 2); //validate object

		//data privacy
		add_filter('em_data_privacy_export_bookings_item', 'EM_Attendees_Form::data_privacy_export', 10, 2);
		
	}
	
	
	/**
	 * Gets the default form structure for creating a new form
	 * @return array
	 */
	public static function get_form_template(){
	    if( empty(self::$form_template )){
    		self::$form_template = apply_filters('em_attendees_form_get_form_template', array (
				'attendee_name' => array ( 'label' => __('Name','events'), 'type' => 'text', 'fieldid'=>'attendee_name', 'required'=>1 )
    		));	        
	    }
	    return self::$form_template;
	}
	
	/**
	 * Get the EM_Attendee_Form (Extended EM_Form)
	 * @param EM_Event $EM_Event
	 * @return EM_Attendee_Form
	 */
	public static function get_form($EM_Event = false){
		if( empty(self::$form) || (!empty($EM_Event) && (empty(self::$form->event_id) || $EM_Event->event_id != self::$form->event_id)) ){

			if(is_numeric($EM_Event)){ $EM_Event = EM_Event::find($EM_Event); }
			
			self::$form_id = get_post_meta($EM_Event->post_id, '_attendee_form', true);

			$form_data = EM_Form::get_form_data(self::$form_id);

			if(empty($form_data)) {
				$form_data = array('form' => self::get_form_template());
				self::$form_name = __('Default','events');
			}

			self::$form_name = get_the_title(self::$form_id);
			self::$form = new EM_Attendee_Form($form_data, 'em_attendee_form', false);
			self::$form->form_required_error = __('Please fill in the field: %s','events');
		}
		return self::$form;
	}

	

	public static function get_attendee_form($event_id){
		$form_id = get_post_meta($event_id, '_attendee_form', true);
		$form_data = EM_Form::get_form_data($form_id, false);
		return $form_data;
	}
	
	/**
	 * Gets the form ID to use from a given EM_Event object or returns the default form id if not defined or no object passed
	 * @param EM_Event $EM_Event
	 */
	public static function get_form_id($EM_Event = false){
		$custom_form_id = ( !empty($EM_Event->post_id) ) ? get_post_meta($EM_Event->post_id, '_custom_attendee_form', true):0;
		$form_id = empty($custom_form_id) ? get_option('em_attendee_form_fields') : $custom_form_id;
	    return $form_id;
	}
	
	
	/**
	 * Converts the relevant field names to be relevant for attendees format (i.e. in an array due to unknown number of attendees per booking)
	 * @param EM_Attendee_Form $form
	 * @param Ticket $ticket
	 * @return EM_Attendee_Form
	 */
	public static function get_ticket_form($form, $ticket){
		//modify field ids to contain ticket number and []
		foreach($form->form_fields as $field_id => $form_data){
		    if( $form_data['type'] == 'date' || $form_data['type'] == 'time'){
				$form->form_fields[$field_id]['name'] = "em_attendee_fields[".$ticket->ticket_id."][$field_id][%s][]";
		    }elseif( in_array($form_data['type'], array('radio','checkboxes','multiselect')) ){
			    $form->form_fields[$field_id]['name'] = "em_attendee_fields[".$ticket->ticket_id."][$field_id][%n]";
			}else{
				$form->form_fields[$field_id]['name'] = "em_attendee_fields[".$ticket->ticket_id."][$field_id][]";
		    }
		}
		return $form;
	}
	
	/**
	 * Returns a formatted multi-dimensional associative array of attendee information for a specific booking, split by ticket > attendee > attendee data.
	 * example : array('ticket_id' => array('Attendee 1' => array('Label'=>'Value', 'Label 2'=>'Value 2'), 'Attendee 2' => array(...)...)...);
	 * @param EM_Booking $EM_Booking
	 */
	public static function get_booking_attendees( $EM_Booking ){
		$attendee_data = array();
		foreach( $EM_Booking->get_tickets_bookings()->tickets_bookings as $ticket_booking ){ 
			//Display ticket info
			if( !empty($EM_Booking->booking_meta['attendees'][$ticket_booking->ticket_id]) && is_array($EM_Booking->booking_meta['attendees'][$ticket_booking->ticket_id]) ){
			    $ticket_booking->booking = $EM_Booking; //avoid extra loading in sub-function
			    $attendee_data[$ticket_booking->ticket_id] = self::get_ticket_attendees($ticket_booking);
			}else{
				$attendee_data[$ticket_booking->ticket_id] = array();
				for($i=1; $i <= $ticket_booking->ticket_booking_spaces; $i++){
					$key = sprintf(__('Attendee %s','events'), $i);
					$attendee_data[$ticket_booking->ticket_id][$key] = array();
				}
			}
		}
		return $attendee_data;
	}
	
	/**
	 * Returns a formatted multi-dimensional associative array of attendee information for a specific booking ticket.
	 * example : array('Attendee 1' => array('Label'=>'Value', 'Label 2'=>'Value 2'), 'Attendee 2' => array(...)...);
	 * @param TicketBooking $ticket_booking
	 * @param boolean $padding
	 * @return array $attendees
	 */
	public static function get_ticket_attendees( $ticket_booking, $padding = false ){
	    $attendees = array();
    	$EM_Form = EM_Attendees_Form::get_form($ticket_booking->get_booking()->event_id); //can be repeated since object is stored temporarily
	    if( !empty($ticket_booking->get_booking()->booking_meta['attendees'][$ticket_booking->ticket_id]) && is_array($ticket_booking->get_booking()->booking_meta['attendees'][$ticket_booking->ticket_id]) ){
			$i = 1; //counter
	    	foreach( $ticket_booking->get_booking()->booking_meta['attendees'][$ticket_booking->ticket_id] as $field_values ){
	    		$EM_Form->field_values = $field_values;
	    		//output the field values
				
	    		$key = sprintf(__('Attendee %s','events'), $i);
	    		$attendees[$key] = array();
	    		foreach( $EM_Form->form_fields as $fieldid => $field){
					if( $field['type'] == 'html' ) continue;
	    			
					$field_value = (isset($EM_Form->field_values[$fieldid])) ? $EM_Form->field_values[$fieldid]:'n/a';
					$field_label = (isset($field['label'])) ? $field['label']:$fieldid;
					
					$attendees[$key][$field_label] = $EM_Form->get_formatted_value($field, $field_value);
	    		}
	    		$i++;
		    }
	    }elseif( $padding ){
	    	//no attendees so pad with empty values
	    	for( $space_no = 1; $space_no <= $ticket_booking->ticket_booking_spaces; $space_no++ ){
	    		$key = sprintf(__('Attendee %s','events'), $space_no);
	    		$attendees[$key] = array();
	    		foreach( $EM_Form->form_fields as $fieldid => $field){
	    			if( $field['type'] != 'html' ){
	    				$attendees[$key][$field['label']] = $EM_Form->get_formatted_value($field, 'n/a');
	    			}
	    		}
	    	}
	    }
		
	    return $attendees;
	}
	
	/**
	 * Hooks into em_booking_get_post and validates the 
	 * @param boolean $result
	 * @param EM_Booking $EM_Booking
	 * @return bool
	 */
	public static function em_booking_get_post($result, $EM_Booking){
		//get, store and validate post data 
		$EM_Form = self::get_form($EM_Booking->event_id);
		if( self::$form_id > 0 ){
			if( (empty($EM_Booking->booking_id) || (!empty($EM_Booking->booking_id) && $EM_Booking->can_manage())) ){
			    $EM_Booking->booking_meta['attendees'] = array();
				foreach ($EM_Booking->get_tickets_bookings()->tickets_bookings as $ticket_booking ){
				    for( $i = 0; $i < $ticket_booking->ticket_booking_spaces; $i++ ){
						$EM_Booking->booking_meta['attendees'][$ticket_booking->ticket_id][$i] = array();
						foreach($EM_Form->fields as $field){
							$field['label'] = str_replace('#NUM#', $i+1, $field['label']);
						}
					    if( $EM_Form->get_post(false, $ticket_booking->ticket_id, $i) ){ //passing false for $validate, since it'll be done in em_booking_validate hook
							foreach($EM_Form->get_values() as $fieldid => $value){
								//get results and put them into booking meta
								$EM_Booking->booking_meta['attendees'][$ticket_booking->ticket_id][$i][$fieldid] = $value;
							}			
					    }
				    }
				}		
			}
			if( count($EM_Form->get_errors()) > 0 ){
				$result = false;
				$EM_Booking->add_error($EM_Form->get_errors());
			}
		}
		return $result;
	}
	
	/**
	 * Validates a booking against the attendee fields provided
	 * @param boolean $result
	 * @param EM_Booking $EM_Booking
	 * @return boolean
	 */
	public static function em_booking_validate($result, $EM_Booking){
		//going through each ticket type booked
		$EM_Form = self::get_form($EM_Booking->event_id);
		if( self::$form_id > 0 ){
			foreach ($EM_Booking->get_tickets_bookings()->tickets_bookings as $ticket_booking ){
				//get original field labels for replacement of #NUM#
				$original_fields = array();
				foreach($EM_Form->form_fields as $key => $field){
					$original_fields[$key] = $EM_Form->form_fields[$key]['label'];
				}
				//validate a form for each space booked
				for( $i = 0; $i < $ticket_booking->ticket_booking_spaces; $i++ ){
					if( isset($EM_Booking->booking_meta['attendees'][$ticket_booking->ticket_id][$i]) ){ //unlike post values each attendee has an array within the array of a ticket attendee info
						$EM_Form->field_values = $EM_Booking->booking_meta['attendees'][$ticket_booking->ticket_id][$i];
						$EM_Form->errors = array();
						//change the field labels in case of #NUM#
						foreach($EM_Form->form_fields as $key => $field){
							$EM_Form->form_fields[$key]['label'] = str_replace('#NUM#', $i+1, $original_fields[$key]);
						}
						//validate and save errors within this ticket user
						if( !$EM_Form->validate($EM_Form->field_values['name']) ){
							$title = $ticket_booking->get_ticket()->ticket_name . " - " . sprintf(__('Attendee %s','events'), $i+1);
							$error = array( $title => $EM_Form->get_errors());
						    $EM_Booking->add_error($EM_Form->get_errors());
						    $result = false;
						}
					}
				}
			}
		}
		return $result;
	}
	
	/*
	 * ----------------------------------------------------------
	 * Booking Table and CSV Export
	 * ----------------------------------------------------------
	 */
	
	/**
	 * Intercepts a CSV export request before the core version hooks in and using similar code generates a breakdown of bookings with all attendees included at the end.
	 * Hooking into the original version of this will cause more looping, which is why we're flat out overriding this here.
	 */
	public static function intercept_csv_export(){
		if( !empty($_REQUEST['action']) && $_REQUEST['action'] == 'export_bookings_csv' && !empty($_REQUEST['show_attendees']) && wp_verify_nonce($_REQUEST['_wpnonce'], 'export_bookings_csv')){
			$EM_Event = false;
			if( !empty($_REQUEST['event_id']) ){
				$EM_Event = EM_Event::find( absint($_REQUEST['event_id']) );
			}
			$title = $EM_Event ? $EM_Event->event_slug : "all";

			if( !empty($_REQUEST['cols']) && is_array($_REQUEST['cols']) ){
				$cols = array();
				foreach($_REQUEST['cols'] as $col => $active){
					if( $active ){ $cols[] = $col; }
				}
				$_REQUEST['cols'] = $cols;
			}
			$_REQUEST['limit'] = 0;
		
			//generate bookings export according to search request
			$EM_Bookings_Table = new EM_Bookings_Table(true);
			$alphabet = range('A', 'Z');
			
			//Rows
			$EM_Bookings_Table->limit = 250; //if you're having server memory issues, try messing with this number
			$EM_Bookings = $EM_Bookings_Table->get_bookings();
			$form_fields = self::get_form($EM_Event->event_id)->form_fields;
			
			$headers = $EM_Bookings_Table->get_headers(true);
			$registration_length = count($headers);
			$titles = array_fill(0, count($headers), '<b><middle><style height="50" bgcolor="#f2f2f2" color="#000000"></style></middle></b>');
			$titles[0] = '<b><middle><style height="25" bgcolor="#f2f2f2" color="#000000">' . __('Registration Fields','events') . '</style></middle></b>';
			foreach($headers as $key => $header){
				$headers[$key] = '<b><middle><style height="50" bgcolor="#f2f2f2" color="#000000">' . $header . '</style></middle></b>';
			}
			if( !empty($_REQUEST['event_id']) ){
				$i = 0;
				foreach($form_fields as $field ){
					if( $field['type'] != 'html' ){
						$headers[] = EM_Bookings_Table::sanitize_spreadsheet_cell('<b><middle><style height="25" bgcolor="#e2efda" color="#375623">' . $field['label'] . '</style></middle></b>');
						$titles[] = $i == 0 ? '<b><middle><style height="25" bgcolor="#e2efda" color="#375623">' . __('Attendee','events') . '</style></middle></b>' : '<b><middle><style height="25" bgcolor="#e2efda" color="#375623"></style></middle></b>';
						$i++;
					}
				}
			}
			
			$excel_sheet = [$titles];
			$excel_sheet[] = $headers;
			

			while(!empty($EM_Bookings->bookings)){
				foreach( $EM_Bookings->bookings as $EM_Booking ) {
					$attendees_data = self::get_booking_attendees($EM_Booking);
					foreach($EM_Booking->get_tickets_bookings()->tickets_bookings as $ticket_booking){
						$orig_row = $EM_Bookings_Table->get_row_csv($ticket_booking);
						if( !empty($attendees_data[$ticket_booking->ticket_id]) ){ 
							foreach($attendees_data[$ticket_booking->ticket_id] as $attendee_title => $attendee_data){
								$row = $orig_row;
								foreach( $attendee_data as $field_value){
									$row[] = EM_Bookings_Table::sanitize_spreadsheet_cell($field_value);
								}
								array_push($excel_sheet, $row);
							}
						}
					}
				}
				//reiterate loop
				$EM_Bookings_Table->offset += $EM_Bookings_Table->limit;
				$EM_Bookings = $EM_Bookings_Table->get_bookings();
			}
			$xlsx = Shuchkin\SimpleXLSXGen::fromArray( $excel_sheet );
			$xlsx->mergeCells('A1:'. $alphabet[$registration_length-1].'1');
			$xlsx->mergeCells($alphabet[$registration_length].'1:'. $alphabet[count($headers)-1].'1');
			$xlsx->downloadAs($title . '-' . lcfirst(__('Bookings', 'events')) . '.xlsx');
			exit();
		}
	}
	
	public static function em_bookings_table_export_options(){
		?>
		<p><input type="checkbox" name="show_attendees" value="1" /><label><?php _e('Split bookings by attendee','events')?> </label>
		
		<script type="text/javascript">
			jQuery(document).ready(function($){
				$('#em-bookings-table-export-form input[name=show_attendees]').click(function(){
					$('#em-bookings-table-export-form input[name=show_tickets]').attr('checked',true);
					//copied from export_overlay_show_tickets function:
					$('#em-bookings-table-export-form .em-bookings-col-item-ticket').show();
					$('#em-bookings-table-export-form #em-bookings-export-cols-active .em-bookings-col-item-ticket input').val(1);
				});
				$('#em-bookings-table-export-form input[name=show_tickets]').change(function(){
					if( !this.checked ){
						$('#em-bookings-table-export-form input[name=show_attendees]').attr('checked',false);
					}
				});
			});
		</script>
		<?php
		
	}
	
	
	
	
	/**
	 * Saves the custom attendee form as post meta. This is done on em_event_save_meta_pre since at that point we know the post id and this will get passed onto recurrences as well.
	 * @param EM_Event $EM_Event
	 */
	public static function em_event_save_meta_pre($EM_Event){
		global $wpdb;
		if( !empty($EM_Event->duplicated) ) return; //if just duplicated, we ignore this and let EM carry over duplicate event data
		if( $EM_Event->event_rsvp && !empty($_REQUEST['custom_attendee_form']) && is_numeric($_REQUEST['custom_attendee_form']) ){
			//Make sure form id exists
			$id = $wpdb->get_var('SELECT meta_id FROM '.EM_META_TABLE." WHERE meta_id='{$_REQUEST['custom_attendee_form']}'");
			if( $id == $_REQUEST['custom_attendee_form'] ){
				//add or modify custom booking form id in post data
				update_post_meta($EM_Event->post_id, '_custom_attendee_form', $id);
			}
		}elseif( $EM_Event->event_rsvp && !empty($_REQUEST['custom_attendee_form']) && $_REQUEST['custom_attendee_form'] == 'none' ){
			update_post_meta($EM_Event->post_id, '_custom_attendee_form', 'none');
		}else{
			delete_post_meta($EM_Event->post_id, '_custom_attendee_form');
		}
	}

	

	public static function data_privacy_export( $export_item, $EM_Booking ){
	    if( get_class($EM_Booking) == 'EM_Multiple_Booking' ) return $export_item; //skip multiple bookings
		$tickets_bookings = $EM_Booking->get_tickets_bookings();
		$attendee_datas = EM_Attendees_Form::get_booking_attendees($EM_Booking);
		$attendee_string = array();
		foreach( $tickets_bookings->tickets_bookings as $ticket_booking ){
			//Display ticket info
			if( !empty($attendee_datas[$ticket_booking->ticket_id]) ){
				$attendee_string[$ticket_booking->ticket_id] = __('Ticket','events').' - '. $ticket_booking->get_ticket()->ticket_name ."<br>-----------------------------";
				//display a row for each space booked on this ticket
				foreach( $attendee_datas[$ticket_booking->ticket_id] as $attendee_title => $attendee_data ){
					$attendee_string[$ticket_booking->ticket_id] .= '<br>'. $attendee_title ."<br>------------";
					foreach( $attendee_data as $field_label => $field_value){
						$attendee_string[$ticket_booking->ticket_id] .= "<br>". $field_label .': '. $field_value;
					}
				}
			}
		}
		if( !empty($attendee_string) ) $export_item['data']['attendees'] = array('name'=> __('Attendees', 'events'), 'value' => implode('<br><br>', $attendee_string));
		return $export_item;
	}
}
EM_Attendees_Form::init();

?>