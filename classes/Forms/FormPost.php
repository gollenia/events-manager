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
		  '/wp-admin/edit.php?post_type=bookingform' => '/wp-admin/edit.php?post_type=event&page=events-manager-forms',
		  '/wp-admin/edit.php?post_type=attendeeform' => '/wp-admin/edit.php?post_type=event&page=events-manager-forms',
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
				'label' => __('Forms','events-manager'),
				'description' => __('Display forms on your blog.','events-manager'),
				'template' => [
					['events-manager/form-container', [], [
						['events-manager/form-email', ["lock" => ["remove" => true, "move" => false], "required" => true, "label" => __('Email', 'events-manager'), "fieldid" => 'user_email']],
						['events-manager/form-text', ["lock" => ["remove" => true, "move" => false], "required" => true, "width" => 3, "label" => __('First Name', 'events-manager'), "fieldid" => 'first_name']],
						['events-manager/form-text', ["lock" => ["remove" => true, "move" => false], "required" => true, "width" => 3, "label" => __('Last Name', 'events-manager'), "fieldid" => 'last_name']]]]
				],
				'labels' => [
					'name' => __('Booking Form','events-manager'),
					'singular_name' => __('Form','events-manager'),
					'menu_name' => __('Forms','events-manager'),
					'add_new' => __('Add Booking Form','events-manager'),
					'add_new_item' => __('Add New Form','events-manager'),
					'edit' => __('Edit','events-manager'),
					'edit_item' => __('Edit Form','events-manager'),
					'new_item' => __('New Form','events-manager'),
					'view' => __('View','events-manager'),
					'view_item' => __('View Form','events-manager'),
					'search_items' => __('Search Forms','events-manager'),
					'not_found' => __('No Forms Found','events-manager'),
					'not_found_in_trash' => __('No Forms Found in Trash','events-manager'),
					'parent' => __('Parent Form','events-manager'),
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
				'label' => __('Forms','events-manager'),
				'description' => __('Display forms on your blog.','events-manager'),
				'template' => [
					['events-manager/form-container', [], [
						['events-manager/form-text', ["required" => true, "width" => 3, "label" => __('Name', 'events-manager'), "fieldid" => 'name']]
					]]
				],
				'labels' => [
					'name' => __('Attendee Form','events-manager'),
					'singular_name' => __('Form','events-manager'),
					'menu_name' => __('Forms','events-manager'),
					'add_new' => __('Add Form','events-manager'),
					'add_new_item' => __('Add New Form','events-manager'),
					'edit' => __('Edit','events-manager'),
					'edit_item' => __('Edit Form','events-manager'),
					'new_item' => __('New Form','events-manager'),
					'view' => __('View','events-manager'),
					'view_item' => __('View Form','events-manager'),
					'search_items' => __('Search Forms','events-manager'),
					'not_found' => __('No Forms Found','events-manager'),
					'not_found_in_trash' => __('No Forms Found in Trash','events-manager'),
					'parent' => __('Parent Form','events-manager'),
				],
			];
	
			register_post_type('bookingform', $forms_post_type);
			register_post_type('attendeeform', $attendeeforms_post_type);
		}
	}
}

Post::init();