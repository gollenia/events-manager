import { __ } from '@wordpress/i18n';
import sendOrder from './modules/sendOrder';

const Footer = ( { state, dispatch } ) => {
	const { data, request, wizzard } = state;

	const [ TICKETS, REGISTRATION, PAYMENT, SUCCESS ] = [
		wizzard.step == 0,
		wizzard.step == 1,
		wizzard.step == 2,
		wizzard.step == 3,
	];

	const TICKETS_MISSING =
		( TICKETS && request.tickets.length == 0 ) ||
		( REGISTRATION && data.attendee_fields.length == 0 && request.tickets.length == 0 );
	const ATTENDEES_MISSING = TICKETS && request.tickets.length > 0 && ! wizzard.steps.tickets.valid;
	const REGISTRATION_MISSING = REGISTRATION && ! wizzard.steps.registration.valid;
	const PAYMENT_MISSING = PAYMENT && request.gateway == '';
	const ONLINE_PAYMENT = request.gateway != 'offline';
	const PRIVACY_MISSING =
		data.l10n.consent &&
		( ( PAYMENT && ! request.registration.data_privacy_consent ) ||
			( ! wizzard.steps.payment.enabled && REGISTRATION && ! request.registration.data_privacy_consent ) );

	const FINAL_STEP = wizzard.steps.payment.enabled ? 2 : 1;

	const ERROR = ATTENDEES_MISSING || REGISTRATION_MISSING || PAYMENT_MISSING || TICKETS_MISSING;

	const SHOW_NEXT = ! ERROR && ! ONLINE_PAYMENT && ! PRIVACY_MISSING;

	const message = () => {
		if ( TICKETS_MISSING ) return __( 'Please select at least one ticket', 'events' );
		if ( ATTENDEES_MISSING ) return __( 'Please fill in all required fields', 'events' );
		if ( REGISTRATION_MISSING ) return __( 'Please fill in all required fields', 'events' );
		if ( PAYMENT_MISSING ) return __( 'Please select a payment method', 'events' );
		if ( PRIVACY_MISSING ) return __( 'Please accept the privacy policy', 'events' );
		if ( ONLINE_PAYMENT ) return __( 'You will be redirected to the payment gateway', 'events' );
		return '';
	};
	return (
		<div className="section">
			<div className="container button-group button-group--right">
				{ message() != '' && <span className="button--pseudo">{ __( message(), 'events' ) }</span> }

				{ /* Back Button  */ }
				{ wizzard.step > ( wizzard.steps.tickets.enabled ? 0 : 1 ) && wizzard.step < 3 && (
					<button
						className="button button--secondary booking-button wp-block-events-manager-booking"
						onClick={ () => {
							dispatch( { type: 'DECREMENT_WIZZARD' } );
						} }
					>
						{ __( 'Back', 'events' ) }
					</button>
				) }

				{ /* Next Button */ }
				{ wizzard.step < FINAL_STEP && (
					<button
						type="button"
						disabled={ ERROR }
						className="button button--primary booking-button wp-block-events-manager-booking"
						onClick={ () => {
							dispatch( { type: 'INCREMENT_WIZZARD' } );
						} }
					>
						{ __( 'Next', 'events' ) }
					</button>
				) }

				{ wizzard.step == FINAL_STEP && (
					<button
						disabled={ ! wizzard.steps.registration.valid || ! wizzard.steps.payment.valid }
						className="button button--primary"
						onClick={ () => {
							sendOrder( state, dispatch );
						} }
					>
						{ data.attributes?.bookNow !== '' ? data.attributes?.bookNow : __( 'Book now', 'events' ) }
					</button>
				) }
				{ SUCCESS && (
					<button
						className="button button--success"
						onClick={ () => {
							dispatch( { type: 'RESET' } );
						} }
					>
						{ __( 'Close', 'events' ) }
					</button>
				) }
			</div>
		</div>
	);
};

export default Footer;
