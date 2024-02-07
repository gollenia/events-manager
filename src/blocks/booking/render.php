<?php

$event = \EM_Event::find(get_the_ID(), 'post_id');

if(!$event->can_book()) {
	return;
}

$classNames = [
	'ctx__button',
	$attributes['iconOnly'] ? 'ctx__button--icon-only' : '',
	$attributes['iconRight'] ? 'ctx__button--reverse' : '',
];

$block_attributes = get_block_wrapper_attributes(['class' => join(" ", $classNames)]);

?>

<button <?php echo $block_attributes ?> id="booking_button">
<?php
if($attributes['buttonIcon']) {
	echo "<i class=\"material-icons material-symbols-outlined\">{$attributes['buttonIcon']}</i>";
} 
if(!$attributes['iconOnly']) {
	echo $attributes['buttonTitle'] ?: __("Log In", "events-manager");
}
?>
</button>

<?php

add_action('wp_footer', function() {
	echo "<div id=\"booking_app\" data-post=\"" . get_the_ID() . "\"></div>";
});
?>

