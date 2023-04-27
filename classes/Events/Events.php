<?php
use EM_Event_Locations\Event_Locations;
//TODO EM_Events is currently static, better we make this non-static so we can loop sets of events, and standardize with other objects.
/**
 * Use this class to query and manipulate sets of events. If dealing with more than one event, you probably want to use this class in some way.
 *
 */
class EM_Events extends EM_Object {
	
	/**
	 * Like WPDB->num_rows it holds the number of results found on the last query.
	 * @var int
	 */
	public static $num_rows;
	
	/**
	 * If $args['pagination'] is true or $args['offset'] or $args['page'] is greater than one, and a limit is imposed when using a get() query, 
	 * this will contain the total records found without a limit for the last query.
	 * If no limit was used or pagination was not enabled, this will be the same as self::$num_rows
	 * @var int
	 */
	public static $num_rows_found;
	
	protected static $context = 'event';
	
	/**
	 * Returns an array of EM_Events that match the given specs in the argument, or returns a list of future evetnts in future 
	 * (see EM_Events::get_default_search() ) for explanation of possible search array values. You can also supply a numeric array
	 * containing the ids of the events you'd like to obtain 
	 * 
	 * @param array $args
	 * @return EM_Event[]
	 */
	public static function get( $args = array(), $count=false ) {
		global $wpdb;	 
		$events_table = EM_EVENTS_TABLE;
		$locations_table = EM_LOCATIONS_TABLE;
		
		//Quick version, we can accept an array of IDs, which is easy to retrieve
		if( is_array($args) && !empty($args) && array_is_list($args) ){ //Array of numbers, assume they are event IDs to retreive
			//We can just get all the events here and return them
			$events = array();
			foreach($args as $event_id){
				$events[$event_id] = EM_Event::find($event_id);
			}
			return apply_filters('em_events_get', $events, $args);
		}
		
		//We assume it's either an empty array or array of search arguments to merge with defaults			
		$args = self::get_default_search($args);
		$limit = ( $args['limit'] && is_numeric($args['limit'])) ? "LIMIT {$args['limit']}" : '';
		$offset = ( $limit != "" && is_numeric($args['offset']) ) ? "OFFSET {$args['offset']}" : '';
		
		//Get fields that we can use in ordering and grouping, which can be event and location (excluding ambiguous) fields
		$EM_Event = new EM_Event();
		$EM_Location = new EM_Location();
		$event_fields = array_keys($EM_Event->fields);
		$location_fields = array(); //will contain location-specific fields, not ambiguous ones
		foreach( array_keys($EM_Location->fields) as $field_name ){
			if( !in_array($field_name, $event_fields) ) $location_fields[] = $field_name;
		}
		if( get_option('dbem_locations_enabled') ){
			$accepted_fields = array_merge($event_fields, $location_fields);
		}else{
			//if locations disabled then we don't accept location-specific fields
			$accepted_fields = $event_fields;
		}
		
		//Start SQL statement
		
		//Create the SQL statement selectors
		$calc_found_rows = $limit && ( $args['pagination'] || $args['offset'] > 0 || $args['page'] > 0 );
		if( $count ){
			$selectors = 'COUNT(DISTINCT '.$events_table . '.event_id)';
			$limit = 'LIMIT 1';
			$offset = 'OFFSET 0';
		}else{
			if( $args['array'] ){
				//get all fields from table, add events table prefix to avoid ambiguous fields from location
				$selectors = $events_table . '.*';
			}else{
				$selectors = $events_table.'.post_id';
			}
			if( $calc_found_rows ) $selectors = 'SQL_CALC_FOUND_ROWS ' . $selectors; //for storing total rows found
			$selectors = 'DISTINCT ' . $selectors; //duplicate avoidance
		}
		
		//check if we need to join a location table for this search, which is necessary if any location-specific are supplied, or if certain arguments such as orderby contain location fields
		if( !empty($args['groupby']) ){
			$location_specific_args = array('town', 'state', 'country', 'region', 'near', 'geo', 'search');
			$join_locations = false;
			foreach( $location_specific_args as $arg ) if( !empty($args[$arg]) ) $join_locations = true;
			//if set to false the following would provide a false negative in the line above
			if( $args['location_status'] !== false ){ $join_locations = true; }
			//check ordering and grouping arguments for precense of location fields requiring a join
			if( !$join_locations ){
				foreach( array('groupby', 'orderby', 'groupby_orderby') as $arg ){
					if( !is_array($args[$arg]) ) continue; //ignore this argument if set to false
					//we assume all these arguments are now array thanks to self::get_search_defaults() cleaning it up
					foreach( $args[$arg] as $field_name ){
						if( in_array($field_name, $location_fields) ){
							$join_locations = true;
							break; //we join, no need to keep searching
						}
					}
				}
			}
		}else{ $join_locations = true; }//end temporary if( !empty($args['groupby']).... wrapper
		//plugins can override this optional joining behaviour here in case they add custom WHERE conditions or something like that
		$join_locations = apply_filters('em_events_get_join_locations_table', $join_locations, $args, $count);
		//depending on whether to join we do certain things like add a join SQL, change specific values like status search
		$location_optional_join = $join_locations ? "LEFT JOIN $locations_table ON {$locations_table}.location_id={$events_table}.location_id" : '';
		
		//Build ORDER BY and WHERE SQL statements here, after we've done all the pre-processing necessary
		$conditions = self::build_sql_conditions($args);
		$where = ( count($conditions) > 0 ) ? " WHERE " . implode ( " AND ", $conditions ):'';
		$orderby = self::build_sql_orderby($args, $accepted_fields, get_option('dbem_events_default_order'));
		$orderby_sql = ( count($orderby) > 0 ) ? 'ORDER BY '. implode(', ', $orderby) : '';
		
		if( empty($sql) ){
			//THE Query
			$sql = "SELECT $selectors FROM $events_table $location_optional_join $where $orderby_sql $limit $offset";
		}
	
		$sql = apply_filters('em_events_get_sql', $sql, $args);
				
		if( $count ){
			self::$num_rows_found = self::$num_rows = $wpdb->get_var($sql);
			return apply_filters('em_events_get_count', self::$num_rows, $args);		
		}
		
		//get the result and count results
		$results = $wpdb->get_results( $sql, ARRAY_A);
		self::$num_rows = $wpdb->num_rows;
		if( $calc_found_rows ){
			self::$num_rows_found = $wpdb->get_var('SELECT FOUND_ROWS()');
		}else{
			self::$num_rows_found = self::$num_rows;
		}

		//If we want results directly in an array, why not have a shortcut here?
		if( $args['array'] == true ){
			return apply_filters('em_events_get_array',$results, $args);
		}
		
		//Make returned results EM_Event objects
		$results = (is_array($results)) ? $results:array();
		$events = array();
		
		foreach ( $results as $event ){
			$events[] = EM_Event::find($event['post_id'], 'post_id');
		}
		
		return apply_filters('em_events_get', $events, $args);
	}

