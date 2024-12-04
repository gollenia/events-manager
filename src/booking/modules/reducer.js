import { __ } from '@wordpress/i18n';

const initializer = ( initialState ) => initialState;

const reducer = ( state = {}, action ) => {
	const { type, payload } = action;
	const { data } = state;
	switch ( type ) {
		case 'SET_DATA':
			state.data = payload;
			state.wizard.step = payload?.attendee_fields?.length === 0 ? 1 : 0;
			return { ...state };

		case 'SET_WIZARD':
			state.wizard.step = payload;
			state.wizard.checkValidity = true;
			return { ...state };

		case 'INCREMENT_WIZARD':
			state.wizard.step = state.wizard.step + ( payload ? payload : 1 );
			state.wizard.checkValidity = true;
			return { ...state };

		case 'DECREMENT_WIZARD':
			state.wizard.step = state.wizard.step - ( payload ? payload : 1 );
			state.wizard.checkValidity = true;
			return { ...state };

		case 'SET_MODAL':
			state.modal.visible = payload;
			state.modal.title = payload
				? `${ __( 'Registration', 'events' ) } ${ data.event?.title }`
				: state.originalDocumentTitle;
			return { ...state };

		case 'SET_INIT_STATE':
			state.modal.initState = payload;
			return { ...state };

		case 'ADD_TICKET':
			state.request.tickets.push( JSON.parse( JSON.stringify( state.data.available_tickets[ payload ] ) ) );
			state.wizard.checkValidity = true;
			return { ...state };

		case 'SET_FIELD':
			if ( payload.form === 'ticket' ) {
				state.request.tickets[ payload.index ].fields[ payload.field ] = payload.value;
			}
			if ( payload.form === 'registration' ) {
				state.request.registration[ payload.field ] = payload.value;
			}
			if ( payload.form === 'donation' ) {
				state.request.donation = payload.value;
			}
			state.wizard.checkValidity = true;
			return { ...state };

		case 'REMOVE_TICKET':
			const index =
				payload.index !== undefined
					? payload.index
					: state.request.tickets.findIndex( ( ticket ) => ticket.id === payload.id );
			state.request.tickets.splice( index, 1 );
			state.wizard.checkValidity = true;
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
			console.log( 'BOOKING_RESPONSE', payload );
			state.response.booking = payload.response;
			state.modal.orderState = payload.state;
			state.wizard.step = state.wizard.step + 1;
			return { ...state };

		case 'SET_GATEWAY':
			state.request.gateway = payload;
			state.wizard.checkValidity = true;
			return { ...state };

		case 'VALIDITY':
			for ( let key in payload ) {
				state.wizard.steps[ key ].valid = payload[ key ];
			}
			state.wizard.checkValidity = false;
			return { ...state };

		case 'RESET':
			return initializer();

		default:
	}

	return { ...state };
};
export default reducer;
