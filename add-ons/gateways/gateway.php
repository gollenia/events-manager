<?php
/**
 * This class is a parent class which gateways should extend. There are various variables and functions that are automatically taken care of by
 * EM_Gateway, which will reduce redundant code and unecessary errors across all gateways. You can override any function you want on your gateway,
 * but it's advised you read through before doing so.
 *
 */
class EM_Gateway {
	/**
	 * Gateway reference, which is used in various places for referencing gateway info. Use lowercase characters/numbers and underscores.
	 * @var string
	 */
	var $gateway = 'unknown';
	/**
	 * This will be what admins see as the gatweway name (e.g. Offline, PayPal, Authorize.net ...)
	 * @var string
	 */
	var $title = 'Unknown';
	/**
	 * The default status value your gateway assigns this booking. Default is 0, i.e. pending 'something'.
	 * @var int
	 */
	var $status = 0;
	/**
	 * Set this to any true value and this will trigger the em_my_bookings_booked_message function to override the status name of this booking when in progress.
	 * @var string
	 */
	var $status_txt = '';
	/**
	 * If your gateway supports the ability to pay without requiring further fields (e.g. credit card info), then you can set this to true.
	 * 
	 * You will automatically have the ability to show buttons within your gateway. It's up to you to change what happens after by 
	 * overriding functions from EM_Gateway such as modifying booking_add or booking_form_feedback.
	 *  
	 * @var boolean
	 */
	var $button_enabled = false;
	/**
	 * If your gateway is compatible with our Multiple Bookings Mode, then you can set this to true, otherwise your gateway won't be available for booking in this mode.
	 *  
	 * @var boolean
	 */
	var $supports_multiple_bookings = false;	
	/**
	 * Some external gateways (e.g. PayPal IPNs) return information back to your site about payments, which allow you to automatically track refunds made outside Events Manager.
	 * If you enable this to true, be sure to add an overriding handle_payment_return function to deal with the information sent by your gateway.
	 * @var unknown_type
	 */
	var $payment_return = false;
	/**
	 * Counts bookings with pending spaces for availability 
	 * @var boolean
	 */
	var $count_pending_spaces = false;
	
	/**
	 * Associated array containing counts for pending spaces of specific events, which can be reused when called again later on.
	 * @var array
	 */
	protected $event_pending_spaces = array();
	/**
	 * Multidimensional associated containing pending spaces for specific tickets, within eacy array item is an array of event id keys and corresponding counts. 
	 * @var array
	 */
	protected $ticket_pending_spaces = array();
	/**
	 * Unassociated array containing the url sprintable structure to a live transaction detail, test transaction detail and title service name for link.
	 * Example: array('https://test.com/transaction/%s', 'https://sandbox.test.com/transaction/%s', 'test.com');
	 * @var array
	 */
	var $transaction_detail = array();

	/**
	 * Adds some basic actions and filters to hook into the EM_Gateways class and Events Manager bookings interface. 
	 */
	function __construct() {
		// Actions and Filters, only if gateway is active
		if( $this->is_active() ){
			add_filter('em_booking_output_placeholder',array(&$this, 'em_booking_output_placeholder'),1,4); //add booking placeholders
			if( $this->payment_return ){
				add_action('em_handle_payment_return_' . $this->gateway, array(&$this, 'handle_payment_return')); //handle return payment notifications
				add_action('rest_api_init', array( $this, 'register_handle_payment_api' ));
			}
			if(!empty($this->status_txt)){
				//Booking UI
				add_filter('em_my_bookings_booked_message',array(&$this,'em_my_bookings_booked_message'),10,2);
				add_filter('em_booking_get_status',array(&$this,'em_booking_get_status'),10,2);
			}
			if( !empty($this->transaction_detail) ){
				add_filter('em_gateways_transactions_table_gateway_id_'. $this->gateway, array(&$this, 'em_gateways_transactions_table_gateway_id'), 10, 2); //transaction list link
			}
		}
		if( $this->count_pending_spaces ){
			//Modify spaces calculations, required even if inactive, due to previously made bookings whilst this may have been active
			add_filter('em_bookings_get_pending_spaces', array(&$this, 'em_bookings_get_pending_spaces'),1,3);
			add_filter('em_ticket_get_pending_spaces', array(&$this, 'em_ticket_get_pending_spaces'),1,3);
			add_filter('em_booking_is_reserved', array(&$this, 'em_booking_is_reserved'),1,2);
			add_filter('em_booking_is_pending', array(&$this, 'em_booking_is_pending'),1,2);
		}
		//checkout-specific functions for redirects
		$this->handle_return_url();
	}
	
