const initializer = ( initialState ) => initialState;

const createTicket = ( availableFields, availableTickets ) => {
	const fields = {};
	for ( let field of availableFields ) {
		fields[ field.fieldid ] = field.defaultValue || '';
		if ( field.type === 'select' || field.type === 'radio' ) {
			fields[ field.fieldid ] = field.options[ 0 ];
		}
	}
	return {
		ticket_id: parseInt( Object.keys( availableTickets )[ 0 ] ),
		fields,
	};
};

const reducer = ( state = {}, action ) => {
	const { type, payload } = action;
	const { data } = state;

	const getCouponData = ( couponId ) => {
		const coupon = state.data.available_coupons.find( ( coupon ) => coupon.value === couponId );

		return {
			discount: coupon ? coupon.discount : 0,
			discountType: coupon ? coupon.type : '%',
		};
	};

	switch ( type ) {
		case 'SET_DATA':
			state.data = payload;
			state.coupon = getCouponData( state.data.booking.coupon );
			return { ...state };

		case 'SET_STATE':
			state.state = payload;
			return { ...state };

		case 'ADD_TICKET':
			const ticket = createTicket( data.attendee_fields, data.available_tickets );
			state.data.attendees.push( ticket );
			state.currentTicket = state.data.attendees.length - 1;
			state.sendState = 'unsaved';
			return { ...state };

		case 'SET_FIELD':
			console.log( 'payload', payload );
			if ( payload.form === 'ticket' ) {
				console.log( 'payload', payload );
				state.data.attendees[ payload.index ].fields[ payload.field ] = payload.value;
			}
			if ( payload.form === 'registration' ) {
				state.data.registration[ payload.field ] = payload.value;
			}
			if ( payload.form === 'donation' ) {
				state.data.booking.donation = payload.value;
			}
			state.sendState = 'unsaved';
			return { ...state };

		case 'REMOVE_TICKET':
			const index =
				payload.index !== undefined
					? payload.index
					: state.request.tickets.findIndex( ( ticket ) => ticket.ticket_id === payload.ticket_id );
			state.data.attendees.splice( index, 1 );
			state.sendState = 'unsaved';
			return { ...state };

		case 'SET_CURRENT_TICKET':
			state.currentTicket = payload;
			return { ...state };

		case 'SET_TICKET':
			console.log( 'payload', payload );
			state.data.attendees[ payload.index ] = payload.ticket;
			state.sendState = 'unsaved';
			return { ...state };

		case 'SET_COUPON':
			state.data.booking.coupon = payload;
			state.coupon = getCouponData( payload );
			state.sendState = 'unsaved';
			return { ...state };

		case 'SET_GATEWAY':
			state.data.booking.gateway = payload;
			state.sendState = 'unsaved';
			return { ...state };

		case 'SET_PRICE':
			state.fullPrice = payload;
			return { ...state };

		case 'SET_SEND_STATE':
			state.sendState = payload;
			return { ...state };

		case 'SET_NOTE':
			state.data.booking.note = payload;
			return { ...state };

		case 'RESET':
			return initializer();

		default:
	}

	return { ...state };
};
export default reducer;
