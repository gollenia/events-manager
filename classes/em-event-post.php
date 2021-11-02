<?php
/**
 * Controls how events are queried and displayed via the WordPress Custom Post APIs
 * @author marcus
 *
 */
class EM_Event_Post {
	
	public static function init(){
		//Front Side Modifiers
		if( !is_admin() ){

			//Override post template tags
			add_filter('get_the_date',array('EM_Event_Post','the_date'),10,3);
			add_filter('get_the_time',array('EM_Event_Post','the_time'),10,3);
			add_filter('the_category',array('EM_Event_Post','the_category'),10,3);
		}
		add_action('parse_query', array('EM_Event_Post','parse_query'));
		add_action('publish_future_post',array('EM_Event_Post','publish_future_post'),10,1);
	}
	
	public static function publish_future_post($post_id){
		global $EM_Event;
		$post_type = get_post_type($post_id);
		$is_post_type = $post_type == EM_POST_TYPE_EVENT || $post_type == 'event-recurring';
		$saving_status = !in_array(get_post_status($post_id), array('trash','auto-draft')) && !defined('DOING_AUTOSAVE');
		if(!defined('UNTRASHING_'.$post_id) && $is_post_type && $saving_status ){
		    $EM_Event = em_get_event($post_id, 'post_id');
		    $EM_Event->set_status(1);
		}
	}
	
	/**
	 * Overrides the default post format of an event and can display an event as a page, which uses the page.php template.
	 * @param string $template
	 * @return string
	 */
	public static function single_template($template){
		global $post;
		if( !locate_template('single-'.EM_POST_TYPE_EVENT.'.php') && $post->post_type == EM_POST_TYPE_EVENT ){
			//do we have a default template to choose for events?
			if( get_option('dbem_cp_events_template') == 'page' ){
				$post_templates = array('page.php','index.php');
			}else{
			    $post_templates = array(get_option('dbem_cp_events_template'));
			}
			if( !empty($post_templates) ){
			    $post_template = locate_template($post_templates,false);
			    if( !empty($post_template) ) $template = $post_template;
			}
		}
		return $template;
	}
	

	
	public static function enable_the_content( $content ){
		add_filter('the_content', array('EM_Event_Post','the_content'));
		return $content;
	}
	public static function disable_the_content( $content ){
		remove_filter('the_content', array('EM_Event_Post','the_content'));
		return $content;
	}
	
	public static function the_date( $the_date, $d = '', $post = null ){
		$post = get_post( $post );
		if( $post->post_type == EM_POST_TYPE_EVENT ){
			$EM_Event = em_get_event($post);
			if ( '' == $d ){
				$the_date = $EM_Event->start()->i18n(get_option('date_format'));
			}else{
				$the_date = $EM_Event->start()->i18n($d);
			}
		}
		return $the_date;
	}
	
	public static function the_time( $the_time, $f = '', $post = null ){
		$post = get_post( $post );
		if( $post->post_type == EM_POST_TYPE_EVENT ){
			$EM_Event = em_get_event($post);
			if ( '' == $f ){
				$the_time = $EM_Event->start()->i18n(get_option('time_format'));
			}else{
				$the_time = $EM_Event->start()->i18n($f);
			}
		}
		return $the_time;
	}
	
	public static function the_category( $thelist, $separator = '', $parents='' ){
		global $post, $wp_rewrite;
		if( $post->post_type == EM_POST_TYPE_EVENT ){
			$EM_Event = em_get_event($post);
			$categories = $EM_Event->get_categories();
			if( empty($categories) ) return '';
			
			/* Copied from get_the_category_list function, with a few minor edits to make urls work, and removing parent stuff (for now) */
			$rel = ( is_object( $wp_rewrite ) && $wp_rewrite->using_permalinks() ) ? 'rel="category tag"' : 'rel="category"';

			$thelist = '';
			if ( '' == $separator ) {
				$thelist .= '<ul class="post-categories">';
				foreach ( $categories as $category ) {
					$thelist .= "\n\t<li>";
					switch ( strtolower( $parents ) ) {
						case 'multiple':
							$thelist .= '<a href="' . $category->get_url() . '" title="' . esc_attr( sprintf( __( "View all posts in %s", 'events-manager'), $category->name ) ) . '" ' . $rel . '>' . $category->name.'</a></li>';
							break;
						case 'single':
							$thelist .= '<a href="' . $category->get_url() . '" title="' . esc_attr( sprintf( __( "View all posts in %s", 'events-manager'), $category->name ) ) . '" ' . $rel . '>';
							$thelist .= $category->name.'</a></li>';
							break;
						case '':
						default:
							$thelist .= '<a href="' . $category->get_url() . '" title="' . esc_attr( sprintf( __( "View all posts in %s", 'events-manager'), $category->name ) ) . '" ' . $rel . '>' . $category->name.'</a></li>';
					}
				}
				$thelist .= '</ul>';
			} else {
				$i = 0;
				foreach ( $categories as $category ) {
					if ( 0 < $i )
						$thelist .= $separator;
					switch ( strtolower( $parents ) ) {
						case 'multiple':
							$thelist .= '<a href="' . $category->get_url() . '" title="' . esc_attr( sprintf( __( "View all posts in %s", 'events-manager'), $category->name ) ) . '" ' . $rel . '>' . $category->name.'</a>';
							break;
						case 'single':
							$thelist .= '<a href="' . $category->get_url() . '" title="' . esc_attr( sprintf( __( "View all posts in %s", 'events-manager'), $category->name ) ) . '" ' . $rel . '>';
							$thelist .= "$category->name</a>";
							break;
						case '':
						default:
							$thelist .= '<a href="' . $category->get_url() . '" title="' . esc_attr( sprintf( __( "View all posts in %s", 'events-manager'), $category->name ) ) . '" ' . $rel . '>' . $category->name.'</a>';
					}
					++$i;
				}
			}
			/* End copying */
		}
		return $thelist;
	}
	
