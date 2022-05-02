<?php if( !function_exists('current_user_can') || !current_user_can('manage_options') ) return; ?>
<!-- BOOKING OPTIONS -->
<div class="em-menu-bookings em-menu-group"  <?php if( !defined('EM_SETTINGS_TABS') || !EM_SETTINGS_TABS) : ?>style="display:none;"<?php endif; ?>>	
	
	<div  class="postbox " id="em-opt-bookings-general" >
	<div class="handlediv" title="<?php __('Click to toggle', 'events-manager'); ?>"><br /></div><h3><span><?php echo sprintf(__( '%s Options', 'events-manager'),__('General','events-manager')); ?> </span></h3>
	<div class="inside">
		<table class='form-table'> 
			<?php 
			em_options_radio_binary ( __( 'Approval Required?', 'events-manager'), 'dbem_bookings_approval', __( 'Bookings will not be confirmed until the event administrator approves it.', 'events-manager').' '.__( 'This setting is not applicable when using payment gateways, see individual gateways for approval settings.', 'events-manager'));
			em_options_radio_binary ( __( 'Reserved unconfirmed spaces?', 'events-manager'), 'dbem_bookings_approval_reserved', __( 'By default, event spaces become unavailable once there are enough CONFIRMED bookings. To reserve spaces even if unapproved, choose yes.', 'events-manager') );
			
			em_options_radio_binary ( __( 'Allow overbooking when approving?', 'events-manager'), 'dbem_bookings_approval_overbooking', __( 'If you get a lot of pending bookings and you decide to allow more bookings than spaces allow, setting this to yes will allow you to override the event space limit when manually approving.', 'events-manager') );
			
			echo $save_button; 
			?>
		</table>
	</div> <!-- . inside -->
	</div> <!-- .postbox -->
	
	<div  class="postbox " id="em-opt-pricing-options" >
	<div class="handlediv" title="<?php __('Click to toggle', 'events-manager'); ?>"><br /></div><h3><span><?php echo sprintf(__( '%s Options', 'events-manager'),__('Pricing','events-manager')); ?> </span></h3>
	<div class="inside">
		<table class='form-table'>
			<?php
			/* Tax & Currency */
			em_options_select ( __( 'Currency', 'events-manager'), 'dbem_bookings_currency', em_get_currencies()->names, __( 'Choose your currency for displaying event pricing.', 'events-manager') );
			em_options_input_text ( __( 'Thousands Separator', 'events-manager'), 'dbem_bookings_currency_thousands_sep', '<code>'.get_option('dbem_bookings_currency_thousands_sep')." = ".em_get_currency_symbol().'100<strong>'.get_option('dbem_bookings_currency_thousands_sep').'</strong>000<strong>'.get_option('dbem_bookings_currency_decimal_point').'</strong>00</code>' );
			em_options_input_text ( __( 'Decimal Point', 'events-manager'), 'dbem_bookings_currency_decimal_point', '<code>'.get_option('dbem_bookings_currency_decimal_point')." = ".em_get_currency_symbol().'100<strong>'.get_option('dbem_bookings_currency_decimal_point').'</strong>00</code>' );
			
			em_options_input_text ( __( 'Tax Rate', 'events-manager'), 'dbem_bookings_tax', __( 'Add a tax rate to your ticket prices (entering 10 will add 10% to the ticket price).', 'events-manager') );
			em_options_radio_binary ( __( 'Add tax to ticket price?', 'events-manager'), 'dbem_bookings_tax_auto_add', __( 'When displaying ticket prices and booking totals, include the tax automatically?', 'events-manager') );
			echo $save_button; 
			?>
		</table>
	</div> <!-- . inside -->
	</div> <!-- .postbox --> 
	
	<div  class="postbox " id="em-opt-booking-feedbacks" >
	<div class="handlediv" title="<?php __('Click to toggle', 'events-manager'); ?>"><br /></div><h3><span><?php _e( 'Customize Feedback Messages', 'events-manager'); ?> </span></h3>
	<div class="inside">
		<p><?php _e('Below you will find texts that will be displayed to users in various areas during the bookings process, particularly on booking forms.','events-manager'); ?></p>
		<table class='form-table'>
			
			
			<tr class="em-header"><td colspan='2'><h4><?php _e('Booking form feedback messages','events-manager') ?></h4></td></tr>
			<tr><td colspan='2'><?php _e('When a booking is made by a user, a feedback message is shown depending on the result, which can be customized below.','events-manager'); ?></td></tr>
			<?php
			em_options_input_text ( __( 'Successful booking', 'events-manager'), 'dbem_booking_feedback', __( 'When a booking is registered and confirmed.', 'events-manager') );
			em_options_input_text ( __( 'Successful pending booking', 'events-manager'), 'dbem_booking_feedback_pending', __( 'When a booking is registered but pending.', 'events-manager') );
			em_options_input_text ( __( 'Not enough spaces', 'events-manager'), 'dbem_booking_feedback_full', __( 'When a booking cannot be made due to lack of spaces.', 'events-manager') );
			em_options_input_text ( __( 'Errors', 'events-manager'), 'dbem_booking_feedback_error', __( 'When a booking cannot be made due to an error when filling the form. Below this, there will be a dynamic list of errors.', 'events-manager') );
			em_options_input_text ( __( 'Error mailing user', 'events-manager'), 'dbem_booking_feedback_nomail', __( 'If a booking is made and an email cannot be sent, this is added to the success message.', 'events-manager') );
			em_options_input_text ( __( 'No spaces booked', 'events-manager'), 'dbem_booking_feedback_min_space', __( 'If the user tries to make a booking without requesting any spaces.', 'events-manager') );$notice_full = __('Sold Out', 'events-manager');
			em_options_textarea(__('Privacy Message', 'events-manager'), 'dbem_privacy_message', __('You can type any HTML Content here.', 'events-manager'));
			
			echo $save_button; 
			?>
		</table>
	</div> <!-- . inside -->
	</div> <!-- .postbox --> 
	
	
	
	<div  class="postbox " id="em-opt-ticket-options" >
	<div class="handlediv" title="<?php __('Click to toggle', 'events-manager'); ?>"><br /></div><h3><span><?php echo sprintf(__( '%s Options', 'events-manager'),__('Ticket','events-manager')); ?> </span></h3>
	<div class="inside">
		<table class='form-table'>
			<?php
			
			em_options_radio_binary ( __( 'Enable custom ticket ordering?', 'events-manager'), 'dbem_bookings_tickets_ordering', __( 'When enabled, users can custom-order their tickets using drag and drop. If enabled, saved ordering supercedes the default ticket ordering below.', 'events-manager') );
			$ticket_orders = apply_filters('em_tickets_orderby_options', array(
				'ticket_price DESC, ticket_name ASC'=>__('Ticket Price (Descending)','events-manager'),
				'ticket_price ASC, ticket_name ASC'=>__('Ticket Price (Ascending)','events-manager'),
				'ticket_name ASC, ticket_price DESC'=>__('Ticket Name (Ascending)','events-manager'),
				'ticket_name DESC, ticket_price DESC'=>__('Ticket Name (Descending)','events-manager')
			));
			em_options_select ( __( 'Order Tickets By', 'events-manager'), 'dbem_bookings_tickets_orderby', $ticket_orders, __( 'Choose which order your tickets appear.', 'events-manager') );
			echo $save_button; 
			?>
		</table>
	</div> <!-- . inside -->
	</div> <!-- .postbox --> 
			
	<?php do_action('em_options_page_footer_bookings'); ?>
	
</div> <!-- .em-menu-bookings -->