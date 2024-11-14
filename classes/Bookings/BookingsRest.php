<?php

namespace Contexis\Events\Bookings;

use EM_Event;
use EM_Gateway;
use WP_REST_Response;
use WP_REST_Server;
use Contexis\Events\Tickets\Tickets;

class BookingsRest {

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

		register_rest_route( 'events/v2', '/tickets', [
			['methods' => WP_REST_Server::READABLE, 'callback' => [$this, 'get_tickets'], 'permission_callback' => function ( \WP_REST_Request $request ) {
				return true;
			},],
		], true);
	}

	/**
	 * Create a new booking
	 *
	 * @param \WP_REST_Request $request
	 * @return void
	 */
	public function create_booking($request) {
		$booking = \EM_Booking::find();
		$booking->get_request($request);

		$result = [
			'success' => false
		];

		if(!$booking->validate()) {
			http_response_code(400);
			$result['validation'] = "bibbl";
			$result['errors'] = $booking->errors;
			return $result;
		}

		$success = $booking->save();

		if(!$success) {
			http_response_code(400);
			return $result;
		}
		http_response_code($success = 200);
		do_action('em_booking_add', $booking);
		$result['errors'] = $booking->errors;
		$result['response_code'] = $success;
		$result['booking_id'] = $success ? $booking->booking_id : null;
		$result['message'] = $booking->feedback_message;

		return apply_filters('em_booking_response', $result, $booking);
		
	}

	/**
	 * Update an existing booking
	 *
	 * @param \WP_REST_Request $request
	 * @return void
	 */
	public function update_booking(\WP_REST_Request $request) {
		$id = $request->get_param('id');
		$booking = new \EM_Booking($id);

		if(!$booking->can_manage('edit')) {
			http_response_code(403);
			return [
				'success' => false,
				'error' => 'You do not have permission to edit this booking'
			];
		}

		
		$booking->get_request($request);
		//wp_verify_nonce( $request['_nonce'], 'events-manager' );
		if(!$booking->validate()) {
			http_response_code(400);
			return [
				'success' => false,
				'errors' => $booking->errors,
				'booking_meta' => $booking->booking_meta,
				'request' => $request
			];
		}
		$success = $booking->save();
		return new WP_REST_Response($success);
	}

	public function can_get_bookings() {
		if(!is_user_logged_in()) return false;
		return current_user_can('manage_others_bookings');
	}

	/**
	 * Get data of an existing booking by its ID
	 * used by src/admin/booking/index.js
	 *
	 * @param \WP_REST_Request $request
	 * @return void
	 */
	public function read_booking($request) {

		$booking = $request->has_param('id') ? \EM_Booking::find($request->get_param('id')) : false;
		$event_id = $booking ? $booking->event_id : intval($request->get_param('event_id'));
		
		$event = \EM_Event::find($event_id, $booking ? 'event_id' : 'post_id');
		if(!$event || ($request->get_param('id') && !$booking)) {
			http_response_code(404);
			return;
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
			'registration' => $registration,
			'attendees' => $booking ? $booking->get_attendees() : [],
			'booking' => $booking ? [
				'date' => $booking->get_booking_date(),
				'id' => $booking->booking_id,
				'status' => $booking->status,
				'status_array' => $booking->status_array,
				'price' => $booking->get_price(),
				'paid' => $booking->get_price_summary_array(),
				'gateway' => $booking->booking_meta['gateway'],
				'coupon' => $booking->booking_meta['coupon'],
				'note' => $booking->booking_meta['note'],
			] : null
		];

		http_response_code(200);

		return $data;
	}

	/**
	 * Delete a booking by its ID
	 *
	 * @param \WP_REST_Request $request
	 * @return void
	 */
	public function delete_booking($request) {
		$id = $request->get_param('id');
		$booking = new \EM_Booking($id);
		$success = $booking->delete();
		http_response_code($success ? 200 : 400);
		return [
			'success' => $success
		];
	}

	function register_rest_routes() {
		register_rest_route('events/v2', '/tickets/(?P<event_id>\d+)', [
			[
				'methods' => WP_REST_Server::READABLE,
				'callback' => [$this, 'get_tickets_rest'],
				'permission_callback' => [$this, 'get_tickets_permissions_check'],
			],
			[
				'methods' => WP_REST_Server::CREATABLE,
				'callback' => [$this, 'create_ticket_rest'],
				'permission_callback' => [$this, 'create_ticket_permissions_check'],
			],
			'schema' => [$this, 'get_public_item_schema'],
		]);
	}

	function get_tickets_permissions_check($request) {
		return current_user_can('manage_options');
	}

	function get_tickets($request) {
		
		$event = \EM_Event::find($request->get_param('post_id'), 'post_id');
		
		$ticket_data = new Tickets($event);
		
		$tickets = [];

		foreach( $ticket_data->tickets as $ticket ) {
			$tickets[] = $ticket->get_rest_data();
		}
		return $tickets;
	}
}

BookingsRest::init();