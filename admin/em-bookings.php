<?php 
/**
 * Deprecated - see em-actions.php - this will be removed at some point in 6.0
 * Check if there's any admin-related actions to take for bookings. All actions are caught here.
 * @return null
 * @todo remove in 6.0
 */
function em_admin_actions_bookings() {
	global $EM_Event;	
	if( is_object($EM_Event) && !empty($_REQUEST['action']) ){
		if( $_REQUEST['action'] == 'bookings_export_csv' && wp_verify_nonce($_REQUEST['_wpnonce'],'bookings_export_csv') ){
			$EM_Event->get_bookings()->export_csv();
			exit();
		}
	}
}
add_action('admin_init','em_admin_actions_bookings',100);

/**
 * Decide what content to show in the bookings section. 
 */
function em_bookings_page(){
	//First any actions take priority
	do_action('em_bookings_admin_page');
	if( !empty($_REQUEST['_wpnonce']) ){ $_REQUEST['_wpnonce'] = $_GET['_wpnonce'] = $_POST['_wpnonce'] = esc_attr($_REQUEST['_wpnonce']); } //XSS fix just in case here too
	if( !empty($_REQUEST['action']) && substr($_REQUEST['action'],0,7) != 'booking' ){ //actions not starting with booking_
		do_action('em_bookings_'.$_REQUEST['action']);
	}elseif( !empty($_REQUEST['booking_id']) ){
		em_bookings_single();
	}elseif( !empty($_REQUEST['person_id']) ){
		em_bookings_person();
	}elseif( !empty($_REQUEST['ticket_id']) ){
		em_bookings_ticket();
	}elseif( !empty($_REQUEST['event_id']) ){
		em_bookings_event();
	}else{
		em_bookings_dashboard();
	}
}

/**
 * Generates the bookings dashboard, showing information on all events 
 */
function em_bookings_dashboard(){
	global $EM_Notices;
	?>
	<div class='wrap em-bookings-dashboard'>
		<?php if( is_admin() ): ?>
  		<h1><?php esc_html_e('Event Bookings Dashboard', 'events-manager'); ?></h1>
  		<?php else: echo $EM_Notices; ?>
  		<?php endif; ?>
  		<div class="em-bookings-recent">
			<h2><?php esc_html_e('Recent Bookings','events-manager'); ?></h2>	
	  		<?php
			$EM_Bookings_Table = new EM_Bookings_Table();
			$EM_Bookings_Table->status = get_option('dbem_bookings_approval') ? 'needs-attention':'confirmed';
			$EM_Bookings_Table->output();
	  		?>
  		</div>
  		<br class="clear" />
  		<div class="em-bookings-events">
			<h2><?php esc_html_e('Events With Bookings Enabled','events-manager'); ?></h2>		
			<?php em_bookings_events_table(); ?>
			<?php do_action('em_bookings_dashboard'); ?>
		</div>
	</div>
	<?php		
}

/**
 * Shows all booking data for a single event 
 */
