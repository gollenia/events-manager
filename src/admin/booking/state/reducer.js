const initializer = ( initialState ) => initialState;

const createTicket = ( availableFields, availableTickets ) => {
	const fields = {};
	for ( let field of availableFields ) {
		fields[ field.fieldid ] = '';
	}
	return {
		ticket: parseInt( Object.keys( availableTickets )[ 0 ] ),
		fields,
	};
};

const reducer = ( state = {}, action ) => {
	const { type, payload } = action;
	const { data } = state;
	switch ( type ) {
		case 'SET_DATA':
			state.data = payload;
			return { ...state };

		case 'SET_STATE':
			state.state = payload;
			return { ...state };

		case 'ADD_TICKET':
			const ticket = createTicket( data.attendeeFields, data.availableTickets );
			state.data.attendees.push( ticket );
			state.modal.ticket = state.data.attendees.length;
			return { ...state };

		case 'SET_FIELD':
			if ( payload.form === 'ticket' ) {
				state.data.attendees[ payload.index ].fields[ payload.field ] = payload.value;
			}
			if ( payload.form === 'registration' ) {
				state.data.registration[ payload.field ] = payload.value;
			}
			state.wizzard.checkValidity = true;
			return { ...state };

		case 'REMOVE_TICKET':
			const index =
				payload.index !== undefined
					? payload.index
					: state.request.tickets.findIndex( ( ticket ) => ticket.id === payload.id );
			state.data.attendees.splice( index, 1 );
			return { ...state };

		case 'SET_MODAL':
			state.modal.ticket = payload;
			return { ...state };

		case 'SET_COUPON':
			state.request.coupon = payload;
			return { ...state };

		case 'SET_COUPON_LOADING':
			state.modal.couponButton = payload;
			return { ...state };

		case 'COUPON_RESPONSE':
			state.response.coupon = payload;
			return { ...state };

		case 'BOOKING_RESPONSE':
			state.response.booking = payload;
			return { ...state };

		case 'SET_GATEWAY':
			state.request.gateway = payload;
			state.wizzard.checkValidity = true;
			return { ...state };

		case 'VALIDITY':
			for ( let key in payload ) {
				state.wizzard.steps[ key ].valid = payload[ key ];
			}
			state.wizzard.checkValidity = false;
			return { ...state };

		case 'RESET':
			return initializer();

		default:
	}

	return { ...state };
};
export default reducer;
