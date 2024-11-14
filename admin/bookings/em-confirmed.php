<?php

/**
 * Generates a "widget" table of confirmed bookings for a specific event.
 * 
 * @param int $event_id
 */
function em_bookings_confirmed_table(){
	global $EM_Event;
	
	$ticket = new \Contexis\Events\Tickets\Ticket();
	$action_scope = ( !empty($_REQUEST['em_obj']) && $_REQUEST['em_obj'] == 'em_bookings_confirmed_table' );
	$limit = ( $action_scope && !empty($_GET['limit']) ) ? $_GET['limit'] : 20;//Default limit
	$page = ( $action_scope && !empty($_GET['pno']) ) ? $_GET['pno']:1;
	$offset = ( $action_scope && $page > 1 ) ? ($page-1)*$limit : 0;
	
	if( is_object($ticket) ){
		$EM_Bookings = $ticket->get_bookings()->get_bookings();
	}else{
		if( is_object($EM_Event) ){
			$EM_Bookings = $EM_Event->get_bookings()->get_bookings();
		}else{
			return false;
		}
	}
	$bookings_count = (is_array($EM_Bookings->bookings)) ? count($EM_Bookings->bookings):0;
	?>
		<div class='wrap em_bookings_pending_table em_obj'>
			<form id='bookings-filter' method='get' action='<?php bloginfo('wpurl') ?>/wp-admin/edit.php'>
				<input type="hidden" name="em_obj" value="em_bookings_pending_table" />
				
				<?php if ( $bookings_count >= $limit ) : ?>
				<div class='tablenav'>
					
					<?php 
					if ( $bookings_count >= $limit ) {
						$bookings_nav = Contexis\Events\Admin\Pagination::paginate($bookings_count, $limit, $page, array('em_ajax'=>0, 'em_obj'=>'em_bookings_confirmed_table'));
						echo $bookings_nav;
					}
					?>
					<div class="clear"></div>
				</div>
				<?php endif; ?>
				<div class="clear"></div>
				<?php if( $bookings_count > 0 ): ?>
				<div class='table-wrap'>
				<table id='dbem-bookings-table' class='widefat post '>
					<thead>
						<tr>
							<th class='manage-column column-cb check-column' scope='col'>
								<input class='select-all' type="checkbox" value='1' />
							</th>
							<th class='manage-column' scope='col'><?php _e('Booker', 'events-manager'); ?></th>
							<th class='manage-column' scope='col'><?php _e('E-mail', 'events-manager'); ?></th>
							<th class='manage-column' scope='col'><?php _e('Phone number', 'events-manager'); ?></th>
							<th class='manage-column' scope='col'><?php _e('Spaces', 'events-manager'); ?></th>
							<th class='manage-column' scope='col'>&nbsp;</th>
						</tr>
					</thead>
					<tbody>
						<?php 
						$rowno = 0;
						$event_count = 0;
						foreach ($EM_Bookings->bookings as $EM_Booking) {
							if( ($rowno < $limit || empty($limit)) && ($event_count >= $offset || $offset === 0) ) {
								$rowno++;
								?>
								<tr>
									<th scope="row" class="check-column" style="padding:7px 0px 7px;"><input type='checkbox' value='<?php echo $EM_Booking->booking_id ?>' name='bookings[]'/></th>
									<td><a href="<?php echo EM_ADMIN_URL; ?>&amp;page=events-manager-bookings&amp;person_id=<?php echo $EM_Booking->person_id; ?>"><?php echo $EM_Booking->person->get_name() ?></a></td>
									<td><?php echo $EM_Booking->person->user_email ?></td>
									<td><?php echo $EM_Booking->person->phone ?></td>
									<td><?php echo $EM_Booking->get_spaces() ?></td>
									<td>										
										<?php
										$unapprove_url = add_query_arg(['action'=>'bookings_unapprove', 'booking_id'=>$EM_Booking->booking_id], $_SERVER['REQUEST_URI']);
										$reject_url = add_query_arg(['action'=>'bookings_reject', 'booking_id'=>$EM_Booking->booking_id], $_SERVER['REQUEST_URI']);
										$delete_url = add_query_arg(['action'=>'bookings_delete', 'booking_id'=>$EM_Booking->booking_id], $_SERVER['REQUEST_URI']);
										$cancel_url = add_query_arg(['action'=>'bookings_cancel', 'booking_id'=>$EM_Booking->booking_id], $_SERVER['REQUEST_URI']);
										$edit_url = add_query_arg(['booking_id'=>$EM_Booking->booking_id, 'em_ajax'=>null, 'em_obj'=>null], $_SERVER['REQUEST_URI']);
										?>
										<?php if( current_user_can('manage_bookings') && get_option('dbem_bookings_approval') ): ?>
										<a class="em-bookings-unapprove" href="<?php echo $unapprove_url ?>"><?php _e('Unapprove','events-manager'); ?></a> |
										<?php else: ?>
										<a class="em-bookings-cancel" href="<?php echo $cancel_url ?>"><?php _e('Cancel','events-manager'); ?></a> |
										<?php endif; ?>
										<span class="trash"><a class="em-bookings-delete" href="<?php echo $delete_url ?>"><?php _e('Delete','events-manager'); ?></a></span> |
										<a class="em-bookings-edit" href="<?php echo $edit_url; ?>"><?php _e('Edit/View','events-manager'); ?></a>
									</td>
								</tr>
								<?php
							}
							$event_count++;
						}
						?>
					</tbody>
				</table>
				</div>
				<?php else: ?>
					<?php _e('No confirmed bookings.', 'events-manager'); ?>
				<?php endif; ?>
			</form>
			<?php if( !empty($bookings_nav) && $EM_Bookings >= $limit ) : ?>
			<div class='tablenav'>
				<?php echo $bookings_nav; ?>
				<div class="clear"></div>
			</div>
			<?php endif; ?>
		</div>	
	<?php
	
}
?>