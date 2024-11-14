<?php

/**
 * Object that holds location info and related functions
 *
 * @property string $language       Language of the location, shorthand for location_language
 * @property string $translation    Whether or not a location is a translation (i.e. it was translated from an original location), shorthand for location_translation
 * @property int $parent            Location ID of parent location, shorthand for location_parent
 * @property int $id                The Location ID, case sensitive, shorthand for location_id
 * @property string $slug           Location slug, shorthand for location_slug
 * @property string $name            Location name, shorthand for location_name
 * @property int $owner              ID of author/owner, shorthand for location_owner
 * @property int $status             ID of post status, shorthand for location_status
 */
class EM_Location extends EM_Object {

	const POST_TYPE = EM_POST_TYPE_LOCATION;
	
	//DB Fields
	var $location_id = '';
	var $post_id = '';
	var $blog_id = 0;
	var $location_parent;
	var $location_private = 0;
	var $location_slug = '';
	var $location_name = '';
	var $location_address = '';
	var $location_town = '';
	var $location_state = '';
	var $location_postcode = '';
	var $location_region = '';
	var $location_country = '';
	var $location_latitude = 0;
	var $location_longitude = 0;
	var $post_content = '';
	var $location_owner = '';
	var $location_url = '';
	var $location_status = 0;
	var $location_language;
	var $location_translation = 0;
	/* anonymous submission information */
	var $owner_anonymous;
	var $owner_name;
	var $owner_email;
	//Other Vars
	public array $fields = array( 
		'location_id' => array('name'=>'id','type'=>'%d'),
		'post_id' => array('name'=>'post_id','type'=>'%d'),
		'blog_id' => array('name'=>'blog_id','type'=>'%d'),
		'location_parent' => array('type'=>'%d', 'null'=>true),
		'location_slug' => array('name'=>'slug','type'=>'%s', 'null'=>true), 
		'location_name' => array('name'=>'name','type'=>'%s', 'null'=>true), 
		'location_address' => array('name'=>'address','type'=>'%s','null'=>true),
		'location_town' => array('name'=>'town','type'=>'%s','null'=>true),
		'location_state' => array('name'=>'state','type'=>'%s','null'=>true),
		'location_postcode' => array('name'=>'postcode','type'=>'%s','null'=>true),
		'location_region' => array('name'=>'region','type'=>'%s','null'=>true),
		'location_country' => array('name'=>'country','type'=>'%s','null'=>true),
		'location_latitude' =>  array('name'=>'latitude','type'=>'%f','null'=>true),
		'location_longitude' => array('name'=>'longitude','type'=>'%f','null'=>true),
		'location_url' => array('name'=>'url','type'=>'%s','null'=>true),
		'post_content' => array('name'=>'description','type'=>'%s', 'null'=>true),
		'location_owner' => array('name'=>'owner','type'=>'%d', 'null'=>true),
		'location_status' => array('name'=>'status','type'=>'%d', 'null'=>true),
		'location_language' => array( 'type'=>'%s', 'null'=>true ),
		'location_translation' => array( 'type'=>'%d' ),
	);
	/**
	 * Associative array mapping shorter to full property names in this class, used in EM_Object magic access methods, allowing for interchangeable use when dealing with different object types such as locations and events.
	 * @var array
	 */
	/*
	 protected $shortnames = array(
		// common EM CPT object variables
		'language' => 'location_language',
		'translation' => 'location_translation',
		'parent' => 'location_parent',
		'id' => 'location_id',
		'slug' => 'location_slug',
		'name' => 'location_name',
		'status' => 'location_status',
		'owner' => 'location_owner',
	);
	*/
	var $post_fields = array('post_id','location_slug','location_status', 'location_name','post_content','location_owner');
	var $location_attributes = array();
	var $image_url = '';
	public string $feedback_message = "";
	var array $errors = array();
	/**
	 * previous status of location
	 * @access protected
	 * @var mixed
	 */
	var $previous_status = false;
	
