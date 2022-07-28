<?php
class EM_Location_Post {
	public static function init(){
		//Front Side Modifiers
		if( !is_admin() ){
			//override single page with formats? 
			
			add_filter('rest_api_init', ['EM_Location_Post','register_rest_fields']);
			//override excerpts?
		
		
		
			
		}
		add_action('parse_query', array('EM_Location_Post','parse_query'));
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
		$EM_Location = em_get_location($object["id"], 'post_id');
		return $EM_Location->location_id;
	}
	

	
	
	public static function parse_query(){
	    global $wp_query;
		if( !empty($wp_query->query_vars['post_type']) && $wp_query->query_vars['post_type'] == EM_POST_TYPE_LOCATION ){
			$wp_query->query_vars['orderby'] = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby']:'title';
			$wp_query->query_vars['order'] = (!empty($_REQUEST['order'])) ? $_REQUEST['order']:'ASC';	
		}
	}
}
EM_Location_Post::init();