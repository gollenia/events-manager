<?php

//Function composing the options subpanel
function em_options_save(){
	global $EM_Notices; /* @var EM_Notices $EM_Notices */
	/*
	 * Here's the idea, we have an array of all options that need super admin approval if in multi-site mode
	 * since options are only updated here, its one place fit all
	 */
	if( current_user_can('manage_options') && !empty($_POST['em-submitted']) && check_admin_referer('events-manager-options','_wpnonce') ){
		//Build the array of options here
		$post = $_POST;
		foreach ($_POST as $postKey => $postValue){
			if( $postKey != 'dbem_data' && substr($postKey, 0, 5) == 'dbem_' ){
				//TODO some more validation/reporting
				$numeric_options = array('dbem_locations_default_limit','dbem_events_default_limit');
				if( in_array($postKey, array('dbem_bookings_notify_admin','dbem_event_submitted_email_admin','dbem_js_limit_events_form','dbem_js_limit_search','dbem_js_limit_general','dbem_css_limit_include','dbem_css_limit_exclude','dbem_search_form_geo_distance_options')) ){ $postValue = str_replace(' ', '', $postValue); } //clean up comma separated emails, no spaces needed
				if( in_array($postKey,$numeric_options) && !is_numeric($postValue) ){
					//Do nothing, keep old setting.
				}elseif( ($postKey == 'dbem_category_default_color' || $postKey == 'dbem_tag_default_color') && !sanitize_hex_color($postValue) ){
					$EM_Notices->add_error( sprintf(esc_html_x('Colors must be in a valid %s format, such as #FF00EE.', 'hex format', 'events-manager'), '<a href="http://en.wikipedia.org/wiki/Web_colors">hex</a>').' '. esc_html__('This setting was not changed.', 'events-manager'), true);					
				}elseif( $postKey == 'dbem_oauth' && is_array($postValue) ){
					foreach($postValue as $postValue_key=>$postValue_val){
						EM_Options::set($postValue_key, wp_unslash($postValue_val), 'dbem_oauth');
					}
				}else{
					//TODO slashes being added?
					if( is_array($postValue) ){
					    foreach($postValue as $postValue_key=>$postValue_val) $postValue[$postValue_key] = wp_unslash($postValue_val);
					}else{
					    $postValue = wp_unslash($postValue);
					}
					update_option($postKey, $postValue);
				}
			}elseif( $postKey == 'dbem_data' && is_array($postValue) ){
				foreach( $postValue as $postK => $postV ){
					//TODO slashes being added?
					if( is_array($postV) ){
						foreach($postV as $postValue_key=>$postValue_val) $postV[$postValue_key] = wp_unslash($postValue_val);
					}else{
						$postV = wp_unslash($postV);
					}
					EM_Options::set( $postK, $postV );
				}
			}
		}
		//set capabilities
		if( !empty($_POST['em_capabilities']) && is_array($_POST['em_capabilities']) ){
			global $em_capabilities_array, $wp_roles;
			if( !is_network_admin() ){
			    //normal blog role application
				foreach( $wp_roles->role_objects as $role_name => $role ){
					foreach( array_keys($em_capabilities_array) as $capability){
						if( !empty($_POST['em_capabilities'][$role_name][$capability]) ){
							$role->add_cap($capability);
						}else{
							$role->remove_cap($capability);
						}
					}
				}
			}
		}
		update_option('dbem_flush_needed',1);
		do_action('em_options_save');
		$EM_Notices->add_confirm('<strong>'.__('Changes saved.', 'events-manager').'</strong>', true);
		$referrer = em_wp_get_referer();
		//add tab hash path to url if supplied
		if( !empty($_REQUEST['tab_path']) ){
			$referrer_array = explode('#', $referrer);
			$referrer = esc_url_raw($referrer_array[0] . '#' . $_REQUEST['tab_path']);
		}
		wp_safe_redirect($referrer);
		exit();
	}
	//Migration
	if( !empty($_GET['em_migrate_images']) && check_admin_referer('em_migrate_images','_wpnonce') && get_option('dbem_migrate_images') ){
		include(plugin_dir_path(__FILE__).'../em-install.php');
		$result = em_migrate_uploads();
		if($result){
			$failed = ( $result['fail'] > 0 ) ? $result['fail'] . ' images failed to migrate.' : '';
			$EM_Notices->add_confirm('<strong>'.$result['success'].' images migrated successfully. '.$failed.'</strong>');
		}
		wp_safe_redirect(admin_url().'edit.php?post_type=event&page=events-manager-options&em_migrate_images');
	}elseif( !empty($_GET['em_not_migrate_images']) && check_admin_referer('em_not_migrate_images','_wpnonce') ){
		delete_option('dbem_migrate_images_nag');
		delete_option('dbem_migrate_images');
	}
	//Uninstall
	if( !empty($_REQUEST['action']) && $_REQUEST['action'] == 'uninstall' && current_user_can('activate_plugins') && !empty($_REQUEST['confirmed']) && check_admin_referer('em_uninstall_'.get_current_user_id().'_wpnonce') && em_wp_is_super_admin() ){
		if( check_admin_referer('em_uninstall_'.get_current_user_id().'_confirmed','_wpnonce2') ){
			//We have a go to uninstall
			global $wpdb;
			//delete EM posts
			remove_action('before_delete_post',array('EM_Location_Post_Admin','before_delete_post'),10,1);
			remove_action('before_delete_post',array('EM_Event_Post_Admin','before_delete_post'),10,1);
			remove_action('before_delete_post',array('EM_Event_Recurring_Post_Admin','before_delete_post'),10,1);
			$post_ids = $wpdb->get_col('SELECT ID FROM '.$wpdb->posts." WHERE post_type IN ('".EM_POST_TYPE_EVENT."','".EM_POST_TYPE_LOCATION."','event-recurring')");
			foreach($post_ids as $post_id){
				wp_delete_post($post_id);
			}
			//delete categories
			$cat_terms = get_terms(EM_TAXONOMY_CATEGORY, array('hide_empty'=>false));
			foreach($cat_terms as $cat_term){
				wp_delete_term($cat_term->term_id, EM_TAXONOMY_CATEGORY);
			}
			$tag_terms = get_terms(EM_TAXONOMY_TAG, array('hide_empty'=>false));
			foreach($tag_terms as $tag_term){
				wp_delete_term($tag_term->term_id, EM_TAXONOMY_TAG);
			}
			//delete EM tables
			$wpdb->query('DROP TABLE '.EM_EVENTS_TABLE);
			$wpdb->query('DROP TABLE '.EM_BOOKINGS_TABLE);
			$wpdb->query('DROP TABLE '.EM_LOCATIONS_TABLE);
			$wpdb->query('DROP TABLE '.EM_TICKETS_TABLE);
			$wpdb->query('DROP TABLE '.EM_TICKETS_BOOKINGS_TABLE);
			$wpdb->query('DROP TABLE '.EM_RECURRENCE_TABLE);
			$wpdb->query('DROP TABLE '.EM_META_TABLE);
			
			//delete options
			$wpdb->query('DELETE FROM '.$wpdb->options.' WHERE option_name LIKE \'em_%\' OR option_name LIKE \'dbem_%\'');
			//deactivate and go!
			deactivate_plugins(array('events-manager/events-manager.php','events-manager-pro/events-manager-pro.php'), true);
			wp_safe_redirect(admin_url('plugins.php?deactivate=true'));
			exit();
		}
	}
	//Reset
	if( !empty($_REQUEST['action']) && $_REQUEST['action'] == 'reset' && !empty($_REQUEST['confirmed']) && check_admin_referer('em_reset_'.get_current_user_id().'_wpnonce') && em_wp_is_super_admin() ){
		if( check_admin_referer('em_reset_'.get_current_user_id().'_confirmed','_wpnonce2') ){
			//We have a go to uninstall
			global $wpdb;
			//delete options
			$wpdb->query('DELETE FROM '.$wpdb->options.' WHERE option_name LIKE \'em_%\' OR option_name LIKE \'dbem_%\'');
			//reset capabilities
			global $em_capabilities_array, $wp_roles;
			foreach( $wp_roles->role_objects as $role_name => $role ){
				foreach( array_keys($em_capabilities_array) as $capability){
					$role->remove_cap($capability);
				}
			}
			//go back to plugin options page
			$EM_Notices->add_confirm(__('Settings have been reset back to default. Your events, locations and categories have not been modified.','events-manager'), true);
			wp_safe_redirect(em_wp_get_referer());
			exit();
		}
	}
	//Cleanup Event Orphans
	if( !empty($_REQUEST['action']) && $_REQUEST['action'] == 'cleanup_event_orphans' && check_admin_referer('em_cleanup_event_orphans_'.get_current_user_id().'_wpnonce') && em_wp_is_super_admin() ){
		//Firstly, get all orphans
		global $wpdb;
		$sql = 'SELECT event_id FROM '.EM_EVENTS_TABLE.' WHERE post_id NOT IN (SELECT ID FROM ' .$wpdb->posts. ' WHERE post_type="'. EM_POST_TYPE_EVENT .'" OR post_type="event-recurring")';
	
		$results = $wpdb->get_col($sql);
		$deleted_events = 0;
		foreach( $results as $event_id ){
			$EM_Event = new EM_Event($event_id);
			if( !empty($EM_Event->orphaned_event) && $EM_Event->delete() ){
				$deleted_events++;
			}
		}
		//go back to plugin options page
		$EM_Notices->add_confirm(sprintf(__('Found %d orphaned events, deleted %d successfully','events-manager'), count($results), $deleted_events), true);
		wp_safe_redirect(em_wp_get_referer());
		exit();
	}
	//Force Update Recheck - Workaround for now
	if( !empty($_REQUEST['action']) && $_REQUEST['action'] == 'recheck_updates' && check_admin_referer('em_recheck_updates_'.get_current_user_id().'_wpnonce') && em_wp_is_super_admin() ){
		//force recheck of plugin updates, to refresh dl links
		delete_transient('update_plugins');
		delete_site_transient('update_plugins');
		$EM_Notices->add_confirm(__('If there are any new updates, you should now see them in your Plugins or Updates admin pages.','events-manager'), true);
		wp_safe_redirect(em_wp_get_referer());
		exit();
	}
	//Flag version checking to look at trunk, not tag
	if( !empty($_REQUEST['action']) && $_REQUEST['action'] == 'check_devs' && check_admin_referer('em_check_devs_wpnonce') && em_wp_is_super_admin() ){
		//delete transients, and add a flag to recheck dev version next time round
		delete_transient('update_plugins');
		delete_site_transient('update_plugins');
		update_option('em_check_dev_version', true);
		$EM_Notices->add_confirm(__('Checking for dev versions.','events-manager').' '. __('If there are any new updates, you should now see them in your Plugins or Updates admin pages.','events-manager'), true);
		wp_safe_redirect(em_wp_get_referer());
		exit();
	}
	
	//reset timezones
	if( !empty($_REQUEST['action']) && $_REQUEST['action'] == 'reset_timezones' && check_admin_referer('reset_timezones') && em_wp_is_super_admin() ){
		include(EM_DIR.'/em-install.php');
		if( empty($_REQUEST['timezone_reset_value']) ) return;
		$timezone = str_replace('UTC ', '', $_REQUEST['timezone_reset_value']);
		
		$result = em_migrate_datetime_timezones(true, true, $timezone);
		
		if( $result !== true ){
			$EM_Notices->add_error($result, true);
		}else{
			
			$EM_Notices->add_confirm(sprintf(__('Event timezones have been reset to %s.','events-manager'), '<code>'.$timezone.'</code>'), true);
			
		}
		wp_safe_redirect(em_wp_get_referer());
		exit();
	}
	
	//update scripts that may need to run
	$blog_updates = EM_Options::get('updates');
	if( is_array($blog_updates) ) {
		foreach ( $blog_updates as $update => $update_data ) {
			$filename = EM_DIR . '/admin/settings/updates/' . $update . '.php';
			if ( file_exists( $filename ) ) {
				include_once( $filename );
			}
			do_action( 'em_admin_update_' . $update, $update_data );
		}
	}
}
add_action('admin_init', 'em_options_save');

