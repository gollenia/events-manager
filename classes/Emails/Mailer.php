<?php
/**
 * phpmailer support
 *
 */
class EM_Mailer {
	
	/**
	 * if any errors crop up, here they are
	 * @var array
	 */
	public $errors = array();
	/**
	 * Array of attachments which will be added to WP_Mail's phpmailer just before sending, and subsequently emptied.
	 * @var array
	 */
	public static $attachments = array();
	
	/**
	 * Send an email via the EM-saved settings.
	 * @param $subject
	 * @param $body
	 * @param $receiver
	 * @param $attachments
	 * @return boolean
	 */
	public function send($subject="no title",$body="No message specified", $receiver='', $attachments = array() ) {
		
		$subject = html_entity_decode(wp_kses_data($subject)); //decode entities, but run kses first just in case users use placeholders containing html
		if( is_array($receiver) ){
			$receiver_emails = array();
			foreach($receiver as $k => $receiver_email){
				$receiver_email = trim($receiver_email);
				$receiver[$k] = $receiver_email;
				$receiver_emails[] = is_email($receiver_email);
			}
			$emails_ok = !in_array(false, $receiver_emails);
		}else{
			$receiver = trim($receiver);
			$emails_ok = is_email($receiver);
		}

		if( get_option('dbem_smtp_html') && get_option('dbem_smtp_html_br') ){
			$body = nl2br($body);
		}

		if ( $emails_ok ) {
			$from = get_option('dbem_mail_sender_address', get_bloginfo('admin_email'));
			$name = get_option('dbem_mail_sender_name', get_bloginfo('name'));
			$headers = [
				get_option('dbem_mail_sender_name') ? 'From: '.$name.' <'.$from.'>':'From: '.$from,
				get_option('dbem_mail_sender_name') ? 'Reply-To: '.$name.' <'.$from.'>':'From: '.$from
			];
			if( get_option('dbem_smtp_html') ){ //create filter to change content type to html in wp_mail
				add_filter('wp_mail_content_type','EM_Mailer::return_texthtml');
			}
			self::$attachments = $attachments;
			
			$send = wp_mail($receiver, $subject, $body, $headers);
			self::delete_email_attachments($attachments);
			return $send;
		}

		$this->errors[] = __('Please supply a valid email format.', 'events');
		self::delete_email_attachments($attachments);
		return false;
		
	}

	/**
	 * Shorthand function for filters to return 'text/html' string.
	 * @return string 'text/html'
	 */
	public static function return_texthtml(){
		return "text/html";
	}

	
	public static function delete_email_attachments( $attachments ){
		foreach( $attachments as $attachment ){
			if( !empty($attachment['delete']) ){
				@unlink( $attachment['path']);
			}
		}
	}
	
	/**
	 * Returns the path of the attachments folder, creating it if non-existent. Returns false if folder could not be created.
	 * A .htaccess file is also attempted to be created, although this will still return as true even if it cannot be created.
	 * @return bool|string
	 */
	public static function get_attachments_dir(){
		//get and possibly create attachment directory path
		$upload_dir = wp_upload_dir();
		$attachments_dir = trailingslashit($upload_dir['basedir'])."em-email-attachments/";
		if( !is_dir($attachments_dir) ){
			//try to make a directory and create an .htaccess file
			if( @mkdir($attachments_dir, 0755) ){
				return $attachments_dir;
			}
			//could not create directory
			return false;
		}
		//add .htaccess file to prevent access to folder by guessing filenames
		if( !file_exists($attachments_dir.'.htaccess') ){
			$file = @fopen($attachments_dir.'.htaccess','w');
			if( $file ){
				fwrite($file, 'deny from all');
				fclose($file);
			}
		}
		return $attachments_dir;
	}
	
	/**
	 * Adds file to email attachments folder, which defaults to wp-content/uploads/em-email-attachments/ and returns the location of said file, false if file could not be created.
	 * @param $file_name
	 * @param $file_content
	 * @return bool|string
	 */
	public static function add_email_attachment( $file_name, $file_content ){
		$attachment_dir = self::get_attachments_dir();
		if( $attachment_dir ){
			$file = fopen($attachment_dir.$file_name,'w+');
			if( $file ){
				fwrite($file, $file_content);
				fclose($file);
				return $attachment_dir . $file_name;
			}
		}
		return false;
	}
}
?>