	public function register_handle_payment_api(){
		register_rest_route( 'events-manager/v1', '/gateways/'.$this->gateway.'/notify', array(
			array(
				'methods'  => 'GET,POST',
				'callback' => array( $this, 'handle_payment_return_api' ),
				'permission_callback' => array($this, 'gateway_api_permission')
			)
		) );
	}

	public function gateway_api_permission() {
		return true;
	}

	/*
	 * --------------------------------------------------
	 * OVERRIDABLE FUNCTIONS - should be overriden by the extending class
	 * --------------------------------------------------
	 */

	/**
	 * Triggered by the em_booking_add_yourgateway action, modifies the booking status if the event isn't free and also adds a filter to modify user feedback returned.
	 * @param EM_Event $EM_Event
	 * @param EM_Booking $EM_Booking
	 * @param boolean $post_validation
	 */
	function booking_add($EM_Event,$EM_Booking, $post_validation = false){
		global $wpdb, $wp_rewrite, $EM_Notices;
		add_filter('em_action_booking_add',array(&$this, 'booking_form_feedback'),1,2);//modify the payment return
		add_filter('em_action_emp_checkout',array(&$this, 'booking_form_feedback'),1,2);//modify the payment return
		if( $EM_Booking->get_price() > 0 ){
			$EM_Booking->booking_status = $this->status; //status 4 = awaiting online payment
		}
	}

	/**
	 * Intercepts return JSON and adjust feedback messages when booking with this gateway. This filter is added only when the em_booking_add function is triggered by the em_booking_add filter.
	 * @param array $return
	 * @param EM_Booking $EM_Booking
	 * @return array
	 */
	function booking_form_feedback( $return, $EM_Booking = false ){
		return $return; //remember this, it's a filter!	
	}

	/**
	 * Outputs extra custom content e.g. information about this gateway or extra form fields to be requested if this gateway is selected (not applicable with Quick Pay Buttons).
	 */
	function booking_form(){}

	/**
	 * Called by $this->settings(), override this to output your own gateway options on this gateway settings page  
	 */
	function mysettings(){}
	
	/**
	 * Run by EM_Gateways_Admin::handle_gateways_panel_updates() if this gateway has been updated. You should capture the values of your new fields above and save them as options here.
	 * @param $options array of option names that get updated when this gateway settings page is saved
	 * return boolean 
	 * @todo add $options as a parameter to method, and update all extending classes to prevent strict errors
	 */
	function update() {
		//custom options as well as ML options
		$function_args = func_get_args();
		$options = !empty($function_args[0]) ? $function_args[0]:array();
		//default action is to return true
		if($this->button_enabled){ 
			$options_wpkses[] = 'em_'.$this->gateway . '_button';
			add_filter('update_em_'.$this->gateway . '_button','wp_kses_post');
		}
		$options_wpkses[] = 'em_'.$this->gateway . '_option_name';		
		$options_wpkses[] = 'em_'.$this->gateway . '_form';
		//add filters for all $option_wpkses values so they go through wp_kses_post
		foreach( $options_wpkses as $option_wpkses ) add_filter('gateway_update_'.$option_wpkses,'wp_kses_post');
		$options = array_merge($options, $options_wpkses);	
		
		//go through the options, grab them from $_REQUEST, run them through a filter for sanitization and save 
		foreach( $options as $option_index => $option_name ){
			if( is_array( $option_name ) ){
				$option_values = array();
				foreach( $option_name as $option_key ){
				    $option_value_raw = !empty($_REQUEST[$option_index.'_'.$option_key]) ? stripslashes($_REQUEST[$option_index.'_'.$option_key]) : '';
				    $option_values[$option_key] = apply_filters('gateway_update_'.$option_index.'_'.$option_key, $option_value_raw);
				}
			    update_option($option_index, $option_values);
			}else{
			    $option_value_raw = !empty($_REQUEST[$option_name]) ? stripslashes($_REQUEST[$option_name]) : '';
			    $option_value = apply_filters('gateway_update_'.$option_name, $option_value_raw);
			    update_option($option_name, $option_value);
			}
		}
		do_action('em_updated_gateway_options', $options, $this);
		do_action('em_gateway_update', $this);
		return true;
	}

