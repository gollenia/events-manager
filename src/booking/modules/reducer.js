import { __ } from '@wordpress/i18n';

const initializer = ( initialState ) => initialState;

const reducer = ( state = {}, action ) => {
	const { type, payload } = action;
	const { data } = state;
	switch ( type ) {
		case 'SET_DATA':
			state.data = payload;
			state.wizzard.steps.tickets.enabled = payload?.attendee_fields?.length > 0;
			state.wizzard.steps.payment.enabled = ! payload?.event?.price?.free;
			state.wizzard.step = payload?.attendee_fields?.length === 0 ? 1 : 0;
			return { ...state };

		case 'SET_WIZZARD':
			state.wizzard.step = payload;
			state.wizzard.checkValidity = true;
			return { ...state };

		case 'INCREMENT_WIZZARD':
			state.wizzard.step = state.wizzard.step + ( payload ? payload : 1 );
			state.wizzard.checkValidity = true;
			return { ...state };

		case 'DECREMENT_WIZZARD':
			state.wizzard.step = state.wizzard.step - ( payload ? payload : 1 );
			state.wizzard.checkValidity = true;
			return { ...state };

		case 'SET_MODAL':
			state.modal.visible = payload;
			state.modal.title = payload
				? `${ __( 'Registration', 'events' ) } ${ data.event.title }`
				: state.originalDocumentTitle;
			return { ...state };

		case 'SET_LOADING':
			state.modal.loading = payload;
			return { ...state };

		case 'ADD_TICKET':
			state.request.tickets.push( JSON.parse( JSON.stringify( state.data.available_tickets[ payload ] ) ) );
			state.wizzard.checkValidity = true;
			return { ...state };

		case 'SET_FIELD':
			if ( payload.form === 'ticket' ) {
				state.request.tickets[ payload.index ].fields[ payload.field ] = payload.value;
			}
			if ( payload.form === 'registration' ) {
				state.request.registration[ payload.field ] = payload.value;
			}
			state.wizzard.checkValidity = true;
			return { ...state };

		case 'REMOVE_TICKET':
			const index =
				payload.index !== undefined
					? payload.index
					: state.request.tickets.findIndex( ( ticket ) => ticket.id === payload.id );
			state.request.tickets.splice( index, 1 );
			state.wizzard.checkValidity = true;
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
			console.log( 'resetting...' );
			return initializer();

		default:
			console.log( 'UNKNOWN ACTION', action );
	}

	return { ...state };
};
export default reducer;
