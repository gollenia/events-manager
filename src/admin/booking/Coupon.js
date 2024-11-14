import { SelectControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import React from 'react';

const Coupon = ( { store } ) => {
	const [ state, dispatch ] = store;

	return (
		<div className="booking-coupon">
			<SelectControl
				label={ __( 'Coupon Code', 'events-manager' ) }
				value={ state.data.booking.coupon }
				onChange={ ( value ) => {
					dispatch( {
						type: 'SET_COUPON',
						payload: value,
					} );
				} }
				options={ [ { value: '', label: 'Select Coupon' }, ...state.data.available_coupons ] }
			/>
		</div>
	);
};

export default Coupon;
