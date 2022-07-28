<?php
class EM_Booking_Form {
	static $validate;
	/**
	 * @var EM_Form
	 */
	static $form;
	static $form_id; 
	static $event_id;
	static $form_name;
	static $form_template;
	
	public static function init(){	
				
		//Booking admin and exports
		add_action('em_bookings_single_custom', array('EM_Booking_Form', 'em_bookings_single_custom'),1,1); //show booking form and ticket summary
		//Booking Tables UI
		add_filter('em_bookings_table_rows_col', array('EM_Booking_Form','em_bookings_table_rows_col'),10,5);
		add_filter('em_bookings_table_cols_template', array('EM_Booking_Form','em_bookings_table_cols_template'),10,2);
		// Actions and Filters
		add_filter('em_booking_form_custom', array('EM_Booking_Form','booking_form'),10,1); //handle the booking form template
        add_filter('em_booking_form_custom_json', array('EM_Booking_Form','booking_form_json'),10,1); //handle the booking form template
		//Booking interception
		$booking_button_request = !empty($_REQUEST['action']) && $_REQUEST['action'] == 'booking_add_one' && is_user_logged_in(); //in order to disable the form if booking button is pressed
		if( !$booking_button_request ){
			add_filter('em_booking_save', array('EM_Booking_Form', 'em_booking_save'), 1, 2); //add new user fields to current EM_Person instance for use on this run
			add_filter('em_booking_get_post', array('EM_Booking_Form', 'em_booking_get_post'), 10, 2); //get post data + validate
			add_filter('em_booking_validate', array('EM_Booking_Form', 'em_booking_validate'), 10, 2); //validate object
			add_action('em_bookings_added', array('EM_Booking_Form', 'em_bookings_added'), 10, 1); //add extra use reg data
		}
		
		//Data Privacy
        add_filter('em_data_privacy_export_bookings_item', 'EM_Booking_Form::data_privacy_export', 10, 2);
	}
	
	/**
	 * Gets the default form structure for creating a new form
	 * @return array
	 */
	public static function get_form_template(){
	    if( empty(self::$form_template )){
    		self::$form_template = apply_filters('em_booking_form_get_form_template', array (
    			'first_name' => array ( 'label' => __('First Name','events-manager'), 'type' => 'name', 'fieldid'=>'user_name', 'required'=>1 ),
				'last_name' => array ( 'label' => __('Last Name','events-manager'), 'type' => 'name', 'fieldid'=>'last_name', 'required'=>1 ),
    			'user_email' => array ( 'label' => __('Email','events-manager'), 'type' => 'user_email', 'fieldid'=>'user_email', 'required'=>1 ),
    		  	'booking_comment' => array ( 'label' => __('Comment','events-manager'), 'type' => 'textarea', 'fieldid'=>'booking_comment' ),
    		));        
	    }
	    return self::$form_template;
	}
	
	/**
	 * @param EM_Booking $EM_Booking
	 */
	public static function get_form( $EM_Event = false, $custom_form_id = false ){
	    //make sure we don't need to get another form rather than the one already stored in this object
	    $reload = (is_numeric($EM_Event) && $EM_Event != self::$event_id) || ( !empty($EM_Event->event_id) && $EM_Event->event_id != self::$event_id ) || ( empty($EM_Event) && $custom_form_id && $custom_form_id != self::$form_id );
	    //get the right form

		if( empty(self::$form) || $reload ){

			if(is_numeric($EM_Event)){ $EM_Event = EM_Event::find($EM_Event); }
			
			self::$form_id = get_post_meta($EM_Event->post_id, '_booking_form', true);

			$form_data = self::get_form_data($EM_Event);

			if(empty($form_data)) {
				$form_data = array('form' => self::get_form_template());
				self::$form_name = __('Default','em-pro');
			}

			self::$form = new EM_Form($form_data['form'], 'em_bookings_form');
			self::$form->form_required_error = __('Please fill in the field: %s','em-pro');

			
		}
        
		return self::$form;
	}

