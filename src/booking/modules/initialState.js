import { __ } from '@wordpress/i18n';
import eventData from './eventData';

const getTempForm = () => {
	let tempForm = {};
	if ( ! eventData ) return tempForm;
	for ( let field of eventData.registration_fields ) {
		tempForm[ field.name ] = field.value;
	}
	tempForm[ 'data_privacy_consent' ] = '';
	return tempForm;
};

let requiredTickets = [];
for ( let ticketKey in eventData.tickets ) {
	for ( let i = 0; i < eventData.tickets[ ticketKey ].min; i++ ) {
		let ticket = { ...eventData.tickets[ ticketKey ] };
		ticket.uid = Math.floor( Math.random() * 1000 );
		requiredTickets.push( ticket );
	}
}

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
				enabled: eventData?.attendee_fields?.length > 0,
				step: 0,
				label: __( 'Tickets', 'events' ),
				valid: document.getElementById( 'user-attendee-form' )?.checkValidity(),
			},
			registration: {
				enabled: true,
				step: 1,
				label: __( 'Registration', 'events' ),
				valid: document.getElementById( 'user-registration-form' )?.checkValidity(),
			},
			payment: {
				enabled: ! eventData?.event?.price?.free,
				step: 2,
				label: __( 'Payment', 'events' ),
				valid: eventData?.event?.is_free,
			},
			success: {
				enabled: true,
				step: 3,
				label: __( 'Done', 'events' ),
				valid: true,
			},
		},
		keys: [ 'tickets', 'registration', 'payment', 'success' ],
		step: eventData?.attendee_fields?.length === 0 ? 1 : 0,
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
		tickets: requiredTickets,
		registration: getTempForm(),
		gateway: 'offline',
		coupon: '',
	},
	data: { ...eventData },
};

export default initialState;
