<?php 

$id = get_the_ID();
$event = EM_Event::find($id, 'post_id');
$time = \Contexis\Events\Intl\Date::get_time($event->start()->getTimestamp(), $event->end()->getTimestamp());

?>

<div class="event-details-item">
	<div class="event-details-image">
		<i class="event-details-icon material-icons"><?php echo $attributes['icon'] ? $attributes['icon'] : 'schedule' ?></i>
	</div>
	<div class="event-details-text">
		<h4><?php echo $attributes['description'] ?: __("Time", "em-pro") ?></h4>
		<div class="description-data"><?php echo $time ?></div> 
	</div>
</div> 