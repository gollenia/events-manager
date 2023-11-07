<?php


class EM_Form extends EM_Object {
	
	public $form_fields = [];
	public $form_name = 'Default';
	public $field_values = [];
	public $user_fields = [];
	public $core_user_fields = array(
		'name' => 'Name',
		'user_login' => 'Username Login',
		'user_email' => 'E-mail (required)',
		'user_password' => 'Password',
		'first_name' => 'First Name',
		'last_name' => 'Last Name',
		'user_url' => 'Website',
	);
	protected $custom_user_fields = [];
	public $form_required_error = '';
	static $validate;
	/**
	 * If this form represents user fields, then it's set to true, otherwise set to false
	 * @var boolean
	 */
	public $is_user_form = false;
	
	function __construct( $form_data, $form_name=false, $user_fields = true ){
		if( is_array($form_data) ){
			//load form data from array
			$this->form_fields = $form_data;
		}else{
			//assume the text is the form name
			$this->form_fields = get_option($form_data);
			$this->form_name = $form_data;
		}
		if( !empty($form_name) ){
			$this->form_name = $form_name;
		}
		if( $user_fields ){
			$this->user_fields = apply_filters('emp_form_user_fields',$this->core_user_fields, $this);
			$this->custom_user_fields = array_diff($this->user_fields, $this->core_user_fields);
		}
	}
	
	function get_post( ){
	    $custom_user_fields = EM_User_Fields::get_form()->form_fields;
		
		foreach($this->form_fields as $field){
			
		    $fieldid = $field['fieldid'];
			$value = '';
			if( !isset($_REQUEST[$fieldid]) ){ //for things like checkboxes when editing
			    $_REQUEST[$fieldid] = '';
			    if($field['type'] == 'checkbox') $_REQUEST[$fieldid] = '0'; //force save a 0 rather than a blank so we can check it
			}
			if( !is_array($_REQUEST[$fieldid])){
				$this->field_values[$fieldid] = wp_kses_data(stripslashes($_REQUEST[$fieldid]));
			}elseif( is_array($_REQUEST[$fieldid])){
			    $array = [];
			    foreach( $_REQUEST[$fieldid] as $key => $array_value ){
			        $array[$key] = wp_kses_data(stripslashes($array_value));
			    }
				$this->field_values[$fieldid] = $array;
			}
			//if this is a custom user field, change $filed to the original field so the right date/time info is retreived
	    	if( array_key_exists($field['type'], $this->custom_user_fields) && array_key_exists($field['fieldid'], $custom_user_fields) ){
	    	    $field = $custom_user_fields[$field['fieldid']];
	    	}
			//dates and time are special
			
			//check that user fields were indeed submitted for validation by logged in users, or were not editable, in which case we populate form with previously saved data 
			if( array_key_exists($field['type'], $this->user_fields) && !self::validate_reg_fields($field) ){
				$this->field_values[$fieldid] = EM_User_Fields::get_user_meta(get_current_user_id(), $field['type']);
			}
		}
		return true;
	}

	public static function load_block_defaults($type) {
		$defaults = [];
		$file = Events::DIR . "/src/blocks/form/" . $type . "/block.json";
		if(!$file) return $defaults;
		$block_data = json_decode( file_get_contents($file) );
		foreach($block_data->attributes as $key => $value) {
			if(!property_exists($value, 'default')) continue;
			$defaults[$key] = $value->default;
		}
		
		return $defaults;
	}
	
	function get_values(){
		return $this->field_values;
	}
	
	function get_formatted_value( $field, $field_value ){
		
		//output formatted value for special fields
		switch( $field['type'] ){
			case 'checkbox':
				$field_value = ($field_value && $field_value != 'n/a') ? __('Yes','events-manager'):__('No','events-manager');
				break;
			
			case 'time':
			    //split ranges (or create single array) and format, then re-implode
			    if( $field_value != 'n/a' ){
					$time_format = get_option('time_format');
				    $field_values = explode(',', $field_value);
				    foreach($field_values as $key => $value){
						$field_values[$key] = date($time_format, strtotime('2010-01-01 '.$value));
					}
				    $field_value = implode(',', $field_values);
					//set seperator and replace the comma
					$seperator = empty($field['options_time_range_seperator']) ? ' - ': $field['options_time_range_seperator'];
					$field_value = str_replace(',',' '.$seperator.' ', $field_value);
				}
				break;
			case 'country':
				if( $field_value != 'n/a' ){ 
					$countries = \Contexis\Events\Intl\Countries::get();
					if( !empty($countries[$field_value]) ) $field_value = $countries[$field_value];
				}
				break;
			default:
			    if( is_array($field_value) ){ $field_value = implode(', ', $field_value); }
			    break;
		}
		return $field_value;
	}
	
