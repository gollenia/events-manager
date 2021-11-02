<?php if( !function_exists('current_user_can') || !current_user_can('manage_options') ) return; ?>
<!-- EMAIL OPTIONS -->
<div class="em-menu-emails em-menu-group" <?php if( !defined('EM_SETTINGS_TABS') || !EM_SETTINGS_TABS) : ?>style="display:none;"<?php endif; ?>>
	
	<?php if ( !is_multisite() ) { em_admin_option_box_email(); } ?>

	<?php if( get_option('dbem_rsvp_enabled') ): ?>
	<div  class="postbox "  id="em-opt-booking-emails">
	<div class="handlediv" title="<?php __('Click to toggle', 'events-manager'); ?>"><br /></div><h3><span><?php _e ( 'Booking Email Templates', 'events-manager'); ?> </span></h3>
	<div class="inside">
	    <?php do_action('em_options_page_booking_email_templates_options_top'); ?>
		<table class='form-table'>
			<?php
			$email_subject_tip = __('You can disable this email by leaving the subject blank.','events-manager');
			em_options_input_text ( __( 'Email events admin?', 'events-manager'), 'dbem_bookings_notify_admin', __( "If you would like every event booking confirmation email sent to an administrator write their email here (leave blank to not send an email).", 'events-manager').' '.__('For multiple emails, separate by commas (e.g. email1@test.com,email2@test.com,etc.)','events-manager') );
			em_options_radio_binary ( __( 'Email event owner?', 'events-manager'), 'dbem_bookings_contact_email', __( 'Check this option if you want the event contact to receive an email when someone books places. An email will be sent when a booking is first made (regardless if confirmed or pending)', 'events-manager') );
			do_action('em_options_page_booking_email_templates_options_subtop');
			?>
			<tr class="em-header"><td colspan='2'><h4><?php _e('Event Admin/Owner Emails', 'events-manager'); ?></h4></td></tr>
			<tbody class="em-subsection">
			<tr class="em-subheader"><td colspan='2'>
				<h5><?php _e('Confirmed booking email','events-manager') ?></h5>
				<em><?php echo __('This is sent when a person\'s booking is confirmed. This will be sent automatically if approvals are required and the booking is approved. If approvals are disabled, this is sent out when a user first submits their booking.','events-manager').$bookings_placeholder_tip ?></em>
			</td></tr>
			<?php
			em_options_input_text ( __( 'Booking confirmed email subject', 'events-manager'), 'dbem_bookings_contact_email_confirmed_subject', $email_subject_tip );
			em_options_textarea ( __( 'Booking confirmed email', 'events-manager'), 'dbem_bookings_contact_email_confirmed_body', '' );
			?>
			<tr class="em-subheader"><td colspan='2'>
				<h5><?php _e('Pending booking email','events-manager') ?></h5>
				<em><?php echo __('This is sent when a person\'s booking is pending. If approvals are enabled, this is sent out when a user first submits their booking.','events-manager').$bookings_placeholder_tip ?></em>
			</td></tr>
			<?php
			em_options_input_text ( __( 'Booking pending email subject', 'events-manager'), 'dbem_bookings_contact_email_pending_subject', $email_subject_tip );
			em_options_textarea ( __( 'Booking pending email', 'events-manager'), 'dbem_bookings_contact_email_pending_body', '' );
			?>
			<tr class="em-subheader"><td colspan='2'>
				<h5><?php _e('Booking cancelled','events-manager') ?></h5>
				<em><?php echo __('An email will be sent to the event contact if someone cancels their booking.','events-manager').$bookings_placeholder_tip ?></em>
			</td></tr>
			<?php
			em_options_input_text ( __( 'Booking cancelled email subject', 'events-manager'), 'dbem_bookings_contact_email_cancelled_subject', $email_subject_tip );
			em_options_textarea ( __( 'Booking cancelled email', 'events-manager'), 'dbem_bookings_contact_email_cancelled_body', '' );
			?>
			<tr class="em-subheader"><td colspan='2'>
				<h5><?php _e('Rejected booking email','events-manager') ?></h5>
				<em><?php echo __( 'This will be sent to event admins when a booking is rejected.', 'events-manager').$bookings_placeholder_tip ?></em>
			</td></tr>
			<?php
			em_options_input_text ( __( 'Booking rejected email subject', 'events-manager'), 'dbem_bookings_contact_email_rejected_subject', $email_subject_tip );
			em_options_textarea ( __( 'Booking rejected email', 'events-manager'), 'dbem_bookings_contact_email_rejected_body', '' );
			?>
			</tbody>
			<tr class="em-header"><td colspan='2'><h4><?php _e('Booked User Emails', 'events-manager'); ?></h4></td></tr>
			<tbody class="em-subsection">
			<tr class="em-subheader"><td colspan='2'>
				<h5><?php _e('Confirmed booking email','events-manager') ?></h5>
				<em><?php echo __('This is sent when a person\'s booking is confirmed. This will be sent automatically if approvals are required and the booking is approved. If approvals are disabled, this is sent out when a user first submits their booking.','events-manager').$bookings_placeholder_tip ?></em>
			</td></tr>
			<?php
			em_options_input_text ( __( 'Booking confirmed email subject', 'events-manager'), 'dbem_bookings_email_confirmed_subject', $email_subject_tip );
			em_options_textarea ( __( 'Booking confirmed email', 'events-manager'), 'dbem_bookings_email_confirmed_body', '' );
			?>
			<tr class="em-subheader"><td colspan='2'>
				<h5><?php _e('Pending booking email','events-manager') ?></h5>
				<em><?php echo __( 'This will be sent to the person when they first submit their booking. Not relevant if bookings don\'t require approval.', 'events-manager').$bookings_placeholder_tip ?></em>
			</td></tr>
			<?php
			em_options_input_text ( __( 'Booking pending email subject', 'events-manager'), 'dbem_bookings_email_pending_subject', $email_subject_tip);
			em_options_textarea ( __( 'Booking pending email', 'events-manager'), 'dbem_bookings_email_pending_body','') ;
			?>
			<tr class="em-subheader"><td colspan='2'>
				<h5><?php _e('Booking cancelled','events-manager') ?></h5>
				<em><?php echo __('This will be sent when a user cancels their booking.','events-manager').$bookings_placeholder_tip ?></em>
			</td></tr>
			<?php
			em_options_input_text ( __( 'Booking cancelled email subject', 'events-manager'), 'dbem_bookings_email_cancelled_subject', $email_subject_tip );
			em_options_textarea ( __( 'Booking cancelled email', 'events-manager'), 'dbem_bookings_email_cancelled_body', '' );
			?>
			<tr class="em-subheader"><td colspan='2'>
				<h5><?php _e('Rejected booking email','events-manager') ?></h5>
				<em><?php echo __( 'This will be sent automatically when a booking is rejected. Not relevant if bookings don\'t require approval.', 'events-manager').$bookings_placeholder_tip ?></em>
			</td></tr>
			<?php
			em_options_input_text ( __( 'Booking rejected email subject', 'events-manager'), 'dbem_bookings_email_rejected_subject', $email_subject_tip );
			em_options_textarea ( __( 'Booking rejected email', 'events-manager'), 'dbem_bookings_email_rejected_body', '' );
			?>
			</tbody>
	        <?php do_action('em_options_page_booking_email_templates_options_bottom'); ?>
			<?php echo $save_button; ?>
		</table>
	</div> <!-- . inside -->
	</div> <!-- .postbox -->
	<?php endif; ?>
			  		
	<?php if( get_option('dbem_rsvp_enabled') ): ?>
	<div  class="postbox "  id="em-opt-registration-emails">
	<div class="handlediv" title="<?php __('Click to toggle', 'events-manager'); ?>"><br /></div><h3><span><?php _e ( 'Registration Email Templates', 'events-manager'); ?> </span></h3>
	<div class="inside">
		<p class="em-boxheader">
			<?php echo sprintf(__('This is only applicable when %s is not active.','events-manager'), '<em>'.__('No-User Booking Mode','events-manager').'</em>'); ?>
			<?php _e('When a guest user makes a booking for the first time in Events Manager, a new user account is created for them and they are sent their credentials in a separate email, which can be modified below.','events-manager'); ?>
		</p>
		<table class='form-table'>
			<?php
			em_options_radio_binary ( __( 'Disable new registration email?', 'events-manager'), 'dbem_email_disable_registration', __( 'Check this option if you want to prevent the WordPress registration email from going out when a user anonymously books an event.', 'events-manager') );
			
			em_options_input_text ( __( 'Registration email subject', 'events-manager'), 'dbem_bookings_email_registration_subject' );
			em_options_textarea ( __( 'Registration email', 'events-manager'), 'dbem_bookings_email_registration_body', sprintf(__('%s is replaced by username, %s is replaced by the user password and %s is replaced by a link to create a password.','events-manager'),'<code>%username%</code>','<code>%password%</code>','<code>%passwordurl%</code>') );
			echo $save_button;
			?>
		</table>
	</div> <!-- . inside -->
	</div> <!-- .postbox -->
	<?php endif; ?>
	
	
	
	<?php do_action('em_options_page_footer_emails'); ?>
	
</div><!-- .em-group-emails --> 