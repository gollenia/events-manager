import React, { useEffect, useRef } from 'react'
import { __ } from '@wordpress/i18n';

import Ticket from './Ticket.jsx';

const TicketList = (props) => {  

    const {
        eventData: {
            tickets, 
            attendee_fields,
            strings
        },
        coupon,
        ticketSelection, 
        addTicket, 
        removeTicket, 
        updateTicket, 
        ticketPrice, 
        fullPrice
    } = props

    const form = useRef(null)

    const checkValidity = () => {
        console.log("formValid", form.current?.checkValidity())
    }

    useEffect(() => {
        checkValidity()
    }, [])

    const addNewTicket = (id) => {
        addTicket(id)
        checkValidity()
    }

    const deleteTicket = (uid) => {
        removeTicket(uid)
        checkValidity()
    }

    const formChange = (id, ticket) => {
        checkValidity();
        updateTicket(id, ticket)
    }

    return (
        <div className="grid xl:grid--columns-2 grid--gap-12">
            <div className="list ">
            { tickets.map((ticket, key) =>
                <div className="list__item" key={key}>
                    <div className="list__content">
                        <div className="list__title">{ticket.name}</div>    
                        <div className="list__subtitle">{tickets.description}</div>
                        <div className="list__subtitle">{__("Base price:", "em-pro")} {ticket.price} {strings.currency}</div>
                    </div>
                    <div className="list__actions">
                        <span className="button button--pseudo nowrap">{ticketPrice(key)} {strings.currency}</span>
                        <button className="button button--primary button--icon" onClick={() => addNewTicket(key)}><i className="material-icons">add_circle</i></button>
                    </div>
                </div>
            )}
                { coupon.success &&
                <div className="list__item" >
                    <div className="list__content">
                        <div className="list__title">{coupon.description || __("Coupon", "em-pro")}</div>    
                        
                    </div>
                    <div className="list__actions">
                        <b className="button button--pseudo nowrap">{coupon.discount} {coupon.percent ? "%" : strings.currency}</b>
                        <button className="button button--primary button--icon invisible"><i className="material-icons">add_circle</i></button>
                    </div>
                </div>
                }
                <div className="list__item" >
                    <div className="list__content">
                        <div className="list__title"><b>{__("Full price", "em-pro")} {strings.currency}</b></div>    
                        
                    </div>
                    <div className="list__actions">
                        <b className="button button--pseudo nowrap">{fullPrice()} {strings.currency} </b>
                        <button className="button button--primary button--icon invisible"><i className="material-icons">add_circle</i></button>
                    </div>
                </div>
            </div>
            { attendee_fields.length > 0 &&
                <form noValidate role="form" ref={form}>
                    {ticketSelection.map((ticket, index) =>
                    
                        <Ticket 
                            ticket={ticket}
                            key={ticket.uid}
                            ticketKey={index}
                            fields={attendee_fields}
                            updateTicket={formChange}
                            removeTicket={deleteTicket}
                        />
                    
                    ) }
                </form>
            }
        </div>
    )
}

export default TicketList
