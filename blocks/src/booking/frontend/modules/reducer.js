import initialState from "./initialState";
import { __ } from '@wordpress/i18n';

const reducer = (state = {}, action) => {

  const { type, payload } = action;
  const { data } = state;
  switch (type) {

	case 'SET_WIZZARD':
		state.wizzard.step = payload;
		return {...state}

	case 'INCREMET_WIZZARD':
		state.wizzard.step = state.wizzard.step + (payload ? payload : 1);
		return {...state}

	case 'DECREMET_WIZZARD':
		state.wizzard.step = state.wizzard.step - (payload ? payload : 1);
		return {...state}

	case 'SET_MODAL':
		state.modal.visible = payload;
		state.modal.title = payload ? `${__('Registration', 'events')} ${data.event.title}` : state.originalDocumentTitle;
		return {...state}

	case 'SET_LOADING':
		state.modal.loading = payload;
		return {...state}

	case 'ADD_TICKET':
		state.request.tickets.push(JSON.parse(JSON.stringify(state.data.available_tickets[payload])));
		return {...state}

	case 'SET_FIELD':
		if (payload.form === 'ticket') {
			state.request.tickets[payload.index].fields[payload.field] = payload.value;
		}
		if (payload.form === 'registration') {
			state.request.registration[payload.field] = payload.value;
		}
		return {...state}

	case 'REMOVE_TICKET':
		const index = payload.index !== undefined ? payload.index : state.request.tickets.findIndex(ticket => ticket.id === payload.id);
		state.request.tickets.splice(index, 1);
		return {...state}

	case 'SET_COUPON':
		state.request.coupon = payload;
		return {...state}
	
	case 'SET_COUPON_LOADING':
		state.modal.couponButton = payload;
		return {...state}
	
	case 'COUPON_RESPONSE':
		state.response.coupon = payload;
		return {...state}
	
	case 'BOOKING_RESPONSE':
		state.response.booking = payload;
		return {...state}

	case 'SET_GATEWAY':
		state.request.gateway = payload;
		return {...state}

	case 'VALIDITY':
		for(let key in payload) {
			state.wizzard.steps[key].valid = payload[key];
		}

	case 'RESET':
		return initialState

	default:
		console.log("UNKNOWN ACTION", action);
	
	}

	return {...state}
}
export default reducer;