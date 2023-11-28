<?php

$event = \EM_Event::find(get_the_ID(), 'post_id');

if(!$event->can_book()) {
	return;
}

if ($full_data->parsed_block['attrs']) $styles = get_block_wrapper_attributes() ?? '';
if(!isset($styles)) $styles = '';
$attributes['className'] = preg_match('/class="([^"]+)"/', $styles, $matches) ? $matches[1] : '';
$attributes['style'] = preg_match('/style="([^"]+)"/', $styles, $matches) ? $matches[1] : '';

$classNames = [
	'ctx__button',
	$attributes['iconOnly'] ? 'ctx__button--icon-only' : '',
	$attributes['iconRight'] ? 'ctx__button--reverse' : '',
];

$block_attributes = get_block_wrapper_attributes(['class' => join(" ", $classNames)]);

?>

<button <?php echo $block_attributes ?> id="booking_button">
<?php
if($attributes['icon']) {
	echo "<i class=\"material-icons\">{$attributes['icon']}</i>";
} 
if(!$attributes['iconOnly']) {
	echo "{$attributes['buttonTitle']}";
}
?>
</button>

<?php

add_action('wp_footer', function() {
	echo "<div id=\"booking_app\" data-post=\"" . get_the_ID() . "\"></div>";
});
?>

