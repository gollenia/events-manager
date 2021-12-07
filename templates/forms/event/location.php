<?php
global $EM_Event;
$required = apply_filters('em_required_html','required');

//determine location types (if neexed)
$location_types = array();

$location_types[0] = array(
	'selected' =>  $EM_Event->location_id === '0' || $EM_Event->location_id === 0,
	'description' => esc_html__('No Location','events-manager'),
);

if( EM_Locations::is_enabled() ){
	$location_types['location'] = array(
		'selected' =>  !empty($EM_Event->location_id),
		'display-class' => 'em-location-type-place',
		'description' => esc_html__('Physical Location','events-manager'),
	);
}
foreach( EM_Event_Locations\Event_Locations::get_types() as $event_location_type => $EM_Event_Location_Class ){ /* @var EM_Event_Locations\Event_Location $EM_Event_Location_Class */
	if( $EM_Event_Location_Class::is_enabled() ){
		$location_types[$EM_Event_Location_Class::$type] = array(
			'display-class' => 'em-event-location-type-'. $EM_Event_Location_Class::$type,
			'selected' => $EM_Event_Location_Class::$type == $EM_Event->event_location_type,
			'description' => $EM_Event_Location_Class::get_label(),
		);
	}
}
?>


<div class="em-input-field em-input-field-select em-location-types <?php if( count($location_types) == 1 ) echo 'em-location-types-single'; ?>">
<table class="em-location-data form-table">
	<tbody>
		<tr>
			<th>
				<label><?php esc_html_e ( 'Location Type', 'events-manager')?></label>
			</th>
			<td>
				<select name="location_type" class="em-location-types-select" data-active="<?php echo esc_attr($EM_Event->event_location_type); ?>">
					<?php foreach( $location_types as $location_type => $location_type_option ): ?>
					<option value="<?php echo esc_attr($location_type); ?>" <?php if( !empty($location_type_option['selected']) ) echo 'selected="selected"'; ?> data-display-class="<?php if( !empty($location_type_option['display-class']) ) echo esc_attr($location_type_option['display-class']); ?>">
						<?php echo esc_html($location_type_option['description']); ?>
					</option>
					<?php endforeach; ?>
				</select>
				<?php if( $EM_Event->has_event_location() ): ?>
					<div class="em-location-type-delete-active-alert em-notice-warning">
						<div class="warning-bold">
							<p><em><?php esc_html_e('You are switching location type, if you update this event your event previous location data will be deleted.', 'events-manager'); ?></em></p>
						</div>
						<?php $EM_Event->get_event_location()->admin_delete_warning(); ?>
					</div>
				<?php endif; ?>
			</td>
		</tr>
	</tbody>
