<?php

namespace Contexis\Events;

class Install {

	public static function intallation_error_notice($message = 'An unknown error occured') {
		if(!class_exists('\IntlDateFormatter')) {
			$message = __('The Events Manager plugin requires the PHP Intl extension to be installed and enabled on your server. Please contact your hosting provider to enable it.', 'events-manager');
		}
		
		$class = 'notice notice-error';
		printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
	}
	
	public static function deactivate_plugin() {
		if ( !is_plugin_active('events/events.php') ) return;
		deactivate_plugins('events/events.php');
		unset($_GET['activate']);
	}

	public static function uninstall() {
		global $wpdb;
		
		remove_action('before_delete_post',array('EM_Location_Post_Admin','before_delete_post'),10,1);
		remove_action('before_delete_post',array('EM_Event_Post_Admin','before_delete_post'),10,1);
		remove_action('before_delete_post',array('EM_Event_Recurring_Post_Admin','before_delete_post'),10,1);
		$post_ids = $wpdb->get_col('SELECT ID FROM '.$wpdb->posts." WHERE post_type IN ('".EM_Event::POST_TYPE."','".EM_POST_TYPE_LOCATION."','event-recurring')");
		foreach($post_ids as $post_id){
			wp_delete_post($post_id);
		}
		
		$cat_terms = get_terms(EM_TAXONOMY_CATEGORY, array('hide_empty'=>false));
		foreach($cat_terms as $cat_term){
			wp_delete_term($cat_term->term_id, EM_TAXONOMY_CATEGORY);
		}
		$tag_terms = get_terms(EM_TAXONOMY_TAG, array('hide_empty'=>false));
		foreach($tag_terms as $tag_term){
			wp_delete_term($tag_term->term_id, EM_TAXONOMY_TAG);
		}
		
		$wpdb->query('DROP TABLE '.EM_EVENTS_TABLE);
		$wpdb->query('DROP TABLE '.EM_BOOKINGS_TABLE);
		$wpdb->query('DROP TABLE '.EM_LOCATIONS_TABLE);
		$wpdb->query('DROP TABLE '.EM_TICKETS_TABLE);
		$wpdb->query('DROP TABLE '.EM_TICKETS_BOOKINGS_TABLE);
		$wpdb->query('DROP TABLE '.EM_RECURRENCE_TABLE);
		$wpdb->query('DROP TABLE '.EM_META_TABLE);
		
		$wpdb->query('DELETE FROM '.$wpdb->options.' WHERE option_name LIKE \'em_%\' OR option_name LIKE \'dbem_%\'');
		
		deactivate_plugin(array('events/events.php'), true);
		wp_safe_redirect(admin_url('plugins.php?deactivate=true'));
		exit();
	}
}