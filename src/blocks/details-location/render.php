<?php
$id = get_the_ID();
$event = EM_Event::find($id, 'post_id');
$location = $event->get_location();
if(empty($location->id)) return;
?>

<div class="event-details-item">
	<div class="event-details-image">
		<i class="material-icons material-symbols-outlined"><?php echo $attributes['icon'] ?: 'place' ?></i>
	</div>
	<div class="event-details-text">
		<h4><?php echo $attributes['description'] ?: __("Location", "events-manager") ?></h4>
		
		<address class="event-details-data">
			<?php if($attributes['showTitle']) echo $location->location_name . '<br />' ?>
			<?php if($attributes['showAddress']) echo $location->location_address . '<br />' ?>
			<?php if($attributes['showZip']) echo $location->location_postcode ?> <?php if($attributes['showCity']) echo $location->location_town  . '<br />' ?>
			<?php if($attributes['showCountry']) echo $location->location_country ?>
		</address>
		
	</div>

	<?php if($attributes['showLink'] ): ?>
		<div class="event-details-action">
			<a target="_blank" href="<?php echo $attributes['url'] ?: $location->location_url ?>"><i class="material-icons material-symbols-outlined">navigation</i></a>
		</div>
	<?php endif; ?>
</div> 
