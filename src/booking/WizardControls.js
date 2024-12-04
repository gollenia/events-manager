import { __ } from '@wordpress/i18n';
import sendOrder from './modules/sendOrder';

const WizardControls = ( { state, dispatch } ) => {
	const { data, request, wizard, modal } = state;

	const [ TICKETS, REGISTRATION, PAYMENT, SUCCESS ] = [
		wizard.step == 0,
		wizard.step == 1,
		wizard.step == 2,
		wizard.step == 3,
	];

	const TICKETS_MISSING =
		( TICKETS && request.tickets.length == 0 ) ||
		( REGISTRATION && data.attendee_fields.length == 0 && request.tickets.length == 0 );
	const ATTENDEES_MISSING = TICKETS && request.tickets.length > 0 && ! wizard.steps.tickets.valid;
	const REGISTRATION_MISSING = REGISTRATION && ! wizard.steps.registration.valid;
	const PAYMENT_MISSING = PAYMENT && request.gateway == '';
	const ONLINE_PAYMENT = request.gateway != 'offline';
	const PRIVACY_MISSING =
		data?.l10n?.consent &&
		( ( PAYMENT && ! request.registration.data_privacy_consent ) ||
			( ! wizard.steps.payment.enabled && REGISTRATION && ! request.registration.data_privacy_consent ) );

	const FINAL_STEP = wizard.steps.payment.enabled ? 2 : 1;

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
		<div className="wizard__controls">
			{ /* Back Button  */ }
			{ wizard.step > ( wizard.steps.tickets.enabled ? 0 : 1 ) && wizard.step < 3 && (
				<button
					className="button button--secondary booking-button wp-block-events-booking"
					onClick={ () => {
						dispatch( { type: 'DECREMENT_WIZARD' } );
					} }
				>
					{ __( 'Back', 'events' ) }
				</button>
			) }

			{ /* Next Button */ }
			{ wizard.step < FINAL_STEP && (
				<>
					{ message() != '' && <span className="button--pseudo">{ __( message(), 'events' ) }</span> }
					<button
						type="button"
						disabled={ ERROR }
						id="focusButton"
						className="button button--primary booking-button wp-block-events-booking"
						onClick={ () => {
							dispatch( { type: 'INCREMENT_WIZARD' } );
						} }
					>
						{ __( 'Next', 'events' ) }
					</button>
				</>
			) }

			{ wizard.step == FINAL_STEP && (
				<button
					disabled={ ! wizard.steps.registration.valid || ! wizard.steps.payment.valid || modal.loading > 0 }
					className="button button--primary"
					id="focusButton"
					onClick={ () => {
						sendOrder( state, dispatch );
					} }
				>
					{ __( 'Book now', 'events' ) }
				</button>
			) }
			{ SUCCESS && (
				<button
					className="button button--success"
					id="focusButton"
					onClick={ () => {
						dispatch( { type: 'RESET' } );
					} }
				>
					{ __( 'Close', 'events' ) }
				</button>
			) }
		</div>
	);
};

export default WizardControls;
