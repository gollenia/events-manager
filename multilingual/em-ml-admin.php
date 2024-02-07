<?php
class EM_ML_Admin{
    
	public static function init(){
		
		if( !defined('EM_SETTINGS_TABS') && count(EM_ML::$langs) > 3 ) define('EM_SETTINGS_TABS',true);
	}
	
	/**
	 * Returns array of settings page names used in $_REQUEST['page'] that must always be served in the original language of the blog 
	 * @return array
	 */
	public static function settings_pages(){
		return apply_filters('em_ml_admin_settings_pages', array('events-manager-options'));
	}
	
	public static function meta_boxes(){
	    global $EM_Event, $EM_Location, $wp_meta_boxes;
	    //decide if it's a master event, if not then hide the meta boxes
	    if( !empty($EM_Event) && !EM_ML::is_original($EM_Event) ){
	        //remove meta boxes for events
		    remove_meta_box('em-event-recurring', 'event-recurring', 'normal');
		    remove_meta_box('em-event-when-recurring', 'event-recurring', 'side');
	    	remove_meta_box('em-event-bookings', EM_POST_TYPE_EVENT, 'normal');
		    remove_meta_box('em-event-bookings', 'event-recurring', 'normal');
		    remove_meta_box('em-event-group', EM_POST_TYPE_EVENT, 'side');
		    remove_meta_box('em-event-group', 'event-recurring', 'side');
		   

		    //add translation-specific meta boxes
		    add_meta_box('em-event-translation', __('Translated Event Information','events-manager'), 'EM_ML_Admin::meta_box_translated_event',EM_POST_TYPE_EVENT, 'side','high');
		    add_meta_box('em-event-translation', __('Translated Event Information','events-manager'), 'EM_ML_Admin::meta_box_translated_event','event-recurring', 'side','high');
		    $event = EM_ML::get_original_event($EM_Event);
		    if( $event->event_rsvp ){
		        add_meta_box('em-event-bookings-translation', __('Bookings/Registration','events-manager'), 'EM_ML_Admin::meta_box_bookings_translation',EM_POST_TYPE_EVENT, 'normal','high');
			    add_meta_box('em-event-bookings-translation', __('Bookings/Registration','events-manager'), 'EM_ML_Admin::meta_box_bookings_translation','event-recurring', 'normal','high');
		    }
	    }
	  
	}
	
	public static function meta_box_translated_event(){
	    global $EM_Event;
	    //output the _emnonce because it won't be output due to missing meta boxes
	    ?>
	    <input type="hidden" name="_emnonce" value="<?php echo wp_create_nonce('edit_event'); ?>" />
	    <p>
	    	<?php
	    	$original_link = EM_ML::get_original_event($EM_Event)->get_edit_url();
		    $original_link = apply_filters('em_ml_admin_original_event_link',$original_link);
			echo __('This is a translated event, therefore your time, location and booking information is handled by your original event translation.', 'events-manager');
			echo ' <a href="'.esc_url($original_link).'">'.__('See original translation.','events-manager').'</a>';
	    	?>
	    </p>
	    <?php
	}
	
	
	public static function meta_box_translated_location(){
	    global $EM_Location;
	    //output the _emnonce because it won't be output due to missing meta boxes
	    ?>
	    <input type="hidden" name="_emnonce" value="<?php echo wp_create_nonce('edit_location'); ?>" />
	    <p>
	    	<?php
	    	$original_link = EM_ML::get_original_location($EM_Location)->get_edit_url();
		    $original_link = apply_filters('em_ml_admin_original_location_link',$original_link);
			echo __('This is a translated location, so address information is handled by your original location translation. You can provide translations for certain fields which will override the original translation.', 'events-manager');
			echo ' <a href="'.esc_url($original_link).'">'.__('See original translation.','events-manager').'</a>';
	    	?>
	    </p>
	    <?php
	}
	
	public static function meta_box_bookings_translation(){
	    global $EM_Event;
	    $event = EM_ML::get_original_event($EM_Event);
	    $lang = EM_ML::$current_language;
	    ?>
	    <p><em><?php esc_html_e('Below are translations for your tickets. If left blank, the language of the original event will be used.','events-manager'); ?></em></p>
	    <table class="event-bookings-ticket-translation form-table">
    	    <?php
    	    foreach( $event->get_tickets()->tickets as $EM_Ticket ){ /* @var $EM_Ticket EM_Ticket */
    	        $name = !empty($EM_Ticket->ticket_meta['langs'][$lang]['ticket_name']) ? $EM_Ticket->ticket_meta['langs'][$lang]['ticket_name'] : '';
    	        $description =  !empty($EM_Ticket->ticket_meta['langs'][$lang]['ticket_description']) ? $EM_Ticket->ticket_meta['langs'][$lang]['ticket_description']: '';
    	        $desc_ph = !empty($EM_Ticket->ticket_description) ? $EM_Ticket->ticket_description:__('Description','events-manager');  
    	        ?>
    	        <tbody>
    	        <tr>
    	            <td><strong><?php echo esc_html($EM_Ticket->ticket_name); ?></strong></td>
    	            <td>
    	                <input placeholder="<?php echo esc_attr($EM_Ticket->ticket_name); ?>" type="text" name="ticket_translations[<?php echo $EM_Ticket->ticket_id ?>][ticket_name]" value="<?php echo esc_attr($name); ?>" />
    	                <br/>
    	                <textarea placeholder="<?php echo esc_attr($desc_ph); ?>" type="text" name="ticket_translations[<?php echo $EM_Ticket->ticket_id ?>][ticket_description]"><?php echo esc_html($description); ?></textarea>
    	            </td>
    	        </tr>
    	        </tbody>
    	        <?php
    	    }
    	    ?>
	    </table>
	    <?php
	}
	
	
}
EM_ML_Admin::init();