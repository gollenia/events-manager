<?php if( !function_exists('current_user_can') || !current_user_can('manage_options') ) return; ?>
<!-- GENERAL OPTIONS -->
<div class="em-menu-general em-menu-group">
	<div  class="postbox " id="em-opt-general"  >
	<div class="handlediv" title="<?php __('Click to toggle', 'events-manager'); ?>"><br /></div> <h3><span><?php _e ( 'General Options', 'events-manager'); ?> </span></h3>
	<div class="inside">
        <table class="form-table">
            <?php 
			em_options_radio_binary ( __( 'Disable thumbnails?', 'events-manager'), 'dbem_thumbnails_enabled', __( 'Select yes to disable Events Manager from enabling thumbnails (some themes may already have this enabled, which we cannot be turned off here).','events-manager') );
			em_options_radio_binary ( __( 'Enable recurrence?', 'events-manager'), 'dbem_recurrence_enabled', __( 'Select yes to enable the recurrence features feature','events-manager') ); 
			em_options_radio_binary ( __( 'Enable bookings?', 'events-manager'), 'dbem_rsvp_enabled', __( 'Select yes to allow bookings and tickets for events.','events-manager') );     
			em_options_radio_binary ( __( 'Enable locations?', 'events-manager'), 'dbem_locations_enabled', __( 'If you disable locations, bear in mind that you should remove your location page, shortcodes and related placeholders from your <a href="#formats" class="nav-tab-link" rel="#em-menu-formats">formats</a>.','events-manager'), '', '.em-location-type-option' );

		    

				if( get_option('dbem_locations_enabled') ){
					
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

		</table>
		<table class="form-table">
			
			<?php
			
			echo $save_button;
			?>
		</table>
		    
	</div> <!-- . inside -->
	</div> <!-- .postbox -->
	


	<?php do_action('em_options_page_footer'); ?>
	

	<?php em_admin_option_box_data_privacy(); ?>
	
	
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