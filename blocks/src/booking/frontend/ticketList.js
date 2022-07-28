import React, { useEffect, useRef } from 'react'
import { __ } from '@wordpress/i18n';
import Ticket from './ticket';
import Summary from './summary';

const TicketList = (props) => {  

    const {
        state,
		dispatch
    } = props

	const { request, data } = state

    const form = useRef(null)
console.log(data.attendee_fields.length)
    return (
        <div className="grid xl:grid--columns-2 grid--gap-12">
            <Summary state={state} dispatch={dispatch} />
            { data.attendee_fields.length > 0 &&
                <form className="form--trap grid grid--columns-1 grid--gap-12" role="form" ref={form} id="user-attendee-form">
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
