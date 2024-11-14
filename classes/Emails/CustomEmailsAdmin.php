<?php
class EM_Custom_Emails_Admin {
    public static $caps = array('custom_emails'=>'manage_bookings');
    
    public static function init(){
		//Custom Event Emails
		if( get_option('dbem_custom_emails_events') ){
				add_action('admin_enqueue_scripts', 'EM_Custom_Emails_Admin::scripts');
				add_action('add_meta_boxes', 'EM_Custom_Emails_Admin::meta_boxes');
				add_filter('em_event_get_post_meta', 'EM_Custom_Emails_Admin::em_event_get_post_meta',100,2);
				if( get_option('dbem_custom_emails_events_admins')){
					add_filter('em_event_validate_meta', 'EM_Custom_Emails_Admin::em_event_validate_meta',100,2);
				}
				add_filter('em_event_save_meta', 'EM_Custom_Emails_Admin::em_event_save_meta',100,2);
				add_filter('em_event_save_events', 'EM_Custom_Emails_Admin::em_event_save_events',100,4);
		}
		add_action( 'admin_enqueue_scripts', function() {
			if ( 'profile' !== get_current_screen()->id ) {
				return;
			}
		 
			// Enqueue code editor and settings for manipulating HTML.
			$settings = wp_enqueue_code_editor( array( 'type' => 'text/html' ) );
		 
			// Bail if user disabled CodeMirror.
			if ( false === $settings ) {
				return;
			}
		 
			wp_add_inline_script(
				'code-editor',
				sprintf(
					'jQuery( function() { wp.codeEditor.initialize( "description", %s ); } );',
					wp_json_encode( $settings )
				)
			);
		} );
		//Custom Gateway Emails
		if( get_option('dbem_custom_emails_gateways') ){
			add_action('em_gateway_settings_footer','EM_Custom_Emails_Admin::em_gateway_settings_footer', 10, 1);
			add_action('em_gateway_update','EM_Custom_Emails_Admin::em_gateway_update', 10, 1);
		}
    }
    
    
    /*
     * --------------------------------------------
     * General Editor Functions for Overriding Emails
     * --------------------------------------------
     */

	 public static function scripts() {
		$cm_settings['codeEditor'] = wp_enqueue_code_editor(array('type' => 'html'));
		wp_localize_script('jquery', 'cm_settings', $cm_settings);
		wp_enqueue_script('wp-theme-plugin-editor');
		wp_enqueue_style('wp-codemirror');
	 }
	