	/* Post Variables - copied out of post object for easy IDE reference */
	var $ID;
	var $post_author;
	var $post_date;
	var $post_date_gmt;
	var $post_title;
	var $post_excerpt = '';
	var $post_status;
	var $comment_status;
	var $ping_status;
	var $post_password;
	var $post_name;
	var $to_ping;
	var $pinged;
	var $post_modified;
	var $post_modified_gmt;
	var $post_content_filtered;
	var $post_parent;
	var $guid;
	var $menu_order;
	var $post_type;
	var $post_mime_type;
	var $comment_count;
	var $ancestors;
	var $filter;
	
	
	/**
	 * Gets data from POST (default), supplied array, or from the database if an ID is supplied
	 * @param WP_Post|int|false $id
	 * @param $search_by - Can be post_id or a number for a blog id if in ms mode with global tables, default is location_id
	 */
	function __construct($id = false,  $search_by = 'location_id' ) {
		//Initialize
		$this->required_fields = array("location_address" => __('The location address', 'events-manager'), "location_town" => __('The location town', 'events-manager'), "location_country" => __('The country', 'events-manager'));
		//Get the post_id/location_id
		$is_post = !empty($id->ID) && $id->post_type == EM_POST_TYPE_LOCATION;
		if( $is_post ){
			$id->ID = absint($id->ID);
		}else{
			$id = absint($id);
		}
		if( $is_post || absint($id) > 0 ){ //only load info if $id is a number
			$location_post = false;
			if($search_by == 'location_id' && !$is_post){
				
				$args = array(
					'post_type'         => 'location',
					'meta_query' => array(
						array(
							'key' => '_location_id',
							'value' => $id,
						)
					)
				);
				 
				$query = new WP_Query( $args );

				
				if ($query->have_posts()) {
					$location_post = $query->posts[0];
				}
			}else{
				if(!$is_post){
					$location_post = get_post($id);	
				}else{
					$location_post = $id;
				}
				$this->post_id = !empty($id->ID) ? $id->ID : $id;
			}
			$this->load_postdata($location_post, $search_by);
		}
		//$this->compat_keys();
		//add this location to the cache
		if( $this->location_id && $this->post_id ){
			wp_cache_set($this->location_id, $this, 'em_locations');
			wp_cache_set($this->post_id, $this->location_id, 'em_locations_ids');
		}
		do_action('em_location', $this, $id, $search_by);
	}

	/**
	 * Get a location in a db friendly way, by checking globals, cache and passed variables to avoid extra class instantiations.
	 * @param mixed $id
	 * @param mixed $search_by
	 * @return EM_Location
	 */
	static function get($id = false, $search_by = 'location_id') {
		global $EM_Location;
	//check if it's not already global so we don't instantiate again
		if( is_object($EM_Location) && get_class($EM_Location) == 'EM_Location' ){
			if( is_object($id) && $EM_Location->post_id == $id->ID ){
				return apply_filters('em_get_location', $EM_Location);
			}elseif( !is_object($id) ){
				if( $search_by == 'location_id' && $EM_Location->location_id == $id ){
					return apply_filters('em_get_location', $EM_Location);
				}elseif( $search_by == 'post_id' && $EM_Location->post_id == $id ){
					return apply_filters('em_get_location', $EM_Location);
				}
			}
		}
		if( is_object($id) && get_class($id) == 'EM_Location' ){
			return apply_filters('em_get_location', $id);
		}else{
			//check the cache first
			$location_id = false;
			if( is_numeric($id) ){
				if( $search_by == 'location_id' ){
					$location_id = $id;
				}elseif( $search_by == 'post_id' ){
					$location_id = wp_cache_get($id, 'em_locations_ids');
				}
			}elseif( !empty($id->ID) && !empty($id->post_type) && $id->post_type == EM_POST_TYPE_LOCATION ){
				$location_id = wp_cache_get($id->ID, 'em_locations_ids');
			}
			if( $location_id ){
				$location = wp_cache_get($location_id, 'em_locations');
				if( is_object($location) && !empty($location->location_id) && $location->location_id){
					return apply_filters('em_get_location', $location);
				}
			}
		}
		return apply_filters('em_get_location', new EM_Location($id,$search_by));
	}
	
