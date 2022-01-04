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
	 * @return void
	 */
    public function generate_qr_code() {
		if(empty($_REQUEST['booking_id'])) return;
		$booking = em_get_booking($_REQUEST['booking_id']);
		$logo_id = get_option("em_offline_beneficiary", true);
		$logo = wp_get_attachment_image( $logo_id );
		$event = em_get_event($booking->event_id);
		$paymentData = Data::create()
			->setName(get_option("em_offline_beneficiary", true))
			->setIban(get_option("em_offline_iban", true))
			->setPurpose($_REQUEST['booking_id'] . "/" . $event->post_title . "/" . $booking->booking_meta['registration']['user_email'])
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

	public function get_payment_info() {
		if(empty($_REQUEST['booking_id'])) return;
		$booking = em_get_booking($_REQUEST['booking_id']);
		$event = em_get_event($booking->event_id);

		$result = [
			"purpose" => $_REQUEST['booking_id'] . "/" . $event->post_title . "/" . $booking->booking_meta['registration']['user_email'],
			"iban" => get_option("em_offline_iban", true),
			"beneficiary" => get_option("em_offline_beneficiary", true),
			"bic" => get_option("em_offline_bic", true),
			"bank" => get_option("em_offline_bank", true),
		];

		header('Content-Type: application/json');
		echo json_encode($result);
		wp_die();
	}


}

EM_QR_Code_Generator::init();