	/**
	 * Returns true if this field is not a user field or an html field, meaning it is stored information not at a user-account level, false if not.
	 * @param mixed $field_or_id
	 * @return boolean
	 */
	public function is_normal_field( $field_or_id ){
        $field_id = is_array($field_or_id) ? $field_or_id['fieldid'] : $field_or_id;
	    return array_key_exists($field_id, $this->form_fields) 
		&& !array_key_exists($field_id, $this->user_fields) 
		&& !in_array($field_id, array('user_email','user_name'));
	}
	
	/**
	 * Returns true if this is a field stored as at a user-account level, false if not.
	 * @param mixed $field_or_id
	 * @return boolean
	 */
	public function is_user_field( $field_or_id ){
        $field_id = ( is_object($field_or_id) ) ? $field_or_id['fieldid'] : $field_or_id;
	    return array_key_exists($field_id, $this->user_fields) || in_array($field_id, array('user_email','user_name'));
	}
	
	/**
	 * Prints html fields according to this field structure.
	 * @param array $booking_form_fields
	 */
	function __toString(){
		$return = '';
		if( is_array($this->form_fields) ){
			foreach($this->form_fields as $field){
				$return .= self::output_field($field);
			}
		}
		return apply_filters('emp_form_output',$return, $this);
	}
	
	/**
	 * Outputs a single field according to this field structure.
	 * @param array $field
	 * @return string
	 * @deprecated since version 6.2.0
	 */
	function output_field($field, $post=true){
		ob_start();
		$required = "";
		$field = $this->translate_field($field);
		switch($field['type']){
			case 'html':
			     echo $this->output_field_input($field, $post);
			     break;
			case 'text':
			case 'textarea':
			case 'date':
			case 'checkboxes':
			case 'radio':
			case 'select':
			case 'country':
			case 'multiselect':
			case 'time':
				
				$tip_type = $field['type'];
				if( $field['type'] == 'textarea' ) $tip_type = 'text';
				if( in_array($field['type'], array('select','multiselect')) ) $tip_type = 'select';
				if( in_array($field['type'], array('checkboxes','radio')) ) $tip_type = 'selection';
				?>
				<p class="input-group input-<?php echo $field['type']; ?> input-field-<?php echo $field['fieldid'] ?>">
					<label for='<?php echo $field['fieldid'] ?>'>
						<?php if( !empty($field['options_'.$tip_type.'_tip']) ): ?>
							<span class="form-tip" title="<?php echo esc_attr($field['options_'.$tip_type.'_tip']); ?>">
								<?php echo $field['label'] ?> 
							</span>
						<?php else: ?>
							<?php echo $field['label'] ?> 
						<?php endif; ?>
					</label>
					<?php echo $this->output_field_input($field, $post); ?>
				</p>
				<?php
				break;
			
			case 'checkbox':
				$tip_type = $field['type'];
				?>
				<p class="input-group input-<?php echo $field['type']; ?> input-field-<?php echo $field['fieldid'] ?>">
					<label>
						<?php echo $this->output_field_input($field, $post); ?>
						<span></span><?php echo $field['label'] ?>
					</label>
				</p>
				<?php
			default:
				if( array_key_exists($field['type'], $this->user_fields) && self::show_reg_fields($field) ){
					if( array_key_exists($field['type'], $this->core_user_fields) ){
						if( $field['type'] == 'user_password' ){
							?>
							<p class="input-<?php echo $field['type']; ?> input-group">
								<label for='<?php echo $field['fieldid'] ?>'>
									<?php if( !empty($field['options_reg_tip']) ): ?>
										<span>
											<?php echo $field['label'] ?> <?php echo $required  ?>
										</span>
									<?php else: ?>
										<?php echo $field['label'] ?> <?php echo $required  ?>
									<?php endif; ?>
								</label>
								<input type="password" name="<?php echo $field['fieldid'] ?>" />
							</p>
							<?php
						}else{
							//registration fields
							
							?>
							<p class="input-<?php echo $field['type']; ?> input-group">
								<label for='<?php echo $field['fieldid'] ?>'>
									<?php if( !empty($field['options_reg_tip']) ): ?>
										<span>
											<?php echo $field['label'] ?> <?php echo $required  ?>
										</span>
									<?php else: ?>
										<?php echo $field['label'] ?> <?php echo $required  ?>
									<?php endif; ?>
								</label> 
								<?php echo $this->output_field_input($field, $post); ?>
							</p>
							<?php	
						}
					}elseif( array_key_exists($field['type'], $this->custom_user_fields) ) {
						?>
						<p class="input-<?php echo $field['type']; ?> input-group">
							<label for='<?php echo $field['fieldid'] ?>'>
								<?php if( !empty($field['options_reg_tip']) ): ?>
									<span>
										<?php echo $field['label'] ?> <?php echo $required  ?>
									</span>
								<?php else: ?>
									<?php echo $field['label'] ?> <?php echo $required  ?>
								<?php endif; ?>
							</label>
							<?php do_action('em_form_output_field_custom_'.$field['type'], $field, $post); ?>
						</p>
						<?php
					}
				}
				break;
		}	
		return apply_filters('emp_forms_output_field', ob_get_clean(), $this, $field);	
	}
	