function em_admin_email_test_ajax(){
    if( wp_verify_nonce($_REQUEST['_check_email_nonce'],'check_email') && current_user_can('activate_plugins') ){
        $subject = __("Events Manager Test Email",'events-manager');
        $content = __('Congratulations! Your email settings work.','events-manager');
        $current_user = get_user_by('id', get_current_user_id());
        //add filters for options used in EM_Mailer so the current supplied ones are used
        ob_start();
        function pre_option_dbem_mail_sender_name(){ return sanitize_text_field($_REQUEST['dbem_mail_sender_name']); }
        add_filter('pre_option_dbem_mail_sender_name', 'pre_option_dbem_mail_sender_name');
        function pre_option_dbem_mail_sender_address(){ return sanitize_text_field($_REQUEST['dbem_mail_sender_address']); }
        add_filter('pre_option_dbem_mail_sender_address', 'pre_option_dbem_mail_sender_address');
        function pre_option_dbem_rsvp_mail_send_method(){ return sanitize_text_field($_REQUEST['dbem_rsvp_mail_send_method']); }
        add_filter('pre_option_dbem_rsvp_mail_send_method', 'pre_option_dbem_rsvp_mail_send_method');
        function pre_option_dbem_rsvp_mail_port(){ return sanitize_text_field($_REQUEST['dbem_rsvp_mail_port']); }
        add_filter('pre_option_dbem_rsvp_mail_port', 'pre_option_dbem_rsvp_mail_port');
	    function pre_option_dbem_smtp_encryption(){ return sanitize_text_field($_REQUEST['dbem_smtp_encryption']); }
	    add_filter('pre_option_dbem_smtp_encryption', 'pre_option_dbem_smtp_encryption');
	    function pre_option_dbem_smtp_autotls(){ return sanitize_text_field($_REQUEST['dbem_smtp_autotls']); }
	    add_filter('pre_option_dbem_smtp_autotls', 'pre_option_dbem_smtp_autotls');
        function pre_option_dbem_rsvp_mail_SMTPAuth(){ return sanitize_text_field($_REQUEST['dbem_rsvp_mail_SMTPAuth']); }
        add_filter('pre_option_dbem_rsvp_mail_SMTPAuth', 'pre_option_dbem_rsvp_mail_SMTPAuth');
        function pre_option_dbem_smtp_host(){ return sanitize_text_field($_REQUEST['dbem_smtp_host']); }
        add_filter('pre_option_dbem_smtp_host', 'pre_option_dbem_smtp_host');
        function pre_option_dbem_smtp_username(){ return sanitize_text_field($_REQUEST['dbem_smtp_username']); }
        add_filter('pre_option_dbem_smtp_username', 'pre_option_dbem_smtp_username');
        function pre_option_dbem_smtp_password(){ return sanitize_text_field($_REQUEST['dbem_smtp_password']); }
        add_filter('pre_option_dbem_smtp_password', 'pre_option_dbem_smtp_password');        
        ob_clean(); //remove any php errors/warnings output
        $EM_Event = new EM_Event();
        if( $EM_Event->email_send($subject,$content,$current_user->user_email) ){
        	$result = array(
        		'result' => true,
        		'message' => sprintf(__('Email sent successfully to %s','events-manager'),$current_user->user_email)
        	);
        }else{
            $result = array(
            	'result' => false,
            	'message' => __('Email not sent.','events-manager')." <ul><li>".implode('</li><li>',$EM_Event->get_errors()).'</li></ul>'
            );
        }
        echo EM_Object::json_encode($result);
    }
    exit();
}
add_action('wp_ajax_em_admin_test_email','em_admin_email_test_ajax');

