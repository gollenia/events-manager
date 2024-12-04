<?php
/*
* This displays the content of the #_BOOKINGSUMMARY placeholder
* You can override the default display settings pages by copying this file to yourthemefolder/plugins/events-manage/placeholders/ and modifying it however you need.
* For more information, see http://wp-events-plugin.com/documentation/using-template-files/
*/
/* @var $EM_Booking EM_Booking */ ?>
<?php foreach($EM_Booking->get_tickets_bookings() as $ticket_booking):  ?>

<?php echo $ticket_booking->get_ticket()->ticket_name; ?>

--------------------------------------
<?php _e('Quantity','events'); ?>: <?php echo $ticket_booking->get_spaces(); ?>

<?php _e('Price','events'); ?>: <?php echo $ticket_booking->format_price($ticket_booking->get_price()); ?>

<?php endforeach; ?>

=======================================

<?php 
$price_summary = $EM_Booking->get_price_summary_array();
//we should now have an array of information including base price, taxes and post/pre tax discounts
?>



<?php _e('Total Price','events'); ?> : <?php echo $price_summary['total']; ?>