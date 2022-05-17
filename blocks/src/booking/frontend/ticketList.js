import React, { useEffect, useRef } from 'react'
import { __ } from '@wordpress/i18n';
import eventData from './modules/eventData';
import Ticket from './ticket';
import Summary from './summary';
import { ticketPrice, fullPrice, formatCurrency } from './modules/priceUtils';

const TicketList = (props) => {  

    const {
        state,
		dispatch
    } = props

	const { request, data } = state

	const { tickets } = state.request;

    const form = useRef(null)

    return (
        <div className="grid xl:grid--columns-2 grid--gap-12">
            <Summary state={state} dispatch={dispatch} />
            { data.attendee_fields.length > 0 &&
                <form className="grid grid--columns-1 grid--gap-12" role="form" ref={form} id="user-attendee-form">
                    {request.tickets.map((ticket, index) =>
                        <Ticket 
                            ticket={ticket}
                            index={index}
							state={state}
							dispatch={dispatch}
                        />
                    ) }
                </form>
            }
        </div>
    )
}

export default TicketList