	function load_postdata($location_post, $search_by = false){
		if( is_object($location_post) ){
			if( $location_post->post_status != 'auto-draft' ){
				
				$location_meta = get_post_meta($location_post->ID);
					
				//Get custom fields
				foreach($location_meta as $location_meta_key => $location_meta_val){
					$field_name = substr($location_meta_key, 1);
					if($location_meta_key[0] != '_'){
						$this->location_attributes[$location_meta_key] = ( is_array($location_meta_val) ) ? $location_meta_val[0]:$location_meta_val;
					}elseif( is_string($field_name) && !in_array($field_name, $this->post_fields) ){
						if( array_key_exists($field_name, $this->fields) ){
							$this->$field_name = $location_meta_val[0];
						}elseif( in_array($field_name, array('owner_name','owner_anonymous','owner_email')) ){
							$this->$field_name = $location_meta_val[0];
						}
					}
				}	
			}
			//load post data - regardless
			$this->post_id = $location_post->ID;
			$this->location_name = $location_post->post_title;
			$this->location_slug = $location_post->post_name;
			$this->location_owner = $location_post->post_author;
			$this->post_content = $location_post->post_content;
			foreach( $location_post as $key => $value ){ //merge the post data into location object
				$this->$key = $value;
			}
			$this->get_status();
		}elseif( !empty($this->post_id) ){
			//we have an orphan... show it, so that we can at least remove it on the front-end
			global $wpdb;
		    $location_array = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".EM_LOCATIONS_TABLE." WHERE post_id=%d",$this->post_id), ARRAY_A);
		    if( is_array($location_array) ){
				$this->post_id = $this->ID = $event_array['post_id'] = null; //reset post_id because it doesn't really exist
				$this->from_array($location_array);
		    }
		}
	}
	
	/**
	 * Retrieve event information via POST (used in situations where posts aren't submitted via WP)
	 * @param boolean $validate whether or not to run validation, default is true
	 * @return boolean
	 */
	function get_post($validate = true){
		do_action('em_location_get_post_pre', $this);
		$this->location_name = ( !empty($_POST['location_name']) ) ? sanitize_post_field('post_title', $_POST['location_name'], $this->post_id, 'db'):'';
		$this->post_content = "";
		$this->get_post_meta(false);
		
		$result = $validate ? $this->validate():true; //validate both post and meta, otherwise return true
		//$this->compat_keys();
		return apply_filters('em_location_get_post', $result, $this);		
	}

	/**
	 * Since the post object has already been saved by the Gutenberg REST, we can safely get the post meta from the database.
	 * @param boolean $validate whether or not to run validation, default is true
	 * @return mixed
	 */
	function get_post_meta($validate = true){
		//We are getting the values via POST or GET
		do_action('em_location_get_post_meta_pre', $this);
		
		$this->location_address = get_post_meta($this->post_id, '_location_address', true);
		$this->location_town = get_post_meta($this->post_id, '_location_town', true);
		$this->location_state = get_post_meta($this->post_id, '_location_state', true);
		$this->location_postcode = get_post_meta($this->post_id, '_location_postcode', true);
		$this->location_region = get_post_meta($this->post_id, '_location_region', true);
		$this->location_country = get_post_meta($this->post_id, '_location_country', true);
		$this->location_url = get_post_meta($this->post_id, '_location_url', true);
		$this->location_latitude = get_post_meta($this->post_id, '_location_latitude', true);
		$this->location_longitude = get_post_meta($this->post_id, '_location_longitude', true);
	
		//$this->compat_keys();
		return apply_filters('em_location_get_post_meta',true, $this, true); //if making a hook, assume that eventually $validate won't be passed on
	}
	
	function validate(){
		$validate_post = true;
		if( empty($this->location_name) ){
			$validate_post = false;
			$this->add_error( __('Location name','events-manager').__(" is required.", 'events-manager') );
		}

		return apply_filters('em_location_validate', $validate_post, $this );		
	}

	/**
	 * Checks if the location has be correctly stored previously
	 * @return boolean
	 */
	function location_exists() : bool {
		global $wpdb;
		
		if(empty($this->location_id)) return false;
		
		if( !empty($this->orphaned_location) && !empty($this->post_id) ) return true;
			
		return $wpdb->get_var('SELECT post_id FROM '.EM_LOCATIONS_TABLE." WHERE location_id={$this->location_id}") == $this->post_id;
	}
	

	function save_meta(){
		
		if (!$this->can_manage('edit_locations','edit_others_locations')) return false;
		
		do_action('em_location_save_meta_pre', $this);
		
		//refresh status
		$this->get_status();
		$this->location_status = (count($this->errors) == 0) ? $this->location_status:null; //set status at this point, it's either the current status, or if validation fails, null
		
		//Save to em_locations table
		$location_array = $this->to_array(true);
		unset($location_array['location_id']);
		$location_array['location_private'] = ( $this->post_status == 'private' ) ? 1:0;
		
		//check if location truly exists, meaning the location_id is actually a valid location id
		$location_exists = $this->location_exists();
		
		//save all the meta
		if( empty($this->location_id) || !$location_exists ){
			
			$this->insert_db($location_array);
		}else{
			$this->update_db($location_array);	
		}

		
		
		//$this->compat_keys();
		
		return apply_filters('em_location_save_meta', count($this->errors) == 0, $this);
	}

