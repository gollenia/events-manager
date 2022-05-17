import React from 'react'
import InputField from './inputField'
import { __ } from '@wordpress/i18n';
import Summary from './summary';

const UserRegistration = (props) => {

  const {
    countTickets,
	state,
	dispatch
  } = props

  const { error } = state.response;
  const { registration } = state.request;
  
  const { data, request, response } = state;


    return (
        <div className="grid xl:grid--columns-2 grid--gap-12">
          <Summary {...props} />
		  <div>
            <form className="form" id="user-registration-form">
            { data.registration_fields.map((field, key) => 
              
              <InputField
                key={key}
                type={field.type}
                name={field.name}
				half={field.half}
                label={field.label}
                required={field.required}
                pattern={field.pattern}
                defaultValue={field.default}
                value={state.request.registration[field.name]}
                options={field.options}
                selectHint={field.select_hint}
                onChange={(event) => {dispatch({type: "SET_FIELD", payload: {form: "registration", field: field.name, value: event}})} }
              />
          ) }
          { data.event.is_free &&  
            <InputField
			  onChange={(event) => {dispatch({type: "SET_FIELD", payload: {form: "registration", field: "data_privacy_consent", value: event}})} }
              name="data_privacy_consent"
              value={state.request.registration.data_privacy_consent}
              label={data.l10n.consent}
              type="checkbox"
            />
          }

          { data.event.is_free && error != "" && 
                <div class="alert bg-error text-white" dangerouslySetInnerHTML={{__html: error}}></div>
          }
          </form>
		  </div>
        </div>
    )
}

export default UserRegistration
