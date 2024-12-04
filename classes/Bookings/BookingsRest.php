<?php

namespace Contexis\Events\Bookings;

use EM_Event;
use EM_Gateway;
use WP_REST_Response;
use WP_REST_Server;
use Contexis\Events\Tickets\Tickets;
use WP;
use WP_REST_Request;

class BookingsRest {

	private array $allowed_actions = ['approve', 'reject', 'unapprove', 'cancel', 'delete'];

	public static function init() {
		$instance = new self();
		add_action('rest_api_init', array($instance, 'register_rest_route') );
	}

	/**
	 * Register the REST API route with CRUD methods
	 *
	 * @return void
	 */
	public function register_rest_route() {
		register_rest_route( 'events/v2', '/booking(?:/(?P<id>\d+))?', [
			['methods' => WP_REST_Server::READABLE, 'callback' => [$this, 'read_booking'], 'permission_callback' => function ( \WP_REST_Request $request ) {
                return true;
            },],
			['methods' => WP_REST_Server::CREATABLE, 'callback' => [$this, 'create_booking'], 'permission_callback' => function ( \WP_REST_Request $request ) {
                return true;
            },],
			['methods' => WP_REST_Server::DELETABLE, 'callback' => [$this, 'delete_booking'], 'permission_callback' => function ( \WP_REST_Request $request ) {
                return true;
            },],
			['methods' => WP_REST_Server::EDITABLE, 'callback' => [$this, 'update_booking'], 'permission_callback' => function ( \WP_REST_Request $request ) {
                return true;
            }, 'login_user_id' => get_current_user_id()],
		], true);
	}

	public function create_booking(\WP_REST_Request $request) : WP_REST_Response 
	{
		$booking = \EM_Booking::find();
		$booking->get_request($request);

		if(!$booking->validate() || !$booking->save()) {
			return new WP_REST_Response(['errors' => $booking->errors], 400);
		}

		do_action('em_booking_add', $booking);

		$result = apply_filters('em_booking_response', [], $booking);

		return new WP_REST_Response(array_merge($result, [
			'errors' => $booking->errors,
			'booking_id' => $booking->booking_id ?? null,
			'message' => $booking->feedback_message
		]), 200);
	}

	public function update_booking(\WP_REST_Request $request) : WP_REST_Response 
	{

		$id = $request->get_param('id');

		if(!$id) {
			return new WP_REST_Response([
				'error' => __('No booking ID given', 'events')
			], 400);
		}

		$booking = new \EM_Booking($id);

		if(!$booking->can_manage('edit')) {
			$response = [
				'success' => false,
				'error' => __('You do not have permission to edit this booking', 'events')
			];
			return new WP_REST_Response($response, 403);
		}

		if(isset($request['action'])) {
			[$code, $result] = $this->set_action($request['action'], $booking);
			return new WP_REST_Response($result, $code);
		}

		$booking->get_request($request);
		//wp_verify_nonce( $request['_nonce'], 'events' );
		if(!$booking->validate()) {
			return new WP_REST_Response([
				'success' => false,
				'errors' => $booking->errors,
				'booking_meta' => $booking->booking_meta,
				'request' => $request
			], 400);
		}

		$success = $booking->save();
		return new WP_REST_Response(['success' => $success], $success ? 200 : 400);
	}

	private function can_get_bookings() : bool
	{
		if(!is_user_logged_in()) return false;
		return current_user_can('manage_others_bookings');
	}

	private function set_action(string $action, \EM_Booking $booking) : array
	{
		
		if(!in_array($action, $this->allowed_actions)) {
			return [400, ['error' => __('Invalid action', 'events')]];
		}

		$result = $booking->$action();

		if($booking->errors && count($booking->errors) > 0) {
			return [400, ['error' => join(', ', $booking->errors)]];	
		}
		
		return [200, ['success' => $result, 'status' => $booking->booking_status, 'status_text' => $booking->get_status()]];
	}

	public function read_booking(\WP_REST_Request $request) : \WP_REST_Response 
	{
		$booking = $request->has_param('id') ? \EM_Booking::find($request->get_param('id')) : false;
		$event_id = $booking ? $booking->event_id : intval($request->get_param('event_id'));
		
		$event = \EM_Event::find($event_id, $booking ? 'event_id' : 'post_id');
		if(!$event || ($request->get_param('id') && !$booking)) {
			return new WP_REST_Response(['error' => __('Booking not found', 'events')], 404);
		}

		$coupons = $booking ? \EM_Coupons::get_options($event) : null;
		$registration = $booking ? (key_exists('booking', $booking->booking_meta) ? array_merge($booking->booking_meta['registration'], $booking->booking_meta['booking']) : $booking->booking_meta['registration']) : [];
		
		$priceFormatter = new \Contexis\Events\Intl\Price(0);

		$data = [
			'rest_url' => get_rest_url(),
			'event' => \EM_Events::get_rest(['event' => $event->event_id])[0],
			'registration_fields' => \EM_Booking_Form::get_booking_form($event->post_id),
		    'attendee_fields' => \EM_Attendees_Form::get_attendee_form($event->post_id),
			'available_tickets' => $event->get_tickets_rest(),
			'available_gateways' => \EM_Gateways::get_rest(),
			'allow_donation' => $event->event_rsvp_donation,
			'l10n' => [
				"consent" => get_option("dbem_privacy_message"),
				"donation" => get_option("dbem_donation_message"),
				"currency" => $priceFormatter->get_currency_code(),
				"locale" => str_replace('_', '-', get_locale()),
			],
			'available_coupons' => $coupons,
			'available_spaces' => $event->get_bookings()->get_available_spaces(),
			'registration' => $registration,
			'attendees' => $booking ? $booking->get_attendees() : [],
			'booking' => $booking ? [
				'date' => $booking->get_booking_date(),
				'id' => $booking->booking_id,
				'status' => $booking->status,
				'status_array' => $booking->status_array,
				'price' => $booking->get_price(),
				'donation' => $booking->booking_donation,
				'paid' => $booking->get_price_summary_array(),
				'gateway' => $booking->booking_meta['gateway'],
				'coupon' => isset($booking->booking_meta['coupon']) ? $booking->booking_meta['coupon'] : null,
				'note' => isset($booking->booking_meta['note']) ? $booking->booking_meta['note'] : null,
			] : null
		];

		return new WP_REST_Response($data, 200);
	}

	public function delete_booking(\WP_REST_Request $request) : \WP_REST_Response 
	{
		$id = $request->get_param('id');
		$booking = new \EM_Booking($id);
		$success = $booking->delete();

		return new WP_REST_Response(['success' => $success, 'errors' => $booking->errors], $success ? 200 : 400);

	}
}

BookingsRest::init();