<?php 

$id = get_the_ID();
$event = EM_Event::find($id, 'post_id');
if(!$event) return;

if($attributes['overwritePrice']) {
	
}
$price = $event->get_formatted_price();
$is_free = $event->is_free();

?>

<?php if($price->free && !$attributes['overwritePrice']) : ?>
	<div class="event-details-item">
		<div class="event-details-image">
			<i class="material-icons">savings</i>
		</div>
		<div class="event-details-text">
			<h4><?php echo $attributes['description'] ? $attributes['description'] : __("Price", "em-pro") ?></h4>
			<div class="event-details-data"><?php echo __("Free", "em-pro") ?></div> 
		</div>
	</div>
<?php else : ?>
	<div class="event-details-item">
		<div class="event-details-image">
			<i class="event-details-icon material-icons"><?php echo $price->currency ?></i>
		</div>
		<div class="event-details-text">
			<h4><?php echo $attributes['description'] ? $attributes['description'] : __("Price", "em-pro") ?></h4>
			<div class="event-details-data"><?php echo $attributes['overwritePrice'] ? $attributes['overwritePrice'] : $price->format ?></div> 
		</div>
	</div>
<?php endif; ?>

