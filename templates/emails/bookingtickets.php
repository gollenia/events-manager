<?php foreach($EM_Booking->get_tickets_bookings() as $EM_Ticket_Booking): ?>
<?php
/* @var $EM_Ticket_Booking EM_Ticket_Booking */
echo $EM_Ticket_Booking->get_ticket()->name; 
?>

<?php _e('Quantity','events-manager'); ?>: <?php echo $EM_Ticket_Booking->get_spaces(); ?>


<?php endforeach; ?>