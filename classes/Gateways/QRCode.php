<?php

namespace Contexis\Events;

use \SepaQr\Data;

use \chillerlan\QRCode\{QRCode, QROptions};
use \chillerlan\QRCode\Data\QRMatrix;
use \chillerlan\QRCode\Output\QROutputInterface;
use \chillerlan\QRCode\Common\EccLevel;
use \chillerlan\QRCode\Output\QRMarkupSVG;
use \chillerlan\QRCode\Output\QRGdImagePNG;



/**
 * Register admin api that creates a banking qr code
 * added by Thomas Gollenia
 * TODO: Rewrite this in JavaScript to reduce API calls and stupid PHP, Composer and stuff
 * 
 */
class EM_QRCode {
    
    public static function init() {
        
        $instance = new self;
		add_action('wp_ajax_nopriv_em_qr_code',[$instance, 'generate_qr_code']);
		add_action('wp_ajax_em_qr_code',[$instance, 'generate_qr_code']);
        
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

		$format = $_REQUEST['format'] ?? 'svg';
		if($format != 'png' && $format != 'svg') $format = 'svg';
		
		$booking = \EM_Booking::find(absint($_REQUEST['booking_id']));
		$event = \EM_Event::find($booking->event_id); 

		$data = Data::create()
			->setName(get_option("em_offline_beneficiary", true))
			->setIban(get_option("em_offline_iban", true))
			->setRemittanceText($_REQUEST['booking_id'] . "-" . $event->post_name . "-" . $booking->booking_meta['registration']['last_name'])
			->setAmount($booking->get_price());
		$options = new QROptions();

		$options->version = 5;
		$options->outputInterface = QRGdImagePNG::class;
		$options->eccLevel = EccLevel::L;
		$options->imageBase64 = false;
		$options->addQuietzone = true;
		$options->scale               = 20;
		$options->imageTransparent = true;
		$options->keepAsSquare = [
			QRMatrix::M_FINDER|QRMatrix::M_DARKMODULE, 
			QRMatrix::M_LOGO, 
			QRMatrix::M_FINDER_DOT, 
			QRMatrix::M_ALIGNMENT|QRMatrix::M_DARKMODULE
		];
		$options->drawCircularModules = true;
		$options->circleRadius = 0.4;
		$options->svgConnectPaths = true;

		$qrcode = new QRCode($options);
		$image = $qrcode->render($data);
		header($format == 'png' ? 'Content-Type: image/png' : 'Content-Type: image/svg+xml');
		echo($image);
		
		wp_die();
    }
}

EM_QRCode::init();