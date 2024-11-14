<?php
class EM_User_Fields {
	public static $form;
	
	public static function init(){
		add_action('emp_form_user_fields',array('EM_User_Fields', 'emp_booking_user_fields'),1,1); //hook for booking form editor
		//Booking interception
		add_filter('em_form_validate_field_custom', array('EM_User_Fields', 'validate'), 1, 4); //validate object
				
		remove_filter( 'user_contactmethods' , array('EM_People','user_contactmethods'),10,1); //disable EM user fields and override with our filter
		//booking no-user mode functions - editing/saving user data
		

		//Booking Table and CSV Export
		add_filter('em_bookings_table_rows_col', array('EM_User_Fields','em_bookings_table_rows_col'),10,5);
		add_filter('em_bookings_table_cols_template', array('EM_User_Fields','em_bookings_table_cols_template'),10,2);
		//Data Privacy - exporting user info saved by user fields or in case of guest user within bookings
		add_filter('em_data_privacy_export_user', 'EM_User_Fields::data_privacy_export_user', 10, 2);
		add_filter('em_booking_get_person', 'EM_User_Fields::em_booking_get_person', 10, 2);
	}
	
	public static function get_form(){
		if( empty(self::$form) ){
			self::$form = new EM_Form('em_user_fields');
			self::$form->form_required_error = __('Please fill in the field: %s','events-manager');
			self::$form->is_user_form = true;
		}
		
		return self::$form;
	}
	
	public static function emp_booking_user_fields( $fields ){
		//just get an array of options here
		$custom_fields = [];
		foreach($custom_fields as $field_id => $field){
			if( !in_array($field_id, $fields) ){
				$fields[$field_id] = $field['label'];
			}
		}
		return $fields;
	}
	
	public static function validate($result, $field, $value, $form){
		$EM_Form = self::get_form();
		if( array_key_exists($field['fieldid'], $EM_Form->user_fields) ){
			//override default regex and error message
			//first figure out the type to modify
			$true_field_type = $EM_Form->form_fields[$field['fieldid']]['type'];
			$true_option_type = $true_field_type;
			if( $true_field_type == 'textarea' ) $true_option_type = 'text';
			if( in_array($true_field_type, array('select','multiselect')) ) $true_option_type = 'select';
			if( in_array($true_field_type, array('checkboxes','radio')) ) $true_option_type = 'selection';
			//now do the overriding
			if( !empty($field['options_reg_error']) ){
				$EM_Form->form_fields[$field['fieldid']]['options_'.$true_option_type.'_error'] = $field['options_reg_error'];
			}
			if( !empty($field['options_reg_regex']) ){
				$EM_Form->form_fields[$field['fieldid']]['options_'.$true_option_type.'_regex'] = $field['options_reg_regex'];
			}
			$EM_Form->form_fields[$field['fieldid']]['label'] = $field['label']; //To prevent double required messages for booking user field with different label to original user field
			//validate the original field type
			if( !$EM_Form->validate_field($field['fieldid'], $value) ){
				$form->add_error($EM_Form->get_errors());
				return false;
			}
			return $result && true;
		}
		return $result;
	}
	
	
	
	/*
	 * ----------------------------------------------------------
	 * Booking Table and CSV Export
	 * ----------------------------------------------------------
	 */
	/**
	 * Provides values for custom field columns in a bookings table.
	 * @param string $value
	 * @param string $col
	 * @param EM_Booking $EM_Booking
	 * @param EM_Bookings_Table $EM_Bookings_Table
	 * @param boolean $csv
	 * @return string
	 */
	public static function em_bookings_table_rows_col($value, $col, $EM_Booking, $EM_Bookings_Table, $csv){
		$EM_Form = self::get_form();
		if( $EM_Form->is_user_field($col) && !empty($EM_Form->form_fields[$col]) ){
			$field = $EM_Form->form_fields[$col];
			$EM_Person = $EM_Booking->get_person();
			$value = !$EM_Booking->is_no_user() ? self::get_user_meta($EM_Person->ID, $col, true):'';
			if( empty($value) && isset($EM_Booking->booking_meta['registration'][$col]) ){
				$value = $EM_Booking->booking_meta['registration'][$col];
			}
			if( !empty($value) ) $value = $EM_Form->get_formatted_value($field, $value);
		}
		return $value;
	}
	