function em_admin_options_reset_page(){
	if( check_admin_referer('em_reset_'.get_current_user_id().'_wpnonce') && em_wp_is_super_admin() ){
		?>
		<div class="wrap">		
			<div id='icon-options-general' class='icon32'><br /></div>
			<h2><?php _e('Reset Events Manager','events-manager'); ?></h2>
			<p style="color:red; font-weight:bold;"><?php _e('Are you sure you want to reset Events Manager?','events-manager')?></p>
			<p style="font-weight:bold;"><?php _e('All your settings, including email templates and template formats for Events Manager will be deleted.','events-manager')?></p>
			<p>
				<a href="<?php echo esc_url(add_query_arg(array('_wpnonce2' => wp_create_nonce('em_reset_'.get_current_user_id().'_confirmed'), 'confirmed'=>1))); ?>" class="button-primary"><?php _e('Reset Events Manager','events-manager'); ?></a>
				<a href="<?php echo esc_url(em_wp_get_referer()); ?>" class="button-secondary"><?php _e('Cancel','events-manager'); ?></a>
			</p>
		</div>		
		<?php
	}
}
function em_admin_options_uninstall_page(){
	if( check_admin_referer('em_uninstall_'.get_current_user_id().'_wpnonce') && em_wp_is_super_admin() ){
		?>
		<div class="wrap">		
			<div id='icon-options-general' class='icon32'><br /></div>
			<h2><?php _e('Uninstall Events Manager','events-manager'); ?></h2>
			<p style="color:red; font-weight:bold;"><?php _e('Are you sure you want to uninstall Events Manager?','events-manager')?></p>
			<p style="font-weight:bold;"><?php _e('All your settings and events will be permanently deleted. This cannot be undone.','events-manager')?></p>
			<p><?php echo sprintf(__('If you just want to deactivate the plugin, <a href="%s">go to your plugins page</a>.','events-manager'), wp_nonce_url(admin_url('plugins.php'))); ?></p>
			<p>
				<a href="<?php echo esc_url(add_query_arg(array('_wpnonce2' => wp_create_nonce('em_uninstall_'.get_current_user_id().'_confirmed'), 'confirmed'=>1))); ?>" class="button-primary"><?php _e('Uninstall and Deactivate','events-manager'); ?></a>
				<a href="<?php echo esc_url(em_wp_get_referer()); ?>" class="button-secondary"><?php _e('Cancel','events-manager'); ?></a>
			</p>
		</div>		
		<?php
	}
}

