<?php
/**
 * An interface for the dbem_data option stored in wp_options as a serialized array. 
 * This option can hold various information which can be stored in one record rather than individual records in wp_options.
 * The functions in this class deal directly with that dbem_data option as if it was the wp_options table itself, and therefore
 * have similarities to the get_option and update_option functions. 
 * @since 5.8.2.0
 *
 */
namespace Contexis\Events;

class Options {

	const TEXT = 0;
	const EMAIL = 1;
	const PASSWORD = 2;
	const NUMBER = 3;
	const DATE = 4;
	const TIME = 5;

	
	/**
	 * Get a specific setting form the EM options array. If no value is set, an empty array is provided by default.
	 * @param string $option_name
	 * @param mixed $default the default value to return
	 * @param string $dataset
	 * @param boolean $site if set to true it'll retrieve a site option in MultiSite instead
	 * @return mixed
	 */
	public static function get( $option_name, $default = array(), $dataset = 'dbem_data', $site = false ){
		$data = $site ? get_site_option($dataset) : get_option($dataset);
		if( !empty($data[$option_name]) ){
			return $data[$option_name];
		}else{
			return $default;
		}
	}
	
	/**
	 * Set a value in the EM options array. Returns result of storage, which may be false if no changes are made.
	 * @param string $option_name
	 * @param mixed $option_value
	 * @param string $dataset
	 * @param boolean $site if set to true it'll retrieve a site option in MultiSite instead
	 * @return boolean
	 */
	public static function set( $option_name, $option_value, $dataset = 'dbem_data', $site = false ){
		$data = $site ? get_site_option($dataset) : get_option($dataset);
		if( empty($data) ) $data = array();
		$data[$option_name] = $option_value;
		return $site ? update_site_option($dataset, $data) : update_option($dataset, $data);
	}
	
	/**
	 * Adds a value to an specific key in the EM options array, and assumes the option name is an array.
	 * Returns true on success or false saving failed or if no changes made.
	 * @param string $option_name
	 * @param string $option_key
	 * @param mixed $option_value
	 * @param string $dataset
	 * @param boolean $site
	 * @return boolean
	 */
	public static function add( $option_name, $option_key, $option_value, $dataset = 'dbem_data', $site = false ){
		$data = $site ? get_site_option($dataset) : get_option($dataset);
		if( empty($data[$option_name]) ){
			$data[$option_name] = array( $option_key => $option_value );
		}else{
			$data[$option_name][$option_key] = $option_value;
		}
		return $site ? update_site_option($dataset, $data) : update_option($dataset, $data);
	}


	public static function option_items($array, $saved_value) {
		$output = "";
		foreach($array as $key => $item) {
			$selected ='';
			if ($key == $saved_value)
				$selected = "selected='selected'";
			$output .= "<option value='".esc_attr($key)."' $selected >".esc_html($item)."</option>\n";
		}
		echo $output;
	}
	
	public static function checkbox_items($name, $array, $saved_values, $horizontal = true) {
		$output = "";
		foreach($array as $key => $item) {
			$checked = "";
			if (in_array($key, $saved_values)) $checked = "checked='checked'";
			$output .= "<label><input type='checkbox' name='".esc_attr($name)."' value='".esc_attr($key)."' $checked /> ".esc_html($item)."</label>&nbsp; ";
			if(!$horizontal)
				$output .= "<br/>\n";
		}
		echo $output;
	
	}

	public static function input($title, $name, $description ='', $args = []) {

		$args = array_merge([
			'class' => 'regular-text',
			'type' => 0,
			'placeholder' => "",
			'default' => ''
		], $args);
		
		$types=['text', 'email', 'password', 'number', 'date', 'time'];

		if( preg_match('/^(.+)\[(.+)?\]$/', $name, $matches) ){
			$value = self::get($matches[2], $args['default'], $matches[1]);
		}else{
			$value = get_option($name, $args['default']);
		}
		?>
		<tr valign="top" id='<?php echo esc_attr($name);?>_row'>
			<th scope="row"><label for="<?php echo esc_attr($name) ?>"><?php echo esc_html($title); ?><label></th>
			<td>
				<input name="<?php echo esc_attr($name) ?>" type=<?php echo $types[$args['type']] ?> id="<?php echo esc_attr($name) ?>" placeholder="<?php echo esc_attr($args['placeholder']) ?>" class="<?php echo esc_attr($args['class']) ?>" value="<?php echo esc_attr($value, ENT_QUOTES); ?>" />
				
				<br />
				
				<p class="description"><?php echo $description; ?></p>
			</td>
		</tr>
		<?php
	}
	
	public static function textarea($title, $name, $description ='') {
		
		?>
		<tr valign="top" id='<?php echo esc_attr($name);?>_row'>
		<th scope="row"><label for="<?php echo esc_attr($name) ?>"><?php echo esc_html($title); ?><label></th>
				<td><fieldset>
					<?php if($description) { ?><p><label for="<?php echo esc_attr($name) ?>"><?php echo $description; ?></label></p><?php } ?>
					<p><textarea class="large-text" name="<?php echo esc_attr($name) ?>" id="<?php echo esc_attr($name) ?>" rows="6"><?php echo esc_attr(get_option($name), ENT_QUOTES);?></textarea></p>
		
					<br />
					
				</fieldset></td>
			</tr>
		<?php
	}
	
