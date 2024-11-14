const initialState = {
	data: false,
	state: 'loading',
	modal: {
		selectedTicketIndex: 999,
		visible: false,
	},
	currentTicket: 999,
	coupon: {
		discount: 0,
		discountType: '%',
	},
	sendState: 'idle',
};

export default initialState;