function em_admin_options_page() {
	global $wpdb, $EM_Notices;
	//Check for uninstall/reset request
	if( !empty($_REQUEST['action']) && $_REQUEST['action'] == 'uninstall' ){
		em_admin_options_uninstall_page();
		return;
	}	
	if( !empty($_REQUEST['action']) && $_REQUEST['action'] == 'reset' ){
		em_admin_options_reset_page();
		return;
	}
	if( !empty($_REQUEST['action']) && $_REQUEST['action'] == 'update' && !empty($_REQUEST['update_action']) ){
		do_action('em_admin_update_settings_confirm_'.$_REQUEST['update_action']);
		return;
	}
	//substitute dropdowns with input boxes for some situations to improve speed, e.g. if there 1000s of locations or users
	$total_users = $wpdb->get_var("SELECT COUNT(ID) FROM {$wpdb->users};");
	if( $total_users > 100 && !defined('EM_OPTIMIZE_SETTINGS_PAGE_USERS') ){ define('EM_OPTIMIZE_SETTINGS_PAGE_USERS',true); }
	$total_locations = EM_Locations::count();
	if( $total_locations > 100 && !defined('EM_OPTIMIZE_SETTINGS_PAGE_LOCATIONS') ){ define('EM_OPTIMIZE_SETTINGS_PAGE_LOCATIONS',true); }
	//TODO place all options into an array
	global $events_placeholder_tip, $locations_placeholder_tip, $categories_placeholder_tip, $bookings_placeholder_tip;
	$events_placeholders = '<a href="'.EM_ADMIN_URL .'&amp;page=events-manager-help#event-placeholders">'. __('Event Related Placeholders','events-manager') .'</a>';
	$locations_placeholders = '<a href="'.EM_ADMIN_URL .'&amp;page=events-manager-help#location-placeholders">'. __('Location Related Placeholders','events-manager') .'</a>';
	$bookings_placeholders = '<a href="'.EM_ADMIN_URL .'&amp;page=events-manager-help#booking-placeholders">'. __('Booking Related Placeholders','events-manager') .'</a>';
	$categories_placeholders = '<a href="'.EM_ADMIN_URL .'&amp;page=events-manager-help#category-placeholders">'. __('Category Related Placeholders','events-manager') .'</a>';
	$events_placeholder_tip = " ". sprintf(__('This accepts %s and %s placeholders.','events-manager'),$events_placeholders, $locations_placeholders);
	$locations_placeholder_tip = " ". sprintf(__('This accepts %s placeholders.','events-manager'), $locations_placeholders);
	$categories_placeholder_tip = " ". sprintf(__('This accepts %s placeholders.','events-manager'), $categories_placeholders);
	$bookings_placeholder_tip = " ". sprintf(__('This accepts %s, %s and %s placeholders.','events-manager'), $bookings_placeholders, $events_placeholders, $locations_placeholders);
	
	global $save_button;
	$save_button = '<tr><th>&nbsp;</th><td><p class="submit" style="margin:0px; padding:0px; text-align:right;"><input type="submit" class="button-primary" name="Submit" value="'. __( 'Save Changes', 'events-manager') .' ('. __('All','events-manager') .')" /></p></td></tr>';
	
	
	if( defined('EM_SETTINGS_TABS') && EM_SETTINGS_TABS ){
	    $tabs_enabled = true;
	    $general_tab_link = esc_url(add_query_arg( array('em_tab'=>'general')));
	    $pages_tab_link = esc_url(add_query_arg( array('em_tab'=>'pages')));
	    $formats_tab_link = esc_url(add_query_arg( array('em_tab'=>'formats')));
	    $bookings_tab_link = esc_url(add_query_arg( array('em_tab'=>'bookings')));
	    $emails_tab_link = esc_url(add_query_arg( array('em_tab'=>'emails')));
	}else{
	    $general_tab_link = $pages_tab_link = $formats_tab_link = $bookings_tab_link = $emails_tab_link = '';
	}
	?>
	<script type="text/javascript" charset="utf-8"><?php include(EM_DIR.'/includes/admin-settings.js'); ?></script>
	<style type="text/css">.postbox h3 { cursor:pointer; }</style>
	<div class="wrap <?php if(empty($tabs_enabled)) echo 'tabs-active' ?>">
		<h1 id="em-options-title"><?php _e ( 'Event Manager Options', 'events-manager'); ?></h1>
		<h2 class="nav-tab-wrapper">
			<a href="<?php echo $general_tab_link; ?>#general" id="em-menu-general" class="nav-tab nav-tab-active"><?php _e('General','events-manager'); ?></a>
			<a href="<?php echo $pages_tab_link; ?>#pages" id="em-menu-pages" class="nav-tab"><?php _e('Pages','events-manager'); ?></a>
			<a href="<?php echo $formats_tab_link; ?>#formats" id="em-menu-formats" class="nav-tab"><?php _e('Formatting','events-manager'); ?></a>
			<?php if( get_option('dbem_rsvp_enabled') ): ?>
			<a href="<?php echo $bookings_tab_link; ?>#bookings" id="em-menu-bookings" class="nav-tab"><?php _e('Bookings','events-manager'); ?></a>
			<?php endif; ?>
			<a href="<?php echo $emails_tab_link; ?>#emails" id="em-menu-emails" class="nav-tab"><?php _e('Emails','events-manager'); ?></a>
			<?php
			$custom_tabs = apply_filters('em_options_page_tabs', array());
			foreach( $custom_tabs as $tab_key => $tab_name ){
				$tab_link = !empty($tabs_enabled) ? esc_url(add_query_arg( array('em_tab'=>$tab_key))) : '';
				$active_class = !empty($tabs_enabled) && !empty($_GET['em_tab']) && $_GET['em_tab'] == $tab_key ? 'nav-tab-active':'';
				echo "<a href='$tab_link#$tab_key' id='em-menu-$tab_key' class='nav-tab $active_class'>$tab_name</a>";
			}
			?>
		</h2>
		<form id="em-options-form" method="post" action="">
			<div class="metabox-holder">         
			<!-- // TODO Move style in css -->
			<div class='postbox-container' style='width: 99.5%'>
			<div id="">
			
			<?php
			if( !empty($tabs_enabled) ){
			    if( empty($_REQUEST['em_tab']) || $_REQUEST['em_tab'] == 'general' ){ 
			        include('settings/general.php');
			    }else{
        			if( $_REQUEST['em_tab'] == 'pages' ) include('settings/pages.php');
        			if( $_REQUEST['em_tab'] == 'formats' ) include('settings/formats.php');
        			if( get_option('dbem_rsvp_enabled') && $_REQUEST['em_tab'] == 'bookings'  ){
        			    include('settings/bookings.php');
        			}
        			if( $_REQUEST['em_tab'] == 'emails' ) include('settings/emails.php');
					if( array_key_exists($_REQUEST['em_tab'], $custom_tabs) ){
						?>
						<div class="em-menu-<?php echo esc_attr($_REQUEST['em_tab']) ?> em-menu-group">
						<?php do_action('em_options_page_tab_'. $_REQUEST['em_tab']); ?>
						</div>
						<?php
					}
			    }
			}else{
    			include('settings/general.php');
    			include('settings/pages.php');
    			include('settings/formats.php');
    			if( get_option('dbem_rsvp_enabled') ){
    			    include('settings/bookings.php');
    			}
    			include('settings/emails.php');
				foreach( $custom_tabs as $tab_key => $tab_name ){
					?>
					<div class="em-menu-<?php echo esc_attr($tab_key) ?> em-menu-group" style="display:none;">
						<?php do_action('em_options_page_tab_'. $tab_key); ?>
					</div>
					<?php
				}
			}
			?>
			
			
			<p class="submit">
				<input type="submit" class="button-primary" name="Submit" value="<?php esc_attr_e( 'Save Changes', 'events-manager'); ?>" />
				<input type="hidden" name="em-submitted" value="1" />
				<input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce('events-manager-options'); ?>" />
			</p>  
			
			</div> <!-- .metabox-sortables -->
			</div> <!-- .postbox-container -->
			
			</div> <!-- .metabox-holder -->	
		</form>
	</div>
	<?php
}

