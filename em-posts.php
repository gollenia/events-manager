<?php

define('EM_POST_TYPE_EVENT','event'); 
define('EM_POST_TYPE_LOCATION','location');
define('EM_TAXONOMY_CATEGORY','event-categories');
define('EM_TAXONOMY_TAG','event-tags');

define('EM_POST_TYPE_EVENT_SLUG',get_option('dbem_cp_events_slug', 'events'));
define('EM_POST_TYPE_LOCATION_SLUG',get_option('dbem_cp_locations_slug', 'locations'));

define('EM_TAXONOMY_CATEGORY_SLUG', get_option('dbem_taxonomy_category_slug', 'events/categories'));
define('EM_TAXONOMY_TAG_SLUG', get_option('dbem_taxonomy_tag_slug', 'events/tags'));


//This bit registers the CPTs
add_action('init','wp_events_plugin_init',1);
function wp_events_plugin_init(){
	define('EM_ADMIN_URL',admin_url().'edit.php?post_type='.EM_POST_TYPE_EVENT); //we assume the admin url is absolute with at least one querystring
	
	register_taxonomy(EM_TAXONOMY_TAG,[EM_POST_TYPE_EVENT,'event-recurring'], apply_filters('em_ct_tags', [
		'hierarchical' => false,
		'public' => true,
		'show_ui' => true,
		'show_in_rest' => true,
		'query_var' => true, 
		'rewrite' => ['slug' => EM_TAXONOMY_TAG_SLUG,'with_front'=>false],
		'label' => __('Event Tags'),
		'show_admin_column' => true,
		'singular_label' => __('Event Tag'),
		'labels' => [
			'name'=>__('Event Tags','events-manager'),
			'singular_name'=>__('Event Tag','events-manager'),
			'search_items'=>__('Search Event Tags','events-manager'),
			'popular_items'=>__('Popular Event Tags','events-manager'),
			'all_items'=>__('All Event Tags','events-manager'),
			'parent_items'=>__('Parent Event Tags','events-manager'),
			'parent_item_colon'=>__('Parent Event Tag:','events-manager'),
			'edit_item'=>__('Edit Event Tag','events-manager'),
			'update_item'=>__('Update Event Tag','events-manager'),
			'add_new_item'=>__('Add New Event Tag','events-manager'),
			'new_item_name'=>__('New Event Tag Name','events-manager'),
			'separate_items_with_commas'=>__('Separate event tags with commas','events-manager'),
			'add_or_remove_items'=>__('Add or remove events','events-manager'),
			'choose_from_the_most_used'=>__('Choose from most used event tags','events-manager'),
		],
		'capabilities' => [
			'manage_terms' => 'edit_event_categories',
			'edit_terms' => 'edit_event_categories',
			'delete_terms' => 'delete_event_categories',
			'assign_terms' => 'edit_events',
		]
	]));

	register_taxonomy(EM_TAXONOMY_CATEGORY,[EM_POST_TYPE_EVENT,'event-recurring'], apply_filters('em_ct_categories', [
		'hierarchical' => true,
		'public' => true,
		'show_ui' => true,
		'show_in_rest' => true,
		'show_admin_column' => true,
		'query_var' => true,
		'rewrite' => ['slug' => EM_TAXONOMY_CATEGORY_SLUG, 'hierarchical' => true,'with_front'=>false],
		'show_in_nav_menus' => true,
		'label' => __('Event Categories','events-manager'),
		'singular_label' => __('Event Category','events-manager'),
		'labels' => [
			'name'=>__('Event Categories','events-manager'),
			'singular_name'=>__('Event Category','events-manager'),
			'search_items'=>__('Search Event Categories','events-manager'),
			'popular_items'=>__('Popular Event Categories','events-manager'),
			'all_items'=>__('All Event Categories','events-manager'),
			'parent_items'=>__('Parent Event Categories','events-manager'),
			'parent_item_colon'=>__('Parent Event Category:','events-manager'),
			'edit_item'=>__('Edit Event Category','events-manager'),
			'update_item'=>__('Update Event Category','events-manager'),
			'add_new_item'=>__('Add New Event Category','events-manager'),
			'new_item_name'=>__('New Event Category Name','events-manager'),
			'separate_items_with_commas'=>__('Separate event categories with commas','events-manager'),
			'add_or_remove_items'=>__('Add or remove events','events-manager'),
			'choose_from_the_most_used'=>__('Choose from most used event categories','events-manager'),
		],
		'capabilities' => [
			'manage_terms' => 'edit_event_categories',
			'edit_terms' => 'edit_event_categories',
			'delete_terms' => 'delete_event_categories',
			'assign_terms' => 'edit_events',
		]
	]));
	
	$event_post_type_supports = apply_filters('em_cp_event_supports', ['title','editor','excerpt','thumbnail','author']);
	$event_post_type = apply_filters('em_cpt_event', [	
		'public' => true,
		'hierarchical' => false,
		'show_ui' => true,
		'show_in_menu' => true,
		'show_in_rest' => true,
		'show_in_nav_menus'=>true,
		'can_export' => true,
		'exclude_from_search' => !get_option('dbem_cp_events_search_results'),
		'publicly_queryable' => true,
		'rewrite' => ['slug' => EM_POST_TYPE_EVENT_SLUG,'with_front'=>false],
		'has_archive' => get_option('dbem_cp_events_has_archive', false) == true,
		'supports' => $event_post_type_supports,
		'capability_type' => 'event',
		'capabilities' => [
			'publish_posts' => 'publish_events',
			'edit_posts' => 'edit_events',
			'edit_others_posts' => 'edit_others_events',
			'delete_posts' => 'delete_events',
			'delete_others_posts' => 'delete_others_events',
			'read_private_posts' => 'read_private_events',
			'edit_post' => 'edit_event',
			'delete_post' => 'delete_event',
			'read_post' => 'read_event',		
		],
		'label' => __('Events','events-manager'),
		'description' => __('Display events on your blog.','events-manager'),
		'labels' => [
			'name' => __('Events','events-manager'),
			'singular_name' => __('Event','events-manager'),
			'menu_name' => __('Events','events-manager'),
			'add_new' => __('Add Event','events-manager'),
			'add_new_item' => __('Add New Event','events-manager'),
			'edit' => __('Edit','events-manager'),
			'edit_item' => __('Edit Event','events-manager'),
			'new_item' => __('New Event','events-manager'),
			'view' => __('View','events-manager'),
			'view_item' => __('View Event','events-manager'),
			'search_items' => __('Search Events','events-manager'),
			'not_found' => __('No Events Found','events-manager'),
			'not_found_in_trash' => __('No Events Found in Trash','events-manager'),
			'parent' => __('Parent Event','events-manager'),
		],
		'menu_icon' => 'dashicons-calendar'
	]);
	if ( get_option('dbem_recurrence_enabled') ){
		$event_recurring_post_type = apply_filters('em_cpt_event_recurring', [	
			'public' => apply_filters('em_cp_event_recurring_public', false),
			'show_ui' => true,
			'show_in_rest' => true,
			'show_in_admin_bar' => true,
			'show_in_menu' => 'edit.php?post_type='.EM_POST_TYPE_EVENT,
			'show_in_nav_menus'=>false,
			'publicly_queryable' => apply_filters('em_cp_event_recurring_publicly_queryable', false),
			'exclude_from_search' => true,
			'has_archive' => false,
			'can_export' => true,
			'hierarchical' => false,
			'supports' => $event_post_type_supports,
			'capability_type' => 'recurring_events',
			'rewrite' => ['slug' => 'events-recurring','with_front'=>false],
			'capabilities' => [
				'publish_posts' => 'publish_recurring_events',
				'edit_posts' => 'edit_recurring_events',
				'edit_others_posts' => 'edit_others_recurring_events',
				'delete_posts' => 'delete_recurring_events',
				'delete_others_posts' => 'delete_others_recurring_events',
				'read_private_posts' => 'read_private_recurring_events',
				'edit_post' => 'edit_recurring_event',
				'delete_post' => 'delete_recurring_event',
				'read_post' => 'read_recurring_event',
			],
			'label' => __('Recurring Events','events-manager'),
			'description' => __('Recurring Events Template','events-manager'),
			'labels' => [
				'name' => __('Recurring Events','events-manager'),
				'singular_name' => __('Recurring Event','events-manager'),
				'menu_name' => __('Recurring Events','events-manager'),
				'add_new' => __('Add Recurring Event','events-manager'),
				'add_new_item' => __('Add New Recurring Event','events-manager'),
				'edit' => __('Edit','events-manager'),
				'edit_item' => __('Edit Recurring Event','events-manager'),
				'new_item' => __('New Recurring Event','events-manager'),
				'view' => __('View','events-manager'),
				'view_item' => __('Add Recurring Event','events-manager'),
				'search_items' => __('Search Recurring Events','events-manager'),
				'not_found' => __('No Recurring Events Found','events-manager'),
				'not_found_in_trash' => __('No Recurring Events Found in Trash','events-manager'),
				'parent' => __('Parent Recurring Event','events-manager'),
			]
		]);
	}
	if( get_option('dbem_locations_enabled', true) ){
		$location_post_type = apply_filters('em_cpt_location', [	
			'public' => true,
			'hierarchical' => false,
			'show_in_rest' => true,
			'show_in_admin_bar' => true,
			'show_ui' => true,
			'show_in_menu' => 'edit.php?post_type='.EM_POST_TYPE_EVENT,
			'show_in_nav_menus'=>true,
			'can_export' => true,
			'exclude_from_search' => !get_option('dbem_cp_locations_search_results'),
			'publicly_queryable' => true,
			'rewrite' => ['slug' => EM_POST_TYPE_LOCATION_SLUG, 'with_front'=>false],
			'query_var' => true,
			'has_archive' => get_option('dbem_cp_locations_has_archive', false) == true,
			'supports' => apply_filters('em_cp_location_supports', ['title','editor','excerpt','custom-fields','comments','thumbnail','author']),
			'capability_type' => 'location',
			'capabilities' => [
				'publish_posts' => 'publish_locations',
				'delete_others_posts' => 'delete_others_locations',
				'delete_posts' => 'delete_locations',
				'delete_post' => 'delete_location',
				'edit_others_posts' => 'edit_others_locations',
				'edit_posts' => 'edit_locations',
				'edit_post' => 'edit_location',
				'read_private_posts' => 'read_private_locations',
				'read_post' => 'read_location',
			],
			'label' => __('Locations','events-manager'),
			'description' => __('Display locations on your blog.','events-manager'),
			'labels' => [
				'name' => __('Locations','events-manager'),
				'singular_name' => __('Location','events-manager'),
				'menu_name' => __('Locations','events-manager'),
				'add_new' => __('Add Location','events-manager'),
				'add_new_item' => __('Add New Location','events-manager'),
				'edit' => __('Edit','events-manager'),
				'edit_item' => __('Edit Location','events-manager'),
				'new_item' => __('New Location','events-manager'),
				'view' => __('View','events-manager'),
				'view_item' => __('View Location','events-manager'),
				'search_items' => __('Search Locations','events-manager'),
				'not_found' => __('No Locations Found','events-manager'),
				'not_found_in_trash' => __('No Locations Found in Trash','events-manager'),
				'parent' => __('Parent Location','events-manager'),
			],
		]);
	}

	
	
	
	

	
	function em_gutenberg_support( $can_edit, $post_type ){
		$recurrences = $post_type == 'event-recurring' && get_option('dbem_recurrence_enabled');
		$locations = $post_type == EM_POST_TYPE_LOCATION && get_option('dbem_locations_enabled', true);
		if( $post_type == EM_POST_TYPE_EVENT || $recurrences || $locations ) $can_edit = true;
		return $can_edit;
	}
	add_filter('gutenberg_can_edit_post_type', 'em_gutenberg_support', 10, 2 ); //Gutenberg

	
	if( strstr(EM_POST_TYPE_EVENT_SLUG, EM_POST_TYPE_LOCATION_SLUG) !== false ){
		//Now register posts, but check slugs in case of conflicts and reorder registrations
		register_post_type(EM_POST_TYPE_EVENT, $event_post_type);
		if ( get_option('dbem_recurrence_enabled') ){
			register_post_type('event-recurring', $event_recurring_post_type);
		}
		register_post_type(EM_POST_TYPE_LOCATION, $location_post_type);
		
	}else{
		register_post_type(EM_POST_TYPE_LOCATION, $location_post_type);
		register_post_type(EM_POST_TYPE_EVENT, $event_post_type);
		//Now register posts, but check slugs in case of conflicts and reorder registrations
		if ( get_option('dbem_recurrence_enabled') ){
			register_post_type('event-recurring', $event_recurring_post_type);
		}
	}

}



