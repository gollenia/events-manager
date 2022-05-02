import React from 'react'
import InputField from './inputField'
import { __ } from '@wordpress/i18n';

const UserRegistration = (props) => {

 

  const {
    eventData: { fields, tickets, attendee_fields, l10n },
    updateForm,
    ticketSelection,
    addTicket,
    ticketPrice,
    error,
    fullPrice,
    coupon,
    formData,
    countTickets,
    removeTicketByType,
	formatCurrency
  } = props

  const updateFormField = (field, value) => {
    updateForm(field, value)
  }


    return (
        <div className="grid xl:grid--columns-2 grid--gap-12">
          <div>
          <div className="list ">
            { Object.keys(tickets).map((id, key) =>
                <div className="list__item" key={key}>
                    <div className="list__content">
                        <div className="list__title">{tickets[id].name}</div>    
                        <div className="list__subtitle">{tickets[id].description}</div>
                        <div className="list__subtitle">{__("Base price:", "events")} {formatCurrency(tickets[id].price)}</div>
                    </div>
                    
                    <div className="list__actions">
                        <span className="button button--pseudo nowrap">{formatCurrency(ticketPrice(id))}</span>
                        { attendee_fields.length == 0 && <div className="number-picker">
                          <button className="button button--primary button--icon" onClick={() => removeTicketByType(tickets[id].id)} disabled={tickets[id].min == countTickets(tickets[id].id)}></button>
                          <input value={countTickets(tickets[id].id)}/>
                          <button className="button button--primary button--icon" onClick={() => addTicket(id)} disabled={tickets[id].max == countTickets(tickets[id].id)}></button>
                        </div> }
                    </div> 
                </div>
            )}
            { coupon.success &&
                <div className="list__item" >
                    <div className="list__content">
                        <div className="list__title">{coupon.description || __("Coupon", "events")}</div>    
                        
                    </div>
                    <div className="list__actions">
                        <b className="button button--pseudo nowrap">{coupon.discount} {coupon.percent ? coupon.discount + " %" : formatCurrency(coupon.discount)}</b>
                        { attendee_fields.length == 0 && <div className="number-picker invisible">
                          <button className="button button--primary button--icon" ></button>
                          <input/>
                          <button className="button button--primary button--icon" ></button>
                        </div> }
                    </div>
                </div>
                }
            <div className="list__item" >
                    <div className="list__content">
                        <div className="list__title"><b>{__("Full price", "events")}</b></div>    
                        
                    </div>
                    <div className="list__actions">
                        <b className="button button--pseudo nowrap">{formatCurrency(fullPrice())}</b>      
                        { attendee_fields.length == 0 && <div className="number-picker invisible">
                          <button className="button button--primary button--icon" ></button>
                          <input/>
                          <button className="button button--primary button--icon" ></button>
                        </div> }                
                    </div>
                </div>
            </div>
          </div>
		  <div>
            <form className="form">
            { fields.map((field, key) => 
              
              <InputField
                key={key}
                type={field.type}
                name={field.name}
				half={field.half}
                label={field.label}
                required={field.required}
                pattern={field.pattern}
                defaultValue={field.default}
                value={formData[field.name]}
                options={field.options}
                selectHint={field.select_hint}
                onChange={(event) => {updateFormField(field.name, event)} }
              />
          ) }
          { fullPrice() == 0 &&  
            <InputField
              onChange={(event) => { updateFormField("data_privacy_consent", event)}}
              name="data_privacy_consent"
              value={formData.data_privacy_consent}
              label={l10n.consent}
              type="checkbox"
            />
          }

          { fullPrice() == 0 && error != "" && 
                <div class="alert bg-error text-white" dangerouslySetInnerHTML={{__html: error}}></div>
          }
          </form>
		  </div>
        </div>
    )
}

export default UserRegistration
