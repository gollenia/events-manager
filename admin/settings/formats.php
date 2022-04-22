<?php if( !function_exists('current_user_can') || !current_user_can('manage_options') ) return; ?>
<!-- FORMAT OPTIONS -->
<div class="em-menu-formats em-menu-group"  <?php if( !defined('EM_SETTINGS_TABS') || !EM_SETTINGS_TABS) : ?>style="display:none;"<?php endif; ?>>				
	<div  class="postbox " id="em-opt-events-formats" >
	<div class="handlediv" title="<?php __('Click to toggle', 'events-manager'); ?>"><br /></div><h3><span><?php _e ( 'Events', 'events-manager'); ?> </span></h3>
	<div class="inside">
    	<table class="form-table">
		 	
			<?php
			$grouby_modes = array(0=>__('None','events-manager'), 'yearly'=>__('Yearly','events-manager'), 'monthly'=>__('Monthly','events-manager'), 'weekly'=>__('Weekly','events-manager'), 'daily'=>__('Daily','events-manager'));
			
			em_options_input_text ( __( 'No events message', 'events-manager'), 'dbem_no_events_message', __( 'The message displayed when no events are available.', 'events-manager') );
			?>
		 	
		</table>
	</div> <!-- . inside -->
	</div> <!-- .postbox -->

	<div  class="postbox " id="em-opt-date-time" >
	<div class="handlediv" title="<?php __('Click to toggle', 'events-manager'); ?>"><br /></div><h3><span><?php _e ( 'Date/Time', 'events-manager'); ?> </span></h3>
	<div class="inside">
		<p class="em-boxheader"><?php
			$date_time_format_tip = sprintf(__('Date and Time formats follow the <a href="%s">WordPress time formatting conventions</a>', 'events-manager'), 'http://codex.wordpress.org/Formatting_Date_and_Time');
			echo $date_time_format_tip; 
		?></p>
		<table class="form-table">
    		<?php
			em_options_input_text ( __( 'Date Range', 'events-manager'), 'dbem_date_range', sprintf(__( 'How should date ranges be formatted?.', 'events-manager'), __('dates','events-manager')) );
			em_options_input_text ( __( 'All Day Message', 'events-manager'), 'dbem_event_all_day_message', sprintf(__( 'If an event lasts all day, this text will show if using the %s placeholder', 'events-manager'), '<code>#_EVENTTIMES</code>') );
			em_options_radio_binary ( __( 'Use 24h Format?', 'events-manager'), 'dbem_time_24h', __( 'When creating events, would you like your times to be shown in 24 hour format?', 'events-manager') );
			echo $save_button;
			?>
		</table>
	</div> <!-- . inside -->
	</div> <!-- .postbox -->
	      
   	<div  class="postbox " id="em-opt-calendar-formats" >
	<div class="handlediv" title="<?php __('Click to toggle', 'events-manager'); ?>"><br /></div><h3><span><?php _e ( 'Calendar', 'events-manager'); ?></span></h3>
	<div class="inside">
    	<table class="form-table">
		   
		    <tr class="em-header"><td colspan="2"><h4><?php echo sprintf(__('iCal Feed Settings','events-manager'),__('Event','events-manager')); ?></h4></td></tr>
		    <?php 
			em_options_input_text ( __( 'iCal Title', 'events-manager'), 'dbem_ical_description_format', __( 'The title that will appear in the calendar.', 'events-manager').$events_placeholder_tip );
			em_options_input_text ( __( 'iCal Description', 'events-manager'), 'dbem_ical_real_description_format', __( 'The description of the event that will appear in the calendar.', 'events-manager').$events_placeholder_tip );
			em_options_input_text ( __( 'iCal Location', 'events-manager'), 'dbem_ical_location_format', __( 'The location information that will appear in the calendar.', 'events-manager').$events_placeholder_tip );
			em_options_select( __('iCal Scope','events-manager'), 'dbem_ical_scope', em_get_scopes(), __('Choose to show events within a specific time range.','events-manager'));
			em_options_input_text ( __( 'iCal Limit', 'events-manager'), 'dbem_ical_limit', __( 'Limits the number of future events shown (0 = unlimited).', 'events-manager') );						
		    echo $save_button;        
			?>
		</table>
	</div> <!-- . inside -->
	</div> <!-- .postbox -->
	

	<div  class="postbox " id="em-opt-rss-formats" >
	<div class="handlediv" title="<?php __('Click to toggle', 'events-manager'); ?>"><br /></div><h3><span><?php _e ( 'RSS', 'events-manager'); ?> </span></h3>
	<div class="inside">
    	<table class="form-table">
			<?php				
			em_options_input_text ( __( 'RSS main title', 'events-manager'), 'dbem_rss_main_title', __( 'The main title of your RSS events feed.', 'events-manager').$events_placeholder_tip );
			em_options_input_text ( __( 'RSS main description', 'events-manager'), 'dbem_rss_main_description', __( 'The main description of your RSS events feed.', 'events-manager') );
			em_options_input_text ( __( 'RSS title format', 'events-manager'), 'dbem_rss_title_format', __( 'The format of the title of each item in the events RSS feed.', 'events-manager').$events_placeholder_tip );
			em_options_input_text ( __( 'RSS description format', 'events-manager'), 'dbem_rss_description_format', __( 'The format of the description of each item in the events RSS feed.', 'events-manager').$events_placeholder_tip );
			em_options_input_text ( __( 'RSS limit', 'events-manager'), 'dbem_rss_limit', __( 'Limits the number of future events shown (0 = unlimited).', 'events-manager') );
			em_options_select( __('RSS Scope','events-manager'), 'dbem_rss_scope', em_get_scopes(), __('Choose to show events within a specific time range.','events-manager'));
			?>							
			<tr valign="top" id='dbem_rss_orderby_row'>
		   		<th scope="row"><?php _e('Default event list ordering','events-manager'); ?></th>
		   		<td>   
					<select name="dbem_rss_orderby" >
						<?php 
							$orderby_options = apply_filters('em_settings_events_default_orderby_ddm', array(
								'event_start_date,event_start_time,event_name' => __('Order by start date, start time, then event name','events-manager'),
								'event_name,event_start_date,event_start_time' => __('Order by name, start date, then start time','events-manager'),
								'event_name,event_end_date,event_end_time' => __('Order by name, end date, then end time','events-manager'),
								'event_end_date,event_end_time,event_name' => __('Order by end date, end time, then event name','events-manager'),
							)); 
						?>
						<?php foreach($orderby_options as $key => $value) : ?>   
		 				<option value='<?php echo esc_attr($key) ?>' <?php echo ($key == get_option('dbem_rss_orderby')) ? "selected='selected'" : ''; ?>>
		 					<?php echo esc_html($value); ?>
		 				</option>
						<?php endforeach; ?>
					</select> 
					<select name="dbem_rss_order" >
						<?php 
						$ascending = __('Ascending','events-manager');
						$descending = __('Descending','events-manager');
						$order_options = apply_filters('em_settings_events_default_order_ddm', array(
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
						<?php foreach( $order_options as $key => $value) : ?>   
		 				<option value='<?php echo esc_attr($key) ?>' <?php echo ($key == get_option('dbem_rss_order')) ? "selected='selected'" : ''; ?>>
		 					<?php echo esc_html($value); ?>
		 				</option>
						<?php endforeach; ?>
					</select>
					<br/>
					<em><?php _e('When Events Manager displays lists of events the default behavior is ordering by start date in ascending order. To change this, modify the values above.','events-manager'); ?></em>
				</td>
		   	</tr>
			<?php
			echo $save_button;
			?>
		</table>
	</div> <!-- . inside -->
	</div> <!-- .postbox -->

	
	<?php do_action('em_options_page_footer_formats'); ?>
	
</div> <!-- .em-menu-formats -->