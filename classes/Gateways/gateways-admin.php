<?php
class EM_Gateways_Admin{
	
	public static function init(){
		add_action('em_create_events_submenu', 'EM_Gateways_Admin::admin_menu',10,1);
		if( !empty($_REQUEST['page']) && $_REQUEST['page'] == 'events-gateways' ){
			add_action('admin_init', 'EM_Gateways_Admin::handle_gateways_panel_updates', 10, 1);
		}
		
	}
	
	
	public static function admin_menu($plugin_pages){
		$plugin_pages[] = add_submenu_page('edit.php?post_type='.EM_POST_TYPE_EVENT, __('Payment Gateways','events'),__('Payment Gateways','events'),'list_users','events-gateways', 'EM_Gateways_Admin::handle_gateways_panel');
		return $plugin_pages;
	}

	public static function handle_gateways_panel() {
		global $action, $page, $EM_Gateways;
		wp_reset_vars( array('action', 'page') );
		switch(addslashes($action)) {
			case 'edit':	
				if(isset($EM_Gateways[addslashes($_GET['gateway'])])) {
					$EM_Gateways[addslashes($_GET['gateway'])]->settings();
				}
				return; // so we don't show the list below
				break;
			case 'transactions':
				if(isset($EM_Gateways[addslashes($_GET['gateway'])])) {
					global $EM_Gateways_Transactions;
					$EM_Gateways_Transactions->output();
				}
				return; // so we don't show the list below
				break;
		}
		$messages = [];
		$messages[1] = __('Gateway activated.', 'events');
		$messages[2] = __('Gateway not activated.', 'events');
		$messages[3] = __('Gateway deactivated.', 'events');
		$messages[4] = __('Gateway not deactivated.', 'events');
		$messages[5] = __('Gateway activation toggled.', 'events');
		?>
		<div class='wrap'>
			<h1><?php _e('Edit Gateways','events'); ?></h1>
			<?php
			if ( isset($_GET['msg']) && !empty($messages[$_GET['msg']]) ) echo '<div id="message" class="updated fade"><p>' . $messages[$_GET['msg']] . '</p></div>';
			?>
			<form method="post" action="" id="posts-filter">
				<div class="tablenav top">
					<div class="alignleft actions">
						<select name="action">
							<option selected="selected" value=""><?php _e('Bulk actions'); ?></option>
							<option value="toggle"><?php _e('Toggle activation', 'events'); ?></option>
						</select>
						<input type="submit" class="button-secondary action" value="<?php _e('Apply','events'); ?>">		
					</div>		
					<div class="alignright actions"></div>		
					<br class="clear">
				</div>	
				<div class="clear"></div>	
				<?php
					wp_original_referer_field(true, 'previous'); wp_nonce_field('emp-gateways');	
					$columns = array(	
						"name" => __('Gateway Name','events'),
						"active" =>	__('Active','events'),
						"transactions" => __('Transactions','events')
					);
					$columns = apply_filters('em_gateways_columns', $columns);	
					$gateways = EM_Gateways::gateways_list();
					$active = EM_Gateways::active_gateways();
				?>	
				<table class="wp-list-table widefat fixed striped table-view-list posts">
					<thead>
					<tr>
					<td class="manage-column column-cb check-column" id="cb" scope="col"><input id="cb-select-all-1" type="checkbox"></td>
						<?php
						foreach($columns as $key => $col) {
							?>
							<th style="" class="manage-column column-<?php echo $key; ?>" id="<?php echo $key; ?>" scope="col"><?php echo $col; ?></th>
							<?php
						}
						?>
					</tr>
					</thead>	
					<tfoot>
					<tr>
					<td class="manage-column column-cb check-column" scope="col"><input type="checkbox"></td>
						<?php
						reset($columns);
						foreach($columns as $key => $col) {
							?>
							<th style="" class="manage-column column-<?php echo $key; ?>" id="<?php echo $key; ?>" scope="col"><?php echo $col; ?></th>
							<?php
						}
						?>
					</tr>
					</tfoot>
					<tbody>
						<?php
						if($gateways) {
							foreach($gateways as $key => $gateway) { 
								if(!isset($EM_Gateways[$key])) {
									continue;
								}
								$EM_Gateway = $EM_Gateways[$key]; /* @var $EM_Gateway EM_Gateway */
								?>
								<tr valign="middle">
									<th class="check-column" scope="row"><input type="checkbox" value="<?php echo esc_attr($key); ?>" name="gateways[]"></th>
									<td class="column-name">
										<strong><a title="Edit <?php echo esc_attr($gateway); ?>" href="<?php echo EM_ADMIN_URL; ?>&amp;page=<?php echo $page; ?>&amp;action=edit&amp;gateway=<?php echo $key; ?>" class="row-title"><?php echo esc_html($gateway); ?></a></strong>
										<?php
											//Check if Multi-Booking Ready
											
											$actions = array();
											$actions['edit'] = "<span class='edit'><a href='".EM_ADMIN_URL."&amp;page=" . $page . "&amp;action=edit&amp;gateway=" . $key . "'>" . __('Settings') . "</a></span>";

											if(array_key_exists($key, $active)) {
												$actions['toggle'] = "<span class='edit activate'><a href='" . wp_nonce_url(EM_ADMIN_URL."&amp;page=" . $page. "&amp;action=deactivate&amp;gateway=" . $key . "", 'toggle-gateway_' . $key) . "'>" . __('Deactivate') . "</a></span>";
											} else {
												$actions['toggle'] = "<span class='edit activate'><a href='" . wp_nonce_url(EM_ADMIN_URL."&amp;page=" . $page. "&amp;action=activate&amp;gateway=" . $key . "", 'toggle-gateway_' . $key) . "'>" . __('Activate') . "</a></span>";
											}
										?>
										<br><div class="row-actions"><?php echo implode(" | ", $actions); ?></div>
										</td>
									<td class="column-active">
										<?php
											if(array_key_exists($key, $active)) {
												echo "<strong>" . __('Active', 'events') . "</strong>";
											} else {
												echo __('Inactive', 'events');
											}
										?>
									</td>
									<td class="column-transactions">
										<a href='<?php echo EM_ADMIN_URL; ?>&amp;page=<?php echo $page; ?>&amp;action=transactions&amp;gateway=<?php echo $key; ?>'><?php _e('View transactions','events'); ?></a>
									</td>
							    </tr>
								<?php
							}
						} else {
							$columncount = count($columns) + 1;
							?>
							<tr valign="middle" class="alternate" >
								<td colspan="<?php echo $columncount; ?>" scope="row"><?php _e('No Payment gateways were found for this install.','events'); ?></td>
						    </tr>
							<?php
						}
						?>
					</tbody>
				</table>
			</form>

		</div> <!-- wrap -->
		<?php
	}
			
