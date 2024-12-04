<?php
class EM_People extends EM_Object {
	
	public static function init(){
	}
	
	
	
	/**
	 * Workaround function for any legacy code requesting the dbem_bookings_registration_user option which should always be 0
	 * @return int
	 */
	public static function dbem_bookings_registration_user(){
		return 0;		
	}
}
?>