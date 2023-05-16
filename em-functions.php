<?php


/**
 * Takes a url and appends GET params (supplied as an assoc array), it automatically detects if you already have a querystring there
 * @param string $url
 * @param array $params
 * @param bool $html
 * @param bool $encode
 * @return string
 */
function em_add_get_params($url, $params=array(), $html=true, $encode=true){
	//Splig the url up to get the params and the page location
	$url_parts = explode('?', $url);
	$url = $url_parts[0];
	$url_params_dirty = array();
	if(count($url_parts) > 1){
		$url_params_dirty = $url_parts[1];
		//get the get params as an array
		if( !is_array($url_params_dirty) ){
			if( strstr($url_params_dirty, '&amp;') !== false ){
				$url_params_dirty = explode('&amp;', $url_params_dirty);
			}else{
				$url_params_dirty = explode('&', $url_params_dirty);
			}
		}
		//split further into associative array
		$url_params = array();
		foreach($url_params_dirty as $url_param){
			if( !empty($url_param) ){
				$url_param = explode('=', $url_param);
				if(count($url_param) > 1){
					$url_params[$url_param[0]] = $url_param[1];
				}
			}
		}
		//Merge it together
		$params = array_merge($url_params, $params);
	}
	//Now build the array back up.
	$count = 0;
	foreach($params as $key=>$value){
		if( $value !== null ){
			if( is_array($value) ) $value = implode(',',$value);
			$value = ($encode) ? urlencode($value):$value;
			if( $count == 0 ){
				$url .= "?{$key}=".$value;
			}else{
				$url .= ($html) ? "&amp;{$key}=".$value:"&{$key}=".$value;
			}
			$count++;
		}
	}
	return $html ? esc_url($url):esc_url_raw($url);
}


/**
 * Returns an array of scopes available to events manager. Hooking into this function's em_get_scopes filter will allow you to add scope options to the event pages.
 */
function em_get_scopes(){
	global $wp_locale;
	$start_of_week = get_option('start_of_week');
	$end_of_week_name = $start_of_week > 0 ? $wp_locale->get_weekday($start_of_week-1) : $wp_locale->get_weekday(6);
	$start_of_week_name = $wp_locale->get_weekday($start_of_week);
	return array(
		'all' => __('All events','events-manager'),
		'future' => __('Future events','events-manager'),
		'past' => __('Past events','events-manager'),
		'today' => __('Today\'s events','events-manager'),
		'tomorrow' => __('Tomorrow\'s events','events-manager'),
		'week' => sprintf(__('Events this whole week (%s to %s)','events-manager'), $wp_locale->get_weekday_abbrev($start_of_week_name), $wp_locale->get_weekday_abbrev($end_of_week_name)),
		'this-week' => sprintf(__('Events this week (today to %s)','events-manager'), $wp_locale->get_weekday_abbrev($end_of_week_name)),
		'month' => __('Events this month','events-manager'),
		'this-month' => __('Events this month (today onwards)', 'events-manager'),
		'next-month' => __('Events next month','events-manager'),
		'1-months'  => __('Events current and next month','events-manager'),
		'2-months'  => __('Events within 2 months','events-manager'),
		'3-months'  => __('Events within 3 months','events-manager'),
		'6-months'  => __('Events within 6 months','events-manager'),
		'12-months' => __('Events within 12 months','events-manager')
	);
}

/**
 * Works like check_admin_referrer(), but also in public mode. If in admin mode, it triggers an error like in check_admin_referrer(), if outside admin it just exits with an error.
 * @param string $action
 */
function em_verify_nonce($action, $nonce_name='_wpnonce'){
	if( is_admin() ){
		if( !wp_verify_nonce($_REQUEST[$nonce_name], $action) ) check_admin_referer('trigger_error');
	}else{
		if( !wp_verify_nonce($_REQUEST[$nonce_name], $action) ) exit( __('Trying to perform an illegal action.','events-manager') );
	}
}

/**
 * Since WP 4.5 em_wp_get_referer() returns false if URL is the same. We use it to get a safe referrer url, so we use the new wp_get_raw_referer() argument instead.
 * @since 5.6.3
 * @return string 
 */
function em_wp_get_referer(){
	if( function_exists('wp_get_raw_referer') ){
		//do essentially what em_wp_get_referer does, but potentially returning the same url as before
		return wp_validate_redirect(wp_get_raw_referer(), false );
	}else{
		return wp_get_referer();
	}
}
