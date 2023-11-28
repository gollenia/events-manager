import { __ } from '@wordpress/i18n';

const initialState = {
	modal: {
		visible: window.location.hash.indexOf( 'booking' ) != -1 ? true : false,
		title: document.title,
		originalDocumentTitle: document.title,
		loading: 0,
	},
	wizzard: {
		steps: {
			tickets: {
				enabled: true,
				step: 0,
				label: __( 'Tickets', 'events' ),
				valid: false,
			},
			registration: {
				enabled: true,
				step: 1,
				label: __( 'Registration', 'events' ),
				valid: false,
			},
			payment: {
				enabled: true,
				step: 2,
				label: __( 'Payment', 'events' ),
				valid: false,
			},
			success: {
				enabled: true,
				step: 3,
				label: __( 'Done', 'events' ),
				valid: true,
			},
		},
		keys: [ 'tickets', 'registration', 'payment', 'success' ],
		step: 0,
		checkValidity: true,
	},
	response: {
		booking: {
			booking_id: 0,
		},
		error: '',
		data: {},
		coupon: {
			code: '',
		},
	},
	request: {
		tickets: [],
		registration: {},
		gateway: 'offline',
		coupon: '',
	},
	data: false,
};

export default initialState;
