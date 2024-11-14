import apiFetch from '@wordpress/api-fetch';

const saveBooking = ( bookingId, state, dispatch ) => {
	const data = state.data;

	let fetchRequest = {
		registration: data.registration,
		booking_id: bookingId,
		gateway: data.booking.gateway,
		event_id: data.event.event_id,
		note: data.booking.note,
		attendees: {},
		coupon: data.booking.coupon,
	};

	console.log( window.EM );

	for ( const id of Object.keys( data.available_tickets ) ) {
		fetchRequest[ 'attendees' ][ id ] = [];
	}

	Object.values( data.attendees ).map( ( ticket ) => {
		fetchRequest.attendees[ ticket.ticket_id ].push( ticket.fields );
	} );

	dispatch( { type: 'SET_SEND_STATE', payload: 'saving' } );
	console.log( 'fetchRequest', fetchRequest );
	apiFetch( {
		path: `/events/v2/booking/${ bookingId }`,
		method: 'PUT',
		data: fetchRequest,
	} )
		.then( ( apiResponse ) => {
			console.log( apiResponse );
			dispatch( { type: 'SET_SEND_STATE', payload: 'saved' } );
		} )
		.catch( ( error ) => {
			console.log( error );
			dispatch( { type: 'SET_SEND_STATE', payload: 'failed' } );
		} );
};

export default saveBooking;
