/*
*   External dependecies
*/
import React, { useEffect, useState, useReducer } from 'react'
import { __ } from '@wordpress/i18n';

import {ErrorBoundary} from 'react-error-boundary'

/*
*   Internal dependecies
*/
import UserRegistration from './userRegistration'
import TicketList from "./ticketList"
import Payment from "./payment"
import Footer from './footer';
import ErrorFallback from './error';
import Success from './success';
import initialState from './modules/initialState.js';
import reducer from './modules/reducer.js';
import Guide from './guide';

// this function  is suposed to open the modal from the parent component

const Booking = () => {

	// if no spaces are left, nothing is shown
	if(window.booking_data.event?.bookings?.spaces === 0) { return <>no data</> }

	//const [state, setstate] = useState(initialState);

	const [state, dispatch] = useReducer(reducer, initialState);

	const {
		wizzard, modal, data, request, response
	} = state;

    if(Object.keys(data).length == 0) return (<span className="button button--error button--pseudo">{__('Error: No connection to server.', 'events')}</span>);

	useEffect(() => {
		dispatch({type: "VALIDITY", payload: { 
			"tickets": document.getElementById('user-attendee-form')?.checkValidity() && request.tickets.length > 0,
			"registration": document.getElementById('user-registration-form')?.checkValidity() && request.tickets.length > 0,
			"payment": data.event.is_free || !data.l10n.consent || request.registration.data_privacy_consent 
		}});
	}, [state])
	
	console.log("state", state);
      
	return (
		<div>
			<ErrorBoundary FallbackComponent={ErrorFallback}>
			<button className="button button--primary" onClick={() => {dispatch({type: "SET_MODAL", payload: true});}}>
				{ data?.attributes?.buttonTitle  !== "" ? data?.attributes?.buttonTitle : __("Registration", "events") }
			</button>
			
			<div className={`modal wizzard modal--fullscreen ${modal.visible ? "modal--open" : ""}`}>
			{ modal.loading > 0 && <div className="modal__overlay">
				<aside>
					<div className="spinning-loader"></div>
					<h4>{__("Please wait", "events")}</h4>
					<h5>{__("Your booking is beeing processed.", "events")}</h5>
					{ modal.loading > 1 && <div className="alert alert--warning">{__("The request is lasting longer than expected. Please check your Internet connection or try again later.", "events")}</div> }
				</aside>
			</div> }
			<div className="modal__dialog">
				<div className="modal__header">
					<div className="container flex xl:flex--center flex--column xl:flex--row">
						<div className='flex--1'><b className="margin--0">Anmeldung</b><h3 className="margin--0">{ data.event.title }</h3></div>
						<Guide state={state} />
					</div>
					<button className="modal__close" onClick={() => {dispatch({type: "SET_MODAL", payload: false})}}></button>
				</div>
				
				
				<div className="modal__content">
					<div className="wizzard__steps">
					{ wizzard.steps.tickets.enabled &&
						<div className={`wizzard__step ${wizzard.step == 0 ? " wizzard__step--active" : ""} ${wizzard.step == 1 ? " wizzard__step--prev" : ""}`}>
							<div className="container">
								<div className="section">
									<TicketList 
										{...{state, dispatch}}
										
									/>
								</div>
							</div>
						
						</div>
					}
					<div className={`wizzard__step ${wizzard.step == 1 ? " wizzard__step--active" : ""} ${wizzard.step == 2 && !data.event.is_free ? " wizzard__step--prev" : ""} ${wizzard.step == 0 ? " wizzard__step--next" : ""}`}>
						<div className="container">
							<UserRegistration 
								{...{state, dispatch}}
						
							/>
						</div>
					</div>
			
					<div className={`wizzard__step ${wizzard.step == 2 ? " wizzard__step--active" : ""} ${wizzard.step == 3 ? "" : ""} ${wizzard.step == 1 ? " wizzard__step--next" : ""}`}>
						<div className="container">
							<Payment {...{state, dispatch}} />
						</div>
					</div>
		
					<div className={`wizzard__step ${wizzard.step == 3 ? " wizzard__step--active" : ""} ${wizzard.step == 2 ? " " : ""}` }>
						<div className="container">
							<Success {...{state, dispatch}} />
						</div>
					</div>
				</div>
				</div>
				<div className="modal__footer">
						<Footer 
							{...{state, dispatch}}
						/>
				</div>
			</div>
			</div> 
			</ErrorBoundary>
		</div>
    )
}

export default Booking;