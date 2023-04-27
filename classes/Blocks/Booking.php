<?php

namespace Contexis\Events\Blocks;

use Contexis\Events\Assets;

class Booking extends Block
{

	public array $args;

	public $blockname = 'booking';

	public static function init()
	{
		$instance = new self;
		$instance->args = Assets::$args;
		add_action('init', [$instance, 'register_block']);
		add_action('wp_enqueue_scripts', [$instance, 'register_scripts']);
		//add_action( 'admin_enqueue_scripts', [$instance, 'admin_enqueue_scripts'] );
	}

	

	/**
	 * Register Scripts for frontend and add the event data  for booking
	 *
	 * @return void
	 */
	public function register_scripts($test)
	{
		$script_asset = require(\Events::DIR . "/includes/booking.asset.php");
		wp_enqueue_script(
			'booking',
			plugins_url('/../../includes/booking.js', __FILE__),
			$script_asset['dependencies'],
			$script_asset['version'],
			true
		);

		wp_set_script_translations('booking', 'events', \Events::DIR  . '/languages');
	}

	public function can_book($request)
	{
		if (WP_DEBUG) {
			return true;
		}
		return wp_verify_nonce($request->get_param('rest_nonce'), 'booking_rest');
	}

	/**
	 * Return the DOM Element that the booking app mounts on - or not
	 * This should become a REST Route!
	 *
	 * @param array $attribures
	 * @return string HTML
	 */
	public function render($attributes, $content, $full_data) : string
	{
		global $post;

		$event = \EM_Event::find($post->id, 'post_id');

		add_action('wp_enqueue_scripts', function () {
			wp_enqueue_script('booking');
		});

		if ($full_data->parsed_block['attrs']) $styles = get_block_wrapper_attributes() ?? '';
		if(!isset($styles)) $styles = '';
		$attributes['className'] = preg_match('/class="([^"]+)"/', $styles, $matches) ? $matches[1] : '';
		$attributes['style'] = preg_match('/style="([^"]+)"/', $styles, $matches) ? $matches[1] : '';

		if(!$event->can_book()) {
			return "";
		}

		$priceFormatter = new \Contexis\Events\Intl\Price(0);

		$data = [
			'attributes' => $attributes, 
			'_nonce' => wp_create_nonce('booking_add'),
			'rest_url' => get_rest_url(),
			'booking_url' => admin_url('admin-ajax.php'),
			'event' => \EM_Events::get_rest(['event' => $event->id])[0],
			'registration_fields' => \EM_Booking_Form::get_booking_form($event->post_id),
			'attendee_fields' => \EM_Attendees_Form::get_attendee_form($event->post_id),
			'available_tickets' => $event->get_tickets_rest(),
			'available_gateways' => \EM_Gateways::get_rest(),
			'l10n' => [
				"consent" => get_option("dbem_privacy_message"),
				"currency" => $priceFormatter->get_currency_code(),
				"locale" => str_replace('_', '-', get_locale()),
				"countries" => \Contexis\Events\Intl\Countries::get()
			]
		];

		$result = Assets::output_to_script_tag($data, 'booking_data');
		$result .= "<div id='booking_app'></div>";
		$result .= '<style>.booking-button { '.$attributes['style'] . '}</style>';

		return $result;
	}
}