	/**
	 * Builds form for overriding booking emails
	 * @param array $emails_array_custom
	 * @param array $emails_values_custom
	 */
	public static function emails_editor( $emails_values_custom = array(), $emails_array_custom = array(), $admin_emails_custom = array(), $param = 'em_custom_email' ){
		//build structure of emails to show
		$emails_array = !empty($emails_array_custom) ? $emails_array_custom : self::get_default_emails(); //override defaults
		$default_emails = self::get_default_email_values();
		
		$emails_values = !empty($emails_values_custom) ? $emails_values_custom : self::get_default_email_values(); //override defaults
		$group_count = count($emails_array);
		//$admin_emails_custom could be an array of email arrays or an array of comma-delimted emails
		if( !is_array($admin_emails_custom) ) $admin_emails_custom = array();
		foreach( $admin_emails_custom as $k => $y ) if( is_array($y) ) $admin_emails_custom[$k] = implode(',', $y); 
		//output the structure in an html form format
		?>
		<div class="em-event-mail-settings">
			<div class="em-nav-side">
			<?php foreach( $emails_array as $email_array_group_name => $email_array_group ): ?>
				<h4 class="em-nav-item<?php echo $email_array_group_name === array_key_first($emails_array) ? " active": "" ?>" rel="<?php echo '#'.$param ?>-<?php echo $email_array_group_name; ?>">
				<?php echo $email_array_group['title'] ?>
				
			</h4>
			<?php endforeach ?>
			</div>
			<div class="em-nav-container">
			<?php foreach( $emails_array as $email_array_group_name => $email_array_group ): ?>
			
			<div class="em-nav-content<?php echo $email_array_group_name === array_key_first($emails_array) ? "": " hidden" ?>" id="<?php echo $param ?>-<?php echo $email_array_group_name; ?>">
				<h3><?php echo $email_array_group['title'] ?></h3>
				<div><?php if(!empty($email_array_group['text']) ) echo $email_array_group['text']; ?></div>
				
				<?php if( is_array($admin_emails_custom) ): ?>
				<div class="em-settings-field">
					<table class="form-table">
						<tr>
						<th><label><?php _e('Also Send Event Owner Emails To:', 'events-manager'); ?></label></th>
						<td><input type="email" class="emp-cet-email regular-text" multiple name="<?php echo $param ?>_admins[<?php echo $email_array_group_name; ?>]" value="<?php if( !empty($admin_emails_custom[$email_array_group_name]) ) echo $admin_emails_custom[$email_array_group_name]; ?>" /><br />
						<p><?php esc_html_e('For multiple emails, seperate by commas (e.g. email1@test.com,email2@test.com, etc.)','events-manager'); ?></p></td>
						</tr>
					</table>
				<?php endif; ?>				
				</div>
				<ul class="em-accordion">
				<?php foreach( $email_array_group['subgroups'] as $email_array_subgroup => $email_array ) : ?>
				<li>
				<div class="em-accordion-item" rel="<?php echo $param ?>-<?php echo $email_array_subgroup; ?>"> 
					<div class="em-accordion-item-handle"> 
						<span class="em-accordion-item-text"> 
						<span><?php echo $email_array['title']; ?></span> 
						</span> 
					</div> 
				</div>
					
				<div class="em-accordion-content hidden" id="<?php echo $param ?>-<?php echo $email_array_subgroup; ?>" >
					<p><?php echo $email_array['text']; ?></p>
					<?php foreach( $email_array['emails'] as $email_type_name => $email_type ): ?>
					<div class="em-subgroup-email">
						<?php 
							$status = !empty($emails_values[$email_array_subgroup][$email_type_name]['status']) ? $emails_values[$email_array_subgroup][$email_type_name]['status'] : 0;
							$subject = !empty($emails_values[$email_array_subgroup][$email_type_name]['subject']) ? $emails_values[$email_array_subgroup][$email_type_name]['subject'] : $default_emails[$email_array_subgroup][$email_type_name]['subject'];
							$message = !empty($emails_values[$email_array_subgroup][$email_type_name]['message']) ? $emails_values[$email_array_subgroup][$email_type_name]['message'] : $default_emails[$email_array_subgroup][$email_type_name]['message'];
						?>
						<div class="em-select-mail-status">
							<div class="em-mail-status-indicator" data-status="<?php echo $status ?>"></div>
							<select class="em-cet-status" name="<?php echo $param; ?>[<?php echo $email_array_subgroup; ?>][<?php echo $email_type_name; ?>][status]">
								<option value="0" class="emp-default"><?php esc_html_e('Default','events-manager'); ?></option>
								<option value="1" class="emp-enabled" <?php if( $status == 1) echo 'selected="selected"' ?>><?php esc_html_e('Enabled','events-manager'); ?></option>
								<option value="2" class="emp-disabled" <?php if( $status == 2) echo 'selected="selected"' ?>><?php esc_html_e('Disabled','events-manager'); ?></option>
							</select>  
						
							<strong><?php echo $email_type['title'] ?></strong>
						</div>
						
						<div class="em-mail-vals <?php echo $status == 1 ? "" : "hidden"; ?>">
							<table class="form-table">
							<tr class="em-mail-val">
								<th><label><?php _e('Subject','events-manager'); ?></label></th>
								<td><input type="text" class="large-text" name="<?php echo $param; ?>[<?php echo $email_array_subgroup; ?>][<?php echo $email_type_name; ?>][subject]" value="<?php echo esc_attr($subject); ?>" /></td>
							</tr>
							<tr class="em-mail-val">
								<th><label><?php _e('Message','events-manager'); ?></label></th>
								<td><textarea id="description" rows="7" class="large-text code codemirror" name="<?php echo $param; ?>[<?php echo $email_array_subgroup; ?>][<?php echo $email_type_name; ?>][message]"><?php echo esc_html($message); ?></textarea></td>
							</td>
							</table>
						</div>
					</div>
					<?php endforeach; ?>
				</div>
					</li>
				<?php endforeach; ?>
				</ul>
			</div>
			<?php endforeach; ?>
		</div>
		<script>
			jQuery(document).ready(function($) {
  				wp.codeEditor.initialize($('#textarea'), cm_settings);
			})
		</script>
		</div>
		
		<?php 
		
	}
	
	
	
