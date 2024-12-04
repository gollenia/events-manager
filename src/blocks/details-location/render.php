<?php
$id = get_the_ID();
$event = EM_Event::find($id, 'post_id');
$location = $event->get_location();
if(empty($location->location_id)) return;
$block_attributes = get_block_wrapper_attributes();

$has_photo = strpos($block_attributes, 'is-style-photo') !== false;
$photo = get_the_post_thumbnail_url( $location->post_id, "post-thumbnail" );
?>

<div class="event-details-item">
	<div class="event-details-image">
		<?php if ($has_photo && $photo) : ?>
			
			<img class="event-details-image" src="<?php echo $photo ?>" alt="<?php echo $speaker->name ?>">
		<?php else: ?>
			<i class="material-icons material-symbols-outlined"><?php echo $attributes['icon'] ?: 'place' ?></i>
		<?php endif; ?>
	</div>
	<div class="event-details-text">
		<h4><?php echo $attributes['description'] ?: __("Location", "events") ?></h4>
		
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
