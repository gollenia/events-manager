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
    	<div  class="postbox" id="em-opt-permalinks" >
		<div class="handlediv" title="<?php __('Click to toggle', 'events-manager'); ?>"><br /></div><h3><span><?php echo sprintf(__('Permalink Slugs','events-manager')); ?></span></h3>
		<div class="inside">
			<p class="em-boxheader"><?php _e('You can change the permalink structure of your events, locations, categories and tags here. Be aware that you may want to set up redirects if you change your permalink structures to maintain SEO rankings.','events-manager'); ?></p>
        	<table class="form-table">
        	<?php
        	em_options_input_text ( __( 'Events', 'events-manager'), 'dbem_cp_events_slug', sprintf(__('e.g. %s - you can use / Separators too', 'events-manager'), '<strong>'.home_url().'/<code>'.esc_html(get_option('dbem_cp_events_slug',EM_POST_TYPE_EVENT_SLUG)).'</code>/2012-olympics/</strong>'), EM_POST_TYPE_EVENT_SLUG );
			if( get_option('dbem_locations_enabled')){
            	em_options_input_text ( __( 'Locations', 'events-manager'), 'dbem_cp_locations_slug', sprintf(__('e.g. %s - you can use / Separators too', 'events-manager'), '<strong>'.home_url().'/<code>'.esc_html(get_option('dbem_cp_locations_slug',EM_POST_TYPE_LOCATION_SLUG)).'</code>/wembley-stadium/</strong>'), EM_POST_TYPE_LOCATION_SLUG );
			}
        	
			em_options_input_text ( __( 'Event Categories', 'events-manager'), 'dbem_taxonomy_category_slug', sprintf(__('e.g. %s - you can use / Separators too', 'events-manager'), '<strong>'.home_url().'/<code>'.esc_html(get_option('dbem_taxonomy_category_slug',EM_TAXONOMY_CATEGORY_SLUG)).'</code>/sports/</strong>'), EM_TAXONOMY_CATEGORY_SLUG );
        	
        	
			em_options_input_text ( __( 'Event Tags', 'events-manager'), 'dbem_taxonomy_tag_slug', sprintf(__('e.g. %s - you can use / Separators too', 'events-manager'), '<strong>'.home_url().'/<code>'.esc_html(get_option('dbem_taxonomy_tag_slug',EM_TAXONOMY_TAG_SLUG)).'</code>/running/</strong>'), EM_TAXONOMY_TAG_SLUG );
        	
        	echo $save_button;
        	?>
        	</table>
		</div> <!-- . inside --> 
		</div> <!-- .postbox -->	
	
    		
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
		
		<?php if( get_option('dbem_locations_enabled') ): ?>
		<div  class="postbox " id="em-opt-location-pages" >
		<div class="handlediv" title="<?php __('Click to toggle', 'events-manager'); ?>"><br /></div><h3><span><?php echo sprintf(__('%s Pages','events-manager'),__('Location','events-manager')); ?></span></h3>
		<div class="inside">
        	<table class="form-table">
        	<?php 
        	//em_options_radio_binary ( sprintf(__( 'Display %s as', 'events-manager'),__('locations','events-manager')), 'dbem_cp_locations_template_page', sprintf($template_page_tip, EM_POST_TYPE_LOCATION), array(__('Posts'),__('Pages')) );
        	em_options_select( sprintf(__( 'Display %s as', 'events-manager'),__('locations','events-manager')), 'dbem_cp_locations_template', $page_templates, sprintf($template_page_tip, __('locations','events-manager'), EM_POST_TYPE_LOCATION) );
        	em_options_radio_binary ( __( 'Override with Formats?', 'events-manager'), 'dbem_cp_locations_formats', sprintf($format_override_tip,__('locations','events-manager')));
        	em_options_radio_binary ( __( 'Enable Comments?', 'events-manager'), 'dbem_cp_locations_comments', sprintf(__('If you would like to disable comments entirely, disable this, otherwise you can disable comments on each single %s. Note that %s with comments enabled will still be until you resave them.','events-manager'),__('location','events-manager'),__('locations','events-manager')));
        	
        	echo $save_button;
			?>
        	</table>
		</div> <!-- . inside --> 
		</div> <!-- .postbox -->	
		
		<div  class="postbox " id="em-opt-location-archives" >
		<div class="handlediv" title="<?php __('Click to toggle', 'events-manager'); ?>"><br /></div><h3><span><?php echo sprintf(__('%s List/Archives','events-manager'),__('Location','events-manager')); ?></span></h3>
		<div class="inside">
        	<table class="form-table">
			<tr>
				<th><?php echo sprintf(__( '%s page', 'events-manager'),__('Locations','events-manager')); ?></th>
				<td>
					<?php wp_dropdown_pages(array('name'=>'dbem_locations_page', 'selected'=>get_option('dbem_locations_page'), 'show_option_none'=>sprintf(__('[No %s Page]', 'events-manager'),__('Locations','events-manager')) )); ?>
					<br />
					<em><?php echo sprintf(__( 'This option allows you to select which page to use as the %s page. If you do not select a %s page, to display lists you can enable archives or use the appropriate shortcodes and/or template tags.','events-manager'),__('locations','events-manager'),__('locations','events-manager')); ?></em>
				</td>
			</tr>
			
			<tr class="em-header">
				<td colspan="2">
					<h4><?php echo sprintf(__('WordPress %s Archives','events-manager'), __('Location','events-manager')); ?></h4>
					<p><?php echo sprintf(__('%s custom post types can have archives, just like normal WordPress posts. If enabled, should you visit your base slug url %s and you will see an post-formatted archive of previous %s', 'events-manager'), __('Location','events-manager'), '<code>'.home_url().'/'.esc_html(get_option('dbem_cp_locations_slug',EM_POST_TYPE_LOCATION_SLUG)).'/</code>', __('locations','events-manager')); ?></p>
					<p><?php echo sprintf(__('Note that assigning a %s page above will override this archive if the URLs collide (which is the default settings, and is recommended for maximum plugin compatibility). You can have both at the same time, but you must ensure that your page and %s slugs are different.','events-manager'), __('locations','events-manager'), __('location','events-manager')); ?></p>
				</td>
			</tr>
			<tbody class="em-location-archive-options">
				<?php
				em_options_radio_binary ( __( 'Enable Archives?', 'events-manager'), 'dbem_cp_locations_has_archive', __( "Allow WordPress post-style archives.", 'events-manager') );						
				?>
			</tbody>
			<tbody class="em-location-archive-options em-location-archive-sub-options">
				<tr valign="top">
			   		<th scope="row"><?php _e('Default archive ordering','events-manager'); ?></th>
			   		<td>   
						<select name="dbem_locations_default_archive_orderby" >
							<?php 
								$locations_list_orderby_options = apply_filters('em_settings_locations_default_archive_orderby_ddm', array(
									'_location_country' => sprintf(__('Order by %s','events-manager'),__('Country','events-manager')),
									'_location_town' => sprintf(__('Order by %s','events-manager'),__('Town','events-manager')),
									'title' => sprintf(__('Order by %s','events-manager'),__('Name','events-manager'))
								)); 
							?>
							<?php foreach($locations_list_orderby_options as $key => $value) : ?>   
			 				<option value='<?php echo esc_attr($key) ?>' <?php echo ($key == get_option('dbem_locations_default_archive_orderby')) ? "selected='selected'" : ''; ?>>
			 					<?php echo esc_html($value) ?>
			 				</option>
							<?php endforeach; ?>
						</select> 
						<select name="dbem_locations_default_archive_order" >
							<?php 
							$ascending = __('Ascending','events-manager');
							$descending = __('Descending','events-manager');
							$locations_list_order_options = apply_filters('em_settings_locations_default_archive_order_ddm', array(
								'ASC' => __('Ascending','events-manager'),
								'DESC' => __('Descending','events-manager')
							)); 
							?>
							<?php foreach( $locations_list_order_options as $key => $value) : ?>   
			 				<option value='<?php echo esc_attr($key) ?>' <?php echo ($key == get_option('dbem_locations_default_archive_order')) ? "selected='selected'" : ''; ?>>
			 					<?php echo esc_html($value) ?>
			 				</option>
							<?php endforeach; ?>
						</select>
					</td>
			   	</tr>	
			</tbody>
			<tr class="em-header">
				<td colspan="2">
					<h4><?php echo _e('General settings','events-manager'); ?></h4>
				</td>
			</tr>
			<?php 
			em_options_radio_binary ( __( 'Override with Formats?', 'events-manager'), 'dbem_cp_locations_archive_formats', sprintf($format_override_tip,__('locations','events-manager')));
			em_options_radio_binary ( __( 'Override Excerpts with Formats?', 'events-manager'), 'dbem_cp_locations_excerpt_formats', sprintf($format_override_tip,__('locations','events-manager')));
        	em_options_radio_binary ( __( 'Include in WordPress Searches?', 'events-manager'), 'dbem_cp_locations_search_results', sprintf(__( "Allow %s to appear in the built-in search results.", 'events-manager'),__('locations','events-manager')) );
			?>
			<tr class="em-header">
				<td colspan="2">
					<h4><?php echo sprintf(__('Default %s list options','events-manager'), __('location','events-manager')); ?></h4>
					<p><?php _e('These can be overridden when using shortcode or template tags.','events-manager'); ?></p>
				</td>
			</tr>							
			
			<?php
			em_options_input_text ( __( 'List Limits', 'events-manager'), 'dbem_locations_default_limit', sprintf(__( "This will control how many %s are shown on one list by default.", 'events-manager'),__('locations','events-manager')) );
        	echo $save_button;
			?>
        	</table>
		</div> <!-- . inside --> 
		</div> <!-- .postbox -->
		<?php endif; ?>
		
		
		<?php do_action('em_options_page_footer_pages'); ?>
		
	</div> <!-- .em-menu-pages -->