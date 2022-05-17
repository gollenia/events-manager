<?php

use SepaQr\Data;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelMedium;
use Endroid\QrCode\Color\Color;
use chillerlan\QRCode\{QRCode, QROptions};
use chillerlan\QRCode\Data\QRMatrix;
use chillerlan\QRCode\Common\EccLevel;

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
		$logo_id = get_option("em_offline_beneficiary", true);
		$logo = wp_get_attachment_image( $logo_id );
		$event = em_get_event($booking->event_id);
		$paymentData = Data::create()
			->setName(get_option("em_offline_beneficiary", true))
			->setIban(get_option("em_offline_iban", true))
			->setRemittanceText($_REQUEST['booking_id'] . "-" . $event->post_name . "-" . $booking->booking_meta['registration']['user_name'])
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
		$result = $this->get_payment_data();
		header('Content-Type: application/json');
		echo json_encode($result);
		wp_die();
	}

	function get_payment_data() {
		if(empty($_REQUEST['booking_id'])) return [
			'success' => false,
			'error' => "No booking id given"
		];
		
		if(!get_option("em_offline_iban", true)) return [
				'success' => false,
				'error' => "No IBAN given"
		];
		

		$booking = EM_Booking::find(absint($_REQUEST['booking_id']));
		$event = em_get_event($booking->event_id);

		return [
			"success" => true,
			"error" => "",
			"purpose" => $_REQUEST['booking_id'] . "-" . $event->post_name . "-" . $booking->booking_meta['registration']['user_name'],
			"iban" => get_option("em_offline_iban", true),
			"beneficiary" => get_option("em_offline_beneficiary", true),
			"bic" => get_option("em_offline_bic", true),
			"bank" => get_option("em_offline_bank", true),
			"amount" => $booking->booking_price
		];
	}


}

EM_QR_Code_Generator::init();