	/**
	 * For REST Calls, we always need a similar type of data which is collected
	 *  and returned here.
	 *
	 * @param array $args
	 * @return array events
	 */
	public static function get_rest($args = []) {
		$result = [];
		if(empty($args)) {
			global $post;
			$args = ['post_id' => $post->id];
		}
		$data = self::get($args);
		if (!$data) return $result;
		foreach($data as $event) {
			$location = $event->get_location();
			$audience = get_post_meta($event->post_id, '_event_audience', true);
			$category = $event->get_categories()->get_first();
			$tags = new EM_Tags($event);
			$speaker = \Contexis\Events\Speaker::get($event->speaker_id);
			$price = 0;
			$booking = new \EM_Bookings($event);
			$tickets = $booking->get_tickets()->tickets;
			if(!empty($tickets)) {
				$first_ticket = key($tickets);
				$price = floatval($tickets[$first_ticket]->ticket_price);
			}


			array_push($result, [
				'bookings' => [
					'has_bookings' => $event->event_rsvp ? true : false,
					'spaces' => $booking->get_available_spaces()
				],
				'ID' => $event->ID,
				'event_id' => $event->event_id,
				'link' => get_permalink($event->ID),
				'image' => $event->event_image,
				'category' => $category ? [ 
					'id' => $category->id,
					'color' => $category->color, 
					'name' => $category->name,
					'slug' => $category->slug
				] : false,
				'location' => [ 
					'ID' => $location->ID,
					'location_id' => $location->location_id, 
					'address' => $location->location_address,
					'zip' => $location->location_postcode,
					'city' => $location->location_town,
					'name' => $location->location_name,
					'url' => $location->location_url,
					'excerpt' => $location->location_excerpt,
					'country' => $location->location_country,
					'state' => $location->location_state,
				],
				'has_coupons' => \EM_Coupons::event_has_coupons($event),
				'date' => \Contexis\Events\Intl\Date::get_date($event->start()->getTimestamp(), $event->end()->getTimestamp()),
				'time' => \Contexis\Events\Intl\Date::get_time($event->start()->getTimestamp(), $event->end()->getTimestamp()),
				'price' => new Contexis\Events\Intl\Price($price),
				'is_free' => $event->is_free(),
				'start' => $event->start()->getTimestamp(),
				'end' => $event->end()->getTimestamp(),
				'single_day' => $event->event_start_date == $event->event_end_date,
				'audience' => $audience,
				'excerpt' => $event->post_excerpt,
				'title' => $event->post_title,
				'speaker' => $speaker,
				'tags' => $tags->terms,
				'bookingEnd' => $event->rsvp_end()->getTimestamp() * 1000,
				'bookingEndFormatted' => \Contexis\Events\Intl\Date::get_date($event->rsvp_end()->getTimestamp())
			]);
		}
		return $result;
	}
	