	public static function get_form_data($event) {

		if(!$event) return [];
		
		if( is_numeric($event) ){ 
			$event = EM_Event::find($event); 
		}
		
		$form_id = get_post_meta($event->post_id, '_booking_form', true);

		if(!$form_id) return [];

		$form = get_post(self::$form_id);

		if (!$form) return [];

		$blocks = parse_blocks( $form->post_content );

		if(count($blocks) < 1) return [];
			
		if(!array_key_exists('innerBlocks', $blocks[0])) return [];
			
		self::$event_id = !empty($EM_Event) ? $EM_Event->event_id:false;
		self::$form_name = $form->post_title;
		$form_data = array('form' => [], 'name' => $form->post_title);
		
		foreach( $blocks[0]['innerBlocks'] as $block ){

			if(substr($block['blockName'], strripos($block['blockName'], '-') + 1) == 'html') continue;
				$type = (in_array($block['attrs']['fieldid'], self::get_form_template()) ? $block['attrs']['fieldid'] : substr($block['blockName'], strripos($block['blockName'], '-') + 1));
				$form_data['form'][$block['attrs']['fieldid']] = $block['attrs'];
				$form_data['form'][$block['attrs']['fieldid']]['type'] = $type;
			
		}
		return $form_data;

	}

	public static function get_booking_form($event_id){
		$form_id = get_post_meta($event_id, '_booking_form', true);
		
		$form = get_post(intval($form_id));
		
		$blocks = parse_blocks( $form->post_content );
		
		if(count($blocks) < 1) return false;
		if(!array_key_exists('innerBlocks', $blocks[0])) return false;
		
		$form_data = array();
		foreach( $blocks[0]['innerBlocks'] as $block ){
			
			$type = substr($block['blockName'], strripos($block['blockName'], '-') + 1);
			$value = $type == 'html' ? render_block($block) : (array_key_exists('default', $block['attrs']) ? $block['attrs']['default'] : null);
			$options = $type == "country" ? \Contexis\Events\Intl\Countries::get() : (array_key_exists('options', $block['attrs']) ? $block['attrs']['options'] : null);	
			$type = $type == "country" ? "select" : $type;
			array_push($form_data, array_merge($block['attrs'], array('value' => $value, 'type' => $type, 'options' => $options)));
			
		}
		return $form_data;
	}
	
	
	/**
	 * Shows the actual booking form. 
	 * @param EM_Event $event
	 */
	public static function booking_form($event = false){
		global $EM_Event;
        $event = empty($event) ? $EM_Event : $event;
        echo self::get_form($event);
	}

    public static function booking_form_json($event = false){
		global $EM_Event;
        $event = empty($event) ? $EM_Event : $event;
        return self::get_form($event)->form_fields;
	}

	
	/**
	 * @param boolean $result
	 * @param EM_Booking $EM_Booking
	 * @return bool
	 */
	public static function em_booking_get_post($result, $EM_Booking){
		//get, store and validate post data 
		$EM_Form = self::get_form($EM_Booking->event_id, $EM_Booking);
        //skip registration fields if manually booking someone that already is a user
		$manual_assigned_booking = !empty($_REQUEST['manual_booking']) && !empty($_REQUEST['person_id']) && $_REQUEST['person_id'] > 0 && wp_verify_nonce($_REQUEST['manual_booking'], 'em_manual_booking_'.$EM_Booking->event_id);				
		//get form fields
		if( $EM_Form->get_post() ){
			foreach($EM_Form->get_values() as $fieldid => $value){
				
				if($fieldid == 'user_password'){
				    $EM_Booking->temporary_password = $value; //assign a random property so it's never saved
				}else{
					//get results and put them into booking meta
					if( !$manual_assigned_booking && (array_key_exists($fieldid, $EM_Form->user_fields) || in_array($fieldid, array('user_email','user_name'))) ){
					    if( !(!empty($EM_Booking->booking_id) && $EM_Booking->can_manage()) || empty($EM_Booking->booking_id) ){ //only save reg fields on first go
							//registration fields
							
							$EM_Booking->booking_meta['registration'][$fieldid] = $value;
					    }
					}else{ //ignore captchas, only for verification
						//booking fields
						$EM_Booking->booking_meta['booking'][$fieldid] = $value;
					}
				}
			}
		}elseif( count($EM_Form->get_errors()) > 0 ){
			$result = false;
			$EM_Booking->add_error($EM_Form->get_errors());
		}
		return $result;
	}
	