/**
 * Meta options box for image sizes. Shared in both MS and Normal options page, hence it's own function 
 */
function em_admin_option_box_image_sizes(){
	global $save_button;
	?>
	<div  class="postbox " id="em-opt-image-sizes" >
	<div class="handlediv" title="<?php __('Click to toggle', 'events-manager'); ?>"><br /></div><h3><span><?php _e ( 'Image Sizes', 'events-manager'); ?> </span></h3>
	<div class="inside">
	    <p class="em-boxheader"><?php _e('These settings will only apply to the image uploading if using our front-end forms. In your WP admin area, images are handled by WordPress.','events-manager'); ?></p>
		<table class='form-table'>
			<?php
			em_options_input_text ( __( 'Maximum width (px)', 'events-manager'), 'dbem_image_max_width', __( 'The maximum allowed width for images uploads', 'events-manager') );
			em_options_input_text ( __( 'Minimum width (px)', 'events-manager'), 'dbem_image_min_width', __( 'The minimum allowed width for images uploads', 'events-manager') );
			em_options_input_text ( __( 'Maximum height (px)', 'events-manager'), 'dbem_image_max_height', __( "The maximum allowed height for images uploaded, in pixels", 'events-manager') );
			em_options_input_text ( __( 'Minimum height (px)', 'events-manager'), 'dbem_image_min_height', __( "The minimum allowed height for images uploaded, in pixels", 'events-manager') );
			em_options_input_text ( __( 'Maximum size (bytes)', 'events-manager'), 'dbem_image_max_size', __( "The maximum allowed size for images uploaded, in bytes", 'events-manager') );
			echo $save_button;
			?>
		</table>
	</div> <!-- . inside -->
	</div> <!-- .postbox -->
	<?php	
}

