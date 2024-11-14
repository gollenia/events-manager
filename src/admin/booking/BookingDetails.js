import { __ } from '@wordpress/i18n';
import React from 'react';

const BookingDetails = ( { store } ) => {
	const [ state, dispatch ] = store;
	const data = state.data;

	const paymentClass = [ state.data.booking.status === 1 ? 'paid' : 'unpaid', 'payment-status' ].join( ' ' );

	return (
		<div className="booking-details">
			<div className="booking-info">
				<span>
					<b>{ __( 'Date', 'events-manager' ) }</b> { data.booking.date }
				</span>
				<span>
					<b>{ __( 'Booking ID', 'events-manager' ) } </b> { data.booking.id }
				</span>
			</div>
			<span className={ paymentClass }>{ data.booking.status_array[ data.booking.status ] }</span>
		</div>
	);
};

export default BookingDetails;