function em_bookings_event(){
	global $EM_Event,$EM_Person,$EM_Notices;
	//check that user can access this page
	if( is_object($EM_Event) && !$EM_Event->can_manage('manage_bookings','manage_others_bookings') ){
		?>
		<div class="wrap"><h2><?php esc_html_e('Unauthorized Access','events-manager'); ?></h2><p><?php esc_html_e('You do not have the rights to manage this event.','events-manager'); ?></p></div>
		<?php
		return false;
	}
	$header_button_classes = is_admin() ? 'page-title-action':'button add-new-h2';
	?>
	<div class='wrap'>
		<?php if( is_admin() ): ?><h1 class="wp-heading-inline"><?php else: ?><h2><?php endif; ?>		
  			<?php echo sprintf(__('Manage %s Bookings', 'events-manager'), "'{$EM_Event->event_name}'"); ?>
  		<?php if( is_admin() ): ?></h1><?php endif; ?>
  			<a href="<?php echo $EM_Event->get_permalink(); ?>" class="<?php echo $header_button_classes; ?>"><?php echo sprintf(__('View %s','events-manager'), __('Event', 'events-manager')) ?></a>
  			<a href="<?php echo $EM_Event->get_edit_url(); ?>" class="<?php echo $header_button_classes; ?>"><?php echo sprintf(__('Edit %s','events-manager'), __('Event', 'events-manager')) ?></a>
  			<?php if( locate_template('plugins/events-manager/templates/csv-event-bookings.php', false) ): //support for legacy template ?>
  			<a href='<?php echo EM_ADMIN_URL ."&amp;page=events-manager-bookings&amp;action=bookings_export_csv&amp;_wpnonce=".wp_create_nonce('bookings_export_csv')."&amp;event_id=".$EM_Event->event_id ?>' class="<?php echo $header_button_classes; ?>"><?php esc_html_e('Export CSV','events-manager')?></a>
  			<?php endif; ?>
  			<?php do_action('em_admin_event_booking_options_buttons'); ?>
		<?php if( !is_admin() ): ?></h2><?php else: ?><hr class="wp-header-end" /><?php endif; ?>
  		<?php if( !is_admin() ) echo $EM_Notices; ?>  
		<div>
			<p><strong><?php esc_html_e('Event Name','events-manager'); ?></strong> : <?php echo esc_html($EM_Event->event_name); ?></p>
			<p>
				<strong><?php esc_html_e('Availability','events-manager'); ?></strong> : 
				<?php echo $EM_Event->get_bookings()->get_booked_spaces() . '/'. $EM_Event->get_spaces() ." ". __('Spaces confirmed','events-manager'); ?>
				<?php if( get_option('dbem_bookings_approval_reserved') ): ?>
				, <?php echo $EM_Event->get_bookings()->get_available_spaces() . '/'. $EM_Event->get_spaces() ." ". __('Available spaces','events-manager'); ?>
				<?php endif; ?>
			</p>
			<p>
				<strong><?php esc_html_e('Date','events-manager'); ?></strong> : 
				<?php echo $EM_Event->output_dates(). ' @ ' . $EM_Event->output_times(); ?>						
			</p>
			<p>
				<strong><?php esc_html_e('Location','events-manager'); ?></strong> :
				<?php if( $EM_Event->location_id == 0 ): ?>
				<em><?php esc_html_e('No Location', 'events-manager'); ?></em>
				<?php else: ?>
				<a class="row-title" href="<?php echo admin_url(); ?>post.php?action=edit&amp;post=<?php echo $EM_Event->get_location()->post_id ?>"><?php echo ($EM_Event->get_location()->location_name); ?></a>
				<?php endif; ?>
			</p>
		</div>
		<h2><?php esc_html_e('Bookings','events-manager'); ?></h2>
		<?php
		$EM_Bookings_Table = new EM_Bookings_Table();
		$EM_Bookings_Table->status = 'all';
		$EM_Bookings_Table->output();
  		?>
		<?php do_action('em_bookings_event_footer', $EM_Event); ?>
	</div>
	<?php
}

/**
 * Shows a ticket view
 */