	public static function em_bookings_table_cols_template($template, $EM_Bookings_Table){
		$EM_Form = self::get_form();
		foreach($EM_Form->form_fields as $field_id => $field ){
			$template[$field_id] = $field['label'];
		}
		return $template;
	}


	
	/*
	 * ----------------------------------------------------------
	 * ADMIN Functions
	 * ----------------------------------------------------------
	 */
	
	/**
	 * Gets data from the right location according to the field ID provided. For example, user_email (emails) are retreived from the wp_users table whereas other info is usually taken from wp_usermeta
	 * @param int $user_id
	 * @param string $field_id
	 * @param bool $single
	 */
	public static function get_user_meta( $user_id = false, $field_id = "", $single=true){
		if( !$user_id ) $user_id = get_current_user_id();
		if( $field_id == 'user_email' ){
			$WP_User = get_user_by('id', $user_id);
			$return = $WP_User->user_email;
		}elseif( $field_id == 'name' ){
			$WP_User = get_user_by('id', $user_id);
			$EM_Person = new EM_Person($WP_User);
			$return = $EM_Person->get_name();
		}elseif( $field_id == 'user_login' ){
			$WP_User = get_user_by('id', $user_id);
			$EM_Person = new EM_Person($WP_User);
			$return = $EM_Person->user_login;
		}else{
			$return = get_user_meta($user_id, $field_id, true);
		}
		return $return;
	}
	
	/**
	 * Updates data to the right location according to the field ID provided. For example, user_email (emails) are saved to the wp_users table whereas other info is usually taken from wp_usermeta
	 * @param string $field_id
	 */
	public static function update_user_meta($user_id = false, $field_id = "", $value = ""){
		global $wpdb;
		if( !$user_id ) $user_id = get_current_user_id();
		if( $field_id == 'user_email' && is_email($value) ){
			return $wpdb->update($wpdb->users, array('user_email'=> $value), array('ID'=>$user_id));
		}elseif( $field_id == 'user_name' ){
			$name = explode(' ', $value);
			update_user_meta($user_id, 'first_name', array_shift($name));
			update_user_meta($user_id, 'last_name', implode(' ',$name));
		}else{
			return update_user_meta($user_id, $field_id, $value);
		}
	}

    public static function data_privacy_export_user( $export_item, $user ){
	    $EM_Form = self::get_form();
	    $export_item['data'] = array();
        foreach( $EM_Form->form_fields as $field_id => $field ){
            if( $field['type'] != 'html' ){
                $value = self::get_user_meta($user->ID, $field_id, true);
                if( !empty($value) ){
                    $export_item['data'][] = array( 'name' => $field['label'], 'value' => $EM_Form->get_formatted_value($field, $value) );
                }
            }
        }
	    return $export_item;
    }

    //Data Privacy Functions

    public static function data_privacy_export_booking( $export_item, EM_Booking $EM_Booking ){
	    if( $EM_Booking->person_id == 0 ){
            $EM_Form = self::get_form();
            foreach( $EM_Form->form_fields as $field_id => $field ){
                if( $field['type'] != 'html' ){
                    if( !empty($EM_Booking->booking_meta['registration'][$field_id]) ){
                        $value = $EM_Booking->booking_meta['registration'][$field_id];
                        $export_item['data'][$field_id] = array( 'name' => $field['label'], 'value' => $EM_Form->get_formatted_value($field, $value) );
                    }
                }
            }
        }
        return $export_item;
    }

    public static function em_booking_get_person( $EM_Person, $EM_Booking ){
	    $EM_Form = self::get_form();
	    foreach( $EM_Form->form_fields as $field_id => $field ){
			
		    if( $field['type'] != 'html' ){
			    if( $EM_Person->person_id == 0 && !empty($EM_Booking->booking_meta['registration'][$field_id]) ){
				    $value = $EM_Booking->booking_meta['registration'][$field_id];
					
				    $EM_Person->custom_user_fields[$field_id] = array('name' => $field['label'], 'value' => $EM_Form->get_formatted_value($field, $value));
			    }else{
				    $value = self::get_user_meta($EM_Person->ID, $field_id, true);
				    $EM_Person->custom_user_fields[$field_id] = array('name' => $field['label'], 'value' => $EM_Form->get_formatted_value($field, $value));
                }
		    }
	    }
	    return $EM_Person;
    }
}
EM_User_Fields::init();