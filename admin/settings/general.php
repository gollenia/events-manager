<?php 
use \Contexis\Events\Options;
?>

<?php if( !function_exists('current_user_can') || !current_user_can('manage_options') ) return; ?>



<!-- GENERAL OPTIONS -->

<div class="em-menu-general em-menu-group">
	 
        <table class="form-table">
            <?php 
			Options::checkbox( __( 'Recurring Events', 'events-manager'), 'dbem_recurrence_enabled', __( 'Events can be managed as a collection of recurring events','events-manager') ); 
			Options::checkbox( __( 'Bookings', 'events-manager'), 'dbem_rsvp_enabled', __( 'Allow bookings and tickets for events.','events-manager') );     
			Options::checkbox( __( 'Locations', 'events-manager'), 'dbem_locations_enabled', __( 'Activate possibility to add locations to events','events-manager'), '', '.em-location-type-option' );
			Options::checkbox( __( 'Current Events', 'events-manager'), 'dbem_events_current_are_past', __( "Currently running events should be treated as already passed", 'events-manager') );
			Options::checkbox( __( 'WordPress Search', 'events-manager'), 'dbem_cp_events_search_results', sprintf(__( "Allow %s to appear in the built-in search results.", 'events-manager'),__('events','events-manager')) );
			Options::input( __( 'Permalink', 'events-manager'), 'dbem_cp_events_slug', sprintf(__('e.g. %s - you can use / Separators too', 'events-manager'), '<strong>'.home_url().'/<code>'.esc_html(get_option('dbem_cp_events_slug',EM_POST_TYPE_EVENT_SLUG)).'</code>/summercamp/</strong>'), ['default' => EM_POST_TYPE_EVENT_SLUG, 'class' => 'regular-text code'] ) ;
		?>
		</table>
		<h2><?php _e("Locations", "events-manager") ?></h2>
		<table class="form-table">
		<?php
			if( get_option('dbem_locations_enabled') ){
					
				$location_options = array();
				$location_options[0] = __('no default location','events-manager');
				$EM_Locations = EM_Locations::get([]);
				
				foreach($EM_Locations as $EM_Location){
					$location_options[$EM_Location->location_id] = $EM_Location->location_name;
				}

				Options::select( __( 'Default Location', 'events-manager'), 'dbem_default_location', $location_options, __( 'This option allows you to select the default location when adding an event.','events-manager') );
				Options::select( __( 'Default Location Country', 'events-manager'), 'dbem_location_default_country', \Contexis\Events\Intl\Countries::get(__('no default country', 'events-manager')), __('If you select a default country, that will be pre-selected when creating a new location.','events-manager') );
			}

			?>

			<tr valign="top" id='dbem_events_default_orderby_row'>
		   		<th scope="row"><?php _e('Default event list ordering','events-manager'); ?></th>
		   		<td>   
					<select name="dbem_events_default_orderby" >
						<?php 
							$event_list_orderby_options = apply_filters('em_settings_events_default_orderby_ddm', array(
								'event_start_date,event_start_time,event_name' => __('Order by start date, start time, then event name','events-manager'),
								'event_name,event_start_date,event_start_time' => __('Order by name, start date, then start time','events-manager'),
								'event_name,event_end_date,event_end_time' => __('Order by name, end date, then end time','events-manager'),
								'event_end_date,event_end_time,event_name' => __('Order by end date, end time, then event name','events-manager'),
							)); 
						?>
						<?php foreach($event_list_orderby_options as $key => $value) : ?>   
		 				<option value='<?php echo esc_attr($key) ?>' <?php echo ($key == get_option('dbem_events_default_orderby')) ? "selected='selected'" : ''; ?>>
		 					<?php echo esc_html($value); ?>
		 				</option>
						<?php endforeach; ?>
					</select> 
					<select name="dbem_events_default_order" >
						<?php 
						$ascending = __('Ascending','events-manager');
						$descending = __('Descending','events-manager');
						$event_list_order_options = apply_filters('em_settings_events_default_order_ddm', array(
							'ASC' => __('All Ascending','events-manager'),
							'DESC,ASC,ASC' => __("$descending, $ascending, $ascending",'events-manager'),
							'DESC,DESC,ASC' => __("$descending, $descending, $ascending",'events-manager'),
							'DESC' => __('All Descending','events-manager'),
							'ASC,DESC,ASC' => __("$ascending, $descending, $ascending",'events-manager'),
							'ASC,DESC,DESC' => __("$ascending, $descending, $descending",'events-manager'),
							'ASC,ASC,DESC' => __("$ascending, $ascending, $descending",'events-manager'),
							'DESC,ASC,DESC' => __("$descending, $ascending, $descending",'events-manager'),
						)); 
						?>
						<?php foreach( $event_list_order_options as $key => $value) : ?>   
		 				<option value='<?php echo esc_attr($key) ?>' <?php echo ($key == get_option('dbem_events_default_order')) ? "selected='selected'" : ''; ?>>
		 					<?php echo esc_html($value); ?>
		 				</option>
						<?php endforeach; ?>
					</select>
					<br/>
					<em><?php _e('When Events Manager displays lists of events the default behavior is ordering by start date in ascending order. To change this, modify the values above.','events-manager'); ?></em>
				</td>
		   	</tr>
		</table>

		    
	
	


	<?php do_action('em_options_page_footer'); ?>

	
	
	
</div> <!-- .em-menu-general -->