	public static function get_default_emails(){
		$emails = array(
			'default' => array(
				'title' => __('Default','events-manager'),
				'subgroups' => array(
					'admin'=>array(
						'title' => __('Event Owner Emails','events-manager'),
						'text' => __('These emails get sent to the event owners when a person has made a booking.','events-manager'),
						'emails' => array(
							0 => array('title'=> __('Pending booking email','events-manager')),
							1 => array('title'=> __('Confirmed booking email','events-manager')),
							2 => array('title'=> __('Rejected booking email','events-manager')),
							3 => array('title'=> __('Booking cancelled','events-manager'))
						)
					),
					'user'=>array(
						'title' => __('Attendee Emails','events-manager'),
						'text' => __('These emails will be sent to the person who booked a place at your event.','events-manager'),
						'emails' => array(
							0 => array('title'=> __('Pending booking email','events-manager')),
							1 => array('title'=> __('Confirmed booking email','events-manager')),
							2 => array('title'=> __('Rejected booking email','events-manager')),
							3 => array('title'=> __('Booking cancelled','events-manager'))
						)
					)
				)
			)
		);
		if( get_option('dbem_custom_emails_gateways') ){
			$emails = array_merge($emails, self::get_gateway_default_emails());
		}
		return $emails;
	}
	
	/**
	 * @param EM_Gateway|false $EM_Gateway
	 * @param boolean $multiple_bookings
	 * @return array
	 */
	public static function get_default_email_values( $EM_Gateway = false, $multiple_bookings = false ){
		//build structure of values to add to these emails
		$mb_opt = $multiple_bookings ? 'multiple_':'';
		$mb_key = $multiple_bookings ? 'mb-':'';
		$email_values = array(
			$mb_key.'admin'=>array(
				0 => array('subject'=>get_option('dbem_'.$mb_opt.'bookings_contact_email_pending_subject'),'message'=>get_option('dbem_'.$mb_opt.'bookings_contact_email_pending_body'),'status'=>0),
				1 => array('subject'=>get_option('dbem_'.$mb_opt.'bookings_contact_email_confirmed_subject'),'message'=>get_option('dbem_'.$mb_opt.'bookings_contact_email_confirmed_body'),'status'=>0),
				2 => array('subject'=>get_option('dbem_'.$mb_opt.'bookings_contact_email_rejected_subject'),'message'=>get_option('dbem_'.$mb_opt.'bookings_contact_email_rejected_body'),'status'=>0),
				3 => array('subject'=>get_option('dbem_'.$mb_opt.'bookings_contact_email_cancelled_subject'),'message'=>get_option('dbem_'.$mb_opt.'bookings_contact_email_cancelled_body'),'status'=>0)
			),
			$mb_key.'user'=>array(
				0 => array('subject'=>get_option('dbem_'.$mb_opt.'bookings_email_pending_subject'),'message'=>get_option('dbem_'.$mb_opt.'bookings_email_pending_body'),'status'=>0),
				1 => array('subject'=>get_option('dbem_'.$mb_opt.'bookings_email_confirmed_subject'),'message'=>get_option('dbem_'.$mb_opt.'bookings_email_confirmed_body'),'status'=>0),
				2 => array('subject'=>get_option('dbem_'.$mb_opt.'bookings_email_rejected_subject'),'message'=>get_option('dbem_'.$mb_opt.'bookings_email_rejected_body'),'status'=>0),
				3 => array('subject'=>get_option('dbem_'.$mb_opt.'bookings_email_cancelled_subject'),'message'=>get_option('dbem_'.$mb_opt.'bookings_email_cancelled_body'),'status'=>0)
			)
		);
		//handle gateways, and only return a single gateway value if requested by $EM_Gateway parameter
		if( get_option('dbem_custom_emails_gateways') ){
			$email_values_template = $email_values;
			if( !empty($EM_Gateway) ){
				$email_values = array();
				$gateways = array($EM_Gateway->gateway => $EM_Gateway->title);
			}else{
				$gateways = EM_Gateways::active_gateways();
			}
    		foreach($gateways as $gateway => $gateway_name ){
    			//default values are - by default - the general settings values, overriden further down by $possible_email_values
    			$gateway_email_defaults = array($gateway.'-'.$mb_key.'admin' => $email_values_template[$mb_key.'admin'], $gateway.'-'.$mb_key.'user' => $email_values_template[$mb_key.'user']);
    		    //temporary fix, we assume everything is online except for offline - maybe a good reason for split offline/online base gateway subclasses
    		    if( $gateway == 'offline' ){
    		    	$gateway_email_defaults[$gateway.'-'.$mb_key.'admin'][5] = array('subject'=>get_option('dbem_'.$mb_opt.'bookings_contact_email_pending_subject'),'message'=>get_option('dbem_'.$mb_opt.'bookings_contact_email_pending_body'),'status'=>0);
    		    	$gateway_email_defaults[$gateway.'-'.$mb_key.'user'][5] = array('subject'=>get_option('dbem_'.$mb_opt.'bookings_email_pending_subject'),'message'=>get_option('dbem_'.$mb_opt.'bookings_email_pending_body'),'status'=>0);
    		    }
    			//get custom values if applicable
    			$possible_email_values = maybe_unserialize(get_option('em_'.$gateway . "_emails"));
    			$gateway_email_values = self::merge_gateway_default_values($gateway, $gateway_email_defaults, $possible_email_values, true); //merge in only messages here not statuses
    		    $email_values = array_merge($email_values, $gateway_email_values);
    		}
		}
		return $email_values;
	}
	