	/**
	 * Adds extra placeholders to the booking email. Called by em_booking_output_placeholder filter, added in this object __construct() function.
	 * 
	 * You can override this function and just use this within your function:
	 * $result = parent::em_booking_output_placeholder($result);
	 * 
	 * @param string $result
	 * @param EM_Booking $EM_Booking
	 * @param string $placeholder
	 * @param string $target
	 * @return string
	 */
	function em_booking_output_placeholder($result,$EM_Booking,$placeholder,$target='html'){	
		global $wpdb;
		if( ($placeholder == "#_BOOKINGTXNID" && !empty($EM_Booking->booking_meta['gateway'])) && $EM_Booking->booking_meta['gateway'] == $this->gateway ){
			if(empty($EM_Booking->BOOKINGTXNID)){
				$sql = $wpdb->prepare( "SELECT transaction_gateway_id FROM ".EM_TRANSACTIONS_TABLE." WHERE booking_id=%d AND transaction_gateway = %s", $EM_Booking->booking_id, $this->gateway );
				$txn_id = $wpdb->get_var($sql);
				if(!empty($txn_id)){
					$result = $EM_Booking->BOOKINGTXNID = $txn_id;
				}else{
				    $result = '';
				}
			}else{
				$result = $EM_Booking->BOOKINGTXNID;
			}
		}
		return $result;
	}
	
	/**
	 * Return a WP REST result for handling a payment return
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 * @return WP_Error|WP_REST_Response
	 */
	public function handle_payment_return_api( $request ) {
		$message = 'Missing POST variables. Identification is not possible. If you are not '.$this->title.' and are visiting this page directly in your browser, this error does not indicate a problem, but simply means Events Manager is correctly set up and ready to receive communication from '.$this->title.' only.';
		return new WP_REST_Response( array('message' => $message), 200 );
	}
	
	/**
	 * If you set your gateway class $payment_return property to true, this function will be called when your external gateway sends a notification of payment.
	 * 
	 * Override this in your function to catch payment returns and do something with this information, such as handling refunds.
	 */
	function handle_payment_return(){}
	
	/**
	 * If you would like to modify the default status message for this payment whilst in progress.
	 * 
	 * This function is triggered if set $this->status_text to something and this will be called automatically. You can also override this function.
	 * 
	 * @param string $message
	 * @param EM_Booking $EM_Booking
	 * @return string
	 */
	function em_booking_get_status($message, $EM_Booking){
		if( !empty($this->status_txt) && $EM_Booking->booking_status == $this->status && $this->uses_gateway($EM_Booking) ){ 
			return $this->status_txt; 
		}
		return $message;
	}

	function get_rest() {
		return array(
			'name' => $this->gateway,
			"title" => get_option('em_'.$this->gateway.'_option_name'),
        	"html" => get_option('em_'.$this->gateway.'_form'),
			"description" => get_option('em_'.$this->gateway.'_option_description'),
		);
	}
	
	/*
	 * --------------------------------------------------
	 * PENDING SPACE COUNTING - if $this->count_pending_spaces is true, depending on the gateway, bookings with this gateway status are considered pending and reserved
	 * --------------------------------------------------
	 */
	
