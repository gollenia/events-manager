<?php
namespace Contexis\Events\Intl;

class Countries {

	/**
	 * Get a array of countries, __d. Keys are 2 character country iso codes. If you supply a string or array that will be the first value in the array (if array, the array key is the first key in the returned array)
	 * @param mixed $add_blank
	 * @return array
	 */
	static function get($add_blank = false, $sort = true){
		global $em_countries_array;
		if( !is_array($em_countries_array) ){
			$em_countries_array = array (
				'AT' => __('Austria', 'events-manager'), 
				'DE' => __('Germany', 'events-manager'), 
				'CH' => __('Switzerland', 'events-manager'), 
				'FR' => __('France', 'events-manager'), 
				'NL' => __('Netherlands', 'events-manager') 
			);
		}
		if($sort){ asort($em_countries_array); }
		if($add_blank !== false){
			if(is_array($add_blank)){
				$em_countries_array = $add_blank + $em_countries_array;
			}else{
				$em_countries_array = array(0 => $add_blank) + $em_countries_array;
			}
		}
		return $em_countries_array;
	}

}