	function output_field_input($field, $post=true){
		
		ob_start();
		$default = '';
		$default_html = '';
		if($post === true && !empty($_REQUEST[$field['fieldid']])) {
			$default = is_array($_REQUEST[$field['fieldid']]) ? $_REQUEST[$field['fieldid']]:esc_attr($_REQUEST[$field['fieldid']]);
			$default_html = is_array($_REQUEST[$field['fieldid']]) ? $_REQUEST[$field['fieldid']] : esc_attr($_REQUEST[$field['fieldid']]);
		}elseif( $post !== true && !empty($post) ){
			$default = is_array($post) ? $post:esc_attr($post);
			$default_html = is_array($post) ? $post : esc_attr($post);
		}
		
		$field['name'] = !empty($field['name']) ? $field['name'] : $field['fieldid'];
		$required = array_key_exists('required', $field) && $field['required'];
		switch($field['type']){
			case 'html':
			    echo $field['options_html_content'];
			    break;			
			case 'text':
				
				if ($required) {
					$error = $field['options_text_error'] ?? "";
					$pattern = $field['options_text_regex'] ?? "";
					echo "<input type='text' class='regular-text' data-text-error='{$error}' name='{$field['name']}' id='{$field['fieldid']}'  value='{$default}' ";
					echo (' required ');
					echo $pattern ? "pattern='{$pattern}'" : "";
					echo " />";
				} else {
					echo '<input type="text" name="' . $field['name'] . '" id="' . $field['fieldid'] . '" class="regular-text" value="' . $default . '" ' . ' />';
				}
				break;	
			case 'email':
				echo '<input type="email" name="' . $field['name'] . '" id="' . $field['fieldid'] . '" class="regular-text" value="' . $default . '" ' . ($required ? "required " : "") . ' />';
				break;	
			case 'phone': 
				echo '<input type="tel" name="' . $field['name'] . '" id="' . $field['fieldid'] . '" class="regular-text" value="' . $default . '" ' . ($required ? "required " : "") . ' />';
				break;
			case 'textarea':
				$size = 'rows="2" cols="20"';				
				echo '<textarea name="' . $field['name'] . '" id="' . $field['fieldid'] . '" class="regular-text code" ' . $size . ' ' . ($required ? " required" : "") . '>' . $default_html . '</textarea>';
				break;
			case 'checkbox':
				$checked = ($default && $default != 'n/a');
				echo '<label><input type="checkbox" name="' . $field['name'] . '" id="' . uniqid() . '" value="1" ' . ($checked ? 'checked="checked"' : '') . ($required ? "required " : "") . '/>' . $field['help'] . '</label>';
				break;
			case 'radio':
				echo "<fieldset><legend class='screen-reader-text'></legend>";
				$values = explode("\r\n",$field['options_selection_values']);
				foreach($field['options'] as $options){
					echo '<label class="radio-inline">';
					echo '<input type="radio" name="' . $field['name'] . '" class="' . $field['fieldid'] . '" value="' . esc_attr($options) . '"' . (($options == $default) ? 'checked="checked"' : '') . '/>';
					echo  '<span>' . $options . '</span></label><br>';
				}
				echo "</fieldset>";
				break;
			case 'select':
			
				$values = $field['options'];
				$multi = $field['type'] == 'multiselect';
				if($multi && !is_array($default)) $default = (empty($default)) ? []:array($default);
				
				echo '<select name="' . $field['name'] . (($multi) ? '[]':'') . '" class="' . $field['fieldid'] . '" ' . ($required ? "required " : "") . '>';

				if( !$field['options_select_default'] ){
					
					echo '<option value="">' . esc_html($field['options_select_default_text']) . '</option>';
					
				}
					
				foreach($values as $key => $value) {
					$value = trim($value);
					echo '<option ' . (( ($key == 1 && $field['options_select_default']) || ($multi && in_array($value, $default)) || ($value == $default) ) ? 'selected="selected"' : '' ) . '>' . esc_html($value) . '</option>';
				}
				
				echo '</select>';
				break;
			case 'country':
				$countries = [
					"de_DE" => "DE",
					"de_AT" => "AT",
					"de_CH" => "CH",
					"fr_FR" => "CH"
				];
				echo '<select name="' . $field['name'] . '" class="' . $field['fieldid'] . '" ' . ($required ? "required " : "") . '>';
				foreach(\Contexis\Events\Intl\Countries::get(__('none selected','em-pro')) as $country_key => $country_name) {
						echo '<option text="' . '" value="' . $country_key .'" ' . (($country_key == $countries[get_locale()]) ? 'selected="selected"':'') . '>' . $country_name . '</option>';
				}
				echo '</select>';
				
				break;
			case 'date':
				echo '<input type="date" name="' . $field['name'] . '" id="' . $field['fieldid'] . '" class="regular-text" value="' . $default . '" ' . ($required ? "required " : "") . ($field['max'] ? " max='" . $field['max'] . "'" : "") . ($field['min'] ? " min='" . $field['min'] . "'" : "") . ' />';
    			break;				
			default:
				if( array_key_exists($field['type'], $this->user_fields) && self::show_reg_fields() ){
					//registration fields
				    if( $field['type'] != 'user_login' || EM_Bookings::$force_registration || !is_user_logged_in() ){
						if ($field['name'] == "user_email") {
							echo '<input type="email" name="' . $field['name'] . '" id="' . $field['fieldid'] . '" class="regular-text" value="' . $default . '"' . ($required ? " required " : " ") . ' />';							
						} else {					
							echo '<input type="text" name="' . $field['name'] . '" id="' . $field['fieldid'] . '" class="regular-text" value="' . $default . '" ' . ($required ? ' required ' : "") . ' />';
						}
					}else{
						echo $default;
					}
				}
				break;
		}	
		return apply_filters('emp_forms_output_field_input', ob_get_clean(), $this, $field, $post);	
	}


	
	/**
	 * Validates all fields, if false, an array of objects is returned.
	 * @return array|string
	 */
	function validate($attendee = false){
		$reg_fields = self::validate_reg_fields();
		foreach( $this->form_fields as $field ){
			$field_id = $field['fieldid'];
			if( $reg_fields || ( !$reg_fields && !array_key_exists($field['type'], $this->user_fields) ) ){ //don't validate reg info if we won't grab anything in get_post
				$value = ( array_key_exists($field_id, $this->field_values) ) ? $this->field_values[$field_id] : '';
				$this->validate_field($field_id, $value, $attendee);
			}
		}
		if( count($this->get_errors()) > 0 ){
			return false;
		}
		return true;
	}
	