function em_bookings_ticket(){
	global $EM_Ticket,$EM_Notices;
	$EM_Event = $EM_Ticket->get_event();
	//check that user can access this page
	if( is_object($EM_Ticket) && !$EM_Ticket->can_manage() ){
		?>
		<div class="wrap"><h2><?php esc_html_e('Unauthorized Access','events-manager'); ?></h2><p><?php esc_html_e('You do not have the rights to manage this ticket.','events-manager'); ?></p></div>
		<?php
		return false;
	}
	$header_button_classes = is_admin() ? 'page-title-action':'button add-new-h2';
	?>
	<div class='wrap'>
		<?php if( is_admin() ): ?><h1 class="wp-heading-inline"><?php else: ?><h2><?php endif; ?>
  			<?php echo sprintf(__('Ticket for %s', 'events-manager'), "'{$EM_Event->name}'"); ?>
  		<?php if( is_admin() ): ?></h1><?php endif; ?>
  			<a href="<?php echo $EM_Event->get_edit_url(); ?>" class="<?php echo $header_button_classes; ?>"><?php esc_html_e('View/Edit Event','events-manager') ?></a>
  			<a href="<?php echo $EM_Event->get_bookings_url(); ?>" class="<?php echo $header_button_classes; ?>"><?php esc_html_e('View Event Bookings','events-manager') ?></a>
  		
		<?php if( !is_admin() ): ?></h2><?php else: ?><hr class="wp-header-end" /><?php endif; ?>
  		<?php if( !is_admin() ) echo $EM_Notices; ?>
		<div>
			<table>
				<tr><td><?php echo __('Name','events-manager'); ?></td><td></td><td><?php echo $EM_Ticket->ticket_name; ?></td></tr>
				<tr><td><?php echo __('Description','events-manager'); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td></td><td><?php echo ($EM_Ticket->ticket_description) ? $EM_Ticket->ticket_description : '-'; ?></td></tr>
				<tr><td><?php echo __('Price','events-manager'); ?></td><td></td><td><?php echo ($EM_Ticket->ticket_price) ? $EM_Ticket->ticket_price : '-'; ?></td></tr>
				<tr><td><?php echo __('Spaces','events-manager'); ?></td><td></td><td><?php echo ($EM_Ticket->ticket_spaces) ? $EM_Ticket->ticket_spaces : '-'; ?></td></tr>
				<tr><td><?php echo __('Min','events-manager'); ?></td><td></td><td><?php echo ($EM_Ticket->ticket_min) ? $EM_Ticket->ticket_min : '-'; ?></td></tr>
				<tr><td><?php echo __('Max','events-manager'); ?></td><td></td><td><?php echo ($EM_Ticket->ticket_max) ? $EM_Ticket->ticket_max : '-'; ?></td></tr>
				<tr><td><?php echo __('Start','events-manager'); ?></td><td></td><td><?php echo ($EM_Ticket->ticket_start) ? $EM_Ticket->start()->formatDefault() : '-'; ?></td></tr>
				<tr><td><?php echo __('End','events-manager'); ?></td><td></td><td><?php echo ($EM_Ticket->ticket_end) ? $EM_Ticket->end()->formatDefault() : '-'; ?></td></tr>
				<?php do_action('em_booking_admin_ticket_row', $EM_Ticket); ?>
			</table>
		</div>
		<h2><?php esc_html_e('Bookings','events-manager'); ?></h2>
		<?php
		$EM_Bookings_Table = new EM_Bookings_Table();
		$EM_Bookings_Table->status = get_option('dbem_bookings_approval') ? 'needs-attention':'confirmed';
		$EM_Bookings_Table->output();
  		?>
		<?php do_action('em_bookings_ticket_footer', $EM_Ticket); ?>
	</div>
	<?php	
}

/**
 * Shows a single booking for a single person. 
 */
function em_bookings_single(){
	global $EM_Booking, $EM_Notices; /* @var $EM_Booking EM_Booking */
	//check that user can access this page
	if( is_object($EM_Booking) && !$EM_Booking->can_manage() ){
		?>
		<div class="wrap"><h2><?php esc_html_e('Unauthorized Access','events-manager'); ?></h2><p><?php esc_html_e('You do not have the rights to manage this event.','events-manager'); ?></p></div>
		<?php
		return false;
	}
	do_action('em_booking_admin', $EM_Booking);
	?>
	<div class='wrap' id="em-bookings-admin-booking">
		<?php if( is_admin() ): ?><h1><?php else: ?><h2><?php endif; ?>		
  			<?php esc_html_e('Edit Booking', 'events-manager'); ?>
		<?php if( !is_admin() ): ?></h2><?php else: ?></h1><?php endif; ?>
  		<?php if( !is_admin() ) echo $EM_Notices; ?>
  		<div class="metabox-holder">
	  		<div class="postbox-container" style="width:99.5%">
				<div class="">
					<h2 class="title">
						<?php esc_html_e( 'Booking Details', 'events-manager'); ?>
					</h2>
					<div class="">
						<?php
						
						$EM_Event = $EM_Booking->get_event();
						?>
						<div id="booking-admin" data-id="<?php echo $EM_Booking->booking_id ?>"></div>
						<?php do_action('em_bookings_admin_booking_event', $EM_Event); ?>
					</div>
				</div>
				
			
				<div id="em-booking-notes" class="">
					<h2 class="title">
						<?php esc_html_e( 'Booking Notes', 'events-manager'); ?>
					</h2>
					<div class="">
						<p><?php esc_html_e('You can add private notes below for internal reference that only event managers will see.','events-manager'); ?></p>
						<?php foreach( $EM_Booking->get_notes() as $note ):
							$user = new EM_Person($note['author']);
						?>
						<div>
							<?php echo sprintf(esc_html_x('%1$s - %2$s wrote','[Date] - [Name] wrote','events-manager'), date(get_option('date_format'), $note['timestamp']), $user->get_name()); ?>:
							<p style="background:#efefef; padding:5px;"><?php echo nl2br($note['note']); ?></p>
						</div>
						<?php endforeach; ?>
						<form method="post" action="" style="padding:5px;">
							<textarea class="widefat" rows="5" name="booking_note"></textarea>
							<input type="hidden" name="action" value="bookings_add_note" />
							<input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce('bookings_add_note'); ?>" />
							<input type="submit" class="em-button button-primary" value="<?php esc_html_e('Add Note', 'events-manager'); ?>" />
						</form>
					</div>
				</div>
				<?php do_action('em_bookings_single_metabox_footer', $EM_Booking); ?>
			</div>
		</div>
		<br style="clear:both;" />
		<?php do_action('em_bookings_single_footer', $EM_Booking); ?>
	</div>
	<?php
	
}

