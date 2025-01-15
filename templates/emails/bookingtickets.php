<?php foreach($EM_Booking->get_tickets_bookings() as $ticket_booking): ?>
<?php

echo $ticket_booking->get_ticket()->name; 
?>

<?php _e('Quantity','events'); ?>: <?php echo $ticket_booking->get_spaces(); ?>


<?php endforeach; ?>