	/**
	 * @param boolean $result
	 * @param EM_Booking $EM_Booking
	 * @return boolean
	 */
	public static function em_booking_validate($result, $EM_Booking){
		$EM_Form = self::get_form($EM_Booking->event_id, $EM_Booking);

		if( empty($EM_Form->field_values) ){
		    //in the event we're validating a booking that wasn't retrieved by post, with booking meta
		    $values = array();
		    if( !empty($EM_Booking->booking_meta['booking']) ){
		        $values = $EM_Booking->booking_meta['booking'];
		    }
		    if( !empty($EM_Booking->booking_meta['registration']) ){
		    	$values = array_merge($values, $EM_Booking->booking_meta['registration']);
		    }
		    $EM_Form->field_values = $values;
		}
		if( !empty($EM_Booking->mb_validate_bookings) ) $EM_Form->ignore_captcha = true; //MB Mode doing a final validation, so no need to re-check captcha
		if( !$EM_Form->validate() ){
		    $EM_Booking->add_error($EM_Form->get_errors());
			return false;
		}
		if( !empty($EM_Booking->mb_validate_bookings) ) unset($EM_Form->ignore_captcha);  //MB Mode doing a final validation, so no need to re-check captcha
		return $result;
	}
	
	public static function em_booking_save($result, $EM_Booking){
		$EM_Form = self::get_form($EM_Booking->event_id, $EM_Booking);
		
		if( !empty($EM_Booking->booking_meta['registration']) && is_array($EM_Booking->booking_meta['registration']) ){
			//assign the common registration fields to person object in case used in this instance
			foreach($EM_Booking->booking_meta['registration'] as $fieldid => $field_value){
				if( !empty($EM_Form->form_fields[$fieldid]['type']) && array_key_exists($EM_Form->form_fields[$fieldid]['type'], $EM_Form->core_user_fields) && EM_Form::show_reg_fields($EM_Form->form_fields[$fieldid]) ){
				    $user_field_type = $EM_Form->form_fields[$fieldid]['type'];
					$EM_Booking->get_person()->$user_field_type = $field_value;
				}
			}
		}
		return $result;
	}
	
	public static function em_bookings_added($EM_Booking){
		$EM_Form = self::get_form($EM_Booking->event_id, $EM_Booking);
		if( !empty($EM_Booking->booking_meta['registration']) && is_array($EM_Booking->booking_meta['registration']) && !$EM_Booking->is_no_user() ){
			$user_data = array();
			foreach($EM_Booking->booking_meta['registration'] as $fieldid => $field_value){
				if( !empty($field_value) && (is_array($field_value) || trim($field_value) !== '') && array_key_exists($fieldid, $EM_Form->form_fields) ){
					$user_data[$fieldid] = $field_value;
				}
			}
			foreach($user_data as $userkey => $uservalue){
				EM_User_Fields::update_user_meta($EM_Booking->person_id, $userkey, $uservalue);
			}
		}
	}
	
	/**
	 * Returns a formatted multi-dimensional associative array of booking form and user information for a specific booking (not including attendees).
	 * example : array('booking' => array('Label'=>'Value', 'Label 2'=>'Value 2'), 'registration' => array(...)...);
	 * @param EM_Booking $EM_Booking
	 */
	public static function get_booking_data( $EM_Booking, $include_registration_info = false ){
	    $booking_data = array('booking'=>array());
	    if( $include_registration_info ) $booking_data['registration'] = array();
	    if( (!empty($EM_Booking->booking_meta['booking']) && is_array($EM_Booking->booking_meta['booking'])) || ($include_registration_info && !empty($EM_Booking->booking_meta['registration']) && is_array($EM_Booking->booking_meta['registration'])) ){
			$EM_Form = self::get_form($EM_Booking->get_event());
			foreach($EM_Form->form_fields as $fieldid => $field){
				
				$field = $EM_Form->translate_field($field);
				$input_value = $field_value = (isset($EM_Booking->booking_meta['booking'][$fieldid])) ? $EM_Booking->booking_meta['booking'][$fieldid]:'n/a';
				if( !array_key_exists($fieldid, $EM_Form->user_fields) && !in_array($fieldid, array('user_email','user_name')) && $field['type'] != 'html' ){
					if( in_array($field['type'], array('date','time')) && $input_value == 'n/a' ) $input_value = '';
					$booking_data['booking'][$field['label']] = $EM_Form->get_formatted_value($field, $input_value);
				}elseif( $field['type'] != 'html' ){
				    $booking_data['registration'][$field['label']] = $EM_Form->get_formatted_value($field, $input_value);
				}
			}
	    }
	    return $booking_data;
	}


	
	/*
	 * ----------------------------------------------------------
	 * Booking Table and CSV Export
	 * ----------------------------------------------------------
	 */
	
