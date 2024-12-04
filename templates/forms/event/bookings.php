<?php
global $EM_Event, $post, $allowedposttags, $col_count;
$reschedule_warnings = !empty($EM_Event->event_id) && $EM_Event->is_recurring() && $EM_Event->event_rsvp;
?>


<div id="event-rsvp-options">
	<?php 
	do_action('em_events_admin_bookings_header', $EM_Event);

	?>

	
	<h3 class="title"><?php esc_html_e('Event Options','events'); ?></h3>

	<hr>
	<?php

		do_action('em_events_admin_bookings_footer', $EM_Event); 
	?>
</div>