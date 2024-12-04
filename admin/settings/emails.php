<?php 
use \Contexis\Events\Options;
use Mpdf\Tag\Option;

?>

<?php if( !function_exists('current_user_can') || !current_user_can('manage_options') ) return; ?>
<!-- EMAIL OPTIONS -->
<div class="em-menu-emails em-menu-group" <?php if( !defined('EM_SETTINGS_TABS') || !EM_SETTINGS_TABS) : ?>style="display:none;"<?php endif; ?>>
	
	<?php $current_user = get_user_by('id', get_current_user_id());
	?>
	<div  class="postbox "  id="em-opt-email-settings">
	<div class="handlediv" title="<?php __('Click to toggle', 'events'); ?>"><br /></div><h3><span><?php _e ( 'Email Settings', 'events'); ?></span></h3>
	<div class="inside em-email-form">
		<p class="em-email-settings-check em-boxheader">
			<em><?php _e('Before you save your changes, you can quickly send yourself a test email by clicking this button.','events'); ?>
			<?php echo sprintf(__('A test email will be sent to your account email - %s','events'), $current_user->user_email . ' <a href="'.admin_url( 'profile.php' ).'">'.__('edit','events').'</a>'); ?></em><br />
			<input type="button" id="em-admin-check-email" class="button-secondary" value="<?php esc_attr_e('Test Email Settings','events'); ?>" />
			<input type="hidden" name="_check_email_nonce" value="<?php echo wp_create_nonce('check_email'); ?>" />
			<span id="em-email-settings-check-status"></span>
		</p>
		<table class="form-table">
			<?php
			Options::input( __( 'Notification sender name', 'events'), 'dbem_mail_sender_name', __( "Insert the display name of the notification sender.", 'events') );
			Options::input( __( 'Notification sender address', 'events'), 'dbem_mail_sender_address', __( "Insert the address of the notification sender.", 'events'), ['type' => Options::EMAIL, 'placeholder' => get_bloginfo('admin_email')] );
			Options::checkbox ( __( 'Send HTML Emails?', 'events'), 'dbem_smtp_html', __( 'If set to yes, your emails will be sent in HTML format, otherwise plaintext.', 'events').' '.__( 'Depending on server settings, some sending methods may ignore this settings.', 'events') );
			Options::checkbox ( __( 'Add br tags to HTML emails?', 'events'), 'dbem_smtp_html_br', __( 'If HTML emails are enabled, br tags will automatically be added for new lines.', 'events') );
		
			echo Options::save_button();
			?>
		</table>
		
	</div> <!-- . inside -->
	</div> <!-- .postbox --> 

	<?php if( get_option('dbem_rsvp_enabled') ): ?>
	<div  class="postbox "  id="em-opt-booking-emails">
	<div class="handlediv" title="<?php __('Click to toggle', 'events'); ?>"><br /></div><h3><span><?php _e ( 'Booking Email Templates', 'events'); ?> </span></h3>
	<div class="">
	    <?php do_action('em_options_page_booking_email_templates_options_top'); ?>
		<table class='form-table'>
			<?php
			$email_subject_tip = __('You can disable this email by leaving the subject blank.','events');
			Options::input ( __( 'Email events admin?', 'events'), 'dbem_bookings_notify_admin', __( "If you would like every event booking confirmation email sent to an administrator write their email here (leave blank to not send an email).", 'events').' '.__('For multiple emails, separate by commas (e.g. email1@test.com,email2@test.com,etc.)','events') );
			Options::checkbox ( __( 'Email event owner?', 'events'), 'dbem_bookings_contact_email', __( 'Check this option if you want the event contact to receive an email when someone books places. An email will be sent when a booking is first made (regardless if confirmed or pending)', 'events') );
			do_action('em_options_page_booking_email_templates_options_subtop');
			?>
			<tr class="em-header"><td colspan='2'><h4><?php _e('Event Admin/Owner Emails', 'events'); ?></h4></td></tr>
			<tbody class="em-subsection">
			<tr class="em-subheader"><td colspan='2'>
				<h5><?php _e('Confirmed booking email','events') ?></h5>
				<em><?php echo __('This is sent when a person\'s booking is confirmed. This will be sent automatically if approvals are required and the booking is approved. If approvals are disabled, this is sent out when a user first submits their booking.','events') ?></em>
			</td></tr>
			<?php
			Options::input ( __( 'Booking confirmed email subject', 'events'), 'dbem_bookings_contact_email_confirmed_subject', $email_subject_tip );
			Options::textarea ( __( 'Booking confirmed email', 'events'), 'dbem_bookings_contact_email_confirmed_body', '' );
			?>
			<tr class="em-subheader"><td colspan='2'>
				<h5><?php _e('Pending booking email','events') ?></h5>
				<em><?php echo __('This is sent when a person\'s booking is pending. If approvals are enabled, this is sent out when a user first submits their booking.','events')?></em>
			</td></tr>
			<?php
			Options::input ( __( 'Booking pending email subject', 'events'), 'dbem_bookings_contact_email_pending_subject', $email_subject_tip );
			Options::textarea ( __( 'Booking pending email', 'events'), 'dbem_bookings_contact_email_pending_body', '' );
			?>
			<tr class="em-subheader"><td colspan='2'>
				<h5><?php _e('Booking cancelled','events') ?></h5>
				<em><?php echo __('An email will be sent to the event contact if someone cancels their booking.','events')?></em>
			</td></tr>
			<?php
			Options::input ( __( 'Booking cancelled email subject', 'events'), 'dbem_bookings_contact_email_cancelled_subject', $email_subject_tip );
			Options::textarea ( __( 'Booking cancelled email', 'events'), 'dbem_bookings_contact_email_cancelled_body', '' );
			?>
			<tr class="em-subheader"><td colspan='2'>
				<h5><?php _e('Rejected booking email','events') ?></h5>
				<em><?php echo __( 'This will be sent to event admins when a booking is rejected.', 'events')?></em>
			</td></tr>
			<?php
			Options::input ( __( 'Booking rejected email subject', 'events'), 'dbem_bookings_contact_email_rejected_subject', $email_subject_tip );
			Options::textarea ( __( 'Booking rejected email', 'events'), 'dbem_bookings_contact_email_rejected_body', '' );
			?>
			</tbody>
			<tr class="em-header"><td colspan='2'><h4><?php _e('Booked User Emails', 'events'); ?></h4></td></tr>
			<tbody class="em-subsection">
			<tr class="em-subheader"><td colspan='2'>
				<h5><?php _e('Confirmed booking email','events') ?></h5>
				<em><?php echo __('This is sent when a person\'s booking is confirmed. This will be sent automatically if approvals are required and the booking is approved. If approvals are disabled, this is sent out when a user first submits their booking.','events')?></em>
			</td></tr>
			<?php
			Options::input ( __( 'Booking confirmed email subject', 'events'), 'dbem_bookings_email_confirmed_subject', $email_subject_tip );
			Options::textarea ( __( 'Booking confirmed email', 'events'), 'dbem_bookings_email_confirmed_body', '' );
			?>
			<tr class="em-subheader"><td colspan='2'>
				<h5><?php _e('Pending booking email','events') ?></h5>
				<em><?php echo __( 'This will be sent to the person when they first submit their booking. Not relevant if bookings don\'t require approval.', 'events')?></em>
			</td></tr>
			<?php
			Options::input ( __( 'Booking pending email subject', 'events'), 'dbem_bookings_email_pending_subject', $email_subject_tip);
			Options::textarea ( __( 'Booking pending email', 'events'), 'dbem_bookings_email_pending_body','') ;
			?>
			<tr class="em-subheader"><td colspan='2'>
				<h5><?php _e('Booking cancelled','events') ?></h5>
				<em><?php echo __('This will be sent when a user cancels their booking.','events')?></em>
			</td></tr>
			<?php
			Options::input ( __( 'Booking cancelled email subject', 'events'), 'dbem_bookings_email_cancelled_subject', $email_subject_tip );
			Options::textarea ( __( 'Booking cancelled email', 'events'), 'dbem_bookings_email_cancelled_body', '' );
			?>
			<tr class="em-subheader"><td colspan='2'>
				<h5><?php _e('Rejected booking email','events') ?></h5>
				<em><?php echo __( 'This will be sent automatically when a booking is rejected. Not relevant if bookings don\'t require approval.', 'events')?></em>
			</td></tr>
			<?php
			Options::input ( __( 'Booking rejected email subject', 'events'), 'dbem_bookings_email_rejected_subject', $email_subject_tip );
			Options::textarea ( __( 'Booking rejected email', 'events'), 'dbem_bookings_email_rejected_body', '' );
			?>
			</tbody>
	        <?php do_action('em_options_page_booking_email_templates_options_bottom'); ?>
			<?php echo Options::save_button(); ?>
		</table>
	</div> <!-- . inside -->
	</div> <!-- .postbox -->
	<?php endif; ?>
			  		
	
	
	<?php do_action('em_options_page_footer_emails'); ?>
	
</div><!-- .em-group-emails --> 