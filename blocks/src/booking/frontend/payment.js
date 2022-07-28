const { React } = require('react');
import { __ } from '@wordpress/i18n';
import PropTypes from "prop-types"
import Coupon from './coupon';

import InputField from './inputField'
import Summary from './summary';

const Payment = (props) => {

    const {
        state: {
			request,
			response,
			data
		},
		state,
		dispatch,
    } = props

	
    const gatewayOptions = () => {
        const result = {}
        if(data.available_gateways == undefined) return result
		Object.keys(data.available_gateways).forEach(id => {
			result[id] = data.available_gateways[id].title;
		});
        return result   
    }
    
    return (
        <div className="grid xl:grid--columns-2 grid--gap-12">
            <div>
				<Summary state={state} dispatch={dispatch} />
            </div>
			<div>
			<form className="form--trap form grid xl:grid--columns-6 grid--gap-8">
				{ data.event.has_coupons && 
					<Coupon state={state} dispatch={dispatch} />
				}
				{ Object.keys(data.available_gateways).length > 1 &&
				<InputField
					onChange={(event) => {dispatch({type: "SET_GATEWAY", payload: event})} }
					field={{
						name: "gateway", 
						label: __("Payment method", "event"),
						type: "select",
						options: gatewayOptions()
					}}
					value={request.gateway}
					
				/> }

				{ data.l10n.consent &&
				<InputField
					onChange={(event) => {dispatch({type: "SET_FIELD", payload: {form: "registration", field: "data_privacy_consent", value: event}})} }
					value={request.registration.data_privacy_consent}
					field={{
						name: "data_privacy_consent",
						help: data.l10n.consent,
						type: "checkbox"
					}}
				/> }
				
				
				{ response.error != "" && 
					<div class="alert bg-error text-white" dangerouslySetInnerHTML={{__html: response.error}}></div>
				}
			</form>
			</div>
        </div>
    )
}

Payment.propTypes = {
    gateways: PropTypes.array,
    coupons: PropTypes.array,
    eventData: PropTypes.object,
    nonce: PropTypes.string
}

export default Payment