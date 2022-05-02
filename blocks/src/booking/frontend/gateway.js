import { __ } from '@wordpress/i18n';
import React from 'react'
import PropTypes from "prop-types"

/*
*   Simple renderer for a given gateway
*/
const Gateway = (props) => {

    if(props.currentGateway == undefined) {
        return (<div>{__("You can pay via bank transaction", "events")}</div>)
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
            <h5>{__("Payment", "events")}</h5>
            <h3>{title}</h3>
            <p dangerouslySetInnerHTML={createMarkup()}></p>
            <div className="description">
                { id=="mollie" && Object.keys(methods).map((method) => {
                    return (<li className={`description-item ${method}`} key={method}><img src={"/wp-content/plugins/events-mollie/assets/methods/" + method + ".svg"}/> {methods[method]}</li>)
                }) }
            </div>
        </div>
    )
}

export default Gateway

Gateway.propTypes = {
    currentGateway: PropTypes.array
}