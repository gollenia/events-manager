<?php
class EM_ML_Options {
	/**
	 * @var array Array of option keys in wp_options that can be translated.
	 */
	static public $translatable_options;
	
    public function __construct(){
	    //define the translatable options for the plugin
		self::$translatable_options = apply_filters('em_ml_translatable_options', array(
			//GENERAL TAB
				//event submission forms
				'dbem_events_anonymous_result_success',
				'dbem_events_form_result_success',
				'dbem_events_form_result_success_updated',
				//privacy policy consent
				'dbem_data_privacy_consent_text',
				'dbem_event_list_item_format_header',
				'dbem_event_list_item_format',
				'dbem_no_events_message',

				'dbem_search_form_submit',
				'dbem_search_form_advanced_hide',
				'dbem_search_form_advanced_show',
				'dbem_search_form_text_label',
				'dbem_search_form_categories_label',
				'dbem_search_form_category_label',
				'dbem_search_form_countries_label',
				'dbem_search_form_country_label',
				'dbem_search_form_regions_label',
				'dbem_search_form_region_label',
				'dbem_search_form_states_label',
				'dbem_search_form_state_label',
				'dbem_search_form_towns_label',
				'dbem_search_form_town_label',
				'dbem_search_form_geo_label',
				'dbem_search_form_geo_units_label',
				'dbem_search_form_dates_label',
				'dbem_search_form_dates_separator',
				
				'dbem_ical_description_format',
				'dbem_ical_real_description_format',
				'dbem_ical_location_format',				
				
				'dbem_no_locations_message',
				'dbem_location_event_single_format',
				'dbem_location_no_event_message',
				
				'dbem_no_categories_message',
				'dbem_no_tags_message',
		
				'dbem_bookings_currency_format',
				'dbem_booking_feedback',
				'dbem_booking_feedback_pending',
				'dbem_booking_feedback_full',				
				'dbem_booking_button_msg_error',
				'dbem_booking_button_msg_full',
				'dbem_booking_button_msg_closed',
				'dbem_bookings_submit_button',
			
				'dbem_bookings_contact_email_pending_subject',
    			'dbem_bookings_contact_email_pending_body',
    			'dbem_bookings_contact_email_confirmed_subject',
    			'dbem_bookings_contact_email_confirmed_body',
    			'dbem_bookings_contact_email_rejected_subject',
    			'dbem_bookings_contact_email_rejected_body',
    			'dbem_bookings_contact_email_cancelled_subject',
    			'dbem_bookings_contact_email_cancelled_body',
				'dbem_bookings_email_confirmed_subject',
				'dbem_bookings_email_confirmed_body',
				'dbem_bookings_email_pending_subject',
				'dbem_bookings_email_pending_body',
				'dbem_bookings_email_rejected_subject',
				'dbem_bookings_email_rejected_body',
				'dbem_bookings_email_cancelled_subject',
				'dbem_bookings_email_cancelled_body',
				//event submission templates
				'dbem_event_submitted_email_subject',
				'dbem_event_submitted_email_body',
				'dbem_event_resubmitted_email_subject',
				'dbem_event_resubmitted_email_body',
				'dbem_event_published_email_subject',
				'dbem_event_published_email_body',
				'dbem_event_reapproved_email_subject',
				'dbem_event_reapproved_email_body',
		));
		//When in the EM settings page translatable values should be shown in the currently active language
		if( is_admin() && !empty($_REQUEST['page']) && $_REQUEST['page'] == 'events-manager-options' ) return;
		//add a hook for all trnalsateable values
	 	foreach( self::$translatable_options as $option ){
	 	    add_filter('pre_option_'.$option, array(&$this, 'pre_option_'.$option), 1,1);
 		}
		
        
    }
	
	/**
	 * Assumes calls are from the pre_option_ filter which were registered during the init() function. 
	 * This takes the filter name and searches for an equivalent translated option if it exists.
	 * 
	 * @param string $filter_name The name of the filter being applied.
	 * @param mixed $value Supplied filter value.
	 * @return mixed Returns either translated data or the supplied value.
	 */
    public function __call($filter_name, $value){
    	if( EM_ML::$current_language != EM_ML::$wplang && strstr($filter_name, 'pre_option_') !== false ){
		    //we're calling an option to be overridden by the default language
		    $option_name = str_replace('pre_option_','',$filter_name);
		    //don't use EM_ML::get_option as it creates an endless loop for options without a translation
			$option_langs = get_option($option_name.'_ml', array());
			if( is_array($option_langs) && !empty($option_langs[EM_ML::$current_language]) ){
				return $option_langs[EM_ML::$current_language];
			}
		}
		return $value[0];
	}
    
	/* START wp_options hooks */
	/**
	 * Gets an option in a specific language. Similar to get_option but will return either the translated option if it exists
	 * @param string $option
	 * @param string $lang
	 * @param boolean $return_original
	 * @return mixed
	 */
	public static function get_option($option, $lang = false, $return_original = true){
		if( self::is_option_translatable($option) ){
			$option_langs = get_option($option.'_ml', array());
			if( empty($lang) ) $lang = EM_ML::$current_language;
			if( !empty($option_langs[$lang]) ){
				return $option_langs[$lang];
			}
		}
		return $return_original ? get_option($option):'';
	}

	/**
	 * Returns whether or not this option name is translatable.
	 * @param string $option Option Name
	 * @return boolean
	 */
	public static function is_option_translatable($option){
		return count(EM_ML::$langs) > 0 && in_array($option, self::$translatable_options);
	}
	
}
$EM_ML_Options = new EM_ML_Options();