	/**
	 * Returns the number of events on a given date
	 * @param $date
	 * @return int
	 */
	public static function count_date($date){
		global $wpdb;
		$table_name = EM_EVENTS_TABLE;
		$sql = "SELECT COUNT(*) FROM  $table_name WHERE (event_start_date  like '$date') OR (event_start_date <= '$date' AND event_end_date >= '$date');";
		return apply_filters('em_events_count_date', $wpdb->get_var($sql));
	}
	
	public static function count( $args = array() ){
		return apply_filters('em_events_count', self::get($args, true), $args);
	}
	
	/**
	 * Will delete given an array of event_ids or EM_Event objects
	 * @param unknown_type $id_array
	 */
	public static function delete( $array ){
		
		//Detect array type and generate SQL for event IDs
		$results = array();
		if( !empty($array) && @get_class(current($array)) != 'EM_Event' ){
			$events = self::get($array);
		}else{
			$events = $array;
		}
		$event_ids = array();
		foreach ($events as $EM_Event){
		    $event_ids[] = $EM_Event->event_id;
			$results[] = $EM_Event->delete();
		}
		//TODO add better error feedback on events delete fails
		return apply_filters('em_events_delete',  in_array(false, $results), $event_ids);
	}
	
	
	
	/* (non-PHPdoc)
	 * DEPRECATED - this class should just contain static classes,
	 * @see EM_Object::can_manage()
	 */
	function can_manage($event_ids = false , $admin_capability = false, $user_to_check = false ){
		global $wpdb;
		if( current_user_can('edit_others_events') ){
			return apply_filters('em_events_can_manage', true, $event_ids);
		}
		if( is_array($event_ids) && !empty($event_ids) && array_is_list($event_ids) ){
			$condition = implode(" OR event_id=", $event_ids);
			//we try to find any of these events that don't belong to this user
			$results = $wpdb->get_var("SELECT COUNT(*) FROM ". EM_EVENTS_TABLE ." WHERE event_owner != '". get_current_user_id() ."' event_id=$condition;");
			return apply_filters('em_events_can_manage', ($results == 0), $event_ids);
		}
		return apply_filters('em_events_can_manage', false, $event_ids);
	}
	
	public static function get_post_search($args = array(), $filter = false, $request = array(), $accepted_args = array()){
		//supply $accepted_args to parent argument since we can't depend on late static binding until WP requires PHP 5.3 or later
		$accepted_args = !empty($accepted_args) ? $accepted_args : array_keys(self::get_default_search());
		return apply_filters('em_events_get_post_search', parent::get_post_search($args, $filter, $request, $accepted_args));
	}