	/**
	 * Modifies pending spaces calculations to include paypal bookings, but only if PayPal bookings are set to time-out (i.e. they'll get deleted after x minutes), therefore can be considered as 'pending' and can be reserved temporarily.
	 * @param integer $count
	 * @param EM_Bookings $EM_Bookings
	 * @return integer
	 */
	function em_bookings_get_pending_spaces($count, $EM_Bookings, $force_refresh = false){
		global $wpdb;
		if( !array_key_exists($EM_Bookings->event_id, $this->event_pending_spaces) || $force_refresh ){
			$sql = 'SELECT SUM(booking_spaces) FROM '.EM_BOOKINGS_TABLE. ' WHERE booking_status=%d AND event_id=%d AND booking_meta LIKE %s';
			$gateway_filter = '%s:7:"gateway";s:'.strlen($this->gateway).':"'.$this->gateway.'";%';
			$pending_spaces = $wpdb->get_var( $wpdb->prepare($sql, array($this->status, $EM_Bookings->event_id, $gateway_filter)) );
			$this->event_pending_spaces[$EM_Bookings->event_id] = $pending_spaces > 0 ? $pending_spaces : 0;
		}
		return $count + $this->event_pending_spaces[$EM_Bookings->event_id];
	}
	
	/**
	 * Changes EM_Booking::is_reserved() return value to true. Only called if $this->count_pending_spaces is set to true.
	 * @param boolean $result
	 * @param EM_Booking $EM_Booking
	 * @return boolean
	 */
	function em_booking_is_reserved( $result, $EM_Booking ){
		if($EM_Booking->booking_status == $this->status && $this->uses_gateway($EM_Booking) && get_option('dbem_bookings_approval_reserved')){
			return true;
		}
		return $result;
	}
	
	function em_booking_is_pending( $result, $EM_Booking ){
		if( $EM_Booking->booking_status == $this->status  && $this->uses_gateway($EM_Booking) && $this->count_pending_spaces ){
			return true;
		}
		return $result;
	}
	
	/**
	 * Modifies pending spaces calculations for individual tickets to include paypal bookings, but only if PayPal bookings are set to time-out (i.e. they'll get deleted after x minutes), therefore can be considered as 'pending' and can be reserved temporarily.
	 * @param integer $count
	 * @param EM_Ticket $EM_Ticket
	 * @return integer
	 */
	function em_ticket_get_pending_spaces($count, $EM_Ticket, $force_refresh = false){
		global $wpdb;
		if( empty($this->ticket_pending_spaces[$EM_Ticket->ticket_id]) || !array_key_exists($EM_Ticket->event_id, $this->ticket_pending_spaces[$EM_Ticket->ticket_id]) || $force_refresh ){
			if( empty($this->ticket_pending_spaces[$EM_Ticket->ticket_id]) ) $this->ticket_pending_spaces[$EM_Ticket->ticket_id] = array();
			$gateway_filter = '%s:7:"gateway";s:'.strlen($this->gateway).':"'.$this->gateway.'";%';
			$booking_ids_sql = $wpdb->prepare('SELECT booking_id FROM '.EM_BOOKINGS_TABLE.' WHERE event_id=%d AND booking_status=%d AND booking_meta LIKE %s', $EM_Ticket->event_id, $this->status, $gateway_filter);
			$sql = 'SELECT SUM(ticket_booking_spaces) FROM '.EM_TICKETS_BOOKINGS_TABLE. ' WHERE ticket_id='.absint($EM_Ticket->ticket_id).' AND booking_id IN ('.$booking_ids_sql.')';
			$pending_spaces = $wpdb->get_var( $sql );
			$this->ticket_pending_spaces[$EM_Ticket->ticket_id][$EM_Ticket->event_id] = $pending_spaces > 0 ? $pending_spaces : 0;
		}
		return $count + $this->ticket_pending_spaces[$EM_Ticket->ticket_id][$EM_Ticket->event_id];
	}
	
		
	/*
	 * --------------------------------------------------
	 * BUTTONS MODE Functions - i.e. booking doesn't require gateway selection, just button click, EMP adds gateway choice via JS to submission
	 * --------------------------------------------------
	 */
	
