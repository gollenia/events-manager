<?php
class EM_Location_Post {

	const POST_TYPE = EM_POST_TYPE_LOCATION;
	public static function init(){ 
		//Front Side Modifiers
		if( !is_admin() ){
			//override single page with formats? 
			
			add_filter('rest_api_init', ['EM_Location_Post','register_rest_fields']);
			//override excerpts?

		}
		add_action('rest_after_insert_location', array("EM_Location_Post","save_post")); //set to 1 so metadata gets saved ASAP
		
		add_action('parse_query', array('EM_Location_Post','parse_query'));
		add_action( 'init', ['EM_Location_Post', "register_meta"] );
	}	
	
	/**
	 * Overrides the default post format of a location and can display a location as a page, which uses the page.php template.
	 * @param string $template
	 * @return string
	 */
	public static function single_template($template){
		global $post;
		if( !locate_template('single-'.EM_POST_TYPE_LOCATION.'.php') && $post->post_type == EM_POST_TYPE_LOCATION ){
			
			$post_templates = array('page.php','index.php');
			
			if( !empty($post_templates) ){
			    $post_template = locate_template($post_templates,false);
			    if( !empty($post_template) ) $template = $post_template;
			}
		}
		return $template;
	}

	public static function register_rest_fields() {
		register_rest_field(
			'location', 
			'location_id', //New Field Name in JSON RESPONSEs
			['get_callback' => ['EM_Location_Post','add_id_to_rest']]
		);
	}

	public static function add_id_to_rest($object) {
		$EM_Location = EM_Location::get($object["id"], 'post_id');
		return $EM_Location->location_id;
	}
	
	public static function parse_query(){
	    global $wp_query;
		if( !empty($wp_query->query_vars['post_type']) && $wp_query->query_vars['post_type'] == EM_POST_TYPE_LOCATION ){
			$wp_query->query_vars['orderby'] = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby']:'title';
			$wp_query->query_vars['order'] = (!empty($_REQUEST['order'])) ? $_REQUEST['order']:'ASC';	
		}
	}

	/**
	 * Once the post is saved, saves EM meta data
	 * @param int $post_id
	 */
	public static function save_post(\WP_Post $post) : void {
		
		global $wpdb, $EM_Location;
		$post_id = $post->ID;
		
		$saving_status = !in_array(get_post_status($post_id), array('trash','auto-draft'));
		
		if(defined('UNTRASHING_'.$post_id) || !$saving_status) return;
			
		$EM_Location = new EM_Location($post_id, 'post_id');
		
		do_action('em_location_save_pre', $EM_Location);
		//check for existence of index
		$loc_truly_exists = $EM_Location->location_id > 0 && $wpdb->get_var('SELECT location_id FROM '.EM_LOCATIONS_TABLE." WHERE location_id={$EM_Location->location_id}") == $EM_Location->location_id;
		if(empty($EM_Location->location_id) || !$loc_truly_exists){ $EM_Location->save_meta(); }
		//continue
		$EM_Location->get_previous_status(); //before we save anything
		$location_status = $EM_Location->get_status(true);
		$where_array = array($EM_Location->location_name, $EM_Location->location_owner, $EM_Location->location_slug, $EM_Location->location_private, $EM_Location->location_id);
		
		$sql = $wpdb->prepare("UPDATE ".EM_LOCATIONS_TABLE." SET location_name=%s, location_owner=%s, location_slug=%s, location_private=%d, location_status={$location_status} WHERE location_id=%d", $where_array);
		$wpdb->query($sql);
		$get_meta = $EM_Location->get_post_meta($post_id);
		$EM_Location->save_meta();
		apply_filters('em_location_save', true , $EM_Location);
		//flag a cache refresh if we get here
		$EM_Location->refresh_cache = true;
		add_filter('save_post', ['EM_Location_Post', 'refresh_cache'], 100000000);
			
	}

	public static function refresh_cache(){
		global $EM_Location;
		//if this is a published event, and the refresh_cache flag was added to this event during save_post, refresh the meta and update the cache
		if( !empty($EM_Location->refresh_cache) && !empty($EM_Location->post_id) && $EM_Location->is_published() ){
			$post = get_post($EM_Location->post_id);
			$EM_Location->load_postdata($post);
			unset($EM_Location->refresh_cache);
			wp_cache_set($EM_Location->location_id, $EM_Location, 'em_locations');
			wp_cache_set($EM_Location->post_id, $EM_Location->location_id, 'em_locations_ids');
		}
	}

	public static function register_meta() {

		
		$meta_array = [
			["_location_address", 'string', ''],
			["_location_town", 'string', ''],
			["_location_state", 'string', ''],
			["_location_postcode", 'string', ''],
			["_location_region", 'string', ''],
			["_location_url", 'string', ''],
			["_location_country", 'string', ''],
			["_location_latitude", "number", 0],
			["_location_longitude", "number", 0]
		];

		foreach($meta_array as $meta) {
			register_post_meta( 'location', $meta[0], [
				'type' => $meta[1],
				'single'       => true,
				'default' => $meta[2],
				'sanitize_callback' => 'sanitize_text_field',
				'auth_callback' => function() {
					return current_user_can( 'edit_posts' );
				},
				'show_in_rest' => [
					'schema' => [
						'default' => $meta[2],
						'style' => $meta[1]
					]
				]
			]);
		}
	}
}
EM_Location_Post::init();