	function update_db($location_array) {
		global $wpdb;
		$this->get_previous_status();

		$success = $wpdb->update(EM_LOCATIONS_TABLE, $location_array, array('location_id'=>$this->location_id));
		
		if( $success === true ){
			//Also set the status here if status != previous status
			if( $this->previous_status != $this->get_status() ) $this->set_status($this->get_status());
		}
	}

	function insert_db($location_array) {
		global $wpdb;
		$this->previous_status = 0; //for sure this was previously status 0

		$success = $wpdb->insert(EM_LOCATIONS_TABLE, $location_array);
		
		if($success) {
			$this->location_id = $wpdb->insert_id;
			update_post_meta($this->post_id, '_location_id', $this->location_id);
			$this->feedback_message = sprintf(__('Successfully saved %s','events-manager'),__('Location','events-manager'));
		}
	}
	
	function delete($force_delete = false){
		$result = false;
		if( $this->can_manage('delete_locations','delete_others_locations') ){
		    if( !is_admin() ){
				include_once('em-location-post-admin.php');
				if( !defined('EM_LOCATION_DELETE_INCLUDE') ){
					EM_Location_Post_Admin::init();
					define('EM_LOCATION_DELETE_INCLUDE',true);
				}
		    }
			do_action('em_location_delete_pre', $this);
			if( $force_delete ){
				$result = wp_delete_post($this->post_id,$force_delete);
			}else{
				$result = wp_trash_post($this->post_id);
				if( !$result && $this->post_status == 'trash' && $this->location_status != -1 ){
				    //we're probably dealing with a trashed post already, which will return a false with wp_trash_post, but the location_status is null from < v5.4.1 so refresh it
				    $this->set_status(-1);
				    $result = true;
				}
			}
			if( !$result && !empty($this->orphaned_location) ){
			    //this is an orphaned event, so the wp delete posts would have never worked, so we just delete the row in our locations table
			    $result = $this->delete_meta();
			}
		}
		return apply_filters('em_location_delete', $result != false, $this);
	}
	
	function delete_meta(){
		global $wpdb;
		$result = false;
		if( $this->can_manage('delete_locations','delete_others_locations') ){
			do_action('em_location_delete_meta_pre', $this);
			
			$result = $wpdb->query ( $wpdb->prepare("DELETE FROM ". EM_LOCATIONS_TABLE ." WHERE location_id=%d", $this->location_id) );
		}
		return apply_filters('em_location_delete_meta', $result !== false, $this);
	}
	
	function is_published(){
		return apply_filters('em_location_is_published', ($this->post_status == 'publish' || $this->post_status == 'private'), $this);
	}
	
	/**
	 * Change the status of the location. This will save to the Database too. 
	 * @param int $status 				A number to change the status to, which may be -1 for trash, 1 for publish, 0 for pending or null if draft.
	 * @param boolean $set_post_status 	If set to true the wp_posts table status will also be changed to the new corresponding status.
	 * @return string
	 */
	function set_status($status, $set_post_status = false){
		global $wpdb;
		//decide on what status to set and update wp_posts in the process
		if($status === null){ 
			$set_status='NULL'; //draft post
			if($set_post_status){
				//if the post is trash, don't untrash it!
				$wpdb->update( $wpdb->posts, array( 'post_status' => 'draft' ), array( 'ID' => $this->post_id ) );
			} 
			$this->post_status = 'draft';
		}elseif( $status == -1 ){ //trashed post
			$set_status = -1;
			if($set_post_status){
				//set the post status of the location in wp_posts too
				$wpdb->update( $wpdb->posts, array( 'post_status' => 'trash' ), array( 'ID' => $this->post_id ) );
			}
			$this->post_status = 'trash'; //set post status in this instance
		}else{
			$set_status = $status ? 1:0; //published or pending post
			$post_status = $set_status ? 'publish':'pending';
			if( empty($this->post_name) ){
				//published or pending posts should have a valid post slug
				$slug = sanitize_title($this->post_title);
				$this->post_name = wp_unique_post_slug( $slug, $this->post_id, $post_status, EM_POST_TYPE_LOCATION, 0);
				$set_post_name = true;
			}
			if($set_post_status){
				$wpdb->update( $wpdb->posts, array( 'post_status' => $post_status, 'post_name' => $this->post_name ), array( 'ID' => $this->post_id ) );
			}elseif( !empty($set_post_name) ){
				//if we've added a post slug then update wp_posts anyway
				$wpdb->update( $wpdb->posts, array( 'post_name' => $this->post_name ), array( 'ID' => $this->post_id ) );
			}
			$this->post_status = $post_status;
		}
		//save in the wp_em_locations table
		$this->previous_status = $wpdb->get_var('SELECT location_status FROM '.EM_LOCATIONS_TABLE.' WHERE location_id='.$this->location_id); //get status from db, not post_status, as posts get saved quickly
		$result = $wpdb->query($wpdb->prepare("UPDATE ".EM_LOCATIONS_TABLE." SET location_status=$set_status, location_slug=%s WHERE location_id=%d", array($this->post_name, $this->location_id)));
		$this->get_status(); //reload status
		return apply_filters('em_location_set_status', $result !== false, $status, $this);
	}
	