	/**
	 * Shows button, not needed if using the new form display
	 * @param string $button
	 * @return string
	 */
	function booking_form_button(){
		ob_start();
		if( preg_match('/https?:\/\//',get_option('em_'. $this->gateway . "_button")) ): ?>
			<input type="image" class="em-booking-submit em-gateway-button" id="em-gateway-button-<?php echo $this->gateway; ?>" src="<?php echo get_option('em_'. $this->gateway . "_button"); ?>" alt="<?php echo $this->title; ?>" />
		<?php else: ?>
			<input type="submit" class="em-booking-submit em-gateway-button" id="em-gateway-button-<?php echo $this->gateway; ?>" value="<?php echo get_option('em_'. $this->gateway . "_button",$this->title); ?>" />
		<?php endif;
		return ob_get_clean();
	}
	
	/*
	 * --------------------------------------------------
	 * PARENT FUNCTIONS - overriding not required, but could be done
	 * --------------------------------------------------
	 */
	
	//START Thank you and cancel page handling for gateways with redirect functionality
	/**
	 * Detect if use was brought back from gateway checkout and needs to be served a thank you message. Adds hooks to thank user on MB checkout page, my bookings page, and event page.
	 */
	function handle_return_url(){
		if( !empty($_GET['payment_complete']) && $_GET['payment_complete'] == $this->gateway ){
			//add actions for each page where a thank you might appear by default
			add_action('em_template_my_bookings_header', array(&$this, 'thank_you_message'));
			add_action('em_booking_form_top', array(&$this, 'thank_you_message'));
		}
	}
	
	/**
	 * Outputs thank you message from gateway settings.
	 * @see EM_Gateway::get_thank_you_message()
	 */
	function thank_you_message(){
		echo $this->get_thank_you_message();
	}
	/**
	 * Returns thank you message from gateway settings.
	 * @return string
	 */
	function get_thank_you_message(){
		return "<div class='em-booking-message em-booking-message-success'>".get_option('em_'.$this->gateway.'_booking_feedback_completed').'</div>';
	}
	
	/**
	 * Gets a return url where a thank you message can be displayed. If no return URL can be determined, the home page will be used even though a thank you message will not show by default.
	 * @param EM_Booking $EM_Booking If provided, and there is no other page to redirect to, the event page of this booking will be used.
	 * @return string
	 */
	function get_return_url( $EM_Booking = null ){
		if( get_option('em_'. $this->gateway . "_return" ) ){
			return get_option('em_'. $this->gateway . "_return" );
		}
		
		if( $EM_Booking ){
			//otherwise, send back to original event page when booking is provided
			$my_bookings_url = $EM_Booking->get_event()->get_permalink();
		}else{
			//no thank you message, but we redirect anyway
			$my_bookings_url = get_home_url();
		}
		
		//add the flag for displaying a message and return
		return add_query_arg('payment_complete', $this->gateway, $my_bookings_url);
	}
	
	/**
	 * Gets a cancellation url where a relevant you message can be displayed. If no cancellation URL has been set, the event page the booking was attempted for will be used.
	 * @param EM_Booking $EM_Booking
	 * @return string
	 */
	function get_cancel_url( $EM_Booking ){
		if( get_option('em_'. $this->gateway . "_cancel" ) ){
			return get_option('em_'. $this->gateway . "_cancel" );
		}else{
			$my_bookings_url = $EM_Booking->get_event()->get_permalink();
			return add_query_arg('payment_cancelled', $this->gateway, $my_bookings_url);
		}
	}
	//END Thank you and cancel page handling for gateways with redirect functionality
	
	/**
	 * Gets the gateway option from the correct place. Does not require prefixing of em_gatewayname_
	 * Will be particularly useful when restricting possible gateway settings in MultiSite mode and sharing accross networks, use this and you're future-proof.
	 * @param string $name
	 * @param mixed $value
	 * @return mixed
	 */
	function get_option( $name ){
		return get_option('em_'.$this->gateway.'_'.$name);
	}
	
	/**
	 * Updates the gateway option to the correct place. Does not require prefixing of em_gatewayname_
	 * Will be particularly useful when restricting possible gateway settings in MultiSite mode and sharing accross networks, use this and you're future-proof.
	 * @param string $name
	 * @param mixed $value
	 * @return boolean
	 */
	function update_option( $name, $value ){
		return update_option('em_'.$this->gateway.'_'.$name, $value);
	}
	