	public static function editor_get_post( $custom_email_defaults = array(), $param = 'em_custom_email' ){
		global $allowedposttags;
		$emails = !empty($custom_email_defaults) ? $custom_email_defaults : self::get_default_emails(); //override defaults
		$custom_emails = array();
		foreach( $emails as $email_group_name => $email_group ){
			foreach( $email_group['subgroups'] as $email_subgroup_name => $email_subgroup ){
				foreach( $email_subgroup['emails'] as $email_type_name => $email_type ){
					if( !empty($_REQUEST[$param][$email_subgroup_name][$email_type_name]['status']) ){
						//status is set, save it and message if needed
						$custom_emails[$email_subgroup_name][$email_type_name]['status'] = $_REQUEST[$param][$email_subgroup_name][$email_type_name]['status'];
						if( $_REQUEST[$param][$email_subgroup_name][$email_type_name]['status'] == 1 ){
							//only if enabled do we need to save an email format
							$custom_emails[$email_subgroup_name][$email_type_name]['subject'] = wp_kses_data(wp_unslash($_REQUEST[$param][$email_subgroup_name][$email_type_name]['subject']));
							$custom_emails[$email_subgroup_name][$email_type_name]['message'] = wp_unslash($_REQUEST[$param][$email_subgroup_name][$email_type_name]['message']);
						}
					}
				}
			}
		}
		return $custom_emails;
	}
	
