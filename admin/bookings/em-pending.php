<?php

/**
 * Generates a "widget" table of pending bookings with some quick admin operation options. 
 * If event id supplied then only pending bookings for that event will show.
 * 
 * @param int $event_id
 */
function em_bookings_pending_table($event_id = false){
	global $EM_Event;
	
	$ticket = new \Contexis\Events\Tickets\Ticket();
	if( get_option('dbem_bookings_approval') == 0 ){
		return false;
	}
	
	$action_scope = ( !empty($_REQUEST['em_obj']) && $_REQUEST['em_obj'] == 'em_bookings_pending_table' );
	$action = ( $action_scope && !empty($_GET ['action']) ) ? $_GET ['action']:'';
	$order = ( $action_scope && !empty($_GET ['order']) ) ? $_GET ['order']:'ASC';
	$limit = ( $action_scope && !empty($_GET['limit']) ) ? $_GET['limit'] : 20;//Default limit
	$page = ( $action_scope && !empty($_GET['pno']) ) ? $_GET['pno']:1;
	$offset = ( $action_scope && $page > 1 ) ? ($page-1)*$limit : 0;
	
	if( is_object($ticket) ){
		$EM_Bookings = $ticket->get_bookings()->get_pending_bookings();
	}else{
		if( is_object($EM_Event) ){
			$EM_Bookings = $EM_Event->get_bookings()->get_pending_bookings();
		}else{
			//To optimize performance, we can do one query here for all pending bookings to show.
			$EM_Bookings = EM_Bookings::get(array('status'=>0));
			$events = array();
			//Now let's create events and bookings for this:
			foreach($EM_Bookings->bookings as $EM_Booking){
				//create event
				if( !array_key_exists($EM_Booking->event_id,$events) ){
					$events[$EM_Booking->event_id] = new EM_Event($EM_Booking->event_id);
				}
			}
		}
	}
	$bookings_count = (is_array($EM_Bookings->bookings)) ? count($EM_Bookings->bookings):0;
	?>
		<div class='wrap em_bookings_pending_table em_obj'>
			<form id='bookings-filter' method='get' action='<?php bloginfo('wpurl') ?>/wp-admin/edit.php'>
				<input type="hidden" name="em_obj" value="em_bookings_pending_table" />
				<!--
				<ul class="subsubsub">
					<li>
						<a href='edit.php?post_type=post' class="current">All <span class="count">(1)</span></a> |
					</li>
				</ul>
				<p class="search-box">
					<label class="screen-reader-text" for="post-search-input"><?php _e('Search', 'events'); ?>:</label>
					<input type="text" id="post-search-input" name="em_search" value="<?php echo (!empty($_GET['em_search'])) ? esc_attr($_GET['em_search']):''; ?>" />
					<input type="submit" value="<?php _e('Search', 'events'); ?>" class="button" />
				</p>
				-->
				<?php if ( $bookings_count >= $limit ) : ?>
				<div class='tablenav'>
					
					<?php 
					if ( $bookings_count >= $limit ) {
						$bookings_nav = Contexis\Events\Admin\Pagination::paginate( $bookings_count, $limit, $page, array('em_ajax'=>0, 'em_obj'=>'em_bookings_pending_table'));
						echo $bookings_nav;
					}
					?>
					<div class="clear"></div>
				</div>
				<?php endif; ?>
				<div class="clear"></div>
				<?php if( $bookings_count > 0 ): ?>
				<div class='table-wrap'>
				<table id='dbem-bookings-table' class='widefat post pending'>
					<thead>
						<tr>
							<th class='manage-column column-cb check-column' scope='col'>
								<input class='select-all' type="checkbox" value='1' />
							</th>
							<th class='manage-column' scope='col'><?php _e('Booker', 'events'); ?></th>
							<?php if( !is_object($EM_Event) && !is_object($ticket) ): ?>
							<th class='manage-column' scope="col"><?php _e('Event', 'events'); ?></th>
							<?php endif; ?>
							<th class='manage-column' scope='col'><?php _e('E-mail', 'events'); ?></th>
							<th class='manage-column' scope='col'><?php _e('Phone number', 'events'); ?></th>
							<th class='manage-column' scope='col'><?php _e('Spaces', 'events'); ?></th>
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
									<td><a href="<?php echo EM_ADMIN_URL; ?>&amp;page=events-bookings&amp;person_id=<?php echo $EM_Booking->person->ID; ?>"><?php echo $EM_Booking->person->get_name() ?></a></td>
									<?php if( !is_object($EM_Event) && !is_object($ticket) ): ?>
									<td><a href="<?php echo EM_ADMIN_URL; ?>&amp;page=events-bookings&amp;event_id=<?php echo $EM_Booking->event_id; ?>"><?php echo $events[$EM_Booking->event_id]->name ?></a></td>
									<?php endif; ?>
									<td><?php echo $EM_Booking->person->user_email ?></td>
									<td><?php echo $EM_Booking->person->phone ?></td>
									<td><?php echo $EM_Booking->get_spaces() ?></td>
									<td>
										<?php
										$approve_url = add_query_arg(['action'=>'bookings_approve', 'booking_id'=>$EM_Booking->booking_id], $_SERVER['REQUEST_URI']);
										$reject_url = add_query_arg(['action'=>'bookings_reject', 'booking_id'=>$EM_Booking->booking_id], $_SERVER['REQUEST_URI']);
										$delete_url = add_query_arg(['action'=>'bookings_delete', 'booking_id'=>$EM_Booking->booking_id], $_SERVER['REQUEST_URI']);
										?>
										<a class="em-bookings-approve" href="<?php echo $approve_url ?>"><?php _e('Approve','events'); ?></a> |
										<a class="em-bookings-reject" href="<?php echo $reject_url ?>"><?php _e('Reject','events'); ?></a> |
										<span class="trash"><a class="em-bookings-delete" href="<?php echo $delete_url ?>"><?php _e('Delete','events'); ?></a></span> |
										
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
					<?php _e('No pending bookings.', 'events'); ?>
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