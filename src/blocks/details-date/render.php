<?php 

$id = get_the_ID();
$event = EM_Event::find($id, 'post_id');
$date = \Contexis\Events\Intl\Date::get_date($event->start()->getTimestamp(), $event->end()->getTimestamp());

?>

<div class="event-details-item">
	<div class="event-details-image">
		<i class="material-icons material-symbols-outlined"><?php echo $attributes['icon'] ?: 'event' ?></i>
	</div>
	<div class="event-details-text">
		<h4><?php echo $attributes['description'] ?: __("Date", "events-manager") ?></h4>
		<div class="event-details-data"><?php echo $date ?></div> 
	</div>
</div> 