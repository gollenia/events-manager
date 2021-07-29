<?php if( !function_exists('current_user_can') || !current_user_can('manage_options') ) return; ?>
<!-- GENERAL OPTIONS -->
<div class="em-menu-general em-menu-group">
	<div  class="postbox " id="em-opt-general"  >
	<div class="handlediv" title="<?php __('Click to toggle', 'events-manager'); ?>"><br /></div> <h3><span><?php _e ( 'General Options', 'events-manager'); ?> </span></h3>
	<div class="inside">
        <table class="form-table">
            <?php em_options_radio_binary ( __( 'Disable thumbnails?', 'events-manager'), 'dbem_thumbnails_enabled', __( 'Select yes to disable Events Manager from enabling thumbnails (some themes may already have this enabled, which we cannot be turned off here).','events-manager') );  ?>					
			<tr class="em-header">
				<td colspan="2">
					<h4><?php echo sprintf(__('%s Settings','events-manager'),__('Event','events-manager')); ?></h4>
				</td>
			</tr>
			<?php
			em_options_radio_binary ( __( 'Enable Timezone Support?', 'events-manager'), 'dbem_timezone_enabled', sprintf(__( 'Each event can have its own timezone if enabled. If set to no, then all newly created events will have the blog timezone, which currently is set to %s','events-manager'), '<code>'.EM_DateTimeZone::create()->getName().'</code>'), '', '.event-timezone-option' );
			?>
			<tr class="event-timezone-option">
				<th>
					<label for="event-timezone"><?php esc_html_e('Default Timezone', 'events-manager'); ?></label>
				</th>
				<td>
					<select id="event-timezone" name="dbem_timezone_default">
						<?php echo wp_timezone_choice( get_option('dbem_timezone_default') ); ?>
					</select><br />
					<i><?php esc_html_e('When creating a new event, this timezone will be applied by default.','events-manager'); ?></i>
				</td>
			</tr>
			<?php
			em_options_radio_binary ( __( 'Enable recurrence?', 'events-manager'), 'dbem_recurrence_enabled', __( 'Select yes to enable the recurrence features feature','events-manager') ); 
			em_options_radio_binary ( __( 'Enable bookings?', 'events-manager'), 'dbem_rsvp_enabled', __( 'Select yes to allow bookings and tickets for events.','events-manager') );     
			em_options_radio_binary ( __( 'Enable tags?', 'events-manager'), 'dbem_tags_enabled', __( 'Select yes to enable the tag features','events-manager') );
			if( !(EM_MS_GLOBAL && !is_main_site()) ){
				em_options_radio_binary ( __( 'Enable categories?', 'events-manager'), 'dbem_categories_enabled', __( 'Select yes to enable the category features','events-manager') );     
				if( get_option('dbem_categories_enabled') ){
					/*default category*/
					$category_options = array();
					$category_options[0] = __('no default category','events-manager');
					$EM_Categories = EM_Categories::get();
					foreach($EM_Categories as $EM_Category){
				 		$category_options[$EM_Category->id] = $EM_Category->name;
				 	}
				 	echo "<tr><th>".__( 'Default Category', 'events-manager')."</th><td>";
					wp_dropdown_categories(array( 'hide_empty' => 0, 'name' => 'dbem_default_category', 'hierarchical' => true, 'taxonomy' => EM_TAXONOMY_CATEGORY, 'selected' => get_option('dbem_default_category'), 'show_option_none' => __('None','events-manager'), 'class'=>''));
					echo "</br><em>" .__( 'This option allows you to select the default category when adding an event.','events-manager').' '.__('If an event does not have a category assigned when editing, this one will be assigned automatically.','events-manager')."</em>";
					echo "</td></tr>";
				}
			}
			em_options_radio_binary ( sprintf(__( 'Enable %s attributes?', 'events-manager'),__('event','events-manager')), 'dbem_attributes_enabled', __( 'Select yes to enable the attributes feature','events-manager') );
			em_options_radio_binary ( sprintf(__( 'Enable %s custom fields?', 'events-manager'),__('event','events-manager')), 'dbem_cp_events_custom_fields', __( 'Custom fields are the same as attributes, except you cannot restrict specific values, users can add any kind of custom field name/value pair. Only available in the WordPress admin area.','events-manager') );
			if( get_option('dbem_attributes_enabled') ){
				em_options_textarea ( sprintf(__( '%s Attributes', 'events-manager'),__('Event','events-manager')), 'dbem_placeholders_custom', sprintf(__( "You can also add event attributes here, one per line in this format <code>#_ATT{key}</code>. They will not appear on event pages unless you insert them into another template below, but you may want to store extra information about an event for other uses. <a href='%s'>More information on placeholders.</a>", 'events-manager'), EM_ADMIN_URL .'&amp;page=events-manager-help') );
			}
			?>
			<tr class="em-header">
				<td colspan="2">
					<h4><?php echo sprintf(__('%s Settings','events-manager'),__('Location','events-manager')); ?></h4>
				</td>
			</tr>
			<?php
			em_options_radio_binary ( __( 'Enable locations?', 'events-manager'), 'dbem_locations_enabled', __( 'If you disable locations, bear in mind that you should remove your location page, shortcodes and related placeholders from your <a href="#formats" class="nav-tab-link" rel="#em-menu-formats">formats</a>.','events-manager'), '', '.em-location-type-option' );
			?>
	        <tbody class="em-location-type-option">
		        <?php
		        em_options_radio_binary ( __( 'Require locations for events?', 'events-manager'), 'dbem_require_location', __( 'Setting this to no will allow you to submit events without locations. You can use the <code>{no_location}...{/no_location}</code> or <code>{has_location}..{/has_location}</code> conditional placeholder to selectively display location information.','events-manager') );
		        ?>
		        <tr valign="top" id='dbem_location_types_row'>
			        <th scope="row"><?php esc_html_e('Location Types', 'events-manager'); ?></th>
			        <td>
				        <?php
				        $location_types = get_option('dbem_location_types', array());
				        ?>
				        <label>
				            <input type="checkbox" name="dbem_location_types[location]" value="1" <?php if( !empty($location_types['location']) ) echo 'checked'; ?> data-trigger=".em-location-type-option-physical" class="em-trigger">
					        <?php esc_html_e('Physicial Locations', 'events-manager'); ?>
				        </label>
				        <?php foreach (EM_Event_Locations\Event_Locations::get_types() as $event_location_type => $EM_Event_Location_Class): /* @var EM_Event_Locations\Event_Location $EM_Event_Location_Class */ ?>
					        <br>
					        <label>
						        <input type="checkbox" name="dbem_location_types[<?php echo esc_attr($event_location_type); ?>]" value="1" <?php if( !empty($location_types[$event_location_type]) ) echo 'checked'; ?> data-trigger=".em-location-type-option-<?php echo esc_attr($event_location_type); ?>" class="em-trigger">
						        <?php echo $EM_Event_Location_Class::get_label('plural'); ?>
					        </label>
				        <?php endforeach; ?>
				        <p><em><?php echo sprintf( esc_html__('You can allow different location types which can be assigned to an event. For more information see our %s.', 'events-manager'), '<a href="http://wp-events-plugin.com/documentation/location-types/" target="_blank">'.esc_html__('documentation', 'events-manager').'</a>'); ?></em></p>
			        </td>
		        </tr>
	        </tbody>
        </table>
		<table class="form-table em-location-type-option">
	        <tbody class="em-location-type-option-physical">
		        <tr class="em-subheader">
			        <td colspan="2">
				        <h5><?php esc_html_e('Physicial Locations', 'events-manager'); ?></h5>
			        </td>
		        </tr>
		        <?php
				if( get_option('dbem_locations_enabled') ){
					em_options_radio_binary ( __( 'Use dropdown for locations?', 'events-manager'), 'dbem_use_select_for_locations', __( 'Select yes to select location from a drop-down menu; location selection will be faster, but you will lose the ability to insert locations with events','events-manager') );
					em_options_radio_binary ( sprintf(__( 'Enable %s attributes?', 'events-manager'),__('location','events-manager')), 'dbem_location_attributes_enabled', __( 'Select yes to enable the attributes feature','events-manager') );
					em_options_radio_binary ( sprintf(__( 'Enable %s custom fields?', 'events-manager'),__('location','events-manager')), 'dbem_cp_locations_custom_fields', __( 'Custom fields are the same as attributes, except you cannot restrict specific values, users can add any kind of custom field name/value pair. Only available in the WordPress admin area.','events-manager') );
					if( get_option('dbem_location_attributes_enabled') ){
						em_options_textarea ( sprintf(__( '%s Attributes', 'events-manager'),__('Location','events-manager')), 'dbem_location_placeholders_custom', sprintf(__( "You can also add location attributes here, one per line in this format <code>#_LATT{key}</code>. They will not appear on location pages unless you insert them into another template below, but you may want to store extra information about an event for other uses. <a href='%s'>More information on placeholders.</a>", 'events-manager'), EM_ADMIN_URL .'&amp;page=events-manager-help') );
					}
					/*default location*/
					if( defined('EM_OPTIMIZE_SETTINGS_PAGE_LOCATIONS') && EM_OPTIMIZE_SETTINGS_PAGE_LOCATIONS ){
						em_options_input_text( __( 'Default Location', 'events-manager'), 'dbem_default_location', __('Please enter your Location ID, or leave blank for no location.','events-manager').' '.__( 'This option allows you to select the default location when adding an event.','events-manager')." ".__('(not applicable with event ownership on presently, coming soon!)','events-manager') );
					}else{
						$location_options = array();
						$location_options[0] = __('no default location','events-manager');
						$EM_Locations = EM_Locations::get();
						foreach($EM_Locations as $EM_Location){
							$location_options[$EM_Location->location_id] = $EM_Location->location_name;
						}
						em_options_select ( __( 'Default Location', 'events-manager'), 'dbem_default_location', $location_options, __('Please enter your Location ID.','events-manager').' '.__( 'This option allows you to select the default location when adding an event.','events-manager')." ".__('(not applicable with event ownership on presently, coming soon!)','events-manager') );
					}
					
					/*default location country*/
					em_options_select ( __( 'Default Location Country', 'events-manager'), 'dbem_location_default_country', em_get_countries(__('no default country', 'events-manager')), __('If you select a default country, that will be pre-selected when creating a new location.','events-manager') );
				}
				?>
	        </tbody>
		</table>
		<table class="form-table">
			<tr class="em-header">
				<td colspan="2">
					<h4><?php echo sprintf(__('%s Settings','events-manager'),__('Other','events-manager')); ?></h4>
				</td>
			</tr>
			<?php
			em_options_radio_binary ( __('Show some love?','events-manager'), 'dbem_credits', __( 'Hundreds of free hours have gone into making this free plugin, show your support and add a small link to the plugin website at the bottom of your event pages.','events-manager') );
			echo $save_button;
			?>
		</table>
		    
	</div> <!-- . inside -->
	</div> <!-- .postbox -->
	
	
	
	<?php if ( !is_multisite() || (em_wp_is_super_admin() && !get_site_option('dbem_ms_global_caps')) ){ em_admin_option_box_caps(); } ?>

	<?php do_action('em_options_page_footer'); ?>
	
	<?php /* 
	<div  class="postbox" id="em-opt-geo" >
	<div class="handlediv" title="<?php __('Click to toggle', 'events-manager'); ?>"><br /></div><h3><span><?php _e ( 'Geo APIs', 'events-manager'); ?> <em>(Beta)</em></span></h3>
	<div class="inside">
		<p><?php esc_html_e('Geocoding is the process of converting addresses into geographic coordinates, which can be used to find events and locations near a specific coordinate.','events-manager'); ?></p>
		<table class="form-table">
			<?php
				em_options_radio_binary ( __( 'Enable Geocoding Features?', 'events-manager'), 'dbem_geo', '', '', '.em-settings-geocoding');
			?>
		</table>
		<div class="em-settings-geocoding">
		<h4>GeoNames API (geonames.org)</h4>
		<p>We make use of the <a href="http://www.geonames.org">GeoNames</a> web service to suggest locations/addresses to users when searching, and converting these into coordinates.</p>
		<p>To be able to use these services, you must <a href="http://www.geonames.org/login">register an account</a>, activate the free webservice and enter your username below. You are allowed up to 30,000 requests per day, if you require more you can purchase credits from your account.</p>
        <table class="form-table">
			<?php em_options_input_text ( __( 'GeoNames Username', 'events-manager'), 'dbem_geonames_username', __('If left blank, this service will not be used.','events-manager')); ?>
		</table>
		</div>
		<table class="form-table"><?php echo $save_button; ?></table>
	</div> <!-- . inside --> 
	</div> <!-- .postbox -->
	*/ ?>

	<?php em_admin_option_box_data_privacy(); ?>
	<?php if ( !is_multisite() ) { em_admin_option_box_uninstall(); } ?>
	
	<?php if( get_option('dbem_migrate_images') ): ?>
	<div  class="postbox " >
	<div class="handlediv" title="<?php __('Click to toggle', 'events-manager'); ?>"><br /></div><h3><span>Migrate Images From Version 4</span></h3>
	<div class="inside">
		<?php /* Not translating as it's temporary */ //EM4 ?>
	   <p>You have the option of migrating images from version 4 so they become the equivalent of 'featured images' like with regular WordPress posts and pages and are also available in your media library.</p>
	   <p>Your event and location images will still display correctly on the front-end even if you don't migrate, but will not show up within your edit location/event pages in the admin area.</p>
	   <p>
	      <a href="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>&amp;em_migrate_images=1&amp;_wpnonce=<?php echo wp_create_nonce('em_migrate_images'); ?>">Migrate Images</a><br />
	      <a href="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>&amp;em_not_migrate_images=1&amp;_wpnonce=<?php echo wp_create_nonce('em_not_migrate_images'); ?>">Do Not Migrate Images</a>
	   </p>
	</div> <!-- . inside --> 
	</div> <!-- .postbox -->
	<?php endif; ?>
</div> <!-- .em-menu-general -->