	/**
	 * Validates a field and adds errors to the object it's referring to (can be any extension of EM_Object)
	 * @param array $field
	 * @param mixed $value
	 */
	function validate_field( $field_id, $value, $attendee = false ){
		$field = array_key_exists($field_id, $this->form_fields) ? $this->form_fields[$field_id]:false;
		$field = $this->translate_field($field);
		$value = (is_array($value)) ? $value:trim($value);
		if(!$field) return;
		$err = sprintf($this->form_required_error, $field['label']);
		if( is_array($field) ){
			$result = true; //innocent until proven guilty
			switch($field['type']){
				case 'text':
				case 'textarea':
					//regex
					if( trim($value) != '' && !empty($field['options_text_regex']) ){
						//check to see if regex has modifiers if starting with a / string
						$regex = $field['options_text_regex'][0] == '/' ? $field['options_text_regex'] : '/'.$field['options_text_regex'].'/';
						if( !@preg_match($regex,$value) ){
							$this_err = (!empty($field['options_text_error'])) ? $field['options_text_error']:$err;
							$this->add_error($this_err);
							$result = false;
						}
					}
					//non-empty match
					if( $result && trim($value) == '' && !empty($field['required']) ){
						$this->add_error($err);
						$result = false;
					}
					break;
				case 'checkbox':
					//non-empty match
					if( empty($value) && !empty($field['required']) ){
						$this_err = (!empty($field['options_checkbox_error'])) ? $field['options_checkbox_error']:$err;
						$this->add_error($this_err);
						$result = false;
					}
					break;
				case 'radio':
				    $value = html_entity_decode($value); //we must make sure all is decoded, both selection values and submitted value itself
					$values = $field['options'];
					foreach($values as $k => $v) $values[$k] = trim($v);
					//in-values
					if( (!empty($value) && !in_array($value, $values)) || (empty($value) && !empty($field['required'])) ){
						$this_err = (!empty($field['error'])) ? $field['error']:$err;
						$this->add_error($this_err);
						$result = false;
					}				
					break;
				
				case 'select':
					$values = $field['options'];
					foreach($values as $k => $v) $values[$k] = trim($v);
					
					if( (!empty($value) && !in_array($value, $values)) || (empty($value) && !empty($field['required'])) ){
						$error = (!empty($field['error'])) ? $field['error']:$err;
						$this->add_error_array(["value" => $field, "form" => $this->form_name, "field" => $field['fieldid'], "error" => $error, "attendee" => $attendee]);
						$result = false;
					}		
					break;
				case 'country':
					$values = \Contexis\Events\Intl\Countries::get(__('none selected','events-manager'));
					//in-values
					$result = false;
					$this_err = "Select a country";
					if( (!empty($value) && !array_key_exists($value, $values)) || (empty($value) && !empty($field['required'])) ){
						$this_err = (!empty($field['options_select_error'])) ? $field['options_select_error']:$err;
						$this->add_error($this_err);
						$result = false;
					}				
					break;			
				case 'date':

					if( empty($value) && !empty($field['required']) ){
						$this_err = (!empty($field['options_date_max'])) ? $field['options_date_max']:$err;
						$this->add_error($this_err);
						$result = false;
					}		    				    
				    
					
					
					if( $field['min'] ) {
						$current = strtotime($value);
						$min = strtotime($field['min']);
						if($current < $min) {
							$this_err = (!empty($field['options_date_min_error'])) ? $field['options_date_min_error']:__('Too mini','events-manager');
							$this->add_error($this_err);
							$result = false;
						}
					}

					if( $field['max'] ) {
						$current = strtotime($value);
						$max = strtotime($field['max']);
						if($current > $max) {
							$this_err = (!empty($field['options_date_min_error'])) ? $field['options_date_max_error']:__('Too maxi','events-manager');
							$this->add_error($this_err);
							$result = false;
						}
					}
					break;	

				case 'email':
					if( ! is_email( $value ) ){
						$this->add_error( __( '<strong>ERROR</strong>: The email address isn&#8217;t correct.', 'events-manager') );
						$result = false;
					}
					break;	

				case 'time':
				   
				    if( !empty($value) ){
						if( !preg_match('/^([01]\d|2[0-3]):([0-5]\d) ?(AM|PM)?$/', $value) ){
							$this_err = (!empty($field['options_time_error_format'])) ? $field['options_time_error_format']:__('Please use the time picker provided to select the appropriate time format.','em-pro');
							$this->add_error($this_err);
							$result = false;
						}
					}
					if( empty($value) && !empty($field['required']) ){
						
							$this_err = (!empty($field['options_time_error'])) ? $field['options_time_error']:$err;
							$this->add_error($this_err);
							$result = false;
						
					}
					break;	
				
				default:
					//Registration and custom fields
					//$is_manual_booking_new_user = (is_user_logged_in() && !empty($_REQUEST['manual_booking']) && wp_verify_nonce($_REQUEST['manual_booking'], 'em_manual_booking_'.$_REQUEST['event_id']) && $_REQUEST['person_id'] == -1 );
					if( array_key_exists($field['type'], $this->user_fields) && self::validate_reg_fields($field) ){
						
						//add field-specific validation
						if ( $field['type'] == 'user_email' ) {
							if( ! is_email( $value ) ){
								$this->add_error( __( '<strong>ERROR</strong>: The email address isn&#8217;t correct.', 'events-manager') );
								$result = false;
							}elseif( is_user_logged_in() ){
								$email_exists = email_exists($value);
								if( $email_exists && $email_exists != get_current_user_id() ){
									$this->add_error( __('This email already exists in our system, please log in to register to proceed with your booking.','events-manager') );
									$result = false;	
								}
							}
						}
						//regex - array values such as dates or checkboxes don't need regex checking
						if( !is_array($value) && trim($value) != '' && !empty($field['options_reg_regex']) ){
							$regex = $field['options_reg_regex'][0] == '/' ? $field['options_reg_regex'] : '/'.$field['options_reg_regex'].'/';
							if( !@preg_match($regex,$value) ){
								$this_err = (!empty($field['options_reg_error'])) ? $field['options_reg_error']:$err;
								$this->add_error($this_err);
								$result = false;
							}
						}
						//non-empty match
						if( empty($value) && !empty($field['required']) ){
							$this->add_error($err);
							$result = false;
						}
						//custom field chekcs
						if( array_key_exists($field['type'], $this->custom_user_fields)) {
							//custom field, so just apply 
							$result = apply_filters('em_form_validate_field_custom', $result, $field, $value, $this);
						}
					}
					break;
			}
		}else{
			$result = false;
		}
		return apply_filters('emp_form_validate_field',$result, $field, $value, $this);
	}
	