/**
 * Meta options box for email settings. Shared in both MS and Normal options page, hence it's own function 
 */
function em_admin_option_box_email(){
	global $save_button;
	$current_user = get_user_by('id', get_current_user_id());
	?>
	<div  class="postbox "  id="em-opt-email-settings">
	<div class="handlediv" title="<?php __('Click to toggle', 'events-manager'); ?>"><br /></div><h3><span><?php _e ( 'Email Settings', 'events-manager'); ?></span></h3>
	<div class="inside em-email-form">
		<p class="em-email-settings-check em-boxheader">
			<em><?php _e('Before you save your changes, you can quickly send yourself a test email by clicking this button.','events-manager'); ?>
			<?php echo sprintf(__('A test email will be sent to your account email - %s','events-manager'), $current_user->user_email . ' <a href="'.admin_url( 'profile.php' ).'">'.__('edit','events-manager').'</a>'); ?></em><br />
			<input type="button" id="em-admin-check-email" class="button-secondary" value="<?php esc_attr_e('Test Email Settings','events-manager'); ?>" />
			<input type="hidden" name="_check_email_nonce" value="<?php echo wp_create_nonce('check_email'); ?>" />
			<span id="em-email-settings-check-status"></span>
		</p>
		<table class="form-table">
			<?php
			em_options_input_text ( __( 'Notification sender name', 'events-manager'), 'dbem_mail_sender_name', __( "Insert the display name of the notification sender.", 'events-manager') );
			em_options_input_text ( __( 'Notification sender address', 'events-manager'), 'dbem_mail_sender_address', __( "Insert the address of the notification sender.", 'events-manager') );
			em_options_select ( __( 'Mail sending method', 'events-manager'), 'dbem_rsvp_mail_send_method', array ('smtp' => 'SMTP', 'mail' => __( 'PHP mail function', 'events-manager'), 'sendmail' => 'Sendmail', 'qmail' => 'Qmail', 'wp_mail' => 'WP Mail' ), __( 'Select the method to send email notification.', 'events-manager') );
			em_options_radio_binary ( __( 'Send HTML Emails?', 'events-manager'), 'dbem_smtp_html', __( 'If set to yes, your emails will be sent in HTML format, otherwise plaintext.', 'events-manager').' '.__( 'Depending on server settings, some sending methods may ignore this settings.', 'events-manager') );
			em_options_radio_binary ( __( 'Add br tags to HTML emails?', 'events-manager'), 'dbem_smtp_html_br', __( 'If HTML emails are enabled, br tags will automatically be added for new lines.', 'events-manager') );
			?>
			<tbody class="em-email-settings-smtp">
				<?php
				em_options_input_text ( 'Mail sending port', 'dbem_rsvp_mail_port', __( "The port through which you e-mail notifications will be sent. Make sure the firewall doesn't block this port", 'events-manager') );
				em_options_radio_binary ( __( 'Use SMTP authentication?', 'events-manager'), 'dbem_rsvp_mail_SMTPAuth', __( 'SMTP authentication is often needed. If you use Gmail, make sure to set this parameter to Yes', 'events-manager') );
				em_options_select ( __( 'SMTP Encryption', 'events-manager'), 'dbem_smtp_encryption', array ('0' => __( 'None', 'events-manager'), 'ssl' => 'SSL', 'tls' => 'TLS' ), __( 'Encryption is always recommended if your SMTP server supports it. If your server supports TLS, this is also the most recommended method.', 'events-manager') );
				em_options_radio_binary ( __( 'AutoTLS', 'events-manager'), 'dbem_smtp_autotls', __( 'We recommend leaving this on unless you are experiencing issues configuring your email.', 'events-manager') );
				em_options_input_text ( 'SMTP host', 'dbem_smtp_host', __( "The SMTP host. Usually it corresponds to 'localhost'. If you use Gmail, set this value to 'tls://smtp.gmail.com:587'.", 'events-manager') );
				em_options_input_text ( __( 'SMTP username', 'events-manager'), 'dbem_smtp_username', __( "Insert the username to be used to access your SMTP server.", 'events-manager') );
				em_options_input_password ( __( 'SMTP password', 'events-manager'), "dbem_smtp_password", __( "Insert the password to be used to access your SMTP server", 'events-manager') );
				?>
			</tbody>
			<?php
			echo $save_button;
			?>
		</table>
		<script type="text/javascript" charset="utf-8">
			jQuery(document).ready(function($){
				$('#dbem_rsvp_mail_send_method_row select').on('change', function(){
					el = $(this);
					if( el.find(':selected').val() == 'smtp' ){
						$('.em-email-settings-smtp').show();
					}else{
						$('.em-email-settings-smtp').hide();
					}
				}).trigger('change');
				$('input#em-admin-check-email').on('click', function(e,el){
					var email_data = $('.em-email-form input, .em-email-form select').serialize();
					$.ajax({
						url: EM.ajaxurl,
						dataType: 'json',
						data: email_data+"&action=em_admin_test_email",
						success: function(data){
							if(data.result && data.message){
								$('#em-email-settings-check-status').css({'color':'green','display':'block'}).html(data.message);
							}else{
								var msg = (data.message) ? data.message:'Email not sent';
								$('#em-email-settings-check-status').css({'color':'red','display':'block'}).html(msg);
							}
						},
						error: function(){ $('#em-email-settings-check-status').css({'color':'red','display':'block'}).html('Server Error'); },
						beforeSend: function(){ $('input#em-admin-check-email').val('<?php _e('Checking...','events-manager') ?>'); },
						complete: function(){ $('input#em-admin-check-email').val('<?php _e('Test Email Settings','events-manager'); ?>');  }
					});
				});
			});
		</script>
	</div> <!-- . inside -->
	</div> <!-- .postbox --> 
	<?php
}

