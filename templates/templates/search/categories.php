<?php $args = !empty($args) ? $args:array(); /* @var $args array */ ?>
<!-- START Category Search -->
<div class="em-search-category em-search-field">
	<label>
		<span><?php echo esc_html($args['category_label']); ?></span>
		<?php 
			
			wp_dropdown_categories(array( 
			    'hide_empty' => 0, 
			    'orderby' =>'name', 
			    'name' => 'category', 
			    'hierarchical' => true, 
			    'taxonomy' => EM_TAXONOMY_CATEGORY, 
			    'selected' => $args['category'], 
			    'show_option_none' => $args['categories_label'], 
			    'option_none_value'=> 0, 
			    'class'=>'em-events-search-category'
			));
			
		?>
	</label>
</div>
<!-- END Category Search -->