	/**
	 * Gets posted admin email values and mnerges it into the passed array of emails. 
	 * @param array $custom_email_defaults Supplied array of admin emails.
	 * @param string $param Parameter name in $_REQUEST containing admin email addresses. 
	 * @return array
	 */
	public static function editor_admin_emails_get_post( $custom_email_defaults = array(), $param = 'em_custom_email_admins' ){
		$emails = !empty($custom_email_defaults) ? $custom_email_defaults : self::get_default_emails(); //override defaults
		$custom_admin_emails = array();
		foreach( $emails as $email_group_name => $email_group ){
			if( isset($_REQUEST[$param][$email_group_name]) && !empty($_REQUEST[$param][$email_group_name]) ){
				$custom_admin_emails[$email_group_name] = str_replace(' ', '', $_REQUEST[$param][$email_group_name]);
			}
		}
		return $custom_admin_emails;
	}
	
    /*
     * --------------------------------------------
     * Custom Gateway Booking Emails
     * --------------------------------------------
     */
	/**
	 * @param EM_Gateway $EM_Gateway
	 */
	public static function em_gateway_settings_footer( $EM_Gateway ){
		//get default email structures and values
	    $default_emails = self::get_gateway_default_emails($EM_Gateway);
		$default_email_values = self::get_default_email_values($EM_Gateway);
		$gateway = $EM_Gateway->gateway;
		//get custom values if applicable and merge them into $email_values
		$gateway_email_values = maybe_unserialize($EM_Gateway->get_option('emails'));
		$email_values = self::merge_gateway_default_values($gateway, $default_email_values, $gateway_email_values);
		$admin_emails = false;
		if( get_option('dbem_custom_emails_gateways_admins') ){
			$admin_emails = EM_Custom_Emails::get_gateway_admin_emails($EM_Gateway);
		}
		echo "<h3>". esc_html__('Custom Booking Email Templates','events-manager').'</h3>';
		$default_emails[$gateway]['title'] = __('Booking Email Templates','events-manager');
		$default_emails[$gateway]['text'] = '<p>'. sprintf(__('Below you can modify the emails that are sent when bookings are made. This will override the default emails located in your %s settings page.','events-manager'), '<a href="'.admin_url('edit.php?post_type=event&page=events-manager-options#emails:booking-emails').'">'.esc_html('Booking Email Templates','events-manager').'</a>');
		$default_emails[$gateway]['text'] .= __('Additionally, you can also override these emails at an event level when editing an event. Should you choose not to, any overriden emails on this page will be considered the default email for this gateway.','events-manager') .'</p>';
		$default_emails[$gateway]['text'] .= '<p>'. __('Note that some gateways do not automatically send pending or confirmed emails, in these cases they may only apply to when event admins manually change the status of a booking resulting in an automated email getting sent.','events-manager').'</p>';
		$default_emails[$gateway]['text'] .= '<p>'. __('Click on the title texts with a plus (+) next to them to reveal further options, and the minus (-) sign to hide them.','events-manager').'</p>';
		
		self::emails_editor($email_values, $default_emails, $admin_emails);
		do_action('after_gatweay_custom_emails', $EM_Gateway, $email_values, $default_emails, $default_email_values, $admin_emails);
	}
	
	public static function add_gateway_mb_default_emails( $default_emails, $EM_Gateway ){
		$gateway = $EM_Gateway->gateway;
		$default_emails[$gateway.'-mb'] = $default_emails[$gateway];
		$default_emails[$gateway.'-mb']['subgroups'][$gateway.'-mb-admin'] = $default_emails[$gateway.'-mb']['subgroups'][$gateway.'-admin'];
		$default_emails[$gateway.'-mb']['subgroups'][$gateway.'-mb-user'] = $default_emails[$gateway.'-mb']['subgroups'][$gateway.'-user'];
		unset($default_emails[$gateway.'-mb']['subgroups'][$gateway.'-admin']);
		unset($default_emails[$gateway.'-mb']['subgroups'][$gateway.'-user']);
		return $default_emails;
	}
	