/**
 * Meta options box for user capabilities. Shared in both MS and Normal options page, hence it's own function 
 */
function em_admin_option_box_caps(){
	global $save_button, $wpdb;
	?>
	<div  class="postbox" id="em-opt-user-caps" >
	<div class="handlediv" title="<?php __('Click to toggle', 'events-manager'); ?>"><br /></div><h3><span><?php _e ( 'User Capabilities', 'events-manager'); ?></span></h3>
	<div class="inside">
            <table class="form-table">
            <tr><td colspan="2" class="em-boxheader">
            	<p><strong><?php _e('Warning: Changing these values may result in exposing previously hidden information to all users.', 'events-manager')?></strong></p>
            	<p><em><?php _e('You can now give fine grained control with regards to what your users can do with events. Each user role can have perform different sets of actions.','events-manager'); ?></em></p>
            </td></tr>
			<?php
            global $wp_roles;
			$cap_docs = array(
				sprintf(__('%s Capabilities','events-manager'),__('Event','events-manager')) => array(
					/* Event Capabilities */
					'publish_events' => sprintf(__('Users can publish %s and skip any admin approval','events-manager'),__('events','events-manager')),
					'delete_others_events' => sprintf(__('User can delete other users %s','events-manager'),__('events','events-manager')),
					'edit_others_events' => sprintf(__('User can edit other users %s','events-manager'),__('events','events-manager')),
					'delete_events' => sprintf(__('User can delete their own %s','events-manager'),__('events','events-manager')),
					'edit_events' => sprintf(__('User can create and edit %s','events-manager'),__('events','events-manager')),
					'read_private_events' => sprintf(__('User can view private %s','events-manager'),__('events','events-manager')),
					/*'read_events' => sprintf(__('User can view %s','events-manager'),__('events','events-manager')),*/
				),
				sprintf(__('%s Capabilities','events-manager'),__('Recurring Event','events-manager')) => array(
					/* Recurring Event Capabilties */
					'publish_recurring_events' => sprintf(__('Users can publish %s and skip any admin approval','events-manager'),__('recurring events','events-manager')),
					'delete_others_recurring_events' => sprintf(__('User can delete other users %s','events-manager'),__('recurring events','events-manager')),
					'edit_others_recurring_events' => sprintf(__('User can edit other users %s','events-manager'),__('recurring events','events-manager')),
					'delete_recurring_events' => sprintf(__('User can delete their own %s','events-manager'),__('recurring events','events-manager')),
					'edit_recurring_events' => sprintf(__('User can create and edit %s','events-manager'),__('recurring events','events-manager'))						
				),
				sprintf(__('%s Capabilities','events-manager'),__('Location','events-manager')) => array(
					/* Location Capabilities */
					'publish_locations' => sprintf(__('Users can publish %s and skip any admin approval','events-manager'),__('locations','events-manager')),
					'delete_others_locations' => sprintf(__('User can delete other users %s','events-manager'),__('locations','events-manager')),
					'edit_others_locations' => sprintf(__('User can edit other users %s','events-manager'),__('locations','events-manager')),
					'delete_locations' => sprintf(__('User can delete their own %s','events-manager'),__('locations','events-manager')),
					'edit_locations' => sprintf(__('User can create and edit %s','events-manager'),__('locations','events-manager')),
					'read_private_locations' => sprintf(__('User can view private %s','events-manager'),__('locations','events-manager')),
					'read_others_locations' => __('User can use other user locations for their events.','events-manager'),
					/*'read_locations' => sprintf(__('User can view %s','events-manager'),__('locations','events-manager')),*/
				),
				sprintf(__('%s Capabilities','events-manager'),__('Other','events-manager')) => array(
					/* Category Capabilities */
					'delete_event_categories' => sprintf(__('User can delete %s categories and tags.','events-manager'),__('event','events-manager')),
					'edit_event_categories' => sprintf(__('User can edit %s categories and tags.','events-manager'),__('event','events-manager')),
					/* Booking Capabilities */
					'manage_others_bookings' => __('User can manage other users individual bookings and event booking settings.','events-manager'),
					'manage_bookings' => __('User can use and manage bookings with their events.','events-manager'),
					'upload_event_images' => __('User can upload images along with their events and locations.','events-manager')
				)
			);
            ?>
        	
            <tr><td colspan="2">
	            <table class="em-caps-table" style="width:auto;" cellspacing="0" cellpadding="0">
					<thead>
						<tr>
							<td>&nbsp;</td>
							<?php 
							$odd = 0;
							foreach(array_keys($cap_docs) as $capability_group){
								?><th class="<?php echo ( !is_int($odd/2) ) ? 'odd':''; ?>"><?php echo $capability_group ?></th><?php
								$odd++;
							} 
							?>
						</tr>
					</thead>
					<tbody>
            			<?php foreach($wp_roles->role_objects as $role): ?>
	            		<tr>
	            			<td class="cap"><strong><?php echo $role->name; ?></strong></td>
							<?php 
							$odd = 0;
							foreach($cap_docs as $capability_group){
								?>
	            				<td class="<?php echo ( !is_int($odd/2) ) ? 'odd':''; ?>">
									<?php foreach($capability_group as $cap => $cap_help){ ?>
	            					<input type="checkbox" name="em_capabilities[<?php echo $role->name; ?>][<?php echo $cap ?>]" value="1" id="<?php echo $role->name.'_'.$cap; ?>" <?php echo $role->has_cap($cap) ? 'checked="checked"':''; ?> />
	            					&nbsp;<label for="<?php echo $role->name.'_'.$cap; ?>"><?php echo $cap; ?></label>&nbsp;<a href="#" title="<?php echo $cap_help; ?>">?</a>
	            					<br />
	            					<?php } ?>
	            				</td>
	            				<?php
								$odd++;
							} 
							?>
	            		</tr>
			            <?php endforeach; ?>
			        </tbody>
	            </table>
	        </td></tr>
	        <?php echo $save_button; ?>
		</table>
	</div> <!-- . inside -->
	</div> <!-- .postbox -->
	<?php
}


