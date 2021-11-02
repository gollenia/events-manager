<?php
/*
 * You can override this by copying this file to wp-content/themesyourthemefolder/plugins/events-manager/placeholders/ and modifying it however you need.
 * There are a few variables made available to you:
 * 
 * $EM_Event - EM_Event object 
 */
$notice_full = get_option('dbem_booking_button_msg_full');
$button_text = get_option('dbem_booking_button_msg_book');

$button_closed = get_option('dbem_booking_button_msg_closed');

/* @var $EM_Event EM_Event */
?>
<?php 
if( is_user_logged_in() ){ //only show this to logged in users
	ob_start();
	$EM_Booking = $EM_Event->get_bookings()->has_booking();
	if( $EM_Event->get_bookings()->is_open() ){
		if( !is_object($EM_Booking) ){
			?><a id="em-booking-button_<?php echo $EM_Event->event_id; ?>_<?php echo wp_create_nonce('booking_add_one'); ?>" class="button em-booking-button" href="#"><?php echo $button_text; ?></a><?php 
		}
	}elseif( $EM_Event->get_bookings()->get_available_spaces() <= 0 ){
		?><span class="em-full-button"><?php echo $notice_full ?></span><?php
	}else{
		?><span class="em-closed-button"><?php echo $button_closed ?></span><?php
	}
	echo apply_filters( 'em_booking_button', ob_get_clean(), $EM_Event );
}; 
?>