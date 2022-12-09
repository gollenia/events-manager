<?php

use SepaQr\Data;

use chillerlan\QRCode\{QRCode, QROptions};
use chillerlan\QRCode\Data\QRMatrix;


/**
 * Register admin api that creates a banking qr code
 * added by Thomas Gollenia
 * TODO: Rewrite this in JavaScript to reduce API calls and stupid PHP, Composer and stuff
 * 
 */
class EM_QR_Code_Generator {
    
    public static function init() {
        
        $instance = new self;
		add_action('wp_ajax_nopriv_em_qr_code',[$instance, 'generate_qr_code']);
		add_action('wp_ajax_nopriv_em_payment_info',[$instance, 'get_payment_info']);
        
    }

	/**
	 * return an image containing the qr code
	 * 
	 * @todo change purpose
	 *
	 * @return void
	 */
    public function generate_qr_code() {
		if(empty($_REQUEST['booking_id'])) return;
		$booking = EM_Booking::find(absint($_REQUEST['booking_id']));
		
		$event = EM_Event::find($booking->event_id); 
		$paymentData = Data::create()
			->setName(get_option("em_offline_beneficiary", true))
			->setIban(get_option("em_offline_iban", true))
			->setRemittanceText($_REQUEST['booking_id'] . "-" . $event->post_name . "-" . $booking->booking_meta['registration']['last_name'])
			->setAmount($booking->booking_price);
		$qrOptions = new QROptions([
			'version' => 7,
			'eccLevel' => QRCode::ECC_M, // required by EPC standard
			'imageBase64' => false,
			'addQuietzone'           => true,
			'imageTransparent'       => false,
			'keepAsSquare' => [QRMatrix::M_FINDER|QRMatrix::M_DARKMODULE, QRMatrix::M_LOGO, QRMatrix::M_FINDER_DOT, QRMatrix::M_ALIGNMENT|QRMatrix::M_DARKMODULE],
			'drawCircularModules' => true,
			'circleRadius' => 0.4,
			'outputType' => QRCode::OUTPUT_MARKUP_SVG,
			'svgConnectPaths' => true
		]);
		$result = new QRCode($qrOptions);
		header('Content-Type: image/svg+xml');
		echo $result->render($paymentData);
		wp_die();
    }

	/**
	 * Return an array with payment information
	 * 
	 * @todo: change purpose
	 *
	 * @return array
	 */
	public function get_payment_info() {
		
		if(empty($_REQUEST['booking_id'])) return [
			'success' => false,
			'error' => "No booking id given"
		];

		$result = $this->get_payment_data($_REQUEST['booking_id']);
		header('Content-Type: application/json');
		echo json_encode($result);
		wp_die();
	}

	function get_payment_data($booking_id) {
		
		if(!get_option("em_offline_iban", true)) return [
				'success' => false,
				'error' => "No IBAN available. Please add an IBAN in the offline payment gateway"
		];
		

		$booking = EM_Booking::find(absint($booking_id));
		$event = EM_Event::find($booking->event_id);

		return [
			"success" => true,
			"error" => "",
			"purpose" => $_REQUEST['booking_id'] . "-" . $event->post_name . "-" . $booking->booking_meta['registration']['last_name'],
			"iban" => get_option("em_offline_iban", true),
			"beneficiary" => get_option("em_offline_beneficiary", true),
			"bic" => get_option("em_offline_bic", true),
			"bank" => get_option("em_offline_bank", true),
			"amount" => $booking->booking_price
		];
	}


}

EM_QR_Code_Generator::init();