<?php
global $EM_Location, $post;
$required = apply_filters('em_required_html','<i>*</i>');
?>

<div id="em-location-data" class="em-location-data">
	<table class="em-location-data form-table">
		<tr class="em-location-data-address">
			<th><?php _e ( 'Address:', 'events-manager')?>&nbsp;</th>
			<td>
				<input id="location-address" type="text" name="location_address" value="<?php echo esc_attr($EM_Location->location_address, ENT_QUOTES); ; ?>" /> <?php echo $required; ?>
			</td>
		</tr>
		<tr class="em-location-data-town">
			<th><?php _e ( 'City/Town:', 'events-manager')?>&nbsp;</th>
			<td>
				<input id="location-town" type="text" name="location_town" value="<?php echo esc_attr($EM_Location->location_town, ENT_QUOTES); ?>" /> <?php echo $required; ?>
			</td>
		</tr>
		<tr class="em-location-data-state">
			<th><?php _e ( 'State/County:', 'events-manager')?>&nbsp;</th>
			<td>
				<input id="location-state" type="text" name="location_state" value="<?php echo esc_attr($EM_Location->location_state, ENT_QUOTES); ?>" />
			</td>
		</tr>
		<tr class="em-location-data-postcode">
			<th><?php _e ( 'Postcode:', 'events-manager')?>&nbsp;</th>
			<td>
				<input id="location-postcode" type="text" name="location_postcode" value="<?php echo esc_attr($EM_Location->location_postcode, ENT_QUOTES); ?>" />
			</td>
		</tr>
		<tr class="em-location-data-region">
			<th><?php _e ( 'Region:', 'events-manager')?>&nbsp;</th>
			<td>
				<input id="location-region" type="text" name="location_region" value="<?php echo esc_attr($EM_Location->location_region, ENT_QUOTES); ?>" />
				<input id="location-region-wpnonce" type="hidden" value="<?php echo wp_create_nonce('search_regions'); ?>" />
			</td>
		</tr>
		<tr class="em-location-data-country">
			<th><?php _e ( 'Country:', 'events-manager')?>&nbsp;</th>
			<td>
				<select id="location-country" name="location_country">
					<?php foreach(\Contexis\Events\Intl\Countries::get(__('none selected','events-manager')) as $country_key => $country_name): ?>
					<option value="<?php echo $country_key; ?>" <?php echo ( $EM_Location->location_country === $country_key || ($EM_Location->location_country == '' && $EM_Location->location_id == '' && get_option('dbem_location_default_country')==$country_key) ) ? 'selected="selected"':''; ?>><?php echo $country_name; ?></option>
					<?php endforeach; ?>
				</select> <?php echo $required; ?>
			</td>
		</tr>
		<tr class="em-location-data-url">
			<th><?php _e ( 'URL:', 'events-manager')?>&nbsp;</th>
			<td>
				<input id="location-url" type="text" name="location_url" value="<?php echo esc_attr($EM_Location->location_url, ENT_QUOTES); ?>" />
			</td>
		</tr>
	</table>
</div>