	public static function parse_query(){
	    global $wp_query;
		//Search Query Filtering
		if( is_admin() ){
		    if( !empty($wp_query->query_vars[EM_TAXONOMY_CATEGORY]) && is_numeric($wp_query->query_vars[EM_TAXONOMY_CATEGORY]) ){
		        //sorts out filtering admin-side as it searches by id
		        $term = get_term_by('id', $wp_query->query_vars[EM_TAXONOMY_CATEGORY], EM_TAXONOMY_CATEGORY);
		        $wp_query->query_vars[EM_TAXONOMY_CATEGORY] = ( $term !== false && !is_wp_error($term) )? $term->slug:0;
		    }
		}
		//Scoping
		if( !empty($wp_query->query_vars['post_type']) && ($wp_query->query_vars['post_type'] == EM_POST_TYPE_EVENT || $wp_query->query_vars['post_type'] == 'event-recurring') && (empty($wp_query->query_vars['post_status']) || !in_array($wp_query->query_vars['post_status'],array('trash','pending','draft'))) ) {
		    $query = array();
			//Let's deal with the scope - default is future
			if( is_admin() ){
				$scope = $wp_query->query_vars['scope'] = (!empty($_REQUEST['scope'])) ? $_REQUEST['scope']:'future';
				//TODO limit what a user can see admin side for events/locations/recurring events
				if( !empty($wp_query->query_vars['recurrence_id']) && is_numeric($wp_query->query_vars['recurrence_id']) ){
				    $query[] = array( 'key' => '_recurrence_id', 'value' => $wp_query->query_vars['recurrence_id'], 'compare' => '=' );
				}
			}else{
				if( !empty($wp_query->query_vars['calendar_day']) ) $wp_query->query_vars['scope'] = $wp_query->query_vars['calendar_day'];
				if( empty($wp_query->query_vars['scope']) ){
					if( is_archive() ){
						$scope = $wp_query->query_vars['scope'] = get_option('dbem_events_archive_scope');
					}else{
						$scope = $wp_query->query_vars['scope'] = 'all'; //otherwise we'll get 404s for past events
					}
				}else{
					$scope = $wp_query->query_vars['scope'];
				}
			}
			if ( $scope == 'today' || $scope == 'tomorrow' || preg_match ( "/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $scope ) ) {
				$EM_DateTime = new EM_DateTime($scope); //create default time in blog timezone
				if( get_option('dbem_events_current_are_past') && $wp_query->query_vars['post_type'] != 'event-recurring' ){
					$query[] = array( 'key' => '_event_start_date', 'value' => $EM_DateTime->getDate() );
				}else{
					$query[] = array( 'key' => '_event_start_date', 'value' => $EM_DateTime->getDate(), 'compare' => '<=', 'type' => 'DATE' );
					$query[] = array( 'key' => '_event_end_date', 'value' => $EM_DateTime->getDate(), 'compare' => '>=', 'type' => 'DATE' );
				}				
			}elseif ($scope == "future" || $scope == 'past' ){
				$EM_DateTime = new EM_DateTime(); //create default time in blog timezone
				$EM_DateTime->setTimezone('UTC');
				$compare = $scope == 'future' ? '>=' : '<';
				if( get_option('dbem_events_current_are_past') && $wp_query->query_vars['post_type'] != 'event-recurring' ){
					$query[] = array( 'key' => '_event_start', 'value' => $EM_DateTime->getDateTime(), 'compare' => $compare, 'type' => 'DATETIME' );
				}else{
					$query[] = array( 'key' => '_event_end', 'value' => $EM_DateTime->getDateTime(), 'compare' => $compare, 'type' => 'DATETIME' );
				}
			}elseif ($scope == "month" || $scope == "next-month" || $scope == 'this-month'){
				$EM_DateTime = new EM_DateTime(); //create default time in blog timezone
				if( $scope == 'next-month' ) $EM_DateTime->add('P1M');
				$start_month = $scope == 'this-month' ? $EM_DateTime->getDate() : $EM_DateTime->modify('first day of this month')->getDate();
				$end_month = $EM_DateTime->modify('last day of this month')->getDate();
				if( get_option('dbem_events_current_are_past') && $wp_query->query_vars['post_type'] != 'event-recurring' ){
					$query[] = array( 'key' => '_event_start_date', 'value' => array($start_month,$end_month), 'type' => 'DATE', 'compare' => 'BETWEEN');
				}else{
					$query[] = array( 'key' => '_event_start_date', 'value' => $end_month, 'compare' => '<=', 'type' => 'DATE' );
					$query[] = array( 'key' => '_event_end_date', 'value' => $start_month, 'compare' => '>=', 'type' => 'DATE' );
				}
			}elseif ($scope == "week" || $scope == 'this-week'){
				$EM_DateTime = new EM_DateTime(); //create default time in blog timezone
				list($start_date, $end_date) = $EM_DateTime->get_week_dates( $scope );
				if( get_option('dbem_events_current_are_past') && $wp_query->query_vars['post_type'] != 'event-recurring' ){
					$query[] = array( 'key' => '_event_start_date', 'value' => array($start_date,$end_date), 'type' => 'DATE', 'compare' => 'BETWEEN');
				}else{
					$query[] = array( 'key' => '_event_start_date', 'value' => $end_date, 'compare' => '<=', 'type' => 'DATE' );
					$query[] = array( 'key' => '_event_end_date', 'value' => $start_date, 'compare' => '>=', 'type' => 'DATE' );
				}
			}elseif( preg_match('/(\d\d?)\-months/',$scope,$matches) ){ // next x months means this month (what's left of it), plus the following x months until the end of that month.
				$EM_DateTime = new EM_DateTime(); //create default time in blog timezone
				$months_to_add = $matches[1];
				$start_month = $EM_DateTime->getDate();
				$end_month = $EM_DateTime->add('P'.$months_to_add.'M')->format('Y-m-t');
				if( get_option('dbem_events_current_are_past') && $wp_query->query_vars['post_type'] != 'event-recurring' ){
					$query[] = array( 'key' => '_event_start_date', 'value' => array($start_month,$end_month), 'type' => 'DATE', 'compare' => 'BETWEEN');
				}else{
					$query[] = array( 'key' => '_event_start_date', 'value' => $end_month, 'compare' => '<=', 'type' => 'DATE' );
					$query[] = array( 'key' => '_event_end_date', 'value' => $start_month, 'compare' => '>=', 'type' => 'DATE' );
				}
			}elseif( !empty($scope) ){
				$query = apply_filters('em_event_post_scope_meta_query', $query, $scope);
			}
		  	if( !empty($query) && is_array($query) ){
				$wp_query->query_vars['meta_query'] = $query;
		  	}
		  	if( is_admin() ){
		  		//admin areas don't need special ordering, so make it simple
		  		if( !empty($_REQUEST['orderby']) && $_REQUEST['orderby'] != 'date-time' ){
		  			$wp_query->query_vars['orderby'] = sanitize_key($_REQUEST['orderby']);
		  		}else{
				  	$wp_query->query_vars['orderby'] = 'meta_value';
				  	$wp_query->query_vars['meta_key'] = '_event_start_local';
				  	$wp_query->query_vars['meta_type'] = 'DATETIME';
		  		}
				$wp_query->query_vars['order'] = (!empty($_REQUEST['order']) && preg_match('/^(ASC|DESC)$/i', $_REQUEST['order'])) ? $_REQUEST['order']:'ASC';
		  	}else{
			  	if( get_option('dbem_events_default_archive_orderby') == 'title'){
			  		$wp_query->query_vars['orderby'] = 'title';
					$wp_query->query_vars['order'] = get_option('dbem_events_default_archive_order','ASC');
			  	}else{
				  	$wp_query->query_vars['orderby'] = 'meta_value';
				  	$wp_query->query_vars['meta_key'] = '_event_start_local';
				  	$wp_query->query_vars['meta_type'] = 'DATETIME';		
			  	}
			  	$wp_query->query_vars['order'] = get_option('dbem_events_default_archive_order','ASC');
		  	}
		}elseif( !empty($wp_query->query_vars['post_type']) && $wp_query->query_vars['post_type'] == EM_POST_TYPE_EVENT ){
			$wp_query->query_vars['scope'] = 'all';
			if( $wp_query->query_vars['post_status'] == 'pending' ){
			  	$wp_query->query_vars['orderby'] = 'meta_value';
			  	$wp_query->query_vars['order'] = 'ASC';
			  	$wp_query->query_vars['meta_key'] = '_event_start_local';
			  	$wp_query->query_vars['meta_type'] = 'DATETIME';	
			}
		}
	}
}
EM_Event_Post::init();