	/* Overrides EM_Object method to apply a filter to result
	 * @see wp-content/plugins/events-manager/classes/EM_Object#build_sql_conditions()
	 */
	public static function build_sql_conditions( $args = array() ){
		global $wpdb;
		//continue with conditions
		$conditions = parent::build_sql_conditions($args);
		//specific location query conditions if locations are enabled
		if( get_option('dbem_locations_enabled') ){
			//events with or without locations
			if( !empty($args['has_location']) ){
				$conditions['has_location'] = '('.EM_EVENTS_TABLE.'.location_id IS NOT NULL AND '.EM_EVENTS_TABLE.'.location_id != 0)';
			}elseif( !empty($args['no_location']) ){
				$conditions['no_location'] = '('.EM_EVENTS_TABLE.'.location_id IS NULL OR '.EM_EVENTS_TABLE.'.location_id = 0)';			
			}elseif( !empty($conditions['location_status']) ){
				$location_specific_args = array('town', 'state', 'country', 'region', 'near', 'geo', 'search');
				foreach( $location_specific_args as $location_arg ){
					if( !empty($args[$location_arg]) ) $skip_location_null_condition = true;
				}
				if( empty($skip_location_null_condition) ){
					$conditions['location_status'] = '('.$conditions['location_status'].' OR '.EM_LOCATIONS_TABLE.'.location_id IS NULL)';
				}
			}
		}
		//search conditions
		if( !empty($args['search']) ){
			if( get_option('dbem_locations_enabled') ){
				$like_search = array('event_name',EM_EVENTS_TABLE.'.post_content','location_name','location_address','location_town','location_postcode','location_state','location_country','location_region');
			}else{
				$like_search = array('event_name',EM_EVENTS_TABLE.'.post_content');
			}
			$like_search_string = '%'.$wpdb->esc_like($args['search']).'%';
			$like_search_strings = array();
			foreach( $like_search as $v ) $like_search_strings[] = $like_search_string;
			$like_search_sql = "(".implode(" LIKE %s OR ", $like_search). "  LIKE %s)";
			$conditions['search'] = $wpdb->prepare($like_search_sql, $like_search_strings);
		}
		//private events
		if( empty($args['private']) ){
			$conditions['private'] = "(`event_private`=0)";			
		}elseif( !empty($args['private_only']) ){
			$conditions['private_only'] = "(`event_private`=1)";
		}
		
		//post search
		if( !empty($args['post_id'])){
			if( is_array($args['post_id']) ){
				$conditions['post_id'] = "(".EM_EVENTS_TABLE.".post_id IN (".implode(',',$args['post_id'])."))";
			}else{
				$conditions['post_id'] = "(".EM_EVENTS_TABLE.".post_id={$args['post_id']})";
			}
		}

		if( !empty($args['exclude'])){
			$excludes = is_array($args['exclude']) ? $args['exclude'] : explode(',',$args['exclude']);
			$conditions['post_id'] = "(".EM_EVENTS_TABLE.".post_id NOT IN (".implode(',',$excludes)."))";
		}

		// event locations
		
		if( isset($args['has_event_location']) && $args['has_event_location'] !== false ){
			if( $args['has_event_location'] ){
				$conditions['has_event_location'] = "event_location_type IS NOT NULL";
			}else{
				$conditions['has_event_location'] = "event_location_type IS NULL";
			}
		}
		return apply_filters( 'em_events_build_sql_conditions', $conditions, $args );
	}
	
	/**
	 * Overrides EM_Object method to clean ambiguous fields and apply a filter to result.
	 * @see EM_Object::build_sql_orderby()
	 */
	public static function build_sql_orderby( $args, $accepted_fields, $default_order = 'ASC' ){
	    $accepted_fields[] = 'event_date_modified';
	    $accepted_fields[] = 'event_date_created';
	    $orderby = parent::build_sql_orderby($args, $accepted_fields, get_option('dbem_events_default_order'));
		$orderby = self::build_sql_ambiguous_fields_helper($orderby); //fix ambiguous fields
		return apply_filters( 'em_events_build_sql_orderby', $orderby, $args, $accepted_fields, $default_order );
	}
	
	/**
	 * Overrides EM_Object method to clean ambiguous fields and apply a filter to result.
	 * @see EM_Object::build_sql_groupby()
	 * @deprecated
	 */
	public static function build_sql_groupby( $args, $accepted_fields, $groupby_order = false, $default_order = 'ASC' ){
	    $accepted_fields[] = 'event_date_modified';
	    $accepted_fields[] = 'event_date_created';
		$groupby = parent::build_sql_groupby($args, $accepted_fields);
		//fix ambiguous fields and give them scope of events table
		$groupby = self::build_sql_ambiguous_fields_helper($groupby);
		return apply_filters( 'em_events_build_sql_groupby', $groupby, $args, $accepted_fields );
	}
	
