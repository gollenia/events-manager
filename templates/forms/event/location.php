<?php
global $EM_Event;
$required = apply_filters('em_required_html','required');

//determine location types (if neexed)
$location_types = array();

$location_types[0] = array(
	'selected' =>  $EM_Event->location_id === '0' || $EM_Event->location_id === 0,
	'description' => esc_html__('No Location','events'),
);

if( EM_Locations::is_enabled() ){
	$location_types['location'] = array(
		'selected' =>  !empty($EM_Event->location_id),
		'display-class' => 'em-location-type-place',
		'description' => esc_html__('Physical Location','events'),
	);
}
?>

<?php if( EM_Locations::is_enabled() ): ?>
<div id="em-location-data" class="em-location-data em-location-type em-location-type-place <?php if( count($location_types) == 1 ) echo 'em-location-type-single'; ?>">
	<table class="em-location-data form-table">
		
		<tbody class="em-location-data">
			<tr class="em-location-data-select">
				<th><?php esc_html_e('Location:','events') ?> </th>
				<td>
					<select name="location_id" id='location-select-id' size="1">
						<?php
						if ( count($location_types) == 1 ){ // we don't consider optional locations as a type for ddm
							?>
							<option value="0"><?php esc_html_e('No Location','events'); ?></option>
							<?php
						}elseif( empty(get_option('dbem_default_location')) ){
							?>
							<option value="0"><?php esc_html_e('Select Location','events'); ?></option>
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

	</table>
	<div id="em-location-reset">						
		<p class="description"><?php esc_html_e('Reset this form to create a location or search again.', 'events'); ?></p>
		<a class="components-button is-primary" href="#"><?php esc_html_e('Reset', 'events')?></a>
	</div>
</div>

<?php endif; ?>
