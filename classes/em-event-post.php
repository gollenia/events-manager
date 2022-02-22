<?php
/**
 * Controls how events are queried and displayed via the WordPress Custom Post APIs
 * @author marcus
 *
 */
class EM_Event_Post {
	
	public static function init(){

		$instance = new self;
		//Front Side Modifiers
		if( !is_admin() ){
			//Override post template tags
			add_filter('get_the_date',array('EM_Event_Post','the_date'),10,3);
			add_filter('the_content',array('EM_Event_Post','the_content'),10,3);
			add_filter('get_the_time',array('EM_Event_Post','the_time'),10,3);
		}
		add_action('parse_query', array('EM_Event_Post','parse_query'));
		add_action('publish_future_post',array('EM_Event_Post','publish_future_post'),10,1);
		add_action('rest_api_init',array($instance,'register_rest'),10,1);
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
		return $template;
	}

	public static function get_speaker($EM_Event) {

        if($EM_Event->speaker_id == 0) return false;

        $args = [
            'p' => $EM_Event->speaker_id,
            'post_type' => 'event-speaker'
        ];

        $speakers = \Timber\Timber::get_posts( $args );
        
        if ($speakers) return $speakers[0];

        return false;
    }

	public static function lowest_price($booking) {
        $tickets = $booking->get_tickets()->tickets;
        if(empty($tickets)) {
            return 0;
        }

		$first_ticket = key($tickets);
		return floatval($tickets[$first_ticket]->ticket_price);
        
    }

	public static function get_related_events($EM_Event, int $limit = 5) {
		global $post;
        $categories = get_the_terms($post, 'event-categories');
	
        if(empty($categories)) {
            return false;
        }

        $args = [
            'post_type' => 'event',
            'orderby' => '_event_start_date',
            'order' => 'ASC',
            'posts_per_page' => $limit,
            'post__not_in' => [$post->id],
            'tax_query' => [
                [
                    'taxonomy' => 'event-categories',
                    'field' => 'slug',
                    'terms' => $categories[0]->slug,
                ]
            ],
            'meta_query' => [
                [
                  'key' => '_event_start_date',
                  'value' => date('Y-m-d'),
                  'compare' => '>=',
                ]
            ]
        ];
        return \Timber\Timber::get_posts( $args );
    }
	
	public static function the_content($content) {
		global $post;
		global $EM_Twig;

		if($post->post_type !== "event") return $content;

		$EM_Event = em_get_event($post);
		$booking = new \EM_Bookings($EM_Event);

		$attributes = [
			"post" => $post,
			"event" => $EM_Event,
			"location" => $EM_Event->location_id != 0 ? \EM_Locations::get($EM_Event->location_id)[0] : false,
			'currency' => em_get_currency_symbol(true,get_option("dbem_bookings_currency")),
			"currency_format" => get_option("dbem_bookings_currency_format"),
			"bookings" => $booking->get_available_spaces(),
			"has_tickets" => $booking->get_bookings()->get_available_tickets(),
			"booking" => \EM_Booking_Api::get_booking_form($EM_Event),
			"speaker" => self::get_speaker($EM_Event),
			"price" => self::lowest_price($booking),
			"events" => self::get_related_events($EM_Event->ID),
			"content" => $content,
			"formatting" => ["time" => get_option("dbem_time_format")]
		];
		$template = get_twig_template('templates/templates/event-single');
		//var_dump(\Timber::$locations);
		//return $EM_Twig::compile($template, $attributes);
		return \Timber\Timber::compile($template, $attributes);
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
					
						$scope = $wp_query->query_vars['scope'] = 'all'; //otherwise we'll get 404s for past events
					
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

	public function register_rest() {
		register_rest_route( 'events/v2', '/events/', ['method' => 'GET', 'callback' => [$this, 'get_rest_data'], 'permission_callback' => '__return_true'], true );
	}

	
	public function get_rest_data() {

		$args = [
            'category' => array_key_exists('category', $_REQUEST) ? $_REQUEST['category'] : null,
			'tag' => array_key_exists('tag', $_REQUEST) ? $_REQUEST['tag'] : null,
			'scope' => array_key_exists('scope', $_REQUEST) ? $_REQUEST['scope'] : null,
			'event' => array_key_exists('event', $_REQUEST) ? $_REQUEST['event'] : null,
			'limit' => array_key_exists('limit', $_REQUEST) ? $_REQUEST['limit'] : 0,
			'location' => array_key_exists('location', $_REQUEST) ? $_REQUEST['location'] : null,
        ];

		$result = [];
		
		$data = EM_Events::get($args);
		if (!$data) return $result;
		foreach($data as $event) {
			$location = $event->get_location();
			$category = $event->get_categories()->get_first();
			$speaker = EM_Speakers::get($event->speaker_id);
			array_push($result, [
				'ID' => $event->ID,
				'event_id' => $event->event_id,
				'guid' => $event->guid,
				'image' => $event->event_image,
				'category' => [ 
					'id' => $category->id,
					'color' => $category->color, 
					'name' => $category->name,
					'slug' => $category->slug
				],
				'location' => [ 
					'ID' => $location->ID,
					'location_id' => $location->location_id, 
					'address' => $location->location_address,
					'city' => $location->location_town,
					'title' => $location->location_name,
					'url' => $location->location_url,
					'excerpt' => $location->location_excerpt,
				],
				'start_date' => $event->start_date,
				'start_time' => $event->start_time,
				'end_date' => $event->end_date,
				'end_time' => $event->end_time,
				'audience' => $event->event_audience,
				'excerpt' => $event->post_excerpt,
				'title' => $event->post_title,
				'speaker' => $speaker

			]);
		}
		return $result;
	}
}
EM_Event_Post::init();