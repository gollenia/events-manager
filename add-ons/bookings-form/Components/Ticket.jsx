import React, { useEffect, useState } from "react";
import InputField from './InputField.jsx'


const Ticket = ({ticket, fields, ticketKey, removeTicket, updateTicket}) => {

    const [attendeeName, setAttendeeName] = useState("");

    const modifyTicket = (field, value) => {
        ticket["fields"][field] = value
        updateTicket(ticketKey, ticket);
        if(field == "attendee_name" || field == "name") {
            
            setAttendeeName(value)
            
        }
    }

    if(ticket == undefined) {
        return
    }

    return (
        <div className="card card--no-image bg-white my-8 card--shadow">
            <div className="card__content">
            <div className="card__title mb-8">{ticket.name} {attendeeName.length > 0 ? "Für" : ""} {attendeeName}</div>
        
            { fields.map((field, key) => 
                <InputField
                    key={key}
                    name={field.name}
                    label={field.label}
                    required={field.required}
                    value={field.value}
                    pattern={field.pattern}
                    min={field.min}
                    max={field.max}
                    defaultValue={field.default}
                    options={field.options}
                    selectHint={field.select_hint}
                    type={field.type}
                    onChange={(value) => modifyTicket(field.name, value)}
                />
            ) }
          
            <div className="card__footer mt-8">
                <div className="card__actions">
                    
                </div>
                <div className="card__supplemental">
                    <button className="button button--error button--icon button--pop" onClick={() => removeTicket(ticket.uid)} disabled={ticket.min >= ticketKey + 1}><i class="material-icons">delete</i></button>
                </div>
            </div>
            </div>
            
        </div>
    )
}

export default Ticket
