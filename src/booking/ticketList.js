import React, { useRef } from 'react';
import Summary from './summary';
import Ticket from './ticket';

const TicketList = ( props ) => {
	const { state, dispatch } = props;

	const { request, data } = state;

	const form = useRef( null );

	return (
		<div className="ticket-grid">
			<Summary state={ state } dispatch={ dispatch } />
			{ data.attendee_fields.length > 0 && (
				<form className="ticket-grid-form" role="form" ref={ form } id="user-attendee-form">
					<div id="firstTicket"></div>
					{ request.tickets.map( ( ticket, index ) => (
						<Ticket key={ index } ticket={ ticket } index={ index } state={ state } dispatch={ dispatch } />
					) ) }
					<div
						tabIndex={ 99 }
						onFocus={ ( event ) => {
							event.preventDefault();
							if ( state.wizard.steps.tickets.valid ) {
								dispatch( { type: 'INCREMENT_WIZARD' } );
								return;
							}
							form.current.querySelector( '#first-ticket-field' ).focus();
						} }
					></div>
				</form>
			) }
		</div>
	);
};

export default TicketList;
