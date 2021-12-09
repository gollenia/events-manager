import { __ } from '@wordpress/i18n';
import React from 'react'


/*
*   Simple renderer for a given gateway
*/
const Gateway = (props) => {

    if(props.currentGateway == undefined) {
        return (<div>No Gateways given</div>)
    }

    const {
        currentGateway: {
            id, title, methods, html
        }
    } = props

    function createMarkup() {
        return {__html: html};
    }

    return (
        <div>
            <h5>{__("Payment", "em-pro")}</h5>
            <h3>{title}</h3>
            <p dangerouslySetInnerHTML={createMarkup()}></p>
            <div>
                { id=="mollie" && Object.keys(methods).map((method) => {
                    return (<li className={`${method}`} key={method}><img src={"/wp-content/plugins/events-manager-pro/add-ons/bookings-form/Components/PaymentIcons/" + method + ".svg"}/> {methods[method]}</li>)
                }) }
            </div>
        </div>
    )
}

export default Gateway
