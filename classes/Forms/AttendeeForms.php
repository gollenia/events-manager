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
		//Booking Admin Pages
		add_action('em_bookings_admin_ticket_row', array('EM_Attendees_Form', 'em_bookings_admin_ticket'),1,2); //show booking form and ticket summary
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
				'attendee_name' => array ( 'label' => __('Name','events-manager'), 'type' => 'text', 'fieldid'=>'attendee_name', 'required'=>1 )
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
				self::$form_name = __('Default','em-pro');
			}

			self::$form_name = get_the_title(self::$form_id);
			self::$form = new EM_Attendee_Form($form_data, 'em_attendee_form', false);
			self::$form->form_required_error = __('Please fill in the field: %s','em-pro');
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
	 * @param EM_Ticket $EM_Ticket
	 * @return EM_Attendee_Form
	 */
	public static function get_ticket_form($form, $EM_Ticket){
		//modify field ids to contain ticket number and []
		foreach($form->form_fields as $field_id => $form_data){
		    if( $form_data['type'] == 'date' || $form_data['type'] == 'time'){
				$form->form_fields[$field_id]['name'] = "em_attendee_fields[".$EM_Ticket->ticket_id."][$field_id][%s][]";
		    }elseif( in_array($form_data['type'], array('radio','checkboxes','multiselect')) ){
			    $form->form_fields[$field_id]['name'] = "em_attendee_fields[".$EM_Ticket->ticket_id."][$field_id][%n]";
			}else{
				$form->form_fields[$field_id]['name'] = "em_attendee_fields[".$EM_Ticket->ticket_id."][$field_id][]";
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
		foreach( $EM_Booking->get_tickets_bookings()->tickets_bookings as $EM_Ticket_Booking ){ /* @var $EM_Ticket_Booking EM_Ticket_Booking */
			//Display ticket info
			if( !empty($EM_Booking->booking_meta['attendees'][$EM_Ticket_Booking->ticket_id]) && is_array($EM_Booking->booking_meta['attendees'][$EM_Ticket_Booking->ticket_id]) ){
			    $EM_Ticket_Booking->booking = $EM_Booking; //avoid extra loading in sub-function
			    $attendee_data[$EM_Ticket_Booking->ticket_id] = self::get_ticket_attendees($EM_Ticket_Booking);
			}else{
				$attendee_data[$EM_Ticket_Booking->ticket_id] = array();
				for($i=1; $i <= $EM_Ticket_Booking->ticket_booking_spaces; $i++){
					$key = sprintf(__('Attendee %s','em-pro'), $i);
					$attendee_data[$EM_Ticket_Booking->ticket_id][$key] = array();
				}
			}
		}
		return $attendee_data;
	}
	
	/**
	 * Returns a formatted multi-dimensional associative array of attendee information for a specific booking ticket.
	 * example : array('Attendee 1' => array('Label'=>'Value', 'Label 2'=>'Value 2'), 'Attendee 2' => array(...)...);
	 * @param EM_Ticket_Booking $EM_Ticket_Booking
	 * @param boolean $padding
	 * @return array $attendees
	 */
	public static function get_ticket_attendees( $EM_Ticket_Booking, $padding = false ){
	    $attendees = array();
    	$EM_Form = EM_Attendees_Form::get_form($EM_Ticket_Booking->get_booking()->event_id); //can be repeated since object is stored temporarily
	    if( !empty($EM_Ticket_Booking->get_booking()->booking_meta['attendees'][$EM_Ticket_Booking->ticket_id]) && is_array($EM_Ticket_Booking->get_booking()->booking_meta['attendees'][$EM_Ticket_Booking->ticket_id]) ){
			$i = 1; //counter
	    	foreach( $EM_Ticket_Booking->get_booking()->booking_meta['attendees'][$EM_Ticket_Booking->ticket_id] as $field_values ){
	    		$EM_Form->field_values = $field_values;
	    		//output the field values
				
	    		$key = sprintf(__('Attendee %s','em-pro'), $i);
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
	    	for( $space_no = 1; $space_no <= $EM_Ticket_Booking->ticket_booking_spaces; $space_no++ ){
	    		$key = sprintf(__('Attendee %s','em-pro'), $space_no);
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
				foreach ($EM_Booking->get_tickets_bookings()->tickets_bookings as $EM_Ticket_Booking ){
				    for( $i = 0; $i < $EM_Ticket_Booking->ticket_booking_spaces; $i++ ){
						$EM_Booking->booking_meta['attendees'][$EM_Ticket_Booking->ticket_id][$i] = array();
						foreach($EM_Form->fields as $field){
							$field['label'] = str_replace('#NUM#', $i+1, $field['label']);
						}
					    if( $EM_Form->get_post(false, $EM_Ticket_Booking->ticket_id, $i) ){ //passing false for $validate, since it'll be done in em_booking_validate hook
							foreach($EM_Form->get_values() as $fieldid => $value){
								//get results and put them into booking meta
								$EM_Booking->booking_meta['attendees'][$EM_Ticket_Booking->ticket_id][$i][$fieldid] = $value;
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
			foreach ($EM_Booking->get_tickets_bookings()->tickets_bookings as $EM_Ticket_Booking ){
				//get original field labels for replacement of #NUM#
				$original_fields = array();
				foreach($EM_Form->form_fields as $key => $field){
					$original_fields[$key] = $EM_Form->form_fields[$key]['label'];
				}
				//validate a form for each space booked
				for( $i = 0; $i < $EM_Ticket_Booking->ticket_booking_spaces; $i++ ){
					if( isset($EM_Booking->booking_meta['attendees'][$EM_Ticket_Booking->ticket_id][$i]) ){ //unlike post values each attendee has an array within the array of a ticket attendee info
						$EM_Form->field_values = $EM_Booking->booking_meta['attendees'][$EM_Ticket_Booking->ticket_id][$i];
						$EM_Form->errors = array();
						//change the field labels in case of #NUM#
						foreach($EM_Form->form_fields as $key => $field){
							$EM_Form->form_fields[$key]['label'] = str_replace('#NUM#', $i+1, $original_fields[$key]);
						}
						//validate and save errors within this ticket user
						if( !$EM_Form->validate($EM_Form->field_values['name']) ){
							$title = $EM_Ticket_Booking->get_ticket()->ticket_name . " - " . sprintf(__('Attendee %s','em-pro'), $i+1);
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
			$title = $EM_Event ? $EM_Event->slug : "all";

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
			$titles[0] = '<b><middle><style height="25" bgcolor="#f2f2f2" color="#000000">' . __('Registration Fields','em-pro') . '</style></middle></b>';
			foreach($headers as $key => $header){
				$headers[$key] = '<b><middle><style height="50" bgcolor="#f2f2f2" color="#000000">' . $header . '</style></middle></b>';
			}
			if( !empty($_REQUEST['event_id']) ){
				$i = 0;
				foreach($form_fields as $field ){
					if( $field['type'] != 'html' ){
						$headers[] = EM_Bookings_Table::sanitize_spreadsheet_cell('<b><middle><style height="25" bgcolor="#e2efda" color="#375623">' . $field['label'] . '</style></middle></b>');
						$titles[] = $i == 0 ? '<b><middle><style height="25" bgcolor="#e2efda" color="#375623">' . __('Attendee','em-pro') . '</style></middle></b>' : '<b><middle><style height="25" bgcolor="#e2efda" color="#375623"></style></middle></b>';
						$i++;
					}
				}
			}
			
			$excel_sheet = [$titles];
			$excel_sheet[] = $headers;
			

			while(!empty($EM_Bookings->bookings)){
				foreach( $EM_Bookings->bookings as $EM_Booking ) {
					/* @var EM_Booking $EM_Booking */
					/* @var EM_Ticket_Booking $EM_Ticket_Booking */
					$attendees_data = self::get_booking_attendees($EM_Booking);
					foreach($EM_Booking->get_tickets_bookings()->tickets_bookings as $EM_Ticket_Booking){
						$orig_row = $EM_Bookings_Table->get_row_csv($EM_Ticket_Booking);
						if( !empty($attendees_data[$EM_Ticket_Booking->ticket_id]) ){ 
							foreach($attendees_data[$EM_Ticket_Booking->ticket_id] as $attendee_title => $attendee_data){
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
			$xlsx->downloadAs($title . '-' . lcfirst(__('Bookings', 'events-manager')) . '.xlsx');
			exit();
		}
	}
	
	public static function em_bookings_table_export_options(){
		?>
		<p><input type="checkbox" name="show_attendees" value="1" /><label><?php _e('Split bookings by attendee','em-pro')?> </label>
		
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
	
	/*
	 * ----------------------------------------------------------
	 * Booking Admin Functions
	 * ----------------------------------------------------------
	 */


	/**
	 * Displayed when viewing/editing info about a single booking under each ticket.
	 * @param EM_Ticket $EM_Ticket
	 * @param EM_Booking $EM_Booking
	 */
	public static function em_bookings_admin_ticket( $EM_Ticket, $EM_Booking ){
		//if you want to mess with these values, intercept the em_bookings_single_custom action instead
		$EM_Tickets_Bookings = $EM_Booking->get_tickets_bookings();
			$EM_Form = self::get_form($EM_Booking->event_id);
			//validate a form for each space booked
			if( self::$form_id > 0 ){
				?>
				<tr>
				<td colspan="3" class="em-attendee-form-admin">
					<div class="em-attendee-details" id="em-attendee-details-<?php echo $EM_Ticket->ticket_id; ?>">
						<div class="em-attendee-fieldset">
						<?php if( !empty($EM_Tickets_Bookings->tickets_bookings[$EM_Ticket->ticket_id]) ): ?>
							<?php
							//output the field values
							$EM_Ticket_Booking = $EM_Tickets_Bookings->tickets_bookings[$EM_Ticket->ticket_id];
							$attendees_data = self::get_ticket_attendees($EM_Ticket_Booking, true);
							
							$attendee_index = 0;
							foreach($attendees_data as $attendee_title => $attendee_data){
								//preload the form object with this attendee information
								if( isset($EM_Booking->booking_meta['attendees'][$EM_Ticket_Booking->ticket_id][$attendee_index]) ){
									$EM_Form->field_values = $EM_Booking->booking_meta['attendees'][$EM_Ticket_Booking->ticket_id][$attendee_index];
									$EM_Form->attendee_number = $attendee_index;
								}
								?>
								<div class="em-booking-single-info">
									<h4><?php echo $attendee_title; ?></h4><table>
									<?php foreach( $attendee_data as $attendee_label => $attendee_value): ?>
									<tr>
										<th><b><?php echo $attendee_label ?></b></th>
										<td><?php echo $attendee_value; ?></td>
									</tr>
									<?php endforeach; ?></table>
								</div>
								<?php
								//output fields form
								?>
								<div class="em-attendee-fields em-booking-single-edit">
									<h4><?php echo $attendee_title; ?></h4>
									<?php self::admin_form($EM_Form, $EM_Ticket_Booking->ticket_id); ?>
								</div>
								<?php
								$attendee_index++;
							}
							//reset form fields to blank for template
							$EM_Form->field_values = array();
							$EM_Form->errors = array();
							$EM_Form->attendee_number = false;
							?>
						<?php endif; ?>
						</div>
						<div class="em-attendee-fields-template" style="display:none;">
							<h4><?php echo sprintf(__('Attendee %s','em-pro'), '#NUM#'); ?></h4>
							<?php self::admin_form($EM_Form, $EM_Ticket->ticket_id); ?>
						</div>
					</div>
				</td>
				</tr>
				<?php
			}
	}


	/*
	 * ----------------------------------------------------------
	 * Event Admin Functions
	 * ----------------------------------------------------------
	 */
		
	/**
	 * Generates a condensed attendee form for admins, stripping away HTML fields.
	 * @param EM_Attendee_Form $EM_Form
	 * @param int $ticket_id
	 */
	public static function admin_form( $EM_Form, $ticket_id ){
		?>
		<table class="em-form-fields" cellspacing="0" cellpadding="0">
		<?php
		foreach( $EM_Form->form_fields as $fieldid => $field){
			if( !array_key_exists($fieldid, $EM_Form->user_fields) && $field['type'] != 'html' ){
				?>
				<tr class="input-group input-<?php echo $field['type']; ?> input-field-<?php echo $field['fieldid'] ?>">
					<th><?php echo $field['label'] ?></th>
					<td>
					<?php
						$value = !empty($EM_Form->field_values[$fieldid]) ? $EM_Form->field_values[$fieldid]:''; 
						echo str_replace('%T', $ticket_id, $EM_Form->output_field_input($field, $value)); 
					?>
					</td>
				</tr>
				<?php
			}
		}
		?>
		</table>
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
		$EM_Tickets_Bookings = $EM_Booking->get_tickets_bookings();
		$attendee_datas = EM_Attendees_Form::get_booking_attendees($EM_Booking);
		$attendee_string = array();
		foreach( $EM_Tickets_Bookings->tickets_bookings as $EM_Ticket_Booking ){
			//Display ticket info
			if( !empty($attendee_datas[$EM_Ticket_Booking->ticket_id]) ){
				$attendee_string[$EM_Ticket_Booking->ticket_id] = __('Ticket','events-manager').' - '. $EM_Ticket_Booking->get_ticket()->ticket_name ."<br>-----------------------------";
				//display a row for each space booked on this ticket
				foreach( $attendee_datas[$EM_Ticket_Booking->ticket_id] as $attendee_title => $attendee_data ){
					$attendee_string[$EM_Ticket_Booking->ticket_id] .= '<br>'. $attendee_title ."<br>------------";
					foreach( $attendee_data as $field_label => $field_value){
						$attendee_string[$EM_Ticket_Booking->ticket_id] .= "<br>". $field_label .': '. $field_value;
					}
				}
			}
		}
		if( !empty($attendee_string) ) $export_item['data']['attendees'] = array('name'=> __('Attendees', 'events-manager-pro'), 'value' => implode('<br><br>', $attendee_string));
		return $export_item;
	}
}
EM_Attendees_Form::init();

?>