/**
 * Meta options box for privacy and data protection rules for GDPR (and other dp laws) compliancy
 */
function em_admin_option_box_data_privacy(){
	global $save_button;
	$privacy_options = array(
		0 => __('Do not include', 'events-manager'),
		1 => __('Include all', 'events-manager'),
		2 => __('Include only guest submissions', 'events-manager')
	);
	?>
    <div  class="postbox " id="em-opt-data-privacy" >
        <div class="handlediv" title="<?php __('Click to toggle', 'events-manager'); ?>"><br /></div><h3><span><?php _e ( 'Privacy', 'events-manager'); ?> </span></h3>
        <div class="inside">
            <p class="em-boxheader"><?php echo sprintf(__('Depending on the nature of your site, you will be subject to one or more national and international privacy/data protection laws such as the %s. Below are some options that you can use to tailor how Events Manager interacts with WordPress privacy tools.','events-manager'), '<a href=http://ec.europa.eu/justice/smedataprotect/index_en.htm">GDPR</a>'); ?></p>
            <p class="em-boxheader"><?php echo sprintf(__('For more information see our <a href="%s">data privacy documentation</a>.','events-manager'), 'http://wp-events-plugin.com/documentation/data-privacy-gdpr-compliance/'); ?></p>
            <p class="em-boxheader"><?php echo __('All options below relate to data that may have been submitted by or collected from the user requesting their personal data, which would also include events and locations where they are the author.', 'events-manager'); ?></p>
            <table class='form-table'>
                <thead>
                    <tr class="em-header">
                        <th colspan="2"><h4><?php esc_html_e('Export Personal Data'); ?></h4></th>
                    </tr>
                </thead>
				<?php
				em_options_select ( __( 'Events', 'events-manager'), 'dbem_data_privacy_export_events', $privacy_options );
				em_options_select ( __( 'Locations', 'events-manager'), 'dbem_data_privacy_export_locations', $privacy_options, __('Locations submitted by guest users are not included, unless they are linked to events also submitted by them.', 'events-manager') );
				em_options_select ( __( 'Bookings', 'events-manager'), 'dbem_data_privacy_export_bookings', $privacy_options, __('This is specific to bookings made by the user, not bookings that may have been made to events they own.', 'events-manager'), $privacy_options );
				?>
                <thead>
                    <tr class="em-header">
                        <th colspan="2"><h4><?php esc_html_e('Erase Personal Data'); ?></h4></th>
                    </tr>
                </thead>
                <?php
                em_options_select ( __( 'Events', 'events-manager'), 'dbem_data_privacy_erase_events', $privacy_options );
                em_options_select ( __( 'Locations', 'events-manager'), 'dbem_data_privacy_erase_locations', $privacy_options, __('Locations submitted by guest users are not included, unless they are linked to events also submitted by them.', 'events-manager') );
                em_options_select ( __( 'Bookings', 'events-manager'), 'dbem_data_privacy_erase_bookings', $privacy_options, __('This is specific to bookings made by the user, not bookings that may have been made to events they own.', 'events-manager'), $privacy_options );
                ?>
                <thead>
                    <tr class="em-header">
                        <th colspan="2">
                            <h4><?php esc_html_e('Consent', 'events-manager'); ?></h4>
                            <p><?php esc_html_e('If you collect personal data, you may want to request their consent. The options below will automatically add checkboxes requesting this consent.', 'events-manager'); ?></p>
                        </th>
                    </tr>
                </thead>
                <?php
                $consent_options = array(
                    0 => __('Do not show', 'events-manager'),
                    1 => __('Show to all', 'events-manager'),
                    2 => __('Only show to guests', 'events-manager')
                );
                $consent_remember = array(
	                0 => __('Always show and ask for consent', 'events-manager'),
                	1 => __('Remember and hide checkbox', 'events-manager'),
	                2 => __('Remember and show checkbox', 'events-manager')
                );
                em_options_input_text( __('Consent Text', 'events-manager'), 'dbem_data_privacy_consent_text', __('%s will be replaced by a link to your site privacy policy page.', 'events-manager') );
                em_options_select( __('Remembering Consent', 'events-manager'), 'dbem_data_privacy_consent_remember', $consent_remember, __('You can hide or leave the consent box checked for registered users who have provided consent previously.', 'events-manager') );
                em_options_select( __( 'Event Submission Forms', 'events-manager'), 'dbem_data_privacy_consent_events', $privacy_options );
                em_options_select( __( 'Location Submission Forms', 'events-manager'), 'dbem_data_privacy_consent_locations', $privacy_options );
                em_options_select( __( 'Bookings Forms', 'events-manager'), 'dbem_data_privacy_consent_bookings', $privacy_options );

                echo $save_button;
                ?>
            </table>
        </div> <!-- . inside -->
    </div> <!-- .postbox -->
	<?php
}
?>