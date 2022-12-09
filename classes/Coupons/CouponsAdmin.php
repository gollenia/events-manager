<?php
use Contexis\Events\Options;

class EM_Coupons_Admin {
    static function init(){
		include('CouponAdmin.php');
        //coupon admin add/edit page
        add_action('em_create_events_submenu', 'EM_Coupons_Admin::admin_menu',10,1);
    }
    
    /**
     * @param EM_Coupon $EM_Coupon
     */
    static function count_sync( $EM_Coupon ){
    	global $wpdb;
        //a bit hacky, but this is the only way at least for now
        $coupon_search = str_replace('a:1:{', '', serialize(array('coupon_code'=>$EM_Coupon->coupon_code)));
        $coupon_search = substr($coupon_search, 0, strlen($coupon_search)-1 );
        $coupons_count = $wpdb->get_var('SELECT COUNT(*) FROM '.EM_BOOKINGS_TABLE." WHERE booking_meta LIKE '%{$coupon_search}%'");
        if( $EM_Coupon->get_count() != $coupons_count ){
			global $wpdb;
            $wpdb->update(EM_META_TABLE, array('meta_value'=>$coupons_count), array('object_id'=>$EM_Coupon->coupon_id, 'meta_key'=>'coupon-count'));
            return true;
        }
        return false;
    }
	
	/**
	 * @param EM_Event $EM_Event
	 */
	static function admin_meta_box($EM_Event){
		//Get available coupons for user
		if( !is_user_logged_in() ) return null;
		//get event owner to search for
		$owner = empty($EM_Event->event_owner) ? get_current_user_id() : $EM_Event->event_owner;
		?>
		
		<h3><?php _e('Coupons','em-pro'); ?></h3>
		
		
    	<div id="em-event-bookings-coupons">
		
		<?php
		//show coupons that aren't event-wide or site-wide, if not in MB mode
		if( current_user_can(EM_Coupons::$can_manage) ){ 
        //not in multiple bookings mode and can create their own coupons 
        ?>
		
    		<p><p class="description"><?php _e('Coupons selected here will be applied to bookings made for this event.','em-pro'); ?></p></p>

			<table class="wp-list-table widefat fixed striped table-view-list">
			<thead>
				<tr>
					<td id="cb" class="manage-column column-cb check-column"><input disabled id="cb-select-all-1" type="checkbox"></td>
					<th class=""><?php _e("Code", "em-pro") ?></th>
					<th class=""><?php _e("Name", "em-pro") ?></th>
					<th class=""><?php _e("Discount", "em-pro") ?></th>
					<th class=""><?php _e("Description", "em-pro") ?></th>
					<th class=""><?php _e("Uses", "em-pro") ?></th>
				</tr>
			</thead>
			<tbody>
    		<?php
    		//get event owner's coupons
			$coupon_args = array( 'sitewide'=>0, 'eventwide'=>0 );
    		$coupons = apply_filters('em_coupons_admin_meta_box_coupons', EM_Coupons::get($coupon_args), $coupon_args, $EM_Event );
    		//loop through coupons and output checkboxes, or let user know no coupons were created.
			if( count($coupons) > 0 ): foreach($coupons as $EM_Coupon): /* @var $EM_Coupon EM_Coupon */  ?>
			
			<tr>
				<th>
    				<input type="checkbox" name="em_coupons[]" value="<?php  echo esc_attr($EM_Coupon->coupon_id); ?>" <?php if(in_array($EM_Coupon->coupon_id, EM_Coupons::event_get_coupon_ids($EM_Event))) echo 'checked="checked"'; ?>/>
				</th>
				<td class="code">
    				<?php if( apply_filters('em_coupons_admin_meta_box_show_code', true, $EM_Coupon, $EM_Event) ): ?>
				    	<?php echo esc_html($EM_Coupon->coupon_code); ?>
					<?php endif; ?>
				</td>
				<td>
				    
    				<?php echo esc_html($EM_Coupon->coupon_name); ?>
				</td>
				<td><?php echo $EM_Coupon->get_discount_text(); ?></td>
				<td><?php echo $EM_Coupon->coupon_description; ?></td>
			</tr>
    		<?php endforeach; ?><?php endif; ?>
			<?php $coupon_args = array('owner'=>$owner, 'sitewide'=>1, 'eventwide'=>1 );
			$coupons = apply_filters('em_coupons_admin_meta_box_coupons', EM_Coupons::get($coupon_args), $coupon_args, $EM_Event );
			if( count($coupons) > 0 ): ?>
				
				<?php foreach($coupons as $EM_Coupon): /* @var $EM_Coupon EM_Coupon */ ?>
					<tr>
				<th>
    				<input disabled type="checkbox" name="em_coupons[]" checked="checked"/>
				</th>
				<td class="code">
    				<?php if( apply_filters('em_coupons_admin_meta_box_show_code', true, $EM_Coupon, $EM_Event) ): ?>
				    	<?php echo esc_html($EM_Coupon->coupon_code); ?>
					<?php endif; ?>
				</td>
				<td>
				    
    				<?php echo esc_html($EM_Coupon->coupon_name); ?>
				</td>
				<td><?php echo $EM_Coupon->get_discount_text(); ?></td>
				<td><?php echo $EM_Coupon->coupon_description; ?></td>
				<td>
									<a href='<?php echo admin_url('edit.php?post_type='.EM_POST_TYPE_EVENT.'&amp;page=events-manager-coupons&amp;action=view&amp;coupon_id='.$EM_Coupon->coupon_id); ?>'>
									<?php 
									if( !empty($EM_Coupon->coupon_max) ){
										echo esc_html($EM_Coupon->get_count() .' / '. $EM_Coupon->coupon_max);
									}else{
										echo esc_html($EM_Coupon->get_count());
									}
									?>
									</a>
								</td>        
			</tr>
				<?php endforeach; ?>
			<?php endif; ?>
			</tbody>
		</table>
		<?php 
		}
    	//Show all coupons that are automatically applied, i.e. sitewide and eventwide 
		?>
		</div>
		<?php
	}
    
