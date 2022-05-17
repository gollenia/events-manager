import React, { useState } from "react";
import InputField from './inputField'
import eventData from "./modules/eventData";
import { __ } from '@wordpress/i18n';

/*
 *	Renders a single ticket with it's form fields
 *  and a delete button
 *
*/
const Ticket = (props) => {

	const {
		state,
		dispatch,
		ticket,
		index,
	} = props;

	const { attendee_fields } = state.data;
	
    return (
        <div className="card card--no-image bg-white my-8 card--shadow">
            <div className="card__content">
            <div className="card__title mb-8">{ticket.name} {ticket.fields?.attendee_name ? __("for", "events") : ""} {ticket.fields.attendee_name}</div>
			<div className="form">
            { attendee_fields.map((field, key) => {
				
				return (
                <InputField
                    key={key}
                    name={field.name}
                    label={field.label}
                    required={field.required}
					half={field.half}
                    value={ticket.fields[field.name]}
                    pattern={field.pattern}
                    min={field.min}
                    max={field.max}
                    defaultValue={field.default}
                    options={field.options}
                    selectHint={field.select_hint}
                    type={field.type}
                    onChange={(value) => dispatch({type: "SET_FIELD", payload: {form: 'ticket', index, field: field.name, value: value}})}
                />)}
            ) }
          	</div>
            <div className="card__footer mt-8">
                <div className="card__actions">
                    
                </div>
                <div className="card__supplemental">
					<span href="" className="button button--error button--icon button--pop" onClick={() => dispatch({type: "REMOVE_TICKET", payload: {index}})} disabled={ticket.min >= index + 1}><i class="material-icons">delete</i></span>
                </div>
            </div>
            </div>
            
        </div>
    )
}

export default Ticket
