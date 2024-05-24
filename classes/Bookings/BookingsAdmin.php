<?php

namespace Contexis\Events\Bookings;


class Admin {

	public static function init() {
		$instance = new self();
		add_action('rest_api_init', array($instance, 'register_rest_route') );
	}

	public function register_rest_route() {
		register_rest_route( 'bookings/v2', '/bookings', array(
			'methods' => 'GET',
			'callback' => array($this, 'get_bookings'),
			'permission_callback' => array($this, 'can_get_bookings'),
		));

		register_rest_route( 'bookings/v2', '/booking/(?P<id>\d+)', array(
			'methods' => 'GET',
			'callback' => array($this, 'get_booking'),
			'permission_callback' => array($this, 'can_get_bookings'),
		));
	}

	public function get_booking($request) {
		$id = $request['id'];
		$booking = \EM_Bookings::get(array(
			'booking_id' => $id,
			'limit' => 1,
		));
		return $booking;
	}

	public function can_get_bookings() {
		if(!is_user_logged_in()) return false;
		return current_user_can('manage_others_bookings');
	}

	public function get_bookings() {
		$bookings = \EM_Bookings::get(array(
			'orderby' => 'booking_id',
			'order' => 'DESC',
			'limit' => 1000,
			'owner' => get_current_user_id(),
			'count' => true,
		));
		return $bookings;
	}
}