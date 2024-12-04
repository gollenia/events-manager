import { Panel, PanelBody, SelectControl, TextControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import React from 'react';
import Coupon from './Coupon';

const Payment = ( { store } ) => {
	const [ state, dispatch ] = store;
	const data = state.data;

	return (
		<>
			<div className="flex-header">
				<h2>{ __( 'Payment', 'events' ) }</h2>
			</div>
			<Panel>
				<PanelBody header="Payment">
					<div className="booking-payment-method">
						<SelectControl
							label={ __( 'Payment Method', 'events' ) }
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

					<TextControl
						type="number"
						label={ __( 'Donation', 'events' ) }
						value={ data.booking.donation }
						onChange={ ( value ) =>
							dispatch( {
								type: 'SET_FIELD',
								payload: {
									form: 'donation',
									field: 'donation',
									value: parseFloat( value ),
								},
							} )
						}
					/>
				</PanelBody>
			</Panel>
		</>
	);
};

export default Payment;