    static function admin_menu($plugin_pages){
    	$plugin_pages[] = add_submenu_page('edit.php?post_type='.EM_POST_TYPE_EVENT, __('Coupons','em-pro'),__('Coupons Manager','em-pro'),'manage_others_bookings','events-manager-coupons','EM_Coupons_Admin::admin_page');
    	return $plugin_pages; //use wp action/filters to mess with the menus
    }
    
    static function admin_page($args = array()){
    	global $EM_Coupon, $EM_Notices;
    	//load coupon if necessary
    	$EM_Coupon = !empty($_REQUEST['coupon_id']) ? new EM_Coupon_Admin($_REQUEST['coupon_id']) : new EM_Coupon_Admin();
    	//save coupon if necessary
    	if( !empty($_REQUEST['action']) && $_REQUEST['action'] == 'coupon_save' && wp_verify_nonce($_REQUEST['_wpnonce'], 'coupon_save') ){
    		if ( $EM_Coupon->get_post() && $EM_Coupon->save() ) {
    			//Success notice
    			$EM_Notices->add_confirm( $EM_Coupon->feedback_message );
    		}else{
    			$EM_Notices->add_error( $EM_Coupon->get_errors() );
    		}
    	}
    	//Delete if necessary
    	if( !empty($_REQUEST['action']) && $_REQUEST['action'] == 'coupon_delete' && wp_verify_nonce($_REQUEST['_wpnonce'], 'coupon_delete_'.$EM_Coupon->coupon_id) ){
    		if ( $EM_Coupon->delete() ) {
    			$EM_Notices->add_confirm( $EM_Coupon->feedback_message );
    		}else{
    			$EM_Notices->add_error( $EM_Coupon->get_errors() );
    		}
    	}
    	//Display relevant page
    	if( !empty($_GET['action']) && $_GET['action']=='edit' ){
    		if( empty($_REQUEST['redirect_to']) ){
    			$_REQUEST['redirect_to'] = em_add_get_params($_SERVER['REQUEST_URI'], array('action'=>null, 'coupon_id'=>null));
    		}
    		self::edit_form();
    	}elseif( !empty($_GET['action']) && $_GET['action']=='view' ){
    		self::view_page();
    	}else{
    		self::select_page();
    	}
    }
    