	function translate_field( $field_data ){
		if( EM_ML::$is_ml && !empty($field_data['ml']) ){
			foreach($field_data['ml'] as $field_key => $translations){
				if( array_key_exists($field_key, $field_data) && !empty($translations[get_locale()]) ){
					$field_data[$field_key] = $translations[get_locale()];
				}
			}
		}
		return $field_data;
	}
	
	/*
	 * --------------------------------------------------------
	 * Admin-Side Functions
	 * --------------------------------------------------------
	 */
	
	static function get_input_default($key, $field_values, $type='text', $value=""){
		$return = '';
		if(is_array($field_values)){
			switch ($type){
				case 'text':
					$return = (array_key_exists($key,$field_values)) ? 'value="'.esc_attr($field_values[$key]).'"':'value="'.esc_attr($value).'"';
					break;
				case 'textarea':
					$return = (array_key_exists($key,$field_values)) ? esc_html($field_values[$key]):esc_html($value);
					break;
				case 'select':
					$return = ( array_key_exists($key,$field_values) && $value == $field_values[$key] ) ? 'selected="selected"':'';
					break;
				case 'checkbox':
					$return = ( !empty($field_values[$key]) && $field_values[$key] == 1 ) ? 'checked="checked"':'';
					break;
				case 'radio':
					$return = ( $value == $field_values[$key] ) ? 'checked="checked"':'';
					break;
			}
		}
		return apply_filters('emp_form_get_input_default',$return, $key, $field_values, $type, $value);
	}

