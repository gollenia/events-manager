<?php if( !function_exists('current_user_can') || !current_user_can('manage_options') ) return; ?>
<!-- GENERAL OPTIONS -->
<div class="em-menu-general em-menu-group">
	
        <table class="form-table">
            <?php 
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

		    
	
	


	<?php do_action('em_options_page_footer'); ?>
	

	<?php 
	//em_admin_option_box_data_privacy(); 
	?>
	
	
	
</div> <!-- .em-menu-general -->