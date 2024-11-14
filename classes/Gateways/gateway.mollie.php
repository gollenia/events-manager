<?php
// Exit if accessed directly

use Contexis\Events\Options;

if (!defined('ABSPATH')) exit;


/**
 * Configure Mollie Gateway for Events Manager Pro.
 */
Class EM_Gateway_Mollie extends EM_Gateway {

	var $gateway 		= 'mollie';
	var $title 			= 'Mollie';
	var $status 		= 4;
	var $status_txt 	= 'Awaiting Mollie Payment';
	var $button_enabled = true;
	var $payment_return = true;
	var $supports_multiple_bookings = true;
	var $transaction_detail = array(
			'Mollie Dashboard',
			'https://www.mollie.com/dashboard/payments/%s',
			'https://www.mollie.com/dashboard/payments/%s'
		);

	var $mollie;

	public function __construct() {
		parent::__construct();
		
		
		$mollie = self::start_mollie();

		if( is_object($mollie) ) {
			$this->mollie = $mollie;
		}

		// Check if the gateway is activated (= toggled).
		if( parent::is_active() ) {
			add_filter('em_booking_validate', array($this, 'booking_validate'), 2, 2);
		}
		add_filter('the_content', array($this, 'handle_mollie_customer_return'));
		add_action('rest_api_init', array($this, 'register_rest_routes'));
	}


	/**
	 * Booking Interception - functions that modify booking object behaviour
	 *
	 * @return void
	 */
	function booking_form() {
		
	}

	function get_rest() {
		return array(
			'name' => $this->gateway,
			"title" => get_option('em_'.$this->gateway.'_option_name'),
        	"html" => get_option('em_'.$this->gateway.'_form'),
			"description" => get_option('em_'.$this->gateway.'_option_description'),
			"methods" => EM_Gateway_Mollie::get_methods(),
		);
	}

	function get_payment_info($booking) {
		$payment = $booking->get_price_summary_array();
		$payment['paymentType'] = $_POST['paymentType'];
		return $payment;
	}


	/**
	 * Hook into booking validation and check validate payment type if present.
	 *
	 * @param boolean $result
	 * @param EM_Booking $EM_Booking
	 * @return boolean
	 */
	function booking_validate($result, $EM_Booking) {
		if (isset( $_POST['paymentType'] ) && empty( $_POST['paymentType'] )) {
			$EM_Booking->add_error( __('Please select a payment method.', 'events-manager') );
			$result = false;
		}

		$api_key = get_option('em_mollie_api_key');
		if( !isset($api_key) || empty($api_key) ) {
			$EM_Booking->add_error( __('Mollie API Key is not found.', 'events-manager') );
			$result = false;
		}

		return $result;
	}
	/**
	 * After form submission by user, add Mollie vars and show feedback message.
	 *
	 * @param string $return
	 * @param EM_Booking $booking
	 * @return array
	 */
	function booking_form_feedback( $response, EM_Booking $booking ) {
		if( !empty($response['errors']) || !is_object($booking) || !$this->uses_gateway($booking) ) return $response;
	
		if ($booking->get_price() == 0 ) {
			$response['message'] 	= get_option('em_mollie_message_free');
			return $response;
		}

		$mollie 	= self::start_mollie();

		$description = $booking->output(get_option('em_mollie_description') ?? sprintf( esc_html__('%s tickets for %s', 'events-manager'), '#_BOOKINGSPACES', '#_EVENTNAME'));

		$args = [
			'amount'  		=> [
				'currency' 		=> strtoupper( get_option('dbem_bookings_currency') ),
				'value'   		=> number_format( $booking->get_price(), 2)
			],
			'description' 	=> $description,
			'redirectUrl' 	=> $this->get_mollie_return_url($booking) . "?em_mollie_return={$booking->booking_id}",
			'webhookUrl' 	=> $this->get_payment_return_url(),
			'locale'   		=> get_locale(),		// Set checkout page to blog's locale.
			'sequenceType'  => 'oneoff',  			// Default for single payment.
			'metadata'  	=> [
				'booking_id' 	=> $booking->booking_id,
				'name'    		=> $booking->get_person()->get_name(),
				'email'   		=> $booking->get_person()->user_email,
			],
		];
		
		$request 	= $mollie->payments->create( $args );

		$response['gateway'] = [
			'url' => $request->getCheckoutUrl(),
			'message' => get_option('em_mollie_message_redirect'),
		];
		return $response;
	}


	/**
	 * Determine the redirect url after Mollie payment.
	 *
	 * @return string URL
	 */
	function get_mollie_return_url($booking) {
		$event = $booking->get_event();

		 if( get_option('em_mollie_return_page') ){
			 return get_permalink(get_option( 'em_mollie_return_page') );
		 }
		 return $event->output("#_EVENTURL");
	}


	/**
	 * Handle content when a user returns from Mollie after payment.
	 *
	 * @param string $content
	 * @return string Page content
	 */
	function handle_mollie_customer_return( $content ) {
	 	if( strpos($_SERVER['REQUEST_URI'], 'em_mollie_free') !== false ) {
			$content = sprintf( '<p><div class="em-booking-message em-booking-message-success">%s</div></p>', get_option('em_mollie_message_free'));
			return $content;
		}

		if( strpos($_SERVER['REQUEST_URI'], 'em_mollie_return') !== false ) {
			// Changed in version 2.3
			$class 			= null;
			$feedback 		= null;
			$result 		= null;
			$booking_id 	= $_REQUEST['em_mollie_return'];
			$EM_Booking 	= EM_Booking::find($booking_id);
			$status 		= (int) $EM_Booking->status;

			$payment_status = array(
				0 => __("Pending", 'events-manager'),
				1 => __("Approved", 'events-manager'),
				2 => __("Rejected", 'events-manager'),
				3 => __("Cancelled", 'events-manager'),
				4 => __("Waiting for Mollie", 'events-manager'),
				5 => __("Pending", 'events-manager'),
			);

			switch( $status ) {
				case 1: 	// Approved
					$class 		= 'success';
					$feedback 	= get_option('dbem_booking_feedback');
				break;
				case 3:		// Cancelled
				case 2: 	// Reject = fallback.
					$class 		= 'error';
					$feedback 	= __('Booking could not be created','events-manager');
				break;
				case 0: 	// Pending/Open
				case 4: 	// Awaiting Online Payment.
				case 5: 	// Awaiting Payment.
					$class 		= 'warning';
					$feedback 	= get_option('dbem_booking_feedback_pending');
					// Add styling for this status only - use EM css for the others.
				break;
			}
			$status_string 	= get_option('em_mollie_status_text') ?? __('The status of your payment is', 'events-manager');
			$status_text 	= sprintf('<h3 class="alert__title">%s: %s</h3>', $status_string, strtoupper($payment_status[$status]) );
			$status_text 	= get_option('em_mollie_show_status') != 'no' ? $status_text : null;
			$feedback_text 	= get_option('em_mollie_show_feedback') != 'no' ? $feedback	: null;
			$button 		= sprintf('<div class="button-group button-group--right"><a class="button button--primary" href=%s>%s</a></div>',
				esc_url(get_permalink(get_option('dbem_events_page'))), esc_attr__('Continue', 'events-manager')	);

			$result 	= sprintf('<section class="section py-12 %s" style="max-width: 33%%;">', $class);
			$result 	.= '<div class="card card--shadow bg-white card__image-top">';
			$result 	.= '<div class="card__content"><div class="card__title">' . $status_text . '</div><div class="card__text">' . $feedback_text . '</div>';
			$result		.= $button;
			$result		.= '</div></div></section>';

			$content = apply_filters('em_mollie_payment_feedback', $result);
			return $content;
		}
		return $content;
	}


	/**
	 * When Mollie calls the webhook, update database, update Booking Status & send emails.
	 *
	 * @return void
	 */
	function handle_payment_return() {
		if( !isset($_REQUEST['em_payment_gateway']) || $_REQUEST['em_payment_gateway'] != 'mollie' || !isset($_REQUEST['id']) ) {
			return;
		}

		$mollie_id = trim( $_REQUEST['id'] );

		// Fetch all transaction info from Mollie.
		$mollie 	= self::start_mollie();

		if( !is_object($mollie) ) return;

		$payment 	= $mollie->payments->get($mollie_id);
		$timestamp  = date('Y-m-d H:i:s', strtotime($payment->createdAt));
		$booking_id = $payment->metadata->booking_id;
		$EM_Booking	= EM_Booking::find($booking_id);
		$note 		= ' ';

		if (empty( $EM_Booking->booking_id )) return
		$EM_Booking->manage_override = true;

		if ($payment->isPaid() && !$payment->hasRefunds() && !$payment->hasChargebacks()) {
			$this->record_transaction( $EM_Booking, $payment->amount->value, strtoupper($payment->amount->currency), $timestamp, $mollie_id, 'Completed', $note );
			$EM_Booking->approve(true, true);
		}

		elseif ($payment->isOpen() || $payment->isPending()) {
			$EM_Booking->set_status(4);
		}

		elseif ($payment->isCanceled() || $payment->isFailed() || $payment->isExpired()) {
			// Mollie uses US spelling.
			$payment->status = ($payment->status != 'canceled') ? $payment->status : 'cancelled';
			$this->record_transaction( $EM_Booking, $payment->amount->value, strtoupper($payment->amount->currency), $timestamp, $mollie_id, 'Canceled', $note );
			$send_mail = get_option('em_mollie_send_cancel_mail') != 'yes' ? false : true;
			$EM_Booking->set_status(3, $send_mail);
		}

		elseif ($payment->hasChargebacks()) {
			$note = __('Charged back', 'events-manager');
			$this->record_transaction( $EM_Booking, $payment->amount->value, strtoupper($payment->amount->currency), $timestamp, $mollie_id, 'Charded back', $note);
			$EM_Booking->set_status(3);
		}
		elseif ($payment->hasRefunds()) {
			// Fetch detailed info for refund from Mollie.
			foreach( $payment->refunds() as $refund ) {
				$date 		= $this->get_localized_time($refund->createdAt);
				$note 		= sprintf( __('Refunded on %s', 'events-manager'), $date );
				$this->record_transaction( $EM_Booking, $payment->amountRefunded->value, strtoupper($payment->amount->currency), $timestamp, $mollie_id, 'Refunded', $note);
			}
		}

		do_action('em_payment_processed', $EM_Booking, $this);
		
		return;
	}


	/**
	 * Gateway Settings Functions
	 *
	 * @return array Settings
	 */
	function define_settings_fields() {
		;
		
		$dashboard_url 	= 'https://www.mollie.com/dashboard/developers/api-keys';
		$force_reload 	= admin_url('edit.php?post_type=event&page=events-manager-gateways&action=edit&gateway=mollie&em_mollie_action=refresh_methods');

		$fields_array = array(
		
			
		
			
			array(
				'id' 		=> 'show_status',
				'label' 	=> __('Display Payment Status', 'events-manager'),
				'type' 		=> 'toggle',
				'default' 	=> 'yes',
				'help' 		=> __('Display the payment status on the Return Page?', 'events-manager') .'<br><code>'. __('The status of your payment is:', 'events-manager') .' [status]</code>',
			),
			array(
				'id' 		=> 'status_text',
				'label' 	=> __('Payment Status Text', 'events-manager'),
				'type' 		=> 'text',
				'default'	=>  __('The status of your payment is', 'events-manager'),
				'help'		=> __('This will change the output of the setting above.', 'events-manager') .'<br>'. __('Default') .': <code>'. __('The status of your payment is', 'events-manager') .'</code>',
			),
			array(
				'id' 		=> 'show_feedback',
				'label' 	=> __('Display Feedback Messages', 'events-manager'),
				'type' 		=> 'toggle',
				'default' 	=> 'yes',
				'help' 		=> sprintf( __('Display the booking feedback messages as set in your <a href=%s target="_blank">Events Manager Settings</a>?', 'events-manager'), admin_url('edit.php?post_type=event&page=events-manager-options#bookings') ),
			),
			array(
				'id' 		=> 'description',
				'label' 	=> __('Payment Description', 'events-manager'),
				'type'		=> 'text',
				'default' 	=> sprintf( esc_html__('%s tickets for %s', 'events-manager'), '#_BOOKINGSPACES', '#_EVENTNAME'),
				'help' 		=> sprintf( esc_html__('Shown in the payment description in your Mollie backend. All %s are allowed.', 'events-manager'), '<a href='. admin_url('edit.php?post_type=event&page=events-manager-help') .' target="_blank">' . __("Events Manager Placeholders", 'events-manager') . '</a>' ),
			),
			array(
				'id'		=> 'send_cancel_mail',
				'label'  	=> __('Send email on failed / cancelled payment?', 'events-manager'),
				'type' 		=> 'toggle',
				'default' 	=> 'yes',
				'help'		=> __('By default Events Manager will send the Booking Cancelled Email if a payment had failed or is incomplete. This can lead to confusion if the user rebooks right after with a successful payment. This option lets you disable sending the automatic Booking Cancelled Email. (Setting this option to "no" will not affect the email if you change the booking status manually.)', 'events-manager'),
			),
		);
		return $fields_array;
	}


	/**
	 * Create the Gateway Settings Page.
	 *
	 * @return void
	 */
	function mysettings() {
		
		wp_enqueue_script('em-mollie');
		wp_enqueue_style('em-mollie');
		$dashboard_url 	= 'https://www.mollie.com/dashboard/developers/api-keys';
		echo '<table class="form-table stonehenge-table">';
			Options::input( __('Mollie API Key', 'events-manager'), 'em_mollie_api_key', sprintf( __('Obtain your Live or Test API Key from your <a href=%s target="_blank">Mollie Dashboard</a>.', 'events-manager'), $dashboard_url ), []);
			Options::checkbox( __('Display Payment Methods', 'events-manager'), 'em_mollie_show_methods', __('Display small images of the activated payment methods on your booking form?', 'events-manager') .'<br>'. sprintf( __( 'You can activate/deactivate each payment method individually in your <a href=%s target="_blank">Mollie Dashboard</a>.', 'events-manager'), $dashboard_url ) );
			Options::input( __('Free Booking Message', 'events-manager'), 'em_mollie_message_free', __('Shown when the total booking price = 0.00. Your customer will <u>not</u> be redirected to Mollie.', 'events-manager'), ['default' => __('Thank you for your booking.<br>You will receive a confirmation email soon.', 'events-manager')] );
			Options::input( __('Redirect Message', 'events-manager'), 'em_mollie_message_redirect', __('Shown when the booking is successfully created and the customer is redirected to Mollie.', 'events-manager'), ['default' => __('Redirecting to complete your online payment...', 'events-manager')] );
			Options::page_select( __('Return Page', 'events-manager'), 'em_mollie_return_page', __('Your customer will be redirected back to this page after the payment. Leave blank to use the Single Event Page.', 'events-manager'), __('None', 'events-manager') );
			Options::checkbox( __('Display Payment Status', 'events-manager'), 'em_mollie_show_status', __('Display the payment status on the Return Page?', 'events-manager') .'<br><code>'. __('The status of your payment is:', 'events-manager') .' [status]</code>' );
			Options::input( __('Payment Status Text', 'events-manager'), 'em_mollie_status_text', __('This will change the output of the setting above.', 'events-manager') .'<br>'. __('Default') .': <code>'. __('The status of your payment is', 'events-manager') .'</code>', ['default' => __('The status of your payment is', 'events-manager')] );
			Options::checkbox( __('Display Feedback Messages', 'events-manager'), 'em_mollie_show_feedback', sprintf( __('Display the booking feedback messages as set in your <a href=%s target="_blank">Events Manager Settings</a>?', 'events-manager'), admin_url('edit.php?post_type=event&page=events-manager-options#bookings') ) );
			Options::input( __('Payment Description', 'events-manager'), 'em_mollie_description', sprintf( esc_html__('%s tickets for %s', 'events-manager'), '#_BOOKINGSPACES', '#_EVENTNAME'), ['help' => sprintf( esc_html__('Shown in the payment description in your Mollie backend. All %s are allowed.', 'events-manager'), '<a href='. admin_url('edit.php?post_type=event&page=events-manager-help') .' target="_blank">' . __("Events Manager Placeholders", 'events-manager') . '</a>')] );
			Options::checkbox( __('Send email on failed / cancelled payment?', 'events-manager'), 'em_mollie_send_cancel_mail', __('By default Events Manager will send the Booking Cancelled Email if a payment had failed or is incomplete. This can lead to confusion if the user rebooks right after with a successful payment. This option lets you disable sending the automatic Booking Cancelled Email. (Setting this option to "no" will not affect the email if you change the booking status manually.)' ));
		echo '</table>';
	}


	/**
	 * Save or update the Gateway Settings Page options.
	 *
	 * @return boolean
	 */
	function update() {
		// Hook into function of Events Manager ->handles sanitation for all inputs.
		$gateway_options = [
			'em_'. $this->gateway . '_api_key',
			'em_'. $this->gateway . '_show_methods',
			'em_'. $this->gateway . '_message_free',
			'em_'. $this->gateway . '_message_redirect',
			'em_'. $this->gateway . '_return_page',
			'em_'. $this->gateway . '_show_status',
			'em_'. $this->gateway . '_status_text',
			'em_'. $this->gateway . '_show_feedback',
			'em_'. $this->gateway . '_description',
			'em_'. $this->gateway . '_send_cancel_mail'
		];
		foreach( $gateway_options as $option_wpkses ) add_filter('gateway_update_'.$option_wpkses,'wp_kses_post');
		return parent::update($gateway_options);
	}


	/**
	 * Load requirements and get API Key
	 *
	 * @return \Mollie\Api\MollieApiClient|boolean
	 */
	static function start_mollie() : \Mollie\Api\MollieApiClient|false {

		// Set the right API key => Live or Test Mode.
		$api_key = get_option('em_mollie_api_key');
		if( isset($api_key) && !empty($api_key) ) {
			$mollie = new \Mollie\Api\MollieApiClient();
			$mollie->setApiKey( $api_key );
			return $mollie;
		}
		return false;
	}

	/**
	 * Get localized time
	 *
	 * @param string $input
	 * @return string
	 */
	function get_localized_time( string $input ) : string {
		$UTC 	= new DateTimeZone("UTC");
		$newTZ 	= new DateTimeZone( get_option('timezone_string') );
		$date 	= new DateTime( date("Y-m-d H:i:s", strtotime($input)), $UTC );
		$date->setTimezone( $newTZ );
		$result = $date->format('Y-m-d H:i:s');
		return $result;
	}

	/**
	 * Translate Mollie status and methods
	 *
	 * @param string $string
	 * @return string
	 */
	public static function translate( string $string ) : string {

		$translate 	= array(
			// Status
			'open'			=> __('open', 'events-manager'),
			'pending' 		=> __('pending', 'events-manager'),
			'paid' 			=> __('paid', 'events-manager'),
			'canceled' 		=> __('canceled', 'events-manager'),
			'expired' 		=> __('expired', 'events-manager'),
			'failed' 		=> __('failed', 'events-manager'),
			'refunded' 		=> __('refunded', 'events-manager'),
			'chargeback' 	=> __('chargeback', 'events-manager'),

		);

		if( !array_key_exists($string, $translate) ) {
			
		}

		return $translate[$string];
	}

	public static function mollie_method( string $method ) : array {
		$names = [
			'applepay' 	    => __('Apple Pay', 'events-manager'),
			'bancontact' 	=> __('Bancontact', 'events-manager'),
			'creditcard' 	=> __('Credit Card', 'events-manager'),
			'directdebit' 	=> __('Direct Debit', 'events-manager'),
			'eps' 			=> __('EPS', 'events-manager'),
			'giftcard' 		=> __('Gift Card', 'events-manager'),
			'googlepay' 	=> __('Google Pay', 'events-manager'),
			'ideal' 		=> __('iDEAL', 'events-manager'),
			'klarna' 		=> __('Klarna', 'events-manager'),
			'paypal' 		=> __('PayPal', 'events-manager'),
			'postfinance' 	=> __('PostFinance', 'events-manager'),
			'sofort' 		=> __('SOFORT Banking', 'events-manager'),
			'swish' 		=> __('Swish', 'events-manager'),
			'twintr' 		=> __('Twint', 'events-manager'),
		];

		$descriptions = [
			'applepay'  	=> __('Pay with your Apple ID', 'events-manager'),
			'bancontact' 	=> __('Digital Payment Service', 'events-manager'),
			'creditcard' 	=> __('Mastercard, VISA, Amex', 'events-manager'),
			'directdebit' 	=> __('Vpay or Maestro', 'events-manager'),
			'eps' 			=> __('Austrian Payment Service', 'events-manager'),
			'giftcard' 		=> __('Gift Card', 'events-manager'),
			'googlepay' 	=> __('Pay with your Google Account', 'events-manager'),
			'ideal' 		=> __('iDEAL', 'events-manager'),
			'klarna' 		=> __('Klarna', 'events-manager'),
			'paypal' 		=> __('PayPal', 'events-manager'),
			'postfinance' 	=> __('PostFinance', 'events-manager'),
			'sofort' 		=> __('Transfer Money from Your Account within Seconds', 'events-manager'),
			'swish' 		=> __('Swish', 'events-manager'),
			'twintr' 		=> __('Swiss payment service', 'events-manager'),
		];

		return [
			'name' 		=> $names[$method],
			'description' => $descriptions[$method],
		];
		
	}

	/**
	 * Get Mollie payment status
	 *
	 * @param string $status
	 * @return string
	 */
	function mollie_status( string $status ) : string {
		return $this->translate($status);
	}


	/**
	 * Load Mollie methods from wp options or get them from Mollie API
	 *
	 * @return array
	 */
	static function get_methods() : array {
		if( !self::start_mollie() ) {
			return false;
		}

		$methods = get_option('mollie_activated_methods');
		if( !$methods ) {
			$methods	= array();
			$mollie 	= self::start_mollie();
			$all 		= $mollie->methods->allActive();
			foreach( $all as $method ) {

				$texts = self::mollie_method($method->id);
				$methods[$method->id] = [
					'name' => $texts['name'],
					'description' => $texts['description'],
					'image' => plugin_dir_url( __FILE__ ) . 'icons/' . $method->id . '.svg',
				];

			}
		}
		return $methods;
	}


	/**
	 * Clear option mollie_activated_methods and call get_methods
	 *
	 * @return array
	 */
	function refresh_methods() : array {
		delete_option('mollie_activated_methods');
		$methods = $this->get_methods();
		update_option('mollie_activated_methods', $methods);
		return $methods;
	}

	/**
	 * Register REST routes
	 */
	function register_rest_routes() {
		register_rest_route( 'em-mollie/v2', '/methods', array(
			'methods' 	=> 'GET',
			'callback' 	=> array($this, 'get_methods'),
		));

		register_rest_route( 'em-mollie/v2', '/refresh', array(
			'methods' 	=> 'GET',
			'callback' 	=> array($this, 'refresh_methods'),
		));
	}

} // End class.

EM_Gateways::register_gateway('mollie', 'EM_Gateway_Mollie');