	/**
	 * Checks an EM_Booking object and returns whether or not this gateway is/was used in the booking.
	 * @param EM_Booking $EM_Booking
	 * @return boolean
	 */
	function uses_gateway($EM_Booking){
		return (!empty($EM_Booking->booking_meta['gateway']) && $EM_Booking->booking_meta['gateway'] == $this->gateway);
	}


	/**
	 * Returns the notification URL which gateways sends return messages to, e.g. notifying of payment status. 
	 * 
	 * Your URL would correspond to http://yoursite.com/wp-admin/admin-ajax.php?action=em_payment&em_payment_gateway=gatewayname
	 * @return string
	 */
	function get_payment_return_url(){
		return admin_url('admin-ajax.php?action=em_payment&em_payment_gateway='.$this->gateway);
	}
	
	/**
	 * Returns the notification URL which gateways sends return messages to, e.g. notifying of payment status.
	 *
	 * Your URL would correspond to http://yoursite.com/wp-admin/admin-ajax.php?action=em_payment&em_payment_gateway=gatewayname
	 * @return string
	 */
	function get_payment_return_api_url(){
		return get_rest_url( get_current_blog_id(), 'events-manager/v1/gateways/'.$this->gateway.'/notify' );
	}

	/**
	 * Records a transaction according to this booking and gateway type.
	 * @param EM_Booking $EM_Booking
	 * @param float $amount
	 * @param string $currency
	 * @param int $timestamp
	 * @param string $txn_id
	 * @param int $payment_status
	 * @param string $note
	 */
	function record_transaction($EM_Booking, $amount, $currency, $timestamp, $txn_id, $payment_status, $note) {
		global $wpdb;
		$data = array();
		$data['booking_id'] = $EM_Booking->booking_id;
		$data['transaction_gateway_id'] = $txn_id;
		$data['transaction_timestamp'] = $timestamp;
		$data['transaction_currency'] = $currency;
		$data['transaction_status'] = $payment_status;
		$data['transaction_total_amount'] = $amount;
		$data['transaction_note'] = $note;
		$data['transaction_gateway'] = $this->gateway;

		if( !empty($txn_id) ){
			$existing = $wpdb->get_row( $wpdb->prepare( "SELECT transaction_id, transaction_status, transaction_gateway_id, transaction_total_amount FROM ".EM_TRANSACTIONS_TABLE." WHERE transaction_gateway = %s AND transaction_gateway_id = %s AND transaction_status=%s", $this->gateway, $txn_id, $payment_status ) );
		}
		$table = EM_TRANSACTIONS_TABLE;
		
		if( !empty($existing->transaction_gateway_id) && $amount == $existing->transaction_total_amount ) {
			return;
			
		}
		
		if( is_numeric($timestamp) ){ //convert unix timestamps
			$data['transaction_timestamp'] = date('Y-m-d H:i:s', $timestamp);
		}
		
		$wpdb->insert( $table, $data );
		
	}
	
	/**
	 * Converts the transaction ID field in transaction admin tables into a clickable link to view the transaction on PayPal.
	 * @param $transaction_id
	 * @param $transaction
	 * @return string
	 */
	function em_gateways_transactions_table_gateway_id($transaction_id, $transaction ){
		$gateway_url = ( get_option('em_'. $this->gateway . "_status" ) == 'live') ? $this->transaction_detail[0] : $this->transaction_detail[1];
		$title = sprintf( esc_attr__('View this transaction on %s', 'events-manager-pro'), $this->transaction_detail[0]);
		$transaction_id = '<a href="'. esc_url(sprintf($gateway_url,$transaction->transaction_gateway_id)) .'" target="_blank" title="'.$title.'">'. $transaction->transaction_gateway_id .'</a>';
		return $transaction_id;
	}

	function toggleactivation() {
		$active = get_option('em_payment_gateways');

		if(array_key_exists($this->gateway, $active)) {
			unset($active[$this->gateway]);
			update_option('em_payment_gateways',$active);
			return true;
		} else {
			$active[$this->gateway] = true;
			update_option('em_payment_gateways',$active);
			return true;
		}
	}