	public static function radio($name, $options, $title='') {
			$option = get_option($name);
			?>
			   <tr valign="top" id='<?php echo esc_attr($name);?>_row'>
				   <?php if( !empty($title) ): ?>
				   <th scope="row"><?php  echo esc_html($title); ?></th>
				   <td>
				   <?php else: ?>
				   <td colspan="2">
				   <?php endif; ?>
					   <table>
					   <?php foreach($options as $value => $text): ?>
						   <tr>
							   <td><input id="<?php echo esc_attr($name) ?>_<?php echo esc_attr($value); ?>" name="<?php echo esc_attr($name) ?>" type="radio" value="<?php echo esc_attr($value); ?>" <?php if($option == $value) echo "checked='checked'"; ?> /></td>
							   <td><?php echo $text ?></td>
						   </tr>
					<?php endforeach; ?>
					</table>
				</td>
			   </tr>
	<?php
	}
	
	public static function radio_binary($title, $name, $description='', $option_names = '', $trigger='', $untrigger=false) {
		if( empty($option_names) ) $option_names = array(0 => __('No','events-manager'), 1 => __('Yes','events-manager'));
		if( substr($name, 0, 7) == 'dbem_ms' ){
			$list_events_page = get_site_option($name);
		}else{
			$list_events_page = get_option($name);
		}
		if( $untrigger ){
			$trigger_att = ($trigger) ? 'data-trigger="'.esc_attr($trigger).'" class="em-untrigger"':'';
		}else{
			$trigger_att = ($trigger) ? 'data-trigger="'.esc_attr($trigger).'" class="em-trigger"':'';
		}
		?>
		   <tr valign="top" id='<?php echo $name;?>_row'>
			   <th scope="row"><?php echo esc_html($title); ?></th>
			   <td>
				   <?php echo $option_names[1]; ?> <input id="<?php echo esc_attr($name) ?>_yes" name="<?php echo esc_attr($name) ?>" type="radio" value="1" <?php if($list_events_page) echo "checked='checked'"; echo $trigger_att; ?> />&nbsp;&nbsp;&nbsp;
				<?php echo $option_names[0]; ?> <input  id="<?php echo esc_attr($name) ?>_no" name="<?php echo esc_attr($name) ?>" type="radio" value="0" <?php if(!$list_events_page) echo "checked='checked'"; echo $trigger_att; ?> />
				<br/><p class="description"><?php echo $description; ?></p>
			</td>
		   </tr>
		<?php
	}
	
	public static function checkbox($title, $name, $description='') {
	
		?>
		   <tr valign="top" id='<?php echo $name;?>_row'>
			   <th scope="row"><?php echo esc_html($title); ?></th>
			   <td>
				<label for="<?php echo esc_attr($name) ?>_yes">
				   <input id="<?php echo esc_attr($name) ?>_no" name="<?php echo esc_attr($name) ?>" type="hidden" value="0"> 
				<input id="<?php echo esc_attr($name) ?>_yes" name="<?php echo esc_attr($name) ?>" type="checkbox" value="1" <?php echo get_option($name) ? 'checked="checked"' : "" ?> />
				<?php echo $description; ?>
				</label>
			</td>
		   </tr>
		<?php
	}
	
	public static function select($title, $name, $list, $description='', $default='') {
		$option_value = get_option($name, $default);
		
		?>
		   <tr valign="top" id='<?php echo esc_attr($name);?>_row'>
		   <th scope="row"><label for="<?php echo esc_attr($name) ?>"><?php echo esc_html($title); ?><label></th>
			   <td>
				<select name="<?php echo esc_attr($name); ?>" >
					<?php 
					foreach($list as $key => $value) {
						if( is_array($value) ){
							?><optgroup label="<?php echo $key; ?>"><?php
							foreach( $value as $key_group => $value_group ){
								?>
								 <option value='<?php echo esc_attr($key_group) ?>' <?php echo ("$key_group" == $option_value) ? "selected='selected' " : ''; ?>>
									 <?php echo esc_html($value_group); ?>
								 </option>
								<?php 
							}
							?></optgroup><?php
						}else{
							?>
							 <option value='<?php echo esc_attr($key) ?>' <?php echo ("$key" == $option_value) ? "selected='selected' " : ''; ?>>
								 <?php echo esc_html($value); ?>
							 </option>
							<?php 
						} 
					}
					?>
				</select> <br/>
				<p class="description"><?php echo $description; ?></p>
			</td>
		   </tr>
		<?php
	}

	public static function save_button() {
		return '<tr><th>&nbsp;</th><td><p class="submit" style="margin:0px; padding:0px; text-align:right;"><input type="submit" class="button-primary" name="Submit" value="'. __( 'Save Changes', 'events-manager') .' ('. __('All','events-manager') .')" /></p></td></tr>';
	}
	
	
}