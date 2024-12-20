<?php

class EM_Speakers {

    public static function register() {
        $instance = new self;
        
        add_action( 'init', array($instance, 'register_post_type') );
		add_action( 'rest_api_init', array($instance, 'register_metadata'));
        add_filter( 'manage_event-speaker_posts_columns', array($instance, 'set_custom_columns') );
        add_action( 'manage_event-speaker_posts_custom_column' , array($instance, 'custom_column'), 10, 2 );
        add_action( 'edit_form_advanced', [$instance, 'add_back_button'] );
    }

	public function register_post_type(){
		$args = apply_filters('em_cpt_speaker', [	
            'public' => false,
            'hierarchical' => false,
            'show_in_rest' => true,
            'show_in_admin_bar' => true,
            'show_ui' => true,
            'show_in_menu' => 'edit.php?post_type=event',
            'show_in_nav_menus'=>true,
            'can_export' => true,
            'publicly_queryable' => false,
            'rewrite' => ['slug' => 'event-speaker', 'with_front'=>false],
            'query_var' => false,
            'has_archive' => false,
            'supports' => ['title', 'thumbnail', 'editor', 'excerpt', 'custom-fields'],
            'label' => __('Speakers','events'),
            'description' => __('Speakers for an event.','events'),
            'labels' => [
                'name' => __('Speakers','events'),
                'singular_name' => __('Speaker','events'),
                'menu_name' => __('Speakers','events'),
                'add_new' => __('Add Speaker','events'),
                'add_new_item' => __('Add New Speaker','events'),
                'edit' => __('Edit','events'),
                'edit_item' => __('Edit Speaker','events'),
                'new_item' => __('New Speaker','events'),
                'view' => __('View','events'),
                'view_item' => __('View Speaker','events'),
                'search_items' => __('Search Speaker','events'),
                'not_found' => __('No Speaker Found','events'),
                'not_found_in_trash' => __('No Speaker Found in Trash','events'),
                'parent' => __('Parent Speaker','events'),
            ],
        ]);

		register_post_type( 'event-speaker', $args );     
    }

	public function register_metadata() {
		
		register_post_meta( 'event-speaker', '_email', [
			'type' => 'string',
			'show_in_rest' => [
				'schema' => [
					'default' => '',
					'style' => "string"
				]
				],
			'single'       => true,
			'default'      => '',
			'auth_callback' => function() {
				return current_user_can( 'edit_posts' );
			}
		]);

		register_post_meta('event-speaker', '_phone', [
			'type' => 'string',
			'show_in_rest' => [
				'schema' => [
					'default' => '',
					'style' => "string"
				]
				],
			'single'       => true,
			'default'      => '',
			'auth_callback' => function() {
				return current_user_can( 'edit_posts' );
			}
		]);

		register_post_meta('event-speaker', '_gender', [
			'type' => 'string',
			'show_in_rest' => [
				'schema' => [
					'default' => '',
					'style' => "string"
				]
				],
			'single'       => true,
			'default'      => 'male',
			'auth_callback' => function() {
				return current_user_can( 'edit_posts' );
			}
		]);

		register_post_meta('event-speaker', '_role', [
			'type' => 'string',
			'show_in_rest' => [
				'schema' => [
					'default' => '',
					'style' => "string"
				]
				],
			'single'       => true,
			'default'      => '',
			'auth_callback' => function() {
				return current_user_can( 'edit_posts' );
			}
		]);

		register_rest_field( 'event-speaker', 'meta', [
			'get_callback' => function($object) {
				$meta = get_post_meta($object['id']);
				$meta['thumbnail'] = get_the_post_thumbnail_url($object['id']);
				return $meta;
			},
			'schema' => null,
		]);

	}

    public function register_custom_fields() {
		
	}
    
    public function set_custom_columns($columns) {
        $columns['email'] = __( 'E-Mail', 'ctx-theme' );

        return $columns;
    }

    public function custom_column( $column, $post_id ) {
        if($column == "email") {
            $email = get_post_meta( $post_id , 'email' , true );
            
            echo $email;
        }
    }

	public static function get($id) {
		if($id == 0) return false;
		$args = array(
			'p'         => $id, // ID of a page, post, or custom type
			'post_type' => 'event-speaker'
		  );
		$query = new WP_Query($args);
		$result = $query->get_posts();
		if(empty($result)) return false;
		$speaker = $result[0];
		
		return $speaker;
	}

    public function add_back_button( $post ) {
        if( $post->post_type == 'event-speaker' )
            echo "<a class='button button-primary button-large' href='edit.php?post_type=event-speaker' id='my-custom-header-link'>" . __('Back', 'ctx-theme') . "</a>";
    }

    
}
    
EM_Speakers::register();