	function activate() {
		$active = get_option('em_payment_gateways', array());
		if(array_key_exists($this->gateway, $active)) {
			return true;
		} else {
			$active[$this->gateway] = true;
			update_option('em_payment_gateways', $active);
			return true;
		}
	}

	function deactivate() {
		$active = get_option('em_payment_gateways');
		if(array_key_exists($this->gateway, $active)) {
			unset($active[$this->gateway]);
			update_option('em_payment_gateways', $active);
			return true;
		} else {
			return true;
		}
	}

	function is_active() {
		$active = get_option('em_payment_gateways', array());
		$is_active = array_key_exists($this->gateway, $active);
		
		return $is_active;			
		
	}

	/**
	 * Generates a settings pages.
	 * @uses EM_Gateway::mysettings()
	 */
	function settings() {
		global $page, $action, $EM_Notices;
		$gateway_link = admin_url('edit.php?post_type='.EM_POST_TYPE_EVENT.'&page=events-manager-options#bookings');
		$messages['updated'] = esc_html__('Gateway updated.', 'em-pro');
		$messages['error'] = esc_html__('Gateway not updated.', 'em-pro');
		?>
	    <script type="text/javascript" charset="utf-8"><?php include(EM_DIR.'/includes/js/admin-settings.js'); ?></script>
		<div class='wrap nosubsub'>
			<h1><?php echo sprintf(__('Edit &quot;%s&quot; settings','em-pro'), esc_html($this->title) ); ?></h1>
			<?php
			if ( isset($_GET['msg']) && !empty($messages[$_GET['msg']]) ){ 
				echo '<div id="message" class="'.$_GET['msg'].' fade"><p>' . $messages[$_GET['msg']] . 
				' <a href="'.em_add_get_params($_SERVER['REQUEST_URI'], array('action'=>null,'gateway'=>null, 'msg' => null)).'">'.esc_html__('Back to gateways','em-pro').'</a>'.
				'</p></div>';
			}
			?>
			<form action='' method='post' name='gatewaysettingsform' class="em-gateway-settings">
				<input type='hidden' name='action' id='action' value='updated' />
				<input type='hidden' name='gateway' id='gateway' value='<?php echo $this->gateway; ?>' />
				<?php wp_nonce_field('updated-' . $this->gateway); ?>
				<h3><?php echo sprintf(esc_html( '%s Options', 'events-manager'),esc_html('Booking Form','events-manager')); ?></h3>
				<table class="form-table">
				<tbody>
                    <?php
                        //Gateway Title
                        $desc = sprintf(__('Only if you have not enabled quick pay buttons in your <a href="%s">gateway settings</a>.', 'em-pro'),$gateway_link).' '.
				  		__('The user will see this as the text option when choosing a payment method.','em-pro'); 
                        em_options_input_text(__('Gateway Title', 'em-pro'), 'em_'.$this->gateway.'_option_name', $desc);

                        //Gateway booking form info
                        $desc = sprintf(__('Only if you have not enabled quick pay buttons in your <a href="%s">gateway settings</a>.','em-pro'),$gateway_link).
                    	' '.__('If a user chooses to pay with this gateway, or it is selected by default, this message will be shown just below the selection.', 'em-pro'); 
                        em_options_textarea(__('Booking Form Information', 'em-pro'), 'em_'.$this->gateway.'_form', $desc); 
                    ?>
				</tbody>
				</table>
				<?php $this->mysettings(); ?>
				<?php if($this->button_enabled): ?>
				<h3><?php echo _e('Quick Pay Buttons','em-pro'); ?></h3>
				<p><?php echo sprintf(__('If you have chosen to only use quick pay buttons in your <a href="%s">gateway settings</a>, these settings below will be used.','em-pro'), $gateway_link); ?></p>
				<table class="form-table">
				<tbody>
				  <?php
				      $desc = sprintf(__('Choose the button text. To use an image instead, enter the full url starting with %s or %s.', 'em-pro' ), '<code>http://...</code>','<code>https://...</code>');
                      em_options_input_text(__('Payment Button', 'em-pro'), 'em_'.$this->gateway.'_button', $desc); 
				  ?>
				</tbody>
				</table>
				<?php endif; ?>
				<?php do_action('em_gateway_settings_footer', $this); ?>
				<p class="submit">
				<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
				</p>
			</form>
		</div> <!-- wrap -->
		<?php
	}
	