</table>
</div>
<?php if( EM_Locations::is_enabled() ): ?>
<div id="em-location-data" class="em-location-data em-location-type em-location-type-place <?php if( count($location_types) == 1 ) echo 'em-location-type-single'; ?>">
	<table class="em-location-data form-table">
		<?php if( get_option('dbem_use_select_for_locations') || !$EM_Event->can_manage('edit_locations','edit_others_locations') ) : ?>
		<tbody class="em-location-data">
			<tr class="em-location-data-select">
				<th><?php esc_html_e('Location:','events-manager') ?> </th>
				<td>
					<select name="location_id" id='location-select-id' size="1">
						<?php
						if ( count($location_types) == 1 ){ // we don't consider optional locations as a type for ddm
							?>
							<option value="0"><?php esc_html_e('No Location','events-manager'); ?></option>
							<?php
						}elseif( empty(get_option('dbem_default_location')) ){
							?>
							<option value="0"><?php esc_html_e('Select Location','events-manager'); ?></option>
							<?php
						}
						$ddm_args = array('private'=>$EM_Event->can_manage('read_private_locations'));
						$ddm_args['owner'] = (is_user_logged_in() && !current_user_can('read_others_locations')) ? get_current_user_id() : false;
						$locations = EM_Locations::get($ddm_args);
						$selected_location = !empty($EM_Event->location_id) || !empty($EM_Event->event_id) ? $EM_Event->location_id:get_option('dbem_default_location');
						foreach($locations as $EM_Location) {
							$selected = ($selected_location == $EM_Location->location_id) ? "selected='selected' " : '';
							if( $selected ) $found_location = true;
					        ?>
					        <option value="<?php echo esc_attr($EM_Location->location_id) ?>" title="<?php echo esc_attr("{$EM_Location->location_latitude},{$EM_Location->location_longitude}"); ?>" <?php echo $selected ?>><?php echo esc_html($EM_Location->location_name); ?></option>
					        <?php
						}
						if( empty($found_location) && !empty($EM_Event->location_id) ){
							$EM_Location = $EM_Event->get_location();
							if( $EM_Location->post_id ){
								?>
						        <option value="<?php echo esc_attr($EM_Location->location_id) ?>" title="<?php echo esc_attr("{$EM_Location->location_latitude},{$EM_Location->location_longitude}"); ?>" selected="selected"><?php echo esc_html($EM_Location->location_name); ?></option>
						        <?php
							}
						}
						?>
					</select>
				</td>
			</tr>
		</tbody>
		<?php else : ?>
		<tbody class="em-location-data">
			<?php
			global $EM_Location;
			if( $EM_Event->location_id !== 0 ){
				$EM_Location = $EM_Event->get_location();
			}elseif(get_option('dbem_default_location') > 0){
				$EM_Location = em_get_location(get_option('dbem_default_location'));
			}else{
				$EM_Location = new EM_Location();
			}
			?>
			<tr class="em-location-data-name">
				<th><?php _e ( 'Location Name:', 'events-manager')?></th>
				<td>
					<input id='location-id' name='location_id' type='hidden' value='<?php echo esc_attr($EM_Location->location_id); ?>' size='15' />
					<input class="regular-text" id="location-name" type="text" name="location_name" <?php echo $required; ?> value="<?php echo esc_attr($EM_Location->location_name, ENT_QUOTES); ?>" />
					<br />
					
					<p class="description" id="em-location-search-tip"><?php esc_html_e( 'Create a location or start typing to search a previously created location.', 'events-manager')?></p>
					
				</td>
		    </tr>
			<tr class="em-location-data-address">
				<th><?php _e ( 'Address:', 'events-manager')?>&nbsp;</th>
				<td>
					<input class="regular-text" id="location-address" type="text" <?php echo $required; ?> name="location_address" value="<?php echo esc_attr($EM_Location->location_address); ; ?>" />
				</td>
			</tr>
			<tr class="em-location-data-town">
				<th><?php _e ( 'City/Town:', 'events-manager')?>&nbsp;</th>
				<td>
					<input class="regular-text" id="location-town" type="text" <?php echo $required; ?> name="location_town" value="<?php echo esc_attr($EM_Location->location_town); ?>" />
				</td>
			</tr>
			<tr class="em-location-data-state">
				<th><?php _e ( 'State/County:', 'events-manager')?>&nbsp;</th>
				<td>
					<input class="regular-text" id="location-state" type="text" name="location_state" value="<?php echo esc_attr($EM_Location->location_state); ?>" />
				</td>
			</tr>
			<tr class="em-location-data-postcode">
				<th><?php _e ( 'Postcode:', 'events-manager')?>&nbsp;</th>
				<td>
					<input class="regular-text" id="location-postcode" type="text" name="location_postcode" value="<?php echo esc_attr($EM_Location->location_postcode); ?>" />
				</td>
			</tr>
			<tr class="em-location-data-region">
				<th><?php _e ( 'Region:', 'events-manager')?>&nbsp;</th>
				<td>
					<input class="regular-text"  id="location-region" type="text" name="location_region" value="<?php echo esc_attr($EM_Location->location_region); ?>" />
				</td>
			</tr>
			<tr class="em-location-data-country">
				<th><?php _e ( 'Country:', 'events-manager')?>&nbsp;</th>
				<td>
					
					<select <?php echo $required; ?> id="location-country" name="location_country">
						<option value="0" <?php echo ( $EM_Location->location_country == '' && $EM_Location->location_id == '' && get_option('dbem_location_default_country') == '' ) ? 'selected="selected"':''; ?>><?php _e('none selected','events-manager'); ?></option>
						<?php foreach(em_get_countries() as $country_key => $country_name): ?>
						<option value="<?php echo esc_attr($country_key); ?>" <?php echo ( $EM_Location->location_country == $country_key || ($EM_Location->location_country == '' && $EM_Location->location_id == '' && get_option('dbem_location_default_country')==$country_key) ) ? 'selected="selected"':''; ?>><?php echo esc_html($country_name); ?></option>
						<?php endforeach; ?>
					</select>
				</td>
			</tr>
			<tr class="em-location-data-url">
				<th><?php esc_html_e( 'URL:', 'events-manager')?>&nbsp;</th>
				<td>
					<input class="regular-text" id="location-url" type="text" name="location_url" value="<?php echo esc_attr($EM_Location->location_url); ; ?>" />
				</td>
			</tr>
		</tbody>
		
		<?php endif; ?>
	</table>
	<div id="em-location-reset">						
		<p class="description"><?php esc_html_e('Reset this form to create a location or search again.', 'events-manager'); ?></p>
		<a class="components-button is-primary" href="#"><?php esc_html_e('Reset', 'events-manager')?></a>
	</div>
</div>

<?php endif; ?>
<div class="em-event-location-data">
	<?php foreach( EM_Event_Locations\Event_Locations::get_types() as $event_location_type => $EM_Event_Location_Class ): /* @var EM_Event_Locations\Event_Location $EM_Event_Location_Class */ ?>
		<?php if( $EM_Event_Location_Class::is_enabled() ): ?>
			<div class="em-location-type em-event-location-type-<?php echo esc_attr($event_location_type); ?>  <?php if( count($location_types) == 1 ) echo 'em-location-type-single'; ?>">
			<?php $EM_Event_Location_Class::load_admin_template(); ?>
			</div>
		<?php endif; ?>
	<?php endforeach; ?>
</div>