<?php
/*
 * This file deals with new privacy tools included in WP 4.9.6 and in line with aiding users with conforming to the GPDR rules.
 * Note that consent mechanisms are not included here and will be baked directly into the templates or Pro booking forms.
 */
class EM_Data_Privacy {

	public static function init(){
		add_action( 'admin_init', 'EM_Data_Privacy::privacy_policy_content' );
		
		add_action( 'wp_privacy_personal_data_export_file_created', 'EM_Data_Privacy::export_cleanup');
	}

	public static function privacy_policy_content() {
		if ( ! function_exists( 'wp_add_privacy_policy_content' ) ) {
			return;
		}

		$content = array();
		$content[] = sprintf(
			__('We use Google services to generate maps and provide autocompletion when searching for events by location, which may collect data via your browser in accordance to Google\'s <a href="%s">privacy policy</a>.', 'events-manager' ),
			'https://policies.google.com/privacy'
		);
		$content[] = __('We collect and store information you submit to us when making a booking, for the purpose of reserving your requested spaces at our event and maintaining a record of attendance.', 'events-manager' );
		$content[] = __('We collect and store information you submit to us about events (and corresponding locations) you would like to publish on our site.', 'events-manager' );
		$content[] = __('We may use cookies to temporarily store information about a booking in progress as well as any error/confirmation messages whilst submitting or managing your events and locations.', 'events-manager' );

		wp_add_privacy_policy_content(
			__('Events Manager', 'events-manager'),
			wp_kses_post( '<p>'. implode('</p><p>', $content) .'</p>' )
		);
	}

	


	
	public static function export_cleanup(){
		delete_post_meta( absint($_REQUEST['id']), '_em_locations_exported');
		delete_post_meta( absint($_REQUEST['id']), '_em_bookings_exported' );
	}

}
//EM_Data_Privacy::init();