	/**
	 * Gets the parent of this location, if none exists, null is returned.
	 * @return EM_Location|null
	 */
	public function get_parent(){
		if( $this->location_parent ){
			return self::get( $this->location_parent );
		}
		return null;
	}
	
	function get_status($db = false){
		switch( $this->post_status ){
			case 'private':
				$this->location_private = 1;
				$this->location_status = $status = 1;
				break;
			case 'publish':
				$this->location_private = 0;
				$this->location_status = $status = 1;
				break;
			case 'pending':
				$this->location_private = 0;
				$this->location_status = $status = 0;
				break;
			case 'trash':
				$this->location_private = 0;
				$this->location_status = $status = -1;
				break;
			default: //draft or unknown
				$this->location_private = 0;
				$status = $db ? 'NULL':null;
				$this->location_status = null;
				break;
		}
		return $status;
	}
	
	function get_previous_status( $force = false ){
		global $wpdb;
		if( $this->previous_status === false || $force ){
			$this->previous_status = $wpdb->get_var('SELECT location_status FROM '.EM_LOCATIONS_TABLE.' WHERE location_id='.$this->location_id); //get status from db, not post_status
		}
		return $this->previous_status;
	}
	
	/**
	 * @param $criteria
	 * @return mixed|void
	 * @deprecated Since 5.9.8.2 - Was never used, assume this may be removed eventually and copy code into your own custom implementation if necessary.
	 */
	function load_similar($criteria){
		global $wpdb;
		if( !empty($criteria['location_name']) && !empty($criteria['location_address']) && !empty($criteria['location_town']) && !empty($criteria['location_state']) && !empty($criteria['location_postcode']) && !empty($criteria['location_country']) ){
			$locations_table = EM_LOCATIONS_TABLE; 
			$prepared_sql = $wpdb->prepare("SELECT * FROM $locations_table WHERE location_name = %s AND location_address = %s AND location_town = %s AND location_state = %s AND location_postcode = %s AND location_country = %s", stripcslashes($criteria['location_name']), stripcslashes($criteria['location_address']), stripcslashes($criteria['location_town']), stripcslashes($criteria['location_state']), stripcslashes($criteria['location_postcode']), stripcslashes($criteria['location_country']) );
			//$wpdb->show_errors(true);
			$location = $wpdb->get_row($prepared_sql, ARRAY_A);
			if( is_array($location) ){
				$this->from_array($location);
			}
			return apply_filters('em_location_load_similar', $location, $this);
		}
		return apply_filters('em_location_load_similar', false, $this);
	}
	
	function has_events( $status = 1 ){	
		$events_count = EM_Events::count(array('location_id' => $this->location_id, 'status' => $status));
		return apply_filters('em_location_has_events', $events_count > 0, $this);
	}
	
	/**
	 * Can the user manage this location? 
	 */
	function can_manage( $owner_capability = false, $admin_capability = false, $user_to_check = false ){
		
		$return = parent::can_manage($owner_capability, $admin_capability, $user_to_check);
		
		return apply_filters('em_location_can_manage', $return, $this, $owner_capability, $admin_capability, $user_to_check);
	}
	
	function get_permalink(){	
		if( empty($link) ){
			$link = get_post_permalink($this->post_id);
		}
		return apply_filters('em_location_get_permalink', $link, $this);	;
	}
	
