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
		
		foreach ($_POST as $postKey => $postValue){
			
			//TODO some more validation/reporting
			
			if( in_array($postKey, array('dbem_bookings_notify_admin','dbem_event_submitted_email_admin','dbem_js_limit_events_form','dbem_js_limit_search','dbem_js_limit_general','dbem_search_form_geo_distance_options')) ){ $postValue = str_replace(' ', '', $postValue); } //clean up comma separated emails, no spaces needed
			
			//TODO slashes being added?
			if( is_array($postValue) ){
				foreach($postValue as $postValue_key=>$postValue_val) $postValue[$postValue_key] = wp_unslash($postValue_val);
			}else{
				$postValue = wp_unslash($postValue);
			}
			update_option($postKey, $postValue);
				
			
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

}
add_action('admin_init', 'em_options_save');



function em_admin_options_page() {
	global $wpdb;
	
	
	if( defined('EM_SETTINGS_TABS') && EM_SETTINGS_TABS ){
	    $tabs_enabled = true;
	    $general_tab_link = esc_url(add_query_arg( array('em_tab'=>'general')));
	    $bookings_tab_link = esc_url(add_query_arg( array('em_tab'=>'bookings')));
	    $emails_tab_link = esc_url(add_query_arg( array('em_tab'=>'emails')));
	}else{
	    $general_tab_link = $bookings_tab_link = $emails_tab_link = '';
	}
	
	?>
	
	<style type="text/css">.postbox h3 { cursor:pointer; }</style>
	<div class="em-tabs wrap <?php if(empty($tabs_enabled)) echo 'tabs-active' ?>">
		<h1 id="em-options-title"><?php _e ( 'Event Manager Options', 'events-manager'); ?></h1>
		<h2 class="nav-tab-wrapper">
			<a href="<?php echo $general_tab_link; ?>#general" id="em-menu-general" class="nav-tab nav-tab-active"><?php _e('General','events-manager'); ?></a>
			
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




?>