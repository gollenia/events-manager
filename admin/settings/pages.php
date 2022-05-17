<?php if( !function_exists('current_user_can') || !current_user_can('manage_options') ) return; ?>
<!-- PAGE OPTIONS -->
<div class="em-menu-pages em-menu-group"  <?php if( !defined('EM_SETTINGS_TABS') || !EM_SETTINGS_TABS) : ?>style="display:none;"<?php endif; ?>>			
    	<?php
    	$template_page_tip = __( "Many themes display extra meta information on post pages such as 'posted by' or 'post date' information, which may not be desired. Usually, page templates contain less clutter.", 'events-manager');
    	$template_page_tip .= ' '. __("If you choose 'Pages' then %s will be shown using your theme default page template, alternatively choose from page templates that come with your specific theme.",'events-manager');
    	$template_page_tip .= ' '. str_replace('#','http://codex.wordpress.org/Post_Types#Template_Files',__("Be aware that some themes will not work with this option, if so (or you want to make your own changes), you can create a file named <code>single-%s.php</code> <a href='#'>as shown on the WordPress codex</a>, and leave this set to Posts.", 'events-manager'));
    	$body_class_tip = __('If you would like to add extra classes to your body html tag when a single %s page is displayed, enter it here. May be useful or necessary if your theme requires special class names for specific templates.','events-manager');
    	$post_class_tip = __('Same concept as the body classes option, but some themes also use the <code>post_class()</code> function within page content to differentiate styling between post types.','events-manager');
    	$format_override_tip = __("By using formats, you can control how your %s are displayed from within the Events Manager <a href='#formats' class='nav-tab-link' rel='#em-menu-formats'>Formatting</a> tab above without having to edit your theme files.",'events-manager');
    	$page_templates = array(''=>__('Posts'), 'page' => __('Pages'), __('Theme Templates','events-manager') => array_flip(get_page_templates()));
    	?>
    
	
    		
		<div  class="postbox " id="em-opt-event-archives" >
		<div class="handlediv" title="<?php __('Click to toggle', 'events-manager'); ?>"><br /></div><h3><span><?php echo sprintf(__('%s List/Archives','events-manager'),__('Event','events-manager')); ?></span></h3>
		<div class="inside">
        	<table class="form-table">
			

			<?php
			em_options_radio_binary ( __( 'Are current events past events?', 'events-manager'), 'dbem_events_current_are_past', __( "By default, events that have an end date later than today will be included in searches, set this to yes to consider events that started 'yesterday' as past.", 'events-manager') );
			em_options_radio_binary ( __( 'Include in WordPress Searches?', 'events-manager'), 'dbem_cp_events_search_results', sprintf(__( "Allow %s to appear in the built-in search results.", 'events-manager'),__('events','events-manager')) );
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
			<?php
			echo $save_button;
        	?>
        	</table>
		</div> <!-- . inside --> 
		</div> <!-- .postbox -->	
		
		
		
		<?php do_action('em_options_page_footer_pages'); ?>
		
	</div> <!-- .em-menu-pages -->