import { STATES } from './constants';

const sendOrder = ( state, dispatch ) => {
	const controller = new AbortController();
	const signal = controller.signal;

	const { request, data, response, modal } = state;
	dispatch( { type: 'SET_ORDER_STATE', payload: STATES.LOADING } );

	setTimeout( () => {
		if ( modal.orderState == STATES.IDLE ) return;
		dispatch( { type: 'SET_ORDER_STATE', payload: STATES.DELAY } );
	}, 3000 );

	setTimeout( () => {
		if ( modal.orderState == STATES.IDLE ) return;
		dispatch( { type: 'SET_ORDER_STATE', payload: STATES.HUGE_DELAY } );
	}, 7000 );

	setTimeout( () => {
		if ( modal.orderState == STATES.IDLE ) return;
		dispatch( { type: 'SET_ORDER_STATE', payload: STATES.ERROR } );
		controller.abort();
	}, 10000 );

	let fetchRequest = {
		...request,
		_wpnonce: data._nonce,
		event_id: data.event.event_id,
		attendees: {},
	};

	for ( const id of Object.keys( data.available_tickets ) ) {
		fetchRequest[ 'attendees' ][ id ] = [];
	}

	request.tickets.map( ( ticket ) => {
		fetchRequest.attendees[ ticket.id ].push( ticket.fields );
	} );

	fetch( `/wp-json/events/v2/booking/${ data.event.event_id }`, {
		method: 'POST',
		body: JSON.stringify( fetchRequest ),
		headers: new Headers( {
			'Content-Type': 'application/json;charset=UTF-8',
		} ),
		beforeSend: function ( xhr ) {
			xhr.setRequestHeader( 'X-WP-Nonce', data._nonce );
		},
	} )
		.then( ( resp ) => resp.json() )
		.then( ( response ) => {
			console.log( 'response', response );
			dispatch( { type: 'BOOKING_RESPONSE', payload: { state: STATES.SUCCESS, response } } );
			if ( response.gateway.url ) {
				window.location.replace( response.gateway.url );
			}
		} )
		.catch( ( error ) => {
			dispatch( {
				type: 'BOOKING_RESPONSE',
				payload: { state: STATES.ERROR, response: { result: false, message: error } },
			} );
		} );
};

export default sendOrder;
