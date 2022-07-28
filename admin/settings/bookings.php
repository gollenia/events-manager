<?php 
use \Contexis\Events\Options;
if( !function_exists('current_user_can') || !current_user_can('manage_options') ) return; ?>
<!-- BOOKING OPTIONS -->
<div class="em-menu-bookings em-menu-group"  <?php if( !defined('EM_SETTINGS_TABS') || !EM_SETTINGS_TABS) : ?>style="display:none;"<?php endif; ?>>	
	
	<h2 class="title"><?php echo sprintf(__( '%s Options', 'events-manager'),__('General','events-manager')); ?></h2>

	<table class='form-table' role="presentation"> 
		<?php 
		Options::checkbox ( __( 'Approval Required?', 'events-manager'), 'dbem_bookings_approval', __( 'Bookings will not be confirmed until the event administrator approves it.', 'events-manager').' '.__( 'This setting is not applicable when using payment gateways, see individual gateways for approval settings.', 'events-manager'));
		Options::checkbox ( __( 'Reserved unconfirmed spaces?', 'events-manager'), 'dbem_bookings_approval_reserved', __( 'By default, event spaces become unavailable once there are enough CONFIRMED bookings. To reserve spaces even if unapproved, choose yes.', 'events-manager') );
		
		Options::checkbox ( __( 'Allow overbooking when approving?', 'events-manager'), 'dbem_bookings_approval_overbooking', __( 'If you get a lot of pending bookings and you decide to allow more bookings than spaces allow, setting this to yes will allow you to override the event space limit when manually approving.', 'events-manager') );
		
		
		?>
	</table>

	
	
	<h2 class="title"><?php echo sprintf(__( '%s Options', 'events-manager'),__('Pricing','events-manager')); ?> </h2>
	
	<table class='form-table' role="presentation">
		<?php
		/* Tax & Currency */
		Options::select ( __( 'Currency', 'events-manager'), 'dbem_bookings_currency', \Contexis\Events\Intl\Price::currency_list()->names, __( 'Choose your currency for displaying event pricing.', 'events-manager') );
		Options::input ( __( 'Tax Rate', 'events-manager'), 'dbem_bookings_tax', __( 'Add a tax rate to your ticket prices (entering 10 will add 10% to the ticket price).', 'events-manager') );
		Options::checkbox ( __( 'Add tax to ticket price?', 'events-manager'), 'dbem_bookings_tax_auto_add', __( 'When displaying ticket prices and booking totals, include the tax automatically?', 'events-manager') );
		
		?>
	</table>

	
	
	<h2 class="title"><?php _e( 'Customize Feedback Messages', 'events-manager'); ?></h2>

	<table class='form-table' role="presentation">
		<?php
		Options::input ( __( 'Successful booking', 'events-manager'), 'dbem_booking_feedback', __( 'When a booking is registered and confirmed.', 'events-manager') );
		Options::input ( __( 'Successful pending booking', 'events-manager'), 'dbem_booking_feedback_pending', __( 'When a booking is registered but pending.', 'events-manager') );
		Options::input ( __( 'Not enough spaces', 'events-manager'), 'dbem_booking_feedback_full', __( 'When a booking cannot be made due to lack of spaces.', 'events-manager') );
		Options::textarea(__('Privacy Message', 'events-manager'), 'dbem_privacy_message', __('You can type any HTML Content here.', 'events-manager'));
		
		?>
	</table>


	<h2 class="title"><?php echo sprintf(__( '%s Options', 'events-manager'),__('Ticket','events-manager')); ?> </h2>
	
	<table class='form-table' role="presentation">
		<?php
		
		Options::checkbox ( __( 'Enable custom ticket ordering?', 'events-manager'), 'dbem_bookings_tickets_ordering', __( 'When enabled, users can custom-order their tickets using drag and drop. If enabled, saved ordering supercedes the default ticket ordering below.', 'events-manager') );
		$ticket_orders = apply_filters('em_tickets_orderby_options', array(
			'ticket_price DESC, ticket_name ASC'=>__('Ticket Price (Descending)','events-manager'),
			'ticket_price ASC, ticket_name ASC'=>__('Ticket Price (Ascending)','events-manager'),
			'ticket_name ASC, ticket_price DESC'=>__('Ticket Name (Ascending)','events-manager'),
			'ticket_name DESC, ticket_price DESC'=>__('Ticket Name (Descending)','events-manager')
		));
		Options::select ( __( 'Order Tickets By', 'events-manager'), 'dbem_bookings_tickets_orderby', $ticket_orders, __( 'Choose which order your tickets appear.', 'events-manager') );
		
		?>
	</table>

			
	<?php do_action('em_options_page_footer_bookings'); ?>
	
</div> <!-- .em-menu-bookings -->