    static function select_page() {
    	global $wpdb, $EM_Notices;
    	$url = empty($url) ? $_SERVER['REQUEST_URI']:$url; //url to this page
    	$limit = ( !empty($_REQUEST['limit']) && is_numeric($_REQUEST[ 'limit']) ) ? $_REQUEST['limit'] : 20;
    	$page = ( !empty($_REQUEST['pno']) ) ? $_REQUEST['pno']:1;
    	$offset = ( $page > 1 ) ? ($page-1)*$limit : 0;
    	$args = array('limit'=>$limit, 'offset'=>$offset);
    	$coupons_mine_count = EM_Coupons::count( array('owner'=>get_current_user_id()) );
    	$coupons_all_count = current_user_can('manage_others_bookings') ? EM_Coupons::count():0;
    	if( !empty($_REQUEST['view']) && $_REQUEST['view'] == 'others' && current_user_can('manage_others_bookings') ){
    		$coupons = EM_Coupons::get( $args );
    		$coupons_count = $coupons_all_count;
    	}else{
    		$coupons = EM_Coupons::get( array_merge($args, array('owner'=>get_current_user_id())) );
    		$coupons_count = $coupons_mine_count;
    	}
    	?>
		<div class='wrap'>
			<h1><?php _e('Edit Coupons','em-pro'); ?>
				<a href="<?php echo add_query_arg(array('action'=>'edit')); ?>" class="page-title-action"><?php esc_html_e('Add New','events-manager'); ?></a>
			</h1>
			<?php echo $EM_Notices; ?>
			<form id='coupons-filter' method='post' action=''>
				<input type='hidden' name='pno' value='<?php echo $page ?>' />
				<div class="tablenav">			
					<div class="alignleft actions">
						<div class="subsubsub">
							<a href='<?php echo em_add_get_params($_SERVER['REQUEST_URI'], array('view'=>null, 'pno'=>null)); ?>' <?php echo ( empty($_REQUEST['view']) ) ? 'class="current"':''; ?>><?php echo sprintf( __( 'My %s', 'events-manager'), __('Coupons','em-pro')); ?> <span class="count">(<?php echo $coupons_mine_count; ?>)</span></a>
							<?php if( current_user_can('manage_others_bookings') ): ?>
							&nbsp;|&nbsp;
							<a href='<?php echo em_add_get_params($_SERVER['REQUEST_URI'], array('view'=>'others', 'pno'=>null)); ?>' <?php echo ( !empty($_REQUEST['view']) && $_REQUEST['view'] == 'others' ) ? 'class="current"':''; ?>><?php echo sprintf( __( 'All %s', 'events-manager'), __('Coupons','em-pro')); ?> <span class="count">(<?php echo $coupons_all_count; ?>)</span></a>
							<?php endif; ?>
						</div>
					</div>
					<?php
					if ( $coupons_count >= $limit ) {
						$coupons_nav = Contexis\Events\Admin\Pagination::paginate( $coupons_count, $limit, $page );
						echo $coupons_nav;
					}
					?>
				</div>
				<?php if ( $coupons_count > 0 ) : ?>
				<table class='widefat'>
					<thead>
						<tr>
							<th><?php esc_html_e('Name', 'events-manager') ?></th>
							<th><?php _e('Code', 'em-pro') ?></th>
							<th><?php _e('Created By', 'em-pro') ?></th>
							<th><?php _e('Description', 'em-pro') ?></th>  
							<th><?php _e('Discount', 'em-pro') ?></th>   
							<th><?php _e('Uses', 'em-pro') ?></th>       
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th><?php esc_html_e('Name', 'events-manager') ?></th>
							<th><?php _e('Code', 'em-pro') ?></th>
							<th><?php _e('Created By', 'em-pro') ?></th>
							<th><?php _e('Description', 'em-pro') ?></th>  
							<th><?php _e('Discount', 'em-pro') ?></th>   
							<th><?php _e('Uses', 'em-pro') ?></th>
						</tr>
					</tfoot>
					<tbody>
						<?php foreach ($coupons as $EM_Coupon) : ?>	
							<tr>
								<td>
									<a href='<?php echo admin_url('edit.php?post_type='.EM_POST_TYPE_EVENT.'&amp;page=events-manager-coupons&amp;action=edit&amp;coupon_id='.$EM_Coupon->coupon_id); ?>'><?php echo $EM_Coupon->coupon_name ?></a>
									<div class="row-actions">
										<span class="trash"><a class="submitdelete" href="<?php echo add_query_arg(array('coupon_id'=>$EM_Coupon->coupon_id,'action'=>'coupon_delete','_wpnonce'=>wp_create_nonce('coupon_delete_'.$EM_Coupon->coupon_id))) ?>"><?php _e('Delete','em-pro')?></a></span>
									</div>
								</td>
								<td class="code"><?php echo esc_html($EM_Coupon->coupon_code); ?></td>
								<td><a href="<?php echo admin_url('user-edit.php?user_id='.$EM_Coupon->get_person()->ID); ?>"><?php echo $EM_Coupon->get_person()->user_nicename; ?></a>
								
								</td>
								<td><?php echo esc_html($EM_Coupon->coupon_description); ?></td>  
								<td><?php echo $EM_Coupon->get_discount_text(); ?></td>            
								<td>
									<a href='<?php echo admin_url('edit.php?post_type='.EM_POST_TYPE_EVENT.'&amp;page=events-manager-coupons&amp;action=view&amp;coupon_id='.$EM_Coupon->coupon_id); ?>'>
									<?php 
									if( !empty($EM_Coupon->coupon_max) ){
										echo esc_html($EM_Coupon->get_count() .' / '. $EM_Coupon->coupon_max);
									}else{
										echo esc_html($EM_Coupon->get_count());
									}
									?>
									</a>
								</td>                 
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
				<?php else: ?>
				<br class="clear" />
				<p><?php _e('No coupons have been inserted yet!', 'em-pro') ?></p>
				<?php endif; ?>
				
				<?php if ( !empty($coupons_nav) ) echo '<div class="tablenav">'. $coupons_nav .'</div>'; ?>
			</form>

		</div> <!-- wrap -->
		<?php
	}
	