	function get_ical_url(){
		global $wp_rewrite;
		if( !empty($wp_rewrite) && $wp_rewrite->using_permalinks() ){
			$return = trailingslashit($this->get_permalink()).'ical/';
		}else{
			$return = add_query_arg(['ical'=>1], $this->get_permalink());
		}
		return apply_filters('em_location_get_ical_url', $return);
	}
	
	function get_rss_url(){
		global $wp_rewrite;
		if( !empty($wp_rewrite) && $wp_rewrite->using_permalinks() ){
			$return = trailingslashit($this->get_permalink()).'feed/';
		}else{
			$return = add_query_arg(['feed'=>1], $this->get_permalink());
		}
		return apply_filters('em_location_get_rss_url', $return);
	}
	
	/*
	 * Extends the default EM_Object function by switching blogs as needed if in MS Global mode
	 * @param string $size
	 * @return string
	 * @see EM_Object::get_image_url()
	 */
	function get_image_url($size = 'full'){
		$return = parent::get_image_url($size);
		if( !empty($switch_back) ){ restore_current_blog(); }
		return $return;
	}
	
	function get_edit_url(){
		if( !$this->can_manage('edit_locations','edit_others_locations') ) return "";
		return apply_filters('em_location_get_edit_url', admin_url()."post.php?post={$this->post_id}&action=edit", $this);
	}
	