	public static function handle_gateways_panel_updates() {	
		global $action, $page, $EM_Gateways;	
		wp_reset_vars ( array ('action', 'page' ) );
		if( !empty($_REQUEST['gateway']) || !empty($_REQUEST['gateways']) ){
			switch (addslashes ( $action )) {		
				case 'deactivate' :
					$key = addslashes ( $_REQUEST ['gateway'] );
					if (isset ( $EM_Gateways [$key] )) {
						if ($EM_Gateways [$key]->deactivate ()) {
							wp_safe_redirect ( add_query_arg ( 'msg', 3, wp_validate_redirect(wp_get_raw_referer(), false ) ) );
						} else {
							wp_safe_redirect ( add_query_arg ( 'msg', 4, wp_validate_redirect(wp_get_raw_referer(), false ) ) );
						}
					}
					break;		
				case 'activate' :
					$key = addslashes ( $_REQUEST ['gateway'] );
					if (isset ( $EM_Gateways[$key] )) {
						if ($EM_Gateways[$key]->activate ()) {
							wp_safe_redirect ( add_query_arg ( 'msg', 1, wp_validate_redirect(wp_get_raw_referer(), false ) ) );
						} else {
							wp_safe_redirect ( add_query_arg ( 'msg', 2, wp_validate_redirect(wp_get_raw_referer(), false ) ) );
						}
					}
					break;		
				case 'toggle' :
					check_admin_referer ( 'emp-gateways' );
					foreach ( $_REQUEST ['gateways'] as $key ) {
						if (isset ( $EM_Gateways [$key] )) {					
							$EM_Gateways [$key]->toggleactivation ();				
						}
					}
					wp_safe_redirect ( add_query_arg ( 'msg', 5, wp_validate_redirect(wp_get_raw_referer(), false ) ) );
					break;		
				case 'updated' :
					$gateway = addslashes ( $_REQUEST ['gateway'] );		
					check_admin_referer ( 'updated-'.$EM_Gateways[$gateway]->gateway );
					if ($EM_Gateways[$gateway]->update ()) {
						wp_safe_redirect ( add_query_arg ( 'msg', 'updated', wp_validate_redirect(wp_get_raw_referer(), false ) ) );
					} else {
						wp_safe_redirect ( add_query_arg ( 'msg', 'error', wp_validate_redirect(wp_get_raw_referer(), false ) ) );
					}			
					break;
			}
		}
	}
}
EM_Gateways_Admin::init();