	static function view_page(){
		global $EM_Notices, $EM_Coupon, $wpdb;
		//check that user can access this page
		if( is_object($EM_Coupon) && !$EM_Coupon->can_manage('manage_bookings','manage_others_bookings') ){
			?>
			<div class="wrap"><h2><?php esc_html_e('Unauthorized Access','events-manager'); ?></h2><p><?php echo sprintf(esc_html('You do not have the rights to manage this %s.','events-manager'),__('coupon','em-pro')); ?></p></div>
			<?php
			return false;
		}elseif( !is_object($EM_Coupon) ){
			$EM_Coupon = new EM_Coupon();
		}
		$limit = ( !empty($_GET['limit']) ) ? $_GET['limit'] : 20;//Default limit
		$page = ( !empty($_GET['pno']) ) ? $_GET['pno']:1;
		$offset = ( $page > 1 ) ? ($page-1)*$limit : 0;
		/* @todo change how coupon-booking relations are stored */
		$coupon_search = str_replace('a:1:{', '', serialize(array('coupon_code'=>$EM_Coupon->coupon_code)));
		$coupon_search = substr($coupon_search, 0, strlen($coupon_search)-1 );
		$bookings = $wpdb->get_col('SELECT booking_id FROM '.EM_BOOKINGS_TABLE." WHERE booking_meta LIKE '%{$coupon_search}%' LIMIT {$limit} OFFSET {$offset}");
		//FIXME : coupon count not syncing correctly, using this as a fallback
		$coupons_count = $EM_Coupon->recount();
		$bookings_count = 0;
		$EM_Bookings = array();
		foreach($bookings as $booking_id){ 
			$EM_Booking = EM_Booking::find($booking_id);
			if( !empty($EM_Booking->booking_meta['coupon']) ){
				$coupon = new EM_Coupon($EM_Booking->booking_meta['coupon']);
				if($EM_Coupon->coupon_code == $coupon->coupon_code && $EM_Coupon->coupon_id == $coupon->coupon_id){
					$bookings_count++;
					$EM_Bookings[] = $EM_Booking;
				}
			}
		}
		?>
		<div class='wrap nosubsub'>
			<h1><?php _e('Coupon Usage History','em-pro'); ?></h1>
			<?php echo $EM_Notices; ?>
			<p><?php echo sprintf(__('You are viewing the details of coupon %s - <a href="%s">edit</a>','em-pro'),'<code>'.$EM_Coupon->coupon_code.'</code>', add_query_arg(array('action'=>'edit'))); ?></p>
			<p>
				<strong><?php echo __('Uses', 'em-pro'); ?>:</strong> 
				<?php
				if( !empty($EM_Coupon->coupon_max) ){
					echo esc_html($coupons_count .' / '. $EM_Coupon->coupon_max);
				}else{
					echo esc_html($coupons_count .'/'. __('Unlimited','em-pro'));
				}
				?>
			</p>
			<?php if ( $coupons_count >= $limit ) : ?>
			<div class='tablenav'>
				<?php 
				$bookings_nav = Contexis\Events\Admin\Pagination::paginate($coupons_count, $limit, $page, array());
				echo $bookings_nav;
				?>
				<div class="clear"></div>
			</div>
			<?php endif; ?>
			<div class="clear"></div>
			<?php if ( $bookings_count > 0 ) : ?>
			<div class='table-wrap'>
				<table id='dbem-bookings-table' class='widefat post '>
					<thead>
						<tr>
							<th class='manage-column' scope='col'><?php esc_html_e('Event', 'events-manager'); ?></th>
							<th class='manage-column' scope='col'><?php esc_html_e('Booker', 'events-manager'); ?></th>
							<th class='manage-column' scope='col'><?php esc_html_e('Spaces', 'events-manager'); ?></th>
							<th><?php _e('Original Total Price','em-pro'); ?></th>
							<th><?php _e('Coupon Discount','em-pro'); ?></th>
							<th><?php _e('Final Price','em-pro'); ?></th>
							<th>&nbsp;</th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th class='manage-column' scope='col'><?php esc_html_e('Event', 'events-manager'); ?></th>
							<th class='manage-column' scope='col'><?php esc_html_e('Booker', 'events-manager'); ?></th>
							<th class='manage-column' scope='col'><?php esc_html_e('Spaces', 'events-manager'); ?></th>
							<th><?php _e('Original Total Price','em-pro'); ?></th>
							<th><?php _e('Coupon Discount','em-pro'); ?></th>
							<th><?php _e('Final Price','em-pro'); ?></th>
							<th>&nbsp;</th>
						</tr>
					</tfoot>
					<tbody>
						<?php
						foreach($EM_Bookings as $EM_Booking){ /* @var EM_Booking $EM_Booking */
							$original_price = $EM_Booking->get_price_post_taxes(false, false);
							$base_price = $EM_Coupon->coupon_tax == 'pre' ? $EM_Booking->get_price_pre_taxes(false, false) : $original_price;
							?>
							<tr>
								<td>
									<?php echo $EM_Booking->output('#_BOOKINGSLINK') ?>
									<?php if( in_array($EM_Booking->booking_status, array(2,3)) ) echo '<p class="description">('. $EM_Booking->get_status() . ')</p> '; ?>
								</td>
								<td><a href="<?php echo EM_ADMIN_URL; ?>&amp;page=events-manager-bookings&amp;person_id=<?php echo $EM_Booking->person_id; ?>"><?php echo $EM_Booking->person->get_name() ?></a></td>
								<td><?php echo $EM_Booking->get_spaces() ?></td>
								<td><?php echo $EM_Booking->get_price_post_taxes(true, false); ?></td>
								<td><?php echo \Contexis\Events\Intl\Price::format($EM_Coupon->get_discount($base_price)); ?> <p class="description">(<?php echo $EM_Coupon->get_discount_text(); ?>)</p></td>
								<td><?php echo \Contexis\Events\Intl\Price::format($EM_Booking->get_price()); ?></td>
								<td>										
									<?php
									$edit_url = em_add_get_params($EM_Booking->get_admin_url());
									?>
									<?php if( $EM_Booking->can_manage() ): ?>
									<a class="em-bookings-edit" href="<?php echo $edit_url; ?>"><?php esc_html_e('Edit/View','events-manager'); ?></a>
									<?php endif; ?>
								</td>
							</tr>
							<?php
						}
						?>
					</tbody>
				</table>
			</div> <!-- table-wrap -->
			<?php else: ?>
			<p><?php _e('Your coupon hasn\'t been used yet!','em-pro'); ?></p>
			<?php endif; ?>
		</div> <!-- wrap -->
		<?php
	}
	
