const React = require('react')
import { __ } from '@wordpress/i18n';
import PropTypes from "prop-types"
import Gateway from './Gateway.jsx';

import InputField from './InputField.jsx'



const Payment = (props) => {

    const {
        eventData: {
            gateways, event, coupons, strings, tickets
        },
        ticketPrice,
        fullPrice,
        coupon,
        error,
        currentGatewayId,
        updateGateway,
        formData,
        updateForm,
        changeCoupon,
        rest_url
    } = props

    const gatewayOptions = () => {
        const result = {}
        if(gateways == undefined) return result
        for (let i = 0; i < gateways.length; ++i) {
            result[gateways[i].id] = gateways[i].title
        }
        return result   
    }

    const currentGateway = () => {
        if(gateways == undefined) return null
        const index = gateways.findIndex((element) => { return element.id === currentGatewayId });
        return gateways[index]
    }

    const gwO = gatewayOptions();

    const selectGateway = (event) => {
        updateGateway(event)
    }

    const updateFormField = (field, value) => {
        
        updateForm(field, value)
      }

    const  checkCoupon = async (code) => {
        const params = {
            event_id: event.event_id,
            code
          }
  
        const url = new URL(rest_url + "events-manager/v2/check_coupon")
        url.search = new URLSearchParams(params).toString();
        
        await fetch(url, {}).then(response => response.json()).then(response => {
            console.log("coupon", response)
            changeCoupon(response)
        })
    }

    
    return (
        <div className="grid grid--columns-2 grid--gap-12">
            <div>
                <div className="list ">
                { Object.keys(tickets).map((id, key) =>
                    <div className="list__item" key={key}>
                        <div className="list__content">
                            <div className="list__title">{tickets[id].name}</div>    
                            <div className="list__subtitle">{tickets[id].description}</div>
                            <div className="list__subtitle">{__("Base price:", "em-pro")} {tickets[id].price} {strings.currency}</div>
                        </div>
                        <div className="list__actions">
                            <span className="button button--pseudo nowrap">{ticketPrice(id)} {strings.currency}</span>
                            
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
                        </div>
                    </div>
                }
                <div className="list__item" >
                        <div className="list__content">
                            <div className="list__title"><b>{__("Full price", "em-pro")}</b></div>    
                            
                        </div>
                        <div className="list__actions">
                            <b className="button button--pseudo nowrap">{fullPrice()} {strings.currency}</b>
                            
                        </div>
                    </div>
                </div>
                <div>
                    <Gateway currentGateway={currentGateway()}/>
                </div>
            </div>
        <form>
            { coupons.available && 
                <div className="input-group">
                <label>{__('Coupon code','em-pro')}</label>
                <input 
                    type="text"
                    label="coupon"
                    name="coupon_code"
                    onBlur={(event) => {checkCoupon(event.target.value)}}
                />
                </div>
            }
            { gateways.length > 1 &&
            <InputField
                onChange={(event) => { selectGateway(event)}}
                name="gateway"
                value={currentGatewayId}
                label={strings.pay_with}
                type="select"
                options={gwO}
            /> }

            <InputField
                onChange={(event) => { updateFormField("data_privacy_consent", event)}}
                name="data_privacy_consent"
                value={formData.data_privacy_consent}
                label={strings.consent}
                type="checkbox"
            />
            
             
            { error != "" && 
                <div class="alert bg-error text-white" dangerouslySetInnerHTML={{__html: error}}></div>
            }
        </form>
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