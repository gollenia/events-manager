<?php

use SepaQr\Data;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelMedium;
use Endroid\QrCode\Color\Color;

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

    public function generate_qr_code($request) {
		if(empty($_REQUEST['booking_id'])) return;
		$booking = em_get_booking($_REQUEST['booking_id']);
		 
		$event = em_get_event($booking->event_id);
		$paymentData = Data::create()
			->setName(get_option("em_offline_beneficiary", true))
			->setIban(get_option("em_offline_iban", true))
			->setPurpose($_REQUEST['booking_id'] . "/" . $event->post_title . "/" . $booking->booking_meta['registration']['user_email'])
			->setAmount($booking->booking_price);
		$result = Builder::create()
			->data($paymentData)
			->errorCorrectionLevel(new ErrorCorrectionLevelMedium()) // required by EPC standard
			->foregroundColor(new Color(255, 255, 255))
			->backgroundColor(new Color(0, 0, 0, 127))
			->build();
		header('Content-Type: '.$result->getMimeType());
		echo $result->getString();
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