	public static function em_bookings_table_rows_col($value, $col, $EM_Booking, $EM_Bookings_Table, $format){
		global $EM_Event;
		//we're either viewing booking columns for a specific event, or all events, whether or not we're searching for a specific ticket.
		$event_id = (!empty($EM_Booking->get_event()->event_id) && !empty($EM_Event->event_id) && $EM_Event->event_id == $EM_Booking->get_event()->event_id ) ? $EM_Event->event_id:false;
		$EM_Form = self::get_form($event_id);
		if( $EM_Form->is_normal_field($col) && isset($EM_Booking->booking_meta['booking'][$col]) ){
			$field = $EM_Form->form_fields[$col];
			$value = $EM_Form->get_formatted_value($field, $EM_Booking->booking_meta['booking'][$col]);
			if( $format == 'html' || empty($format) ) $value = esc_html($value);
		}
		return $value;
	}
	
	public static function em_bookings_table_cols_template($template, $EM_Bookings_Table){
		global $EM_Event;
		$event_id = (!empty($EM_Event->event_id)) ? $EM_Event->event_id:false;
		$EM_Form = self::get_form($event_id);
		foreach($EM_Form->form_fields as $field_id => $field ){
		    if( $EM_Form->is_normal_field($field_id) ){ //user fields already handled, htmls shouldn't show
		    	$field = $EM_Form->translate_field($field);
    			$template[$field_id] = $field['label'];
		    }
		}
		return $template;
	}
	

	/**
	 * Outputs booking form information when viewing a booking in the admin area.  
	 * @param EM_Booking $EM_Booking
	 */
	public static function em_bookings_single_custom( $EM_Booking ){
		//if you want to mess with these values, intercept the em_bookings_single_custom instead
		$EM_Form = self::get_form($EM_Booking->event_id, $EM_Booking);
		foreach($EM_Form->form_fields as $fieldid => $field){
			$field = $EM_Form->translate_field($field);
			if( !array_key_exists($fieldid, $EM_Form->user_fields) && !in_array($fieldid, array('user_email','user_name')) && $field['type'] != 'html' && $field['type'] != 'captcha' ){
				//get value of field
				$input_value = $field_value = (isset($EM_Booking->booking_meta['booking'][$fieldid])) ? $EM_Booking->booking_meta['booking'][$fieldid]:'n/a';
				//account for the free version and the booking_comment field so that old booking info still shows
				if( $input_value == 'n/a' && $fieldid == 'booking_comment' && !empty($EM_Booking->booking_comment)){
					$input_value = $field_value = $EM_Booking->booking_comment;
				}
				//input value should be blank, not n/a
				if( $input_value == 'n/a' ) $input_value = '';
			}
		}
	}

	public static function data_privacy_export( $export_item, EM_Booking $EM_Booking ){
		$EM_Form = EM_Booking_Form::get_form( $EM_Booking->event_id, $EM_Booking );
		foreach( $EM_Form->form_fields as $fieldid => $field ){
			if( !array_key_exists($fieldid, $EM_Form->user_fields) && !in_array($fieldid, array('user_email','user_name')) && $field['type'] != 'html' && $field['type'] != 'captcha' ){
				//get value of field
				$field_value = (isset($EM_Booking->booking_meta['booking'][$fieldid])) ? $EM_Booking->booking_meta['booking'][$fieldid]:'';
				//account for the free version and the booking_comment field so that old booking info still shows
				if( empty($field_value) && $fieldid == 'booking_comment' && !empty($EM_Booking->booking_comment)){
					$field_value = $EM_Booking->booking_comment;
				}
				if( $field_value !== '' ){
    				$export_item['data'][] = array( 'name' => $field['label'], 'value' => $EM_Form->get_formatted_value($field, $field_value) );
                }
			}
		}
        return $export_item;
    }
}

EM_Booking_Form::init();
include('AttendeeForms.php');