	static function input_default($key, $fields, $type = 'text', $value=""){ echo self::get_input_default($key, $fields, $type, $value); }

	
	/**
	 * Returns whether or not to show registration fields, and if a field type or field object is passed it'll check whether that specific field should be shown in this instance.
	 * Takes into account whether a user is logged in and fields like email and name should be shown.
	 * @param string $field
	 * @return mixed
	 */
	public static function show_reg_fields( $field = false ){
		return true;
	}

	public static function validate_reg_fields( $field = false ){
		if( EM_Gateways::is_manual_booking(true) ) return true; //short circuit if we're on a manual booking for a new user
		if( !empty($field['type']) && $field['type'] == 'user_login' && is_user_logged_in() ) return false;
		$validate =  true;
		return $validate && self::show_reg_fields( $field );
	}
	
	
	function editor_save(){
		//Update Values
		return update_option('em_booking_form_fields',$this->form_fields);
	}
	
	function editor_get_post(){
		if( !empty($_REQUEST['form_action']) && $_REQUEST['form_action'] == 'form_fields' && wp_verify_nonce($_REQUEST['_wpnonce'], 'form_fields') ){
			//Booking form fields
			//extract request info back into item lists, but first assign fieldids to new items and sanitize old ones
			$field_ids = [];
			foreach( $_REQUEST['fieldid'] as $fieldid_key => $fieldid ){
				if( !$this->is_user_form && $_REQUEST['type'][$fieldid_key] == 'name' ){ //name field
					$_REQUEST['fieldid'][$fieldid_key] = 'user_name';
				}elseif( !$this->is_user_form && array_key_exists($_REQUEST['type'][$fieldid_key], $this->user_fields) ){ //other fields
					$_REQUEST['fieldid'][$fieldid_key] = $_REQUEST['type'][$fieldid_key];
				}else{
					if( empty($fieldid) ){
						$_REQUEST['fieldid'][$fieldid_key] = sanitize_title($_REQUEST['label'][$fieldid_key]); //assign unique id
					}
					//check for duplicate IDs and if they exist, change the latter one to avoid conflicts
					if( in_array($_REQUEST['fieldid'][$fieldid_key], $field_ids) ){
						$suffix = 2;
						$field_id_raw = $_REQUEST['fieldid'][$fieldid_key];
						if( preg_match('/_([0-9]+)$/', $field_id_raw, $suffix_match) ){
							$field_id_raw = str_replace($suffix_match[0], '', $field_id_raw);
							$suffix = $suffix_match[1] + 1;
						}
						while( in_array($field_id_raw.'_'.$suffix, $field_ids) ) $suffix++;
						$_REQUEST['fieldid'][$fieldid_key] = $field_id_raw.'_'.$suffix;
					}
				}
				$_REQUEST['fieldid'][$fieldid_key] = $field_ids[] = str_replace('.','_',$_REQUEST['fieldid'][$fieldid_key]);
			}
			//get field values
			global $allowedposttags;
			$this->form_fields = [];
			foreach( $_REQUEST as $key => $value){
				if( is_array($value) ){
					foreach($value as $item_index => $item_value){
						if( !empty($_REQUEST['fieldid'][$item_index]) ){
							if( $key == 'options_text_regex' ){
								//regex can contain 'invalid' html which will get stripped, so we allow raw unsanitized strings given they're never output to a user
								if( isset($_REQUEST['em_fields_json']) ){
									//if using the workaround for large forms, regexes seem to already be properly stripped
									$item_value = preg_replace('/  +/', ' ', $item_value);
								}else{
									$item_value = preg_replace('/  +/', ' ', stripslashes($item_value));
								}
								$this->form_fields[$_REQUEST['fieldid'][$item_index]][$key] = $item_value;
							}else{
								$item_value = preg_replace('/  +/', ' ', stripslashes(wp_kses($item_value, $allowedposttags)));
								$this->form_fields[$_REQUEST['fieldid'][$item_index]][$key] = $item_value;
							}
						}
					}
				}
			}
			
			return true;
		}
		return false;
	}

	
	public static function get_form_data($form_id = 0, $associative = true) {

		if(!$form_id) return [];

		$blocks = self::get_form_post($form_id);

		if(empty($blocks)) return [];
		
		foreach( $blocks as $key => $block ) {
			$type = self::get_type_from_blockname($block['blockName']);
			$value = $type == 'html' ? render_block($block) : (array_key_exists('default', $block['attrs']) ? $block['attrs']['default'] : null);
			$block['attrs'] = array_merge(EM_Form::load_block_defaults($type), $block['attrs'], ['type' => $type]);
			if($type == 'html') {
				$block['attrs']['value'] = render_block($block);
				$block['attrs']['fieldid'] = 'html_' . random_int(100,999);
			}
			$index = $associative ? $block['attrs']['fieldid'] : $key;
			$form_data[$index] = $block['attrs'];
			$form_data[$index]['type'] = $type;
		}

		return $form_data;

	}

	public static function get_form_post($form_id = 0) {
		if(!$form_id) return [];
		$form = get_post(intval($form_id));
		
		$blocks = parse_blocks( $form->post_content );
		
		if(count($blocks) == 0) return [];
		if(!array_key_exists('innerBlocks', $blocks[0])) return [];

		return $blocks[0]['innerBlocks'];
	}

	public static function get_type_from_blockname($blockname) {
		return substr($blockname, strripos($blockname, '-') + 1);
	}
}