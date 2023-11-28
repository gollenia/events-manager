<?php 

$id = get_the_ID();
$event = EM_Event::find($id);
$speaker = \Contexis\Events\Speaker::get($event->speaker_id);

?>

<div class="event-details-item">
	<div class="event-details-image">
		<i class="material-icons">
			<?php if ($attributes['showPortrait'] && $speaker->image) : ?>
				<img class="event-details-image" src="<?php echo $speaker->image['sizes']['thumbnail']['url'] ?>" alt="<?php echo $speaker->name ?>">
			<?php else : ?>
			<?php echo $attributes['icon'] ? $attributes['icon'] : 'person' ?>
			<?php endif; ?>
		</i>
	</div>
	<div class="event-details-text">
		<h4><?php echo $attributes['description'] ?: __("Speaker", "em-pro") ?></h4>
		<div class="event-details-data"><?php echo $speaker->name ?></div> 
	</div>
	<div class="event-details-action">
		<a target="_blank" href="<?php echo $attributes['url'] ?: $speaker->email ?>"><i class="material-icons">mail</i></a>
	</div>
</div> 