<?php

namespace Contexis\Events\Forms;	

class Post {

	const ATTENDEE_POST_TYPE = 'attendeeforms';
	const BOOKING_POST_TYPE = 'bookingforms';

	public static function init() {
		$instance = new self;
		add_action( 'init', array( $instance, 'post_type' ) );
		add_filter( 'generate_rewrite_rules', array( $instance, 'rewrite_rules' ) );
		return $instance;
	}

	function rewrite_rules( $wp_rewrite ) {
		$feed_rules = array(
		  '/wp-admin/edit.php?post_type=bookingform' => '/wp-admin/edit.php?post_type=event&page=events-forms',
		  '/wp-admin/edit.php?post_type=attendeeform' => '/wp-admin/edit.php?post_type=event&page=events-forms',
		);
	 
		$wp_rewrite->rules = $feed_rules + $wp_rewrite->rules;
		return $wp_rewrite->rules;
	}
	 
	

	public function post_type() {
		if( get_option('dbem_rsvp_enabled', true) ){
			$forms_post_type = [	
				'public' => false,
				'hierarchical' => false,
				'show_in_rest' => true,
				'show_in_admin_bar' => true,
				'show_ui' => true,
				'show_in_menu' => false,
				'show_in_nav_menus'=>false,
				'can_export' => true,
				'exclude_from_search' => true,
				'publicly_queryable' => true,
				'rewrite' => ['slug' => self::BOOKING_POST_TYPE, 'with_front'=>false],
				'query_var' => true,
				'has_archive' => false,
				'supports' => ['title','excerpt','editor'],
				'label' => __('Forms','events'),
				'description' => __('Display forms on your blog.','events'),
				'template' => [
					['events/form-container', [], [
						['events/form-email', ["lock" => ["remove" => true, "move" => false], "required" => true, "label" => __('Email', 'events'), "fieldid" => 'user_email']],
						['events/form-text', ["lock" => ["remove" => true, "move" => false], "required" => true, "width" => 3, "label" => __('First Name', 'events'), "fieldid" => 'first_name']],
						['events/form-text', ["lock" => ["remove" => true, "move" => false], "required" => true, "width" => 3, "label" => __('Last Name', 'events'), "fieldid" => 'last_name']]]]
				],
				'labels' => [
					'name' => __('Booking Form','events'),
					'singular_name' => __('Form','events'),
					'menu_name' => __('Forms','events'),
					'add_new' => __('Add Booking Form','events'),
					'add_new_item' => __('Add New Form','events'),
					'edit' => __('Edit','events'),
					'edit_item' => __('Edit Form','events'),
					'new_item' => __('New Form','events'),
					'view' => __('View','events'),
					'view_item' => __('View Form','events'),
					'search_items' => __('Search Forms','events'),
					'not_found' => __('No Forms Found','events'),
					'not_found_in_trash' => __('No Forms Found in Trash','events'),
					'parent' => __('Parent Form','events'),
				],
			];
	
			$attendeeforms_post_type = [	
				'public' => false,
				'hierarchical' => false,
				'show_in_rest' => true,
				'show_in_admin_bar' => true,
				'show_ui' => true,
				'show_in_menu' => false,
				'show_in_nav_menus'=>false,
				'can_export' => true,
				'exclude_from_search' => true,
				'publicly_queryable' => true,
				'rewrite' => ['slug' => self::ATTENDEE_POST_TYPE, 'with_front'=>false],
				'query_var' => true,
				'has_archive' => false,
				'supports' => ['title','excerpt','editor'],
				'label' => __('Forms','events'),
				'description' => __('Display forms on your blog.','events'),
				'template' => [
					['events/form-container', [], [
						['events/form-text', ["required" => true, "width" => 3, "label" => __('Name', 'events'), "fieldid" => 'name']]
					]]
				],
				'labels' => [
					'name' => __('Attendee Form','events'),
					'singular_name' => __('Form','events'),
					'menu_name' => __('Forms','events'),
					'add_new' => __('Add Form','events'),
					'add_new_item' => __('Add New Form','events'),
					'edit' => __('Edit','events'),
					'edit_item' => __('Edit Form','events'),
					'new_item' => __('New Form','events'),
					'view' => __('View','events'),
					'view_item' => __('View Form','events'),
					'search_items' => __('Search Forms','events'),
					'not_found' => __('No Forms Found','events'),
					'not_found_in_trash' => __('No Forms Found in Trash','events'),
					'parent' => __('Parent Form','events'),
				],
			];
	
			register_post_type('bookingform', $forms_post_type);
			register_post_type('attendeeform', $attendeeforms_post_type);
		}
	}
}

Post::init();