	/**
	 * Updates the custom email settings for a gateway when a gateway settings page is updated.
	 * @param EM_Gateway $EM_Gateway
	 */
	public static function em_gateway_update( $EM_Gateway ){
		//update templates
		$default_emails = self::get_gateway_default_emails($EM_Gateway);
		
		$custom_booking_emails = self::editor_get_post( $default_emails );
		$EM_Gateway->update_option('emails', serialize($custom_booking_emails));
		//update admin email addresses
		$custom_admin_emails = array();
		if( get_option('dbem_custom_emails_gateways_admins') ){
	        $custom_admin_emails = self::update_gateway_admin_emails($EM_Gateway, $default_emails);
	        if( $custom_admin_emails === false ){
    			global $EM_Notices;
    			$EM_Notices->add_error(__('An invalid admin email was supplied for your custom emails and was not saved in your settings.','events-manager'),true);
    		}else{
    			$EM_Gateway->update_option('emails_admins', serialize($custom_admin_emails));
    		}
		}
		do_action('em_custom_emails_admin_gateway_update',$EM_Gateway, $default_emails, $custom_booking_emails, $custom_admin_emails);
	}
	
	public static function update_gateway_admin_emails( $EM_Gateway, $default_emails = array(), $param = 'em_custom_email_admins' ){
		$custom_admin_emails = self::editor_admin_emails_get_post( $default_emails, $param );
		//validate emails, strip out invalid emails
		$email_errors = false;
		if( !empty($custom_admin_emails) && is_array($custom_admin_emails) ){
			foreach($custom_admin_emails as $group_key => $emails){
				if( !empty($emails) ){
					$emails = explode(',',$emails);
					foreach ($emails as $email){
						if( !is_email($email) ){
							unset($custom_admin_emails[$group_key]);
							$email_errors = true;
						}
					}
				}else{
					$custom_admin_emails[$group_key] = ''; //empty = no email
				}
			}
		}
		if($email_errors){
			return false;
		}else{
			return $custom_admin_emails;
		}
	}

	public static function get_gateway_default_emails( $EM_Gateway = false ){
		global $EM_Gateways;
		$emails = array();
		$gateways = is_object($EM_Gateway) ? array($EM_Gateway->gateway => $EM_Gateway->title) : EM_Gateways::active_gateways();		
		foreach($gateways as $gateway => $gateway_name ){
			$emails[$gateway] = array(
				'title' => sprintf(__('%s Gateway','events-manager'), $gateway_name),
				'subgroups' => array(
					$gateway.'-admin'=>array(
						'title' => __('Event Owner Emails','events-manager'),
						'text' => __('These emails get sent to the event owners when a person has made a booking using this specific gateway.','events-manager'),
						'emails' => array(
							0 => array('title'=> __('Pending booking email','events-manager')),
							1 => array('title'=> __('Confirmed booking email','events-manager')),
							2 => array('title'=> __('Rejected booking email','events-manager')),
							3 => array('title'=> __('Booking cancelled','events-manager'))
						)
					),
					$gateway.'-user'=>array(
						'title' => __('Attendee Emails','events-manager'),
						'text' => __('These emails will be sent to the person who booked a place at your event and selected this specific gateway.','events-manager'),
						'emails' => array(
							0 => array('title'=> __('Pending booking email','events-manager')),
							1 => array('title'=> __('Confirmed booking email','events-manager')),
							2 => array('title'=> __('Rejected booking email','events-manager')),
							3 => array('title'=> __('Booking cancelled','events-manager'))
						)
					)
				)
			);
			//temporary fix, we assume everything is online except for offline - maybe a good reason for split offline/online base gateway subclasses
			if( $gateway == 'offline' ){
				$emails[$gateway]['subgroups'][$gateway.'-admin']['emails'][5] = array('title'=> __('Awaiting Offline Payment','events-manager'));
				$emails[$gateway]['subgroups'][$gateway.'-user']['emails'][5] = array('title'=> __('Awaiting Offline Payment','events-manager'));
			}
		}
		return $emails;
	}
	
