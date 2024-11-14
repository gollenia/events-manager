import { Panel, PanelBody, SelectControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import React from 'react';
import Coupon from './Coupon';

const Payment = ( { store } ) => {
	const [ state, dispatch ] = store;
	const data = state.data;

	return (
		<>
			<div className="flex-header">
				<h2>{ __( 'Payment', 'events-manager' ) }</h2>
			</div>
			<Panel>
				<PanelBody header="Payment">
					<div className="booking-payment-method">
						<SelectControl
							label={ __( 'Payment Method', 'events-manager' ) }
							value={ data.booking.gateway }
							options={ Object.keys( data.available_gateways ).map( ( key ) => {
								return { label: data.available_gateways[ key ].title, value: key };
							} ) }
							onChange={ ( value ) =>
								dispatch( {
									type: 'SET_GATEWAY',
									payload: value,
								} )
							}
						/>
					</div>

					<Coupon store={ store } />
				</PanelBody>
			</Panel>
		</>
	);
};

export default Payment;
