<?php foreach($EM_Booking->get_tickets_bookings() as $ticket_booking): ?>
<?php

echo $ticket_booking->get_ticket()->name; 
?>

<?php _e('Quantity','events-manager'); ?>: <?php echo $ticket_booking->get_spaces(); ?>

<?php _e('Price','events-manager'); ?>: <?php echo \Contexis\Events\Intl\Price::currency_symbol()." ". number_format($ticket_booking->get_price(),2); ?>


<?php endforeach; ?>