	public static function merge_gateway_default_values( $gateway, $email_values, $possible_email_values, $messages_only = false ){
		if( is_array($possible_email_values) ){
			foreach( $possible_email_values as $subgroup_name => $subgroup_msgs ){
				foreach($subgroup_msgs as $msg_key => $msg_data ){
					if( isset($email_values[$subgroup_name][$msg_key]['status']) && $email_values[$subgroup_name][$msg_key]['status'] != 1 ){
						if( $msg_data['status'] == 1 ){
							if( !$messages_only ){
								//gateway default is either disabled or set to the default, so we just override the status
								$email_values[$subgroup_name][$msg_key]['status'] = 1;
							}
							//add the new default subject/message to email values (without changing the status)
							$email_values[$subgroup_name][$msg_key]['subject'] = $msg_data['subject'];
							$email_values[$subgroup_name][$msg_key]['message'] = $msg_data['message'];
						}else{
							if( !$messages_only ){
								//gateway default is either disabled or set to the default, so we just override the status
								$email_values[$subgroup_name][$msg_key] = array_merge($email_values[$subgroup_name][$msg_key], $msg_data);
							}
						}
					}
				}
			}
		}
		return $email_values;
	}
	
    /*
     * --------------------------------------------
     * Custom Event Booking Emails
     * --------------------------------------------
     */
	
	public static function meta_boxes(){
		if( current_user_can(self::$caps['custom_emails']) ){
			add_meta_box('em-event-custom-emails', __('Custom Automated Emails','events-manager'), array('EM_Custom_Emails_Admin','event_meta_box'),EM_POST_TYPE_EVENT, 'normal','low');
			add_meta_box('em-event-custom-emails', __('Custom Automated Emails','events-manager'), array('EM_Custom_Emails_Admin','event_meta_box'), 'event-recurring', 'normal','low');
		}
	}
	
	public static function event_meta_box(){
		global $EM_Event;
		//get custom email values if they exist
		$custom_email_values = EM_Custom_Emails::get_event_emails($EM_Event);
		$custom_admin_emails = false;
		if( get_option('dbem_custom_emails_events_admins')){
			$custom_admin_emails = EM_Custom_Emails::get_event_admin_emails($EM_Event);
		}
		
		echo '<p>'. sprintf(__('Below you can modify the emails that are sent when bookings are made. This will override the default emails located in your %s settings page.','events-manager'), '<a href="'.admin_url('edit.php?post_type=event&page=events-manager-options#emails:booking-emails').'">'.esc_html('Booking Email Templates','events-manager').'</a>');
		if( get_option('dbem_custom_emails_gateways') ){
			echo '<p>'. sprintf(__('You can also create default emails for specific gateways in your individual %s settings page.','events-manager'), '<a href="'.admin_url('edit.php?post_type=event&page=events-manager-gateways').'">'.__('Gateway Settings','events-manager').'</a>');
		}
		echo "<p>".__('Click on the title texts with a plus (+) next to them to reveal further options, and the minus (-) sign to hide them.','events-manager')."</p>";
		self::emails_editor($custom_email_values, array(), $custom_admin_emails);
	}

	public static function em_event_get_post_meta( $result, $EM_Event ){ 
		if( current_user_can(self::$caps['custom_emails']) ){
			$EM_Event->custom_booking_emails = self::editor_get_post();
			if( get_option('dbem_custom_emails_events_admins')){
				$EM_Event->custom_admin_emails = self::editor_admin_emails_get_post();
			}
		}
		return $result;
	}
	
	public static function em_event_validate_meta( $result, $EM_Event ){
		//validate emails
		$email_errors = false;
		if( !empty($EM_Event->custom_admin_emails) && is_array($EM_Event->custom_admin_emails) ){
			foreach($EM_Event->custom_admin_emails as $group_key => $emails){
				if( !empty($emails) ){
					$emails = explode(',',$emails);
					foreach ($emails as $email){
						if( !is_email($email) ){
							unset($EM_Event->custom_admin_emails[$group_key]);
							$email_errors = true;
						}
					}
				}
			}
		}
		if($email_errors){
			global $EM_Notices;
			$EM_Notices->add_error(__('An invalid admin email was supplied for your custom emails and was not saved in your settings.','events-manager'),true);
		}
		return $result;
	}
	
