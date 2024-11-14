<?php

namespace Contexis\Events\Tickets;

use EM_Event;
use EM_Gateway;
use WP_REST_Response;
use WP_REST_Server;

class TicketsController {

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
		register_rest_route( 'events/v2', '/ticket(?:/(?P<id>\d+))?', [
			['methods' => WP_REST_Server::READABLE, 'callback' => [$this, 'read_ticket'], 'permission_callback' => function ( \WP_REST_Request $request ) {
                return true;
            },],
			['methods' => WP_REST_Server::CREATABLE, 'callback' => [$this, 'create_ticket'], 'permission_callback' => function ( \WP_REST_Request $request ) {
                return true;
            },],
			['methods' => WP_REST_Server::DELETABLE, 'callback' => [$this, 'delete_ticket'], 'permission_callback' => function ( \WP_REST_Request $request ) {
                return true;
            },],
			['methods' => WP_REST_Server::EDITABLE, 'callback' => [$this, 'update_ticket'], 'permission_callback' => function ( \WP_REST_Request $request ) {
                return true;
            }, 'login_user_id' => get_current_user_id()],
		], true);

		register_rest_route( 'events/v2', '/tickets', [
			['methods' => WP_REST_Server::READABLE, 'callback' => [$this, 'read_tickets'], 'permission_callback' => function ( \WP_REST_Request $request ) {
				return true;
			},],
		], true);
	}

	/**
	 * Create a new ticket
	 *
	 * @param \WP_REST_Request $request
	 * @return void
	 */
	public function create_ticket($request) {
		$ticket = new Ticket();


		$event = \EM_Event::find($request->get_param('post_id'), 'post_id');

		$ticket->event_id = $event->event_id;
		$ticket->ticket_name = $request->get_param('ticket_name');
		$ticket->ticket_description = $request->get_param('ticket_description');
		$ticket->ticket_min = $request->get_param('ticket_min');
		$ticket->ticket_max = $request->get_param('ticket_max');
		$ticket->ticket_price = $request->get_param('ticket_price');
		$ticket->ticket_spaces = $request->get_param('ticket_spaces');
		$ticket->ticket_required = $request->get_param('ticket_required');
		$ticket->ticket_order = $request->get_param('ticket_order');
		
		$ticket->ticket_meta = [
			'primary' => 0,
		];
		//$ticket->compat_keys();
		$result = $ticket->save();
		if($result) {
			http_response_code(201);
		} else {
			http_response_code(400);
		}


		return apply_filters('em_tickets_response', ['ticket' => $ticket, 'request' => $request]);
		
	}

	/**
	 * Update an existing booking
	 *
	 * @param \WP_REST_Request $request
	 * @return void
	 */
	public function update_ticket(\WP_REST_Request $request) {
		$id = $request->get_param('ticket_id');
		$ticket = new \Contexis\Events\Tickets\Ticket($id);
		$data = $request->get_params();
		
		foreach($data as $key => $value) {
			$ticket->$key = $value;
		}

		$success = $ticket->save();
		return new WP_REST_Response($ticket->errors, $ticket->errors ? 400 : 200);
	}

	public function can_get_bookings() {
		if(!is_user_logged_in()) return false;
		return current_user_can('manage_others_bookings');
	}

	
	/**
	 * Delete a booking by its ID
	 *
	 * @param \WP_REST_Request $request
	 * @return void
	 */
	public function delete_ticket($request) {
		$id = $request->get_param('id');
		$ticket = new Ticket($id);
		$ticket->delete();
		return new WP_REST_Response(true);
	}

	function get_tickets_permissions_check($request) {
		return true;
	}

	function read_tickets($request) {
		
		$event = \EM_Event::find($request->get_param('post_id'), 'post_id');
		
		$ticket_data = new \Contexis\Events\Tickets\Tickets($event);
		
		
		$tickets = [];

		foreach( $ticket_data->tickets as $ticket ) {
			$tickets[] = $ticket->get_rest_data();
		}
		return $tickets;
	}
}

TicketsController::init();