	static function edit_form(){
		global $EM_Notices, $EM_Coupon;
		//check that user can access this page
		if( is_object($EM_Coupon) && !$EM_Coupon->can_manage('manage_bookings','manage_others_bookings') ){
			?>
			<div class="wrap"><h2><?php esc_html_e('Unauthorized Access','events-manager'); ?></h2><p><?php echo sprintf(esc_html('You do not have the rights to manage this %s.','events-manager'),__('coupon','em-pro')); ?></p></div>
			<?php
			return false;
		}elseif( !is_object($EM_Coupon) ){
			$EM_Coupon = new EM_Coupon();
		}
		$required = "<i>(".__('required','em-pro').")</i>";
		?>
		<div class='wrap nosubsub'>
			<h1><?php _e('Edit Coupon','em-pro'); ?></h1>
			<?php echo $EM_Notices; ?>
			<form id='coupon-form' method='post' action=''>
				<input type='hidden' name='action' value='coupon_save' />
				<input type='hidden' name='_wpnonce' value='<?php echo wp_create_nonce('coupon_save'); ?>' />
				<input type='hidden' name='coupon_id' value='<?php echo $EM_Coupon->coupon_id ?>'/>
				<table class="form-table">
					<tbody>
    					<tr valign="top">
    						<th scope="row"><?php _e('Coupon Availability', 'em-pro') ?></th>
    						<td>
    							<select name="coupon_availability">
    								<option value=""><?php _e('Only on specific events that I own', 'em-pro') ?></option>
    								<option value="eventwide" <?php if($EM_Coupon->coupon_eventwide && !$EM_Coupon->coupon_sitewide) echo 'selected="selected"'; ?>><?php _e('All my events', 'em-pro') ?></option>
    								<?php if( current_user_can('manage_others_bookings') || is_super_admin() ): ?>
    								<option value="sitewide" <?php if($EM_Coupon->coupon_sitewide) echo 'selected="selected"'; ?>><?php _e('All events on this site', 'em-pro'); ?></option>
    								<?php endif; ?>
    							</select>
    							<p class="description"><?php _e('Choose whether to allow this coupon to be used only on events you choose, all your events or all events on this site.','em-pro'); ?></p>
    						</td>
    					</tr>
					<tr valign="top">
						<th scope="row"><label for="coupon_code"><?php _e('Coupon Code', 'em-pro') ?></label></th>
							<td><input required onblur="this.checkValidity();" pattern="[A-Za-z0-9]" class="regular-text code" type="text" name="coupon_code" value="<?php echo esc_attr($EM_Coupon->coupon_code); ?>" />
							<p class="description"><?php _e('This is the code you give to users for them to use when booking.','em-pro'); ?></p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="coupon_name"><?php esc_html_e('Name', 'events-manager') ?></label></th>
							<td><input required onblur="this.checkValidity();" class="regular-text" type="text" name="coupon_name" value="<?php echo esc_attr($EM_Coupon->coupon_name); ?>" />
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="coupon_description"><?php esc_html_e('Description', 'events-manager') ?></label></th>
							<td><input class="regular-text" type="text" name="coupon_description" value="<?php echo esc_attr($EM_Coupon->coupon_description); ?>" />
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="coupon_max"><?php _e('Total Coupons', 'em-pro') ?></label></th>
							<td><input class="regular-text" type="number" name="coupon_max" value="<?php echo esc_attr($EM_Coupon->coupon_max); ?>" />
							<br />
							<p class="description"><?php _e('If set, this coupon will only be valid that many times.','em-pro'); ?></p>
						</td>
					</tr>
					<tbody class="em-date-range">
						<tr valign="top">
							<th scope="row"><?php esc_html_e('Start Date', 'events-manager') ?></th>
							<td>
								<input type="date" class="regular-text" />
								<input type="hidden" class="em-date-input" name="coupon_start" value="<?php echo esc_attr(substr($EM_Coupon->coupon_start,0,10)); ?>" />
								<br />
								<p class="description"><?php _e('Coupons will only be valid from this date onwards.','em-pro'); ?></p>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php esc_html_e('End Date', 'events-manager') ?></th>
							<td>
								<input type="date" class="regular-text" />
								<input type="hidden" class="em-date-input" name="coupon_end" value="<?php echo esc_attr(substr($EM_Coupon->coupon_end,0,10)); ?>" />
								<br />
								<p class="description"><?php _e('Coupons will not be valid after this date.','em-pro'); ?></p>
							</td>
						</tr>
					</tbody>
					<tr valign="top">
						<th scope="row"><?php _e('Discount Type', 'em-pro') ?></th>
						<td>
							<select name="coupon_type">
								<option value="%" <?php echo ($EM_Coupon->coupon_type == '%')?'selected="selected"':''; ?>><?php _e('Percentage','em-pro'); ?></option>
								<option value="#" <?php echo ($EM_Coupon->coupon_type == '#')?'selected="selected"':''; ?>><?php _e('Fixed Amount', 'em-pro'); ?></option>
							</select>
							<br />
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e('Apply Before/After Tax', 'em-pro') ?></th>
						<td>
							<select name="coupon_tax" <?php if(get_option('booking_tax_rate', 0) == 0) echo "disabled" ?> >
								<option value="pre" <?php echo ($EM_Coupon->coupon_tax == 'pre')?'selected="selected"':''; ?>><?php _e('Before','em-pro'); ?></option>
								<option value="post" <?php echo ($EM_Coupon->coupon_tax == 'post')?'selected="selected"':''; ?>><?php _e('After', 'em-pro'); ?></option>
							</select>
							<br />
							<p class="description"><?php _e('Choose whether to apply this discount before or after tax has been added, if applicable.','em-pro'); ?></p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e('Discount Amount', 'em-pro') ?></th>
							<td><input required onblur="this.checkValidity();" class="regular-text" type="number" name="coupon_discount" value="<?php echo esc_attr($EM_Coupon->coupon_discount); ?>" />
							<br />
							<p class="description"><?php _e('Enter a number here only, decimals accepted.','em-pro'); ?></p>
						</td>
					</tr>
					</tbody>
				</table>				
				<p class="submit">
				<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
				</p>
			</form>
		</div> <!-- wrap -->
		<?php
    }    
}
EM_Coupons_Admin::init();