<?php

namespace Contexis\Events;

use Contexis\Events\Intl\Date;

/**
 * Controls how events are queried and displayed via the WordPress Custom Post APIs
 * @author marcus
 *
 */
class EventPost {

	const TYPE = "event";
	
	public static function init(){

		$instance = new self;
		//Front Side Modifiers
		if( !is_admin() ){
			//Override post template tags
			add_filter('get_the_date',array($instance,'the_date'),10,3);
			add_filter('get_the_time',array($instance,'the_time'),10,3);
		}
		add_action('parse_query', array($instance,'parse_query'));
		add_action('publish_future_post',array($instance,'publish_future_post'),10,1);
		add_action('rest_api_init', array($instance, 'register_meta') );
		add_action('rest_api_init',array($instance,'register_rest'),10,1);
		
	}
	
	public static function publish_future_post($post_id){
		global $EM_Event;
		$post_type = get_post_type($post_id);
		$is_post_type = $post_type == self::TYPE || $post_type == 'event-recurring';
		$saving_status = !in_array(get_post_status($post_id), array('trash','auto-draft')) && !defined('DOING_AUTOSAVE');
		if(!defined('UNTRASHING_'.$post_id) && $is_post_type && $saving_status ){
		    $EM_Event = \EM_Event::find($post_id, 'post_id');
		    $EM_Event->set_status(1);
		}
	}


	/**
	 * Returns price and free spaces for a given event
	 *
	 * @param [type] $request
	 * @return void
	 */
	public function get_rest_bookinginfo($request) {
		$result = [
			'success' => false,
		];

		$id = $request->get_param('id');
		if(!$id) return $result;
		
		$event = new \EM_Event($id, 'post_id');
		
		$result['success'] = true;

		$data = [
			'price_float' => $event->get_price(),
			'formatted_price' => $event->get_formatted_price(),
			'available_spaces' => $event->get_bookings()->get_available_spaces(),
			'booked_spaces' => $event->get_bookings()->get_booked_spaces(),
		];

		$result['data'] = $data;

		return $result;
	}

	public function register_meta() {

		$metadata = json_decode(file_get_contents(__DIR__ . '/metadata.json'), true);

		foreach($metadata as $meta){
			if(!in_array('event', $meta['post_type'])){
				continue;
			}
			register_meta( 'event', $meta['name'], [
				'type' => $meta['type'],
				'single'       => true,
				
				'sanitize_callback' => '',
				'auth_callback' => function() {
					return current_user_can( 'edit_posts' );
				},
				'show_in_rest' => [
					'schema' => [
						
						'style' => $meta['type']
					]
				]
			]);
		}

		foreach($metadata as $meta){
			if(!in_array('event-recurring', $meta['post_type'])){
				continue;
			}
			register_meta( 'event-recurring', $meta['name'], [
				'type' => $meta['type'],
				'single'       => true,
				'sanitize_callback' => '',
				'auth_callback' => function() {
					return current_user_can( 'edit_posts' );
				},
				'show_in_rest' => [
					'schema' => [
						'style' => $meta['type']
					]
				]
			]);
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
		if( $post->post_type == self::TYPE ){
			$EM_Event = \EM_Event::find($post);
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
		if( $post->post_type == self::TYPE ){
			$EM_Event = \EM_Event::find($post);
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
				$EM_DateTime = new \EM_DateTime($scope); //create default time in blog timezone
				if( get_option('dbem_events_current_are_past') && $wp_query->query_vars['post_type'] != 'event-recurring' ){
					$query[] = array( 'key' => '_event_start_date', 'value' => $EM_DateTime->getDate() );
				}else{
					$query[] = array( 'key' => '_event_start_date', 'value' => $EM_DateTime->getDate(), 'compare' => '<=', 'type' => 'DATE' );
					$query[] = array( 'key' => '_event_end_date', 'value' => $EM_DateTime->getDate(), 'compare' => '>=', 'type' => 'DATE' );
				}				
			}elseif ($scope == "future" || $scope == 'past' ){
				$EM_DateTime = new \EM_DateTime(); //create default time in blog timezone
				$EM_DateTime->setTimezone('UTC');
				$compare = $scope == 'future' ? '>=' : '<';
				if( get_option('dbem_events_current_are_past') && $wp_query->query_vars['post_type'] != 'event-recurring' ){
					$query[] = array( 'key' => '_event_start', 'value' => $EM_DateTime->getDateTime(), 'compare' => $compare, 'type' => 'DATETIME' );
				}else{
					$query[] = array( 'key' => '_event_end', 'value' => $EM_DateTime->getDateTime(), 'compare' => $compare, 'type' => 'DATETIME' );
				}
			}elseif ($scope == "month" || $scope == "next-month" || $scope == 'this-month'){
				$EM_DateTime = new \EM_DateTime(); //create default time in blog timezone
				if( $scope == 'next-month' ) $EM_DateTime->add(new \DateInterval('P1M'));
				$start_month = $scope == 'this-month' ? $EM_DateTime->getDate() : $EM_DateTime->modify('first day of this month')->getDate();
				$end_month = $EM_DateTime->modify('last day of this month')->getDate();
				if( get_option('dbem_events_current_are_past') && $wp_query->query_vars['post_type'] != 'event-recurring' ){
					$query[] = array( 'key' => '_event_start_date', 'value' => array($start_month,$end_month), 'type' => 'DATE', 'compare' => 'BETWEEN');
				}else{
					$query[] = array( 'key' => '_event_start_date', 'value' => $end_month, 'compare' => '<=', 'type' => 'DATE' );
					$query[] = array( 'key' => '_event_end_date', 'value' => $start_month, 'compare' => '>=', 'type' => 'DATE' );
				}
			}elseif ($scope == "week" || $scope == 'this-week'){
				$EM_DateTime = new \EM_DateTime(); //create default time in blog timezone
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
		register_rest_route( 'events/v2', '/bookinginfo/(?P<id>\d+)', ['method' => 'GET', 'callback' => [$this, 'get_rest_bookinginfo'], 'permission_callback' => '__return_true'], true );

	}

	public function get_rest_data() {

		$args = [
            'category' => array_key_exists('category', $_REQUEST) ? $_REQUEST['category'] : null,
			'tag' => array_key_exists('tag', $_REQUEST) ? $_REQUEST['tag'] : null,
			'scope' => array_key_exists('scope', $_REQUEST) ? $_REQUEST['scope'] : null,
			'event' => array_key_exists('event', $_REQUEST) ? $_REQUEST['event'] : null, 
			'excerpt' => array_key_exists('excerpt', $_REQUEST) ? $_REQUEST['excerpt'] : null,
			'exclude' => array_key_exists('exclude', $_REQUEST) ? $_REQUEST['exclude'] : null,
			'limit' => array_key_exists('limit', $_REQUEST) ? $_REQUEST['limit'] : 0,
			'location' => array_key_exists('location', $_REQUEST) ? $_REQUEST['location'] : null,
			'post_id' => array_key_exists('post_id', $_REQUEST) ? $_REQUEST['post_id'] : null,
			'event' => array_key_exists('event', $_REQUEST) ? $_REQUEST['event'] : null,
        ];
		
		return \EM_Events::get_rest($args);
		
	}


	public function get_attendees($booking) {
		$result = [];
		$tickets = $booking->booking_meta['attendees'];
		foreach($tickets as $key => $ticket) {
			foreach ($ticket as $attendee) {
				$result[] = [ 'id' => $key, 'fields' => $attendee ];
			}
		}
		return $result;
	}

}
EventPost::init();