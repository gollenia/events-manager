<?php
class EM_Location_Post_Admin{
	public static function init(){
		global $pagenow;
		if($pagenow == 'post.php' || $pagenow == 'post-new.php' ){ //only needed if editing post
			add_action('admin_head', array('EM_Location_Post_Admin','admin_head'));
		}
		
		//Meta Boxes
		//Save/Edit actions
		
		add_action('before_delete_post',array('EM_Location_Post_Admin','before_delete_post'),10,1);
		add_action('trashed_post',array('EM_Location_Post_Admin','trashed_post'),10,1);
		add_action('untrash_post',array('EM_Location_Post_Admin','untrash_post'),10,1);
		add_action('untrashed_post',array('EM_Location_Post_Admin','untrashed_post'),10,1);
		
	}
	
	public static function admin_head(){
		global $post, $EM_Location;
		if( !empty($post) && $post->post_type == EM_POST_TYPE_LOCATION ){
			$EM_Location = EM_Location::get($post);
		}
	}
	
	
	
	/**
	 * Refreshes the cache of the current global $EM_Location, provided the refresh_cache flag is set to true within the object and the object has a published state
	 */
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

	public static function before_delete_post($post_id){
		if(get_post_type($post_id) !== EM_POST_TYPE_LOCATION) return;
		$EM_Location = EM_Location::get($post_id,'post_id');
		$EM_Location->delete_meta();
	}
	
	public static function trashed_post($post_id){
		if(get_post_type($post_id) == EM_POST_TYPE_LOCATION){
			global $EM_Notices;
			$EM_Location = EM_Location::get($post_id,'post_id');
			$EM_Location->set_status(-1);
			$EM_Notices->remove_all(); //no validation/notices needed
		}
	}
	
	public static function untrash_post($post_id){
		if(get_post_type($post_id) == EM_POST_TYPE_LOCATION){
			//set a constant so we know this event doesn't need 'saving'
			if(!defined('UNTRASHING_'.$post_id)) define('UNTRASHING_'.$post_id, true);
		}
	}
	
	public static function untrashed_post($post_id){
		if(get_post_type($post_id) == EM_POST_TYPE_LOCATION){
			global $EM_Notices;
			$EM_Location = new EM_Location($post_id,'post_id');
			$EM_Location->set_status($EM_Location->get_status());
			$EM_Notices->remove_all(); //no validation/notices needed
		}
	}
	
}

add_action('init', array('EM_Location_Post_Admin','init'));