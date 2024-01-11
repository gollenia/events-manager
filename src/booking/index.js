/*
 *   External dependecies
 */
import { __ } from '@wordpress/i18n';
import { useEffect, useReducer } from 'react';

import { ErrorBoundary } from 'react-error-boundary';
import './style.scss';

/*
 *   Internal dependecies
 */
import ErrorFallback from './error';
import Footer from './footer';
import Guide from './guide';
import initialState from './modules/initialState.js';
import reducer from './modules/reducer.js';
import Payment from './payment';
import Success from './success';
import TicketList from './ticketList';
import UserRegistration from './userRegistration';

// this function  is suposed to open the modal from the parent component

const Booking = ( { post, open } ) => {
	// if no spaces are left, nothing is shown

	const [ state, dispatch ] = useReducer( reducer, initialState );

	const { wizzard, modal, data, request, response } = state;

	useEffect( () => {
		fetch( `/wp-json/events/v2/bookingdata/${ post }` )
			.then( ( response ) => response.json() )
			.then( ( data ) => {
				dispatch( { type: 'SET_DATA', payload: data.data } );
			} );
	}, [] );

	useEffect( () => {
		if ( ! data ) return;
		if ( ! wizzard.checkValidity ) return;
		dispatch( {
			type: 'VALIDITY',
			payload: {
				tickets: document.getElementById( 'user-attendee-form' )?.checkValidity() && request.tickets.length > 0,
				registration:
					document.getElementById( 'user-registration-form' )?.checkValidity() && request.tickets.length > 0,
				payment: ! data?.l10n?.consent || ( data?.l10n?.consent && request.registration.data_privacy_consent ),
			},
		} );
	}, [ state ] );

	if ( ! data ) return <></>;

	return (
		<div>
			<ErrorBoundary FallbackComponent={ ErrorFallback }>
				<div className={ `event-modal wizzard ${ open ? 'event-modal--open' : '' }` }>
					<div className="event-modal-dialog">
						<div className="event-modal-header">
							<div className="container flex xl:flex--center flex--column xl:flex--row">
								<div className="flex--1">
									<b className="margin--0">Anmeldung</b>
									<h3 className="margin--0">{ data?.event?.title }</h3>
								</div>
								<Guide state={ state } />
							</div>
							<button
								className="event-modal-close"
								onClick={ () => {
									dispatch( { type: 'SET_MODAL', payload: false } );
								} }
							></button>
						</div>

						{ modal.loading > 0 ? (
							<div className="event-modal-content">
								<aside>
									<div className="spinning-loader"></div>
									<h3>{ __( 'Please wait', 'events' ) }</h3>
									<h4>{ __( 'Your booking is beeing processed.', 'events' ) }</h4>
									{ modal.loading > 1 && (
										<div className="alert alert--warning">
											{ modal.loading == 2 &&
												__(
													'Please hang on a little longer. This can take a few seconds.',
													'events'
												) }
											{ modal.loading == 3 &&
												__(
													'The request is lasting longer than expected. We try our best',
													'events'
												) }
											{ modal.loading == 4 &&
												__(
													'Something seems to be wrong. Maybe your internet connection is interrupted or our server is overloaded. Please try again later',
													'events'
												) }
										</div>
									) }
								</aside>
							</div>
						) : (
							<div className="event-modal-content">
								<div className="wizzard__steps">
									{ wizzard.steps.tickets.enabled && (
										<div
											className={ `wizzard__step ${
												wizzard.step == 0 ? ' wizzard__step--active' : ''
											} ${ wizzard.step == 1 ? ' wizzard__step--prev' : '' }` }
										>
											<TicketList { ...{ state, dispatch } } />
										</div>
									) }
									<div
										className={ `wizzard__step ${
											wizzard.step == 1 ? ' wizzard__step--active' : ''
										} ${
											wizzard.step == 2 && ! data.event.is_free ? ' wizzard__step--prev' : ''
										} ${ wizzard.step == 0 ? ' wizzard__step--next' : '' }` }
									>
										<UserRegistration { ...{ state, dispatch } } />
									</div>

									<div
										className={ `wizzard__step ${
											wizzard.step == 2 ? ' wizzard__step--active' : ''
										} ${ wizzard.step == 3 ? '' : '' } ${
											wizzard.step == 1 ? ' wizzard__step--next' : ''
										}` }
									>
										<Payment { ...{ state, dispatch } } />
									</div>

									<div
										className={ `wizzard__step ${
											wizzard.step == 3 ? ' wizzard__step--active' : ''
										} ${ wizzard.step == 2 ? ' ' : '' }` }
									>
										<Success { ...{ state, dispatch } } />
									</div>
								</div>
							</div>
						) }
						<div className="event-modal-footer">
							<Footer { ...{ state, dispatch } } />
						</div>
					</div>
				</div>
			</ErrorBoundary>
		</div>
	);
};

export default Booking;
