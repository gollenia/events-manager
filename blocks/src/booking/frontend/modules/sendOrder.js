import qs from "qs";

const sendOrder = (state, dispatch) => {

	const [FALSE, TRUE, DELAY] = [0, 1, 2];

	const { request, data, response,  modal } = state
	dispatch({type: 'SET_LOADING', payload: TRUE});
   
	setTimeout(() => {
		if(modal.loading == 0) return;
		dispatch({type: "SET_LOADING", payload: DELAY})
	}, 10000)
	let fetchRequest = {...request.registration, 
		"_wpnonce": data._nonce,
		"action": "booking_add",
		"event_id": data.event.event_id,
		"em_attendee_fields": {},
		"em_tickets": [],
		"gateway": request.gateway
	}
	
	for (const id of Object.keys(data.available_tickets)) {
		fetchRequest['em_attendee_fields'][id] = []
		fetchRequest['em_tickets'][id] = {spaces: 0}
	}
	
	state.request.tickets.map((ticket) => {
		fetchRequest.em_attendee_fields[ticket.id].push(ticket.fields)
		fetchRequest.em_tickets[ticket.id].spaces +=1
	})

	if(state.response.coupon.code != '') {
		fetchRequest["coupon_code"] = response.coupon.code
	}
	
	const url = new URL(data.booking_url)
	url.search = qs.stringify(fetchRequest)
	
	fetch(url).then((resp) => resp.json()).then((apiResponse) => {
		dispatch({type: 'SET_LOADING', payload: FALSE})
		dispatch({type: 'BOOKING_RESPONSE', payload: apiResponse});

		console.log("apiResponse", apiResponse)
		
		if(!apiResponse.result) {
			return;
		}

		// not good, hard coded
		if(apiResponse.gateway === "mollie") {
			window.location.replace(apiResponse.mollie_url);
		}

		if(apiResponse.gateway === "offline") {
			dispatch({type: 'BOOKING_RESPONSE', payload: apiResponse});
			dispatch({type: 'SET_WIZZARD', payload: 3});
		}
		return;
	})

}

export default sendOrder;