	/**
	 * Overrides EM_Object method to clean ambiguous fields and apply a filter to result.
	 * @see EM_Object::build_sql_groupby_orderby()
	 * @deprecated
	 */
	public static function build_sql_groupby_orderby($args, $accepted_fields, $default_order = 'ASC' ){
	    $accepted_fields[] = 'event_date_modified';
	    $accepted_fields[] = 'event_date_created';
	    $group_orderby = parent::build_sql_groupby_orderby($args, $accepted_fields, get_option('dbem_events_default_order'));
		//fix ambiguous fields and give them scope of events table
		$group_orderby = self::build_sql_ambiguous_fields_helper($group_orderby);
		return apply_filters( 'em_events_build_sql_groupby_orderby', $group_orderby, $args, $accepted_fields, $default_order );
	}
	
	/**
	 * Overrides EM_Object method to provide specific reserved fields and events table.
	 * @see EM_Object::build_sql_ambiguous_fields_helper()
	 */
	protected static function build_sql_ambiguous_fields_helper( $fields, $reserved_fields = array(), $prefix = 'table_name' ){
		//This will likely be removed when PHP 5.3 is the minimum and LSB is a given
		return parent::build_sql_ambiguous_fields_helper($fields, array('post_id', 'location_id', 'blog_id'), EM_EVENTS_TABLE);
	}
	
	/* 
	 * Adds custom Events search defaults
	 * @param array $array_or_defaults may be the array to override defaults
	 * @param array $array
	 * @return array
	 * @uses EM_Object#get_default_search()
	 */
	public static function get_default_search( $array_or_defaults = array(), $array = array() ){
		$defaults = array(
			'recurring' => false, //we don't initially look for recurring events only events and recurrences of recurring events
			'orderby' => get_option('dbem_events_default_orderby'),
			'order' => get_option('dbem_events_default_order'),
			'groupby' => false,
			'groupby_orderby' => 'event_start_date, event_start_time', //groups according to event start time, i.e. by default shows earliest event in a scope
			'groupby_order' => 'ASC', //groups according to event start time, i.e. by default shows earliest event in a scope
			'status' => 1, //approved events only
			'town' => false,
			'state' => false,
			'country' => false,
			'region' => false,
			'postcode' => false,
			'exclude' => false,
			'blog' => get_current_blog_id(),
			'private' => current_user_can('read_private_events'),
			'private_only' => false,
			'post_id' => false,
			//ouput_grouped specific arguments
			'mode' => false,
			'header_format' => false,
			'date_format' => false,
			//event-specific search attributes
			'has_location' => false, //search events with a location
			'no_location' => false, //search events without a location
			'location_status' => false, //search events with locations of a specific publish status
			'event_location_type' => false,
			'has_event_location' => false,
		);
		//sort out whether defaults were supplied or just the array of search values
		if( empty($array) ){
			$array = $array_or_defaults;
		}else{
			$defaults = array_merge($defaults, $array_or_defaults);
		}
		
		//admin-area specific modifiers
		if( is_admin() && !defined('DOING_AJAX') ){
			//figure out default owning permissions
			$defaults['owner'] = !current_user_can('edit_others_events') ? get_current_user_id() : false;
			if( !array_key_exists('status', $array) && current_user_can('edit_others_events') ){
				$defaults['status'] = false; //by default, admins see pending and live events
			}
		}
		//check if we're doing any location-specific searching, if so then we (by default) want to match the status of events
		if( !empty($array['has_location']) ){
			//we're looking for events with locations, so we match the status we're searching for events unless there's an argument passed on for something different
			$defaults['location_status'] = true;
		}elseif( !empty($array['no_location']) ){
			//if no location is being searched for, we should ignore any status searches for location
			$defaults['location_status'] = $array['location_status'] = false;
		}else{
			$location_specific_args = array('town', 'state', 'country', 'region', 'near', 'geo');
			foreach( $location_specific_args as $location_arg ){
				if( !empty($array[$location_arg]) ) $defaults['location_status'] = true;
			}
		}
		$args = parent::get_default_search($defaults,$array);
		//do some post-parnet cleaning up here if locations are enabled or disabled
		if( !get_option('dbem_locations_enabled') ){
			//locations disabled, wipe any args to do with locations so they're ignored
			$location_args = array('town', 'state', 'country', 'region', 'has_location', 'no_location', 'location_status', 'location', 'geo', 'near', 'location_id');
			foreach( $location_args as $arg ) $args[$arg] = false;
		}
		return apply_filters('em_events_get_default_search', $args, $array, $defaults);
	}
}
?>