	function output($format, $target="html") {
		$location_string = $format;


		preg_match_all('/\{([a-zA-Z0-9_]+)\}(.+?)\{\/\1\}/s', $location_string, $conditionals);
		if( count($conditionals[0]) > 0 ){
			//Check if the language we want exists, if not we take the first language there
			foreach($conditionals[1] as $key => $condition){
				$show_condition = false;
				if ($condition == 'has_loc_image'){
					//does this event have an image?
					$show_condition = ( $this->get_image_url() != '' );
				}elseif ($condition == 'no_loc_image'){
					//does this event have an image?
					$show_condition = ( $this->get_image_url() == '' );
				}elseif ($condition == 'has_events'){
					//does this location have any events
					$show_condition = $this->has_events();
				}elseif ($condition == 'no_events'){
					//does this location NOT have any events?
					$show_condition = $this->has_events() == false;
				}
				$show_condition = apply_filters('em_location_output_show_condition', $show_condition, $condition, $conditionals[0][$key], $this); 
				if($show_condition){
					//calculate lengths to delete placeholders
					$placeholder_length = strlen($condition)+2;
					$replacement = substr($conditionals[0][$key], $placeholder_length, strlen($conditionals[0][$key])-($placeholder_length *2 +1));
				}else{
					$replacement = '';
				}
				$location_string = str_replace($conditionals[0][$key], apply_filters('em_location_output_condition', $replacement, $condition, $conditionals[0][$key], $this), $location_string);
			}
		}

		//This is for the custom attributes
		preg_match_all('/#_LATT\{([^}]+)\}(\{([^}]+)\})?/', $location_string, $results);
		foreach($results[0] as $resultKey => $result) {
			//check that we haven't mistakenly captured a closing bracket in second bracket set
			if( !empty($results[3][$resultKey]) && $results[3][$resultKey][0] == '/' ){
				$result = $results[0][$resultKey] = str_replace($results[2][$resultKey], '', $result);
				$results[3][$resultKey] = $results[2][$resultKey] = '';
			}
			//Strip string of placeholder and just leave the reference
			$attRef = substr( substr($result, 0, strpos($result, '}')), 7 );
			$attString = '';
			$placeholder_atts = array('#_ATT', $results[1][$resultKey]);
			if( is_array($this->location_attributes) && array_key_exists($attRef, $this->location_attributes) ){
				$attString = $this->location_attributes[$attRef];
			}elseif( !empty($results[3][$resultKey]) ){
				//Check to see if we have a second set of braces;
				$placeholder_atts[] = $results[3][$resultKey];
				$attStringArray = explode('|', $results[3][$resultKey]);
				$attString = $attStringArray[0];
			}elseif( !empty($attributes['values'][$attRef][0]) ){
				$attString = $attributes['values'][$attRef][0];
			}
			$attString = apply_filters('em_location_output_placeholder', $attString, $this, $result, $target, $placeholder_atts);
			$location_string = str_replace($result, $attString ,$location_string );
		}
	 	preg_match_all("/(#@?_?[A-Za-z0-9_]+)({([^}]+)})?/", $location_string, $placeholders);
	 	$replaces = array();
		foreach($placeholders[1] as $key => $result) {
			$replace = '';
			$full_result = $placeholders[0][$key];
			$placeholder_atts = array($result);
			if( !empty($placeholders[3][$key]) ) $placeholder_atts[] = $placeholders[3][$key];
			switch( $result ){
				case '#_LOCATIONID':
					$replace = $this->location_id;
					break;
				case '#_LOCATIONPOSTID':
					$replace = $this->post_id;
					break;
				case '#_NAME': //Depricated
				case '#_LOCATION': //Depricated
				case '#_LOCATIONNAME':
					$replace = $this->location_name;
					break;
				case '#_ADDRESS': //Depricated
				case '#_LOCATIONADDRESS': 
					$replace = $this->location_address;
					break;
				case '#_LOCATIONTOWN':
					$replace = $this->location_town;
					break;
				case '#_LOCATIONSTATE':
					$replace = $this->location_state;
					break;
				case '#_LOCATIONPOSTCODE':
					$replace = $this->location_postcode;
					break;
				case '#_LOCATIONREGION':
					$replace = $this->location_region;
					break;
				case '#_LOCATIONCOUNTRY':
					$replace = $this->get_country();
					break;
				case '#_LOCATIONFULLLINE':
				case '#_LOCATIONFULLBR':
					$glue = $result == '#_LOCATIONFULLLINE' ? ', ':'<br />';
					$replace = $this->get_full_address($glue);
					break;
				case '#_LOCATIONLONGITUDE':
					$replace = $this->location_longitude;
					break;
				case '#_LOCATIONLATITUDE':
					$replace = $this->location_latitude;
					break;
				case '#_DESCRIPTION':  //Deprecated
				case '#_LOCATIONNOTES':
					$replace = $this->post_content;
					break;
				
				case '#_PASTEVENTS': //Depricated
				case '#_LOCATIONPASTEVENTS':
				case '#_NEXTEVENTS': //Depricated
				case '#_LOCATIONNEXTEVENTS':
				case '#_LOCATIONNEXTEVENT':
					$events = EM_Events::get( array('location'=>$this->location_id, 'scope'=>'future', 'limit'=>1, 'orderby'=>'event_start_date,event_start_time') );
					$replace = get_option('dbem_location_no_event_message');
					foreach($events as $EM_Event){
						$replace = $EM_Event->output(get_option('dbem_location_event_single_format'));
					}
					break;
				default:
					$replace = $full_result;
					break;
			}
			$replaces[$full_result] = apply_filters('em_location_output_placeholder', $replace, $this, $full_result, $target, $placeholder_atts);
		}
		//sort out replacements so that during replacements shorter placeholders don't overwrite longer varieties.
		krsort($replaces);
		foreach($replaces as $full_result => $replacement){
			if( !in_array($full_result, array('#_DESCRIPTION','#_LOCATIONNOTES')) ){
				$location_string = str_replace($full_result, $replacement , $location_string );
			}else{
				$desc_replace[$full_result] = $replacement;
			}
		}

		//Finally, do the location notes, so that previous placeholders don't get replaced within the content, which may use shortcodes
		if( !empty($desc_replace) ){
			foreach($desc_replace as $full_result => $replacement){
				$location_string = str_replace($full_result, $replacement , $location_string );
			}
		}

		return apply_filters('em_location_output', $location_string, $this, $format, $target);	
	}
	
	function get_country(){
		$countries = \Contexis\Events\Intl\Countries::get();
		if( !empty($countries[$this->location_country]) ){
			return apply_filters('em_location_get_country', $countries[$this->location_country], $this);
		}
		return apply_filters('em_location_get_country', false, $this);
			
	}
	
	function get_full_address($glue = ', ', $include_country = false){
		$location_array = array();
		if( !empty($this->location_address) ) $location_array[] = $this->location_address;
		if( !empty($this->location_town) ) $location_array[] = $this->location_town;
		if( !empty($this->location_state) ) $location_array[] = $this->location_state;
		if( !empty($this->location_postcode) ) $location_array[] = $this->location_postcode;
		if( !empty($this->location_region) ) $location_array[] = $this->location_region;
		if( $include_country ) $location_array[] = $this->get_country();
		return implode($glue, $location_array);
	}
}

$loc = new EM_Location();