	public function settings_sensitive_credentials( $api_cred_fields, $is_sandbox ){
		if( !is_ssl() ){
			?>
			 <tr>
				 <td colspan="2">
					<?php
						 echo '<p style="color:red;">';
						 echo sprintf( esc_html__('Your site is not using SSL! Whilst not a requirement, if you\'re going to submit API information for a live %s account, we recommend you do so over a secure connection. If this is not possible, consider an alternative option of submitting your API information as covered in our %s.', 'em-pro'),
							 $this->title, '<a href="http://wp-events-plugin.com/documentation/events-with-paypal/safe-encryption-api-keys/">'.esc_html__('documentation','events-manager').'</a>');
						 echo '</p>';
						 if( (!defined('EMP_GATEWAY_SSL_OVERRIDE') || !EMP_GATEWAY_SSL_OVERRIDE) && ($is_sandbox && empty($_REQUEST['show_keys'])) ){
							 echo '<p>'.esc_html__('If you are only using testing credentials, you can display and save them safely.', 'em-pro');
							 echo ' <a href="'. esc_url(add_query_arg('show_keys', wp_create_nonce('show_'. $this->gateway . '_creds'))) .'" class="button-secondary">'. esc_html__('Show API Keys', 'em-pro') .'</a>';
							 echo '</p>';
						 }
					 ?>
				 </td>
			 </tr>
			<?php
		}
		 $api_options = get_option('em_'. $this->gateway . '_api');
		 if( $this->settings_show_settings_credentials( $is_sandbox ) ) {
		    foreach( $api_cred_fields as $api_cred_opt => $api_cred_label ){
		        $api_cred_value = !empty($api_options[$api_cred_opt]) && $api_options[$api_cred_opt] !== $api_cred_label ? $api_options[$api_cred_opt] : '';
		        ?>
				 <tr valign="top" id='<?php echo 'em_'. $this->gateway . '_api_'. esc_attr($api_cred_opt); ?>_row'>
					 <th scope="row"><?php echo esc_html($api_cred_label); ?></th>
					 <td>
						 <input value="<?php echo esc_attr($api_cred_value); ?>" name="<?php echo 'em_'. $this->gateway . '_api_'. esc_attr($api_cred_opt) ?>" type="text" id="<?php echo 'em_'. $this->gateway . '_api_'. esc_attr($api_cred_opt) ?>" style="width: 95%" size="45" />
					 </td>
				 </tr>
			    <?php
		    }
		 } else {
			foreach( $api_cred_fields as $api_cred_opt => $api_cred_label ){
				$api_cred_value = !empty($api_options[$api_cred_opt]) && $api_options[$api_cred_opt] !== $api_cred_label ? $api_options[$api_cred_opt] : '';
				?>
				 <tr valign="top">
					 <th scope="row"><?php echo esc_html($api_cred_label); ?></th>
					 <td>
						 <?php
						 $chars = '';
						 for( $i = 0; $i < strlen($api_cred_value); $i++ ) $chars = $chars . '*';
						 echo esc_html(str_replace( substr($api_cred_value, 1, -1), $chars, $api_cred_value) );
						 ?>
					 </td>
				 </tr>
				<?php
			}
		}
	}
	
	public function settings_show_settings_credentials( $is_sandbox = false ){
		return is_ssl() || (defined('EMP_GATEWAY_SSL_OVERRIDE') && EMP_GATEWAY_SSL_OVERRIDE) || ($is_sandbox && !empty($_REQUEST['show_keys']) && wp_verify_nonce($_REQUEST['show_keys'], 'show_'. $this->gateway . '_creds'));
	}
}

function emp_gateway_ml_init(){ include('gateway-ml.php'); }
add_action('em_ml_init', 'emp_gateway_ml_init');
?>