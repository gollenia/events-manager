<?php

if( !class_exists('EM_Permalinks') ){
	class EM_Permalinks {
		static $em_queryvars = array(
			'event_id','event_slug', 'em_redirect',
		    'recurrence_id',
			'location_id','location_slug',
			'person_id',
			'booking_id',
			'category_id', 'category_slug',
			'ticket_id',
			'calendar_day',
			'rss', 'ical','event_categories','event_locations'
		);
		
		public static function init(){
			
			if( get_option('dbem_flush_needed') ){
				add_filter('wp_loaded', array('EM_Permalinks','flush')); //flush after init, in case there are themes adding cpts etc.
			}
			add_filter('rewrite_rules_array',array('EM_Permalinks','rewrite_rules_array'));
			add_filter('query_vars',array('EM_Permalinks','query_vars'));
			add_action('parse_query',array('EM_Permalinks','init_objects'), 1);
			add_action('parse_query',array('EM_Permalinks','redirection'), 1);
			if( !defined('EM_EVENT_SLUG') ){ define('EM_EVENT_SLUG','event'); }
			if( !defined('EM_LOCATION_SLUG') ){ define('EM_LOCATION_SLUG','location'); }
			if( !defined('EM_LOCATIONS_SLUG') ){ define('EM_LOCATIONS_SLUG','locations'); }
			if( !defined('EM_CATEGORY_SLUG') ){ define('EM_CATEGORY_SLUG','category'); }
			if( !defined('EM_CATEGORIES_SLUG') ){ define('EM_CATEGORIES_SLUG','categories'); }
			
		}
		
		public static function flush(){
			global $wp_rewrite;
			$wp_rewrite->flush_rules();
			update_option('dbem_flush_needed', 0);
		}
		
		public static function post_type_archive_link($link, $post_type){
			return "";
		}
		
		/**
		 * will redirect old links to new link structures.
		 */
		public static function redirection(){
			global $wpdb, $wp_query;
			if( is_object($wp_query) && $wp_query->get('em_redirect') ){
				//is this a querystring url?
				if( $wp_query->get('event_slug') ){
					$event = $wpdb->get_row('SELECT event_id, post_id FROM '.EM_EVENTS_TABLE." WHERE event_slug='".$wp_query->get('event_slug')."' AND (blog_id=".get_current_blog_id()." OR blog_id IS NULL OR blog_id=0)", ARRAY_A);
					if( !empty($event) ){
						$EM_Event = em_get_event($event['event_id']);
						$url = get_permalink($EM_Event->post_id);
					}
				}elseif( $wp_query->get('location_slug') ){
					$location = $wpdb->get_row('SELECT location_id, post_id FROM '.EM_LOCATIONS_TABLE." WHERE location_slug='".$wp_query->get('location_slug')."' AND (blog_id=".get_current_blog_id()." OR blog_id IS NULL OR blog_id=0)", ARRAY_A);
					if( !empty($location) ){
						$EM_Location = em_get_location($location['location_id']);
						$url = get_permalink($EM_Location->post_id);
					}
				}elseif( $wp_query->get('category_slug') ){
					$url = get_term_link($wp_query->get('category_slug'), EM_TAXONOMY_CATEGORY);
				}
				if(!empty($url)){
					wp_safe_redirect($url,301);
					exit();
				}
			}
		}

		// Adding a new rule
		public static function rewrite_rules_array($rules){
			global $wpdb;
			//get the slug of the event page
			$em_rules = array();
		
			$events_slug = EM_POST_TYPE_EVENT_SLUG;
			$em_rules[$events_slug.'/(\d{4}-\d{2}-\d{2})$'] = 'index.php?post_type='.EM_POST_TYPE_EVENT.'&calendar_day=$matches[1]'; //event calendar date search
			$em_rules[$events_slug.'/(\d{4}-\d{2}-\d{2})/page/?([0-9]{1,})/?$'] = 'index.php?post_type='.EM_POST_TYPE_EVENT.'&calendar_day=$matches[1]&paged=$matches[2]'; //event calendar date search paged
			
			//check for potentially conflicting posts with the same slug as events
			$conflicting_posts = get_posts(array('name'=>EM_POST_TYPE_EVENT_SLUG, 'post_type'=>'any', 'numberposts'=>0));
			if( count($conflicting_posts) > 0 ){ //won't apply on homepage
				foreach($conflicting_posts as $conflicting_post){
					//make sure we hard-code rewrites for child pages of events
					$child_posts = get_posts(array('post_type'=>'any', 'post_parent'=>$conflicting_post->ID, 'numberposts'=>0));
					foreach($child_posts as $child_post){
						$em_rules[EM_POST_TYPE_EVENT_SLUG.'/'.urldecode($child_post->post_name).'/?$'] = 'index.php?page_id='.$child_post->ID; //single event booking form with slug
						//check if child page has children
						$grandchildren = get_pages('child_of='.$child_post->ID);
						if( count( $grandchildren ) != 0 ) {
							foreach($grandchildren as $grandchild) {
								$em_rules[$events_slug.urldecode($child_post->post_name).'/'.urldecode($grandchild->post_name).'/?$'] = 'index.php?page_id='.$grandchild->ID;
							}
						}
					}
				}
			}
			
			//Check the event category and tags pages, because if we're overriding the pages and they're not within the Events page hierarchy it may 404
			//if taxonomy base permalink is same as page permalink
			foreach( array('tags','categories') as $taxonomy_name ){
				if( get_option('dbem_'.$taxonomy_name.'_enabled') ){
					$taxonomy_page_id = get_option ( 'dbem_'.$taxonomy_name.'_page' );
					$taxonomy_page = get_post($taxonomy_page_id);
					if( is_object($taxonomy_page) ){
						//we are using a categories page, so we add it to permalinks if it's not a parent of the events page
						if( !is_object($events_page) || !in_array($events_page->ID, get_post_ancestors($taxonomy_page_id)) ){
							$taxonomy_slug = urldecode(preg_replace('/\/$/', '', str_replace( trailingslashit(home_url()), '', get_permalink($taxonomy_page_id)) ));
							$taxonomy_slug = ( !empty($taxonomy_slug) ) ? trailingslashit($taxonomy_slug) : $taxonomy_slug;
							$em_rules[trim($taxonomy_slug,'/').'/?$'] = 'index.php?pagename='.trim($taxonomy_slug,'/') ;
						}
					}
				}
			}
			$em_rules = apply_filters('em_rewrite_rules_array_events', $em_rules, $events_slug);
			//make sure there's no page with same name as archives, that should take precedence as it can easily be deleted wp admin side
			$em_query = new WP_Query(array('pagename'=>EM_POST_TYPE_EVENT_SLUG));
			if( $em_query->have_posts() ){
				$em_rules[trim(EM_POST_TYPE_EVENT_SLUG,'/').'/?$'] = 'index.php?pagename='.trim(EM_POST_TYPE_EVENT_SLUG,'/') ;
				wp_reset_postdata();
			}
			//make sure there's no page with same name as archives, that should take precedence as it can easily be deleted wp admin side
			$em_query = new WP_Query(array('pagename'=>EM_POST_TYPE_LOCATION_SLUG));
			if( $em_query->have_posts() ){
				$em_rules[trim(EM_POST_TYPE_LOCATION_SLUG,'/').'/?$'] = 'index.php?pagename='.trim(EM_POST_TYPE_LOCATION_SLUG,'/') ;
				wp_reset_postdata();
			}
			//add ical CPT endpoints
			$em_rules[EM_POST_TYPE_EVENT_SLUG."/([^/]+)/ical/?$"] = 'index.php?'.EM_POST_TYPE_EVENT.'=$matches[1]&ical=1';
			if( get_option('dbem_locations_enabled') ){
				$em_rules[EM_POST_TYPE_LOCATION_SLUG."/([^/]+)/ical/?$"] = 'index.php?'.EM_POST_TYPE_LOCATION.'=$matches[1]&ical=1';
			}
			//add ical taxonomy endpoints
			$taxonomies = EM_Object::get_taxonomies();
			foreach($taxonomies as $tax_arg => $taxonomy_info){
				//set the dynamic rule for this taxonomy
				$em_rules[$taxonomy_info['slug']."/(.+)/ical/?$"] = 'index.php?'.$taxonomy_info['query_var'].'=$matches[1]&ical=1';
			}
			//add RSS location CPT endpoint
			if( get_option('dbem_locations_enabled') ){
				$em_rules[EM_POST_TYPE_LOCATION_SLUG."/([^/]+)/rss/?$"] = 'index.php?'.EM_POST_TYPE_LOCATION.'=$matches[1]&rss=1';
			}
			$em_rules = apply_filters('em_rewrite_rules_array', $em_rules);
			return $em_rules + $rules;
		}
		
		/**
		 * deprecated, use get_post_permalink() from now on or the output function with a placeholder
		 * Generate a URL. Pass each section of a link as a parameter, e.g. EM_Permalinks::url('event',$event_id); will create an event link.
		 * @return string 
		 * @deprecated
		 */
		public static function url(){
			return "";
		}
		
		// Adding the id var so that WP recognizes it
		public static function query_vars($vars){
			foreach(self::$em_queryvars as $em_queryvar){
				array_push($vars, $em_queryvar);
			}
		    return $vars;
		}
		
		/**
		 * Not the "WP way" but for now this'll do!
		 * This function tricks WP into thinking an EM static home page is just a page so that query_vars don't prevent the home page from showing properly.
		 * This is an old problem described here : https://core.trac.wordpress.org/ticket/25143
		 * Since we use these query vars in other areas and need to allow home page static settings to work, this connects the two sides so they can co-exist in different environments
		 */
		public static function init_objects(){
			global $wp_rewrite, $wp_query;
			
			if ( is_object($wp_query) && is_object($wp_rewrite) && $wp_rewrite->using_permalinks() ) {
				foreach(self::$em_queryvars as $em_queryvar){
					if( $wp_query->get($em_queryvar) ) {
						$_REQUEST[$em_queryvar] = $wp_query->get($em_queryvar);
					}
				}
		    }
			//dirty rss condition
			if( !empty($_REQUEST['rss']) ){
				$_REQUEST['rss_main'] = 'main';
			}
		}
	}
	EM_Permalinks::init();
}

//Specific links that aren't generated by objects


/**
 * Gets the admin URL for editing events. If called from front-end and there's a front-end edit events page, that will be
 * returned, otherwise a url to the dashboard will be returned.
 */
function em_get_events_admin_url(){
    $admin_url = admin_url('edit.php?post_type=event');
    return apply_filters('em_get_events_admin_url', $admin_url);
}