function supported_custom_fields($supported, $remove = []){
	foreach($supported as $key => $support_field){
		if( in_array($support_field, $remove) ){
			unset($supported[$key]);
		}
	}
	return $supported;
}

function em_map_meta_cap( $caps, $cap, $user_id, $args ) {
    if( !empty( $args[0]) ){
		/* Handle event reads */
		if ( 'edit_event' == $cap || 'delete_event' == $cap || 'read_event' == $cap ) {
			$post = get_post($args[0]);
			//check for revisions and deal with non-event post types
			if( !empty($post->post_type) && $post->post_type == 'revision' ) $post = get_post($post->post_parent);
			if( empty($post->post_type) || !in_array($post->post_type, array(EM_POST_TYPE_EVENT, 'event-recurring')) ) return $caps;
			//continue with getting post type and assigning caps
			$EM_Event = em_get_event($post);
			$post_type = get_post_type_object( $EM_Event->post_type );
			/* Set an empty array for the caps. */
			$caps = [];
			//Filter according to event caps
			switch( $cap ){
				case 'read_event':
					if ( 'private' != $EM_Event->post_status )
						$caps[] = 'read';
					elseif ( $user_id == $EM_Event->event_owner )
						$caps[] = 'read';
					else
						$caps[] = $post_type->cap->read_private_posts;
					break;
				case 'edit_event':
					if ( $user_id == $EM_Event->event_owner )
						$caps[] = $post_type->cap->edit_posts;
					else
						$caps[] = $post_type->cap->edit_others_posts;
					break;
				case 'delete_event':
					if ( $user_id == $EM_Event->event_owner )
						$caps[] = $post_type->cap->delete_posts;
					else
						$caps[] = $post_type->cap->delete_others_posts;
					break;
			}
		}
		if ( 'edit_recurring_event' == $cap || 'delete_recurring_event' == $cap || 'read_recurring_event' == $cap ) {
			$post = get_post($args[0]);
			//check for revisions and deal with non-event post types
			if( !empty($post->post_type) && $post->post_type == 'revision' ) $post = get_post($post->post_parent);
			if( empty($post->post_type) || $post->post_type != 'event-recurring' ) return $caps;
			//continue with getting post type and assigning caps
			$EM_Event = em_get_event($post);
			$post_type = get_post_type_object( $EM_Event->post_type );
			/* Set an empty array for the caps. */
			$caps = [];
			//Filter according to recurring_event caps
			switch( $cap ){
				case 'read_recurring_event':
					if ( 'private' != $EM_Event->post_status )
						$caps[] = 'read';
					elseif ( $user_id == $EM_Event->event_owner )
						$caps[] = 'read';
					else
						$caps[] = $post_type->cap->read_private_posts;
					break;
				case 'edit_recurring_event':
					if ( $user_id == $EM_Event->event_owner )
						$caps[] = $post_type->cap->edit_posts;
					else
						$caps[] = $post_type->cap->edit_others_posts;
					break;
				case 'delete_recurring_event':
					if ( $user_id == $EM_Event->event_owner )
						$caps[] = $post_type->cap->delete_posts;
					else
						$caps[] = $post_type->cap->delete_others_posts;
					break;
			}
		}
		if ( 'edit_location' == $cap || 'delete_location' == $cap || 'read_location' == $cap ) {
			$post = get_post($args[0]);
			//check for revisions and deal with non-location post types
			if( !empty($post->post_type) && $post->post_type == 'revision' ) $post = get_post($post->post_parent);
			if( empty($post->post_type) || $post->post_type != EM_POST_TYPE_LOCATION ) return $caps;
			//continue with getting post type and assigning caps
			$EM_Location = em_get_location($post);
			$post_type = get_post_type_object( $EM_Location->post_type );
			/* Set an empty array for the caps. */
			$caps = [];
			//Filter according to location caps
			switch( $cap ){
				case 'read_location':
					if ( 'private' != $EM_Location->post_status )
						$caps[] = 'read';
					elseif ( $user_id == $EM_Location->location_owner )
						$caps[] = 'read';
					else
						$caps[] = $post_type->cap->read_private_posts;
					break;
				case 'edit_location':
					if ( $user_id == $EM_Location->location_owner )
						$caps[] = $post_type->cap->edit_posts;
					else
						$caps[] = $post_type->cap->edit_others_posts;
					break;
				case 'delete_location':
					if ( $user_id == $EM_Location->location_owner )
						$caps[] = $post_type->cap->delete_posts;
					else
						$caps[] = $post_type->cap->delete_others_posts;
					break;
			}
		}
    }
	/* Return the capabilities required by the user. */
	return $caps;
}
add_filter( 'map_meta_cap', 'em_map_meta_cap', 10, 4 );