/**
 * Shows all bookings made by one person.
 */
function em_bookings_person(){	
	global $EM_Person, $EM_Notices;
	$EM_Person->get_bookings();
	$has_booking = false;
	foreach($EM_Person->get_bookings() as $EM_Booking){
		if($EM_Booking->can_manage('manage_bookings','manage_others_bookings')){
			$has_booking = true;
		}
	}
	if( !$has_booking && !current_user_can('manage_others_bookings') ){
		?>
		<div class="wrap"><h2><?php esc_html_e('Unauthorized Access','events-manager'); ?></h2><p><?php esc_html_e('You do not have the rights to manage this event.','events-manager'); ?></p></div>
		<?php
		return false;
	}
	$header_button_classes = is_admin() ? 'page-title-action':'button add-new-h2';
	?>
	<div class='wrap'>
		<?php if( is_admin() ): ?><h1 class="wp-heading-inline"><?php else: ?><h2><?php endif; ?>
  			<?php esc_html_e('Manage Person\'s Booking', 'events-manager'); ?>
  		<?php if( is_admin() ): ?></h1><?php endif; ?>
  			<?php if( current_user_can('edit_users') ) : ?>
  			<a href="<?php echo admin_url('user-edit.php?user_id='.$EM_Person->ID); ?>" class="<?php echo $header_button_classes; ?>"><?php esc_html_e('Edit User','events-manager') ?></a>
  			<?php endif; ?>
  			<?php if( current_user_can('delete_users') ) : ?>
  			<a href="<?php echo wp_nonce_url( admin_url("users.php?action=delete&amp;user=$EM_Person->ID"), 'bulk-users' ); ?>" class="<?php echo $header_button_classes; ?>"><?php esc_html_e('Delete User','events-manager') ?></a>
  			<?php endif; ?>
		<?php if( !is_admin() ): ?></h2><?php else: ?><hr class="wp-header-end" /><?php endif; ?>
  		<?php if( !is_admin() ) echo $EM_Notices; ?>
		<?php do_action('em_bookings_person_header'); ?>
  		<div id="poststuff" class="metabox-holder has-right-sidebar">
	  		<div id="post-body">
				<div id="post-body-content">
					<div id="event_name" class="stuffbox">
						<h3>
							<?php esc_html_e( 'Personal Details', 'events-manager'); ?>
						</h3>
						<div class="">
							<?php echo $EM_Person->display_summary(); ?>
						</div>
					</div> 
				</div>
			</div>
		</div>
		<br style="clear:both;" />
		<?php do_action('em_bookings_person_body_1'); ?>
		<h2><?php esc_html_e('Past And Present Bookings','events-manager'); ?></h2>
		<?php
		$EM_Bookings_Table = new EM_Bookings_Table();
		$EM_Bookings_Table->status = 'all';
		$EM_Bookings_Table->scope = 'all';
		$EM_Bookings_Table->output();
  		?>
		<?php do_action('em_bookings_person_footer', $EM_Person); ?>
	</div>
	<?php
}

function em_printable_booking_report() {
	global $EM_Event;
	//check that user can access this page
	if( isset($_GET['page']) && $_GET['page']=='events-manager-bookings' && isset($_GET['action']) && $_GET['action'] == 'bookings_report' && is_object($EM_Event)){
		if( is_object($EM_Event) && !$EM_Event->can_manage('edit_events','edit_others_events') ){
			?>
			<div class="wrap"><h2><?php esc_html_e('Unauthorized Access','events-manager'); ?></h2><p><?php esc_html_e('You do not have the rights to manage this event.','events-manager'); ?></p></div>
			<?php
			return false;
		}
		em_locate_template('templates/bookings-event-printable.php', true);
		die();
	}
} 
add_action('admin_init', 'em_printable_booking_report');
?>