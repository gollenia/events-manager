<?php 

$id = get_the_ID();
$event = EM_Event::find($id, 'post_id');
if(!$event->event_rsvp) return;
$spaces = $event->get_bookings()->get_available_spaces();

$icon = $spaces == 0 ? 'sentiment_dissatisfied' : ( $attributes['warningThreshold'] < $spaces ? 'done' : 'report_problem' );

?>

<div class="event-details-item">
	<div class="event-details-image">
		<i class="material-icons material-symbols-outlined"><?php echo $icon ?></i>
	</div>
	<div class="event-details-text">
		<h4><?php echo $attributes['description'] ?: __("Free spaces", "events") ?></h4>
		<div class="event-details-data">
			<?php if ($attributes['showNumber'] && $spaces > 0) : ?>
				<?php if ($spaces <= $attributes['warningThreshold']) : ?>
					<div class="event-details-number"><?php echo sprintf(_n("Only %s space left", "Only %s spaces left", $spaces, "events"), $spaces) ?></div>
				<?php else : ?>
					<div class="event-details-number"><?php echo sprintf(_n("%s space left", "%s spaces left", $spaces, "events"), $spaces) ?></div>
				<?php endif; ?>
			<?php else : ?>
				<?php if ($spaces == 0) : ?>
					<div class="event-details-number"><?php echo __("No spaces left", "events") ?></div>
				<?php elseif ($spaces <= $attributes['warningThreshold']) : ?>
					<div class="event-details-number"><?php echo __("Only few spaces left", "events") ?></div>
				<?php else : ?>
					<div class="event-details-number"><?php echo __("Plenty of spaces left", "events") ?></div>
				<?php endif; ?>
			<?php endif; ?>
		</div> 
	</div>
</div> 