	public static function em_event_save_meta( $result, $EM_Event ){
		global $wpdb;
		if( current_user_can(self::$caps['custom_emails']) && !empty($EM_Event->post_id) ){
			if( !empty($EM_Event->custom_booking_emails) ){
				$sql = $wpdb->prepare('SELECT meta_id FROM '.EM_META_TABLE." WHERE object_id = %d AND meta_key = %s LIMIT 1", $EM_Event->event_id, 'event-emails');
				$meta_id = $wpdb->get_var($sql);
				if( $meta_id > 0 ){
					$wpdb->update(EM_META_TABLE, array('meta_value'=>serialize($EM_Event->custom_booking_emails)), array('meta_id'=>$meta_id), array('%s'));
				}else{
					$wpdb->insert(EM_META_TABLE, array('object_id'=>$EM_Event->event_id, 'meta_key'=>'event-emails', 'meta_value' => serialize($EM_Event->custom_booking_emails)), array('%d','%s','%s'));
				}
			}elseif( isset($EM_Event->custom_booking_emails) ){
				//only delete if custom_booking_emails property is set, since that only happens when populating a specific event in the admin, not another event on the side
				$sql = $wpdb->prepare('DELETE FROM '.EM_META_TABLE." WHERE object_id = %d AND meta_key = %s LIMIT 1", $EM_Event->event_id, 'event-emails');
				$wpdb->query($sql);
			}
			if( get_option('dbem_custom_emails_events_admins')){
				if( !empty($EM_Event->custom_admin_emails) ){
					$sql = $wpdb->prepare('SELECT meta_id FROM '.EM_META_TABLE." WHERE object_id = %d AND meta_key = %s LIMIT 1", $EM_Event->event_id, 'event-admin-emails');
					$meta_id = $wpdb->get_var($sql);
					if( $meta_id > 0 ){
						$wpdb->update(EM_META_TABLE, array('meta_value'=>serialize($EM_Event->custom_admin_emails)), array('meta_id'=>$meta_id), array('%s'));
					}else{
						$wpdb->insert(EM_META_TABLE, array('object_id'=>$EM_Event->event_id, 'meta_key'=>'event-admin-emails', 'meta_value' => serialize($EM_Event->custom_admin_emails)), array('%d','%s','%s'));
					}
				}elseif( isset($EM_Event->custom_admin_emails) ){
					//only delete if custom_admin_emails property is set, since that only happens when populating a specific event in the admin, not another event on the side
    				$sql = $wpdb->prepare('DELETE FROM '.EM_META_TABLE." WHERE object_id = %d AND meta_key = %s LIMIT 1", $EM_Event->event_id, 'event-admin-emails');
    				$wpdb->query($sql);
    			}
			}
		}
		return $result;
	}
	
	public static function em_event_save_events( $result, $EM_Event, $event_ids, $post_ids ){
		global $wpdb;
		if( $result && current_user_can(self::$caps['custom_emails']) ){
			//delete all previous records of event coupons
			if( !empty($event_ids) ){
				$wpdb->query("DELETE FROM ".EM_META_TABLE." WHERE (meta_key='event-admin-emails' OR meta_key='event-emails') AND object_id IN (".implode(',',$event_ids).")");
			}
			//create inserts
			$inserts = array();
			if( !empty($EM_Event->custom_booking_emails) ){
				foreach($event_ids as $event_id){
					$inserts[] = $wpdb->prepare("(%d, 'event-emails', %s)", array($event_id, serialize($EM_Event->custom_booking_emails)));
				}
			}
			if( !empty($EM_Event->custom_admin_emails) ){
				foreach($event_ids as $event_id){
					$inserts[] = $wpdb->prepare("(%d, 'event-admin-emails', %s)", array($event_id, serialize($EM_Event->custom_admin_emails)));
				}
			}
			if( !empty($inserts) ){
				$sql = "INSERT INTO ".EM_META_TABLE." (object_id, meta_key, meta_value) VALUES ". implode(', ', $inserts);
				$wpdb->query($sql);
			}
		}
		return $result;
	}
}
EM_Custom_Emails_Admin::init();