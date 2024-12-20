import { __ } from '@wordpress/i18n';
import React, { useEffect, useState } from 'react';
import { formatPrice } from '../../common/formatPrice.js';

const FullPrice = ( { store } ) => {
	const [ state, dispatch ] = store;
	const [ price, setPrice ] = useState( 0 );
	const [ discount, setDiscount ] = useState( {
		discount: 0,
		discountType: '%',
		discountAmount: 0,
	} );
	const data = state.data;

	const attendees = state.data?.attendees;
	const couponId = state.data?.booking?.coupon;

	useEffect( () => {
		if ( ! attendees ) return;
		let price = 0;
		attendees.forEach( ( attendee ) => {
			price += parseInt( state.data.available_tickets[ attendee.ticket_id ].price );
		} );

		if ( couponId ) {
			const coupon = state.data.available_coupons.find( ( coupon ) => coupon.value === couponId );
			if ( coupon ) {
				const amount = coupon.discount;
				const originalPrice = price;
				price = coupon.type === '%' ? price - ( price * amount ) / 100 : price - amount;
				setDiscount( {
					discount: amount,
					discountType: coupon.type,
					discountAmount: originalPrice - price,
				} );
			}
		}

		price += state.data.booking.donation;
		setPrice( price );
	}, [ state ] );

	return (
		<>
			{ couponId && (
				<tr>
					<td colspan={ data.attendee_fields.length + 1 }>
						{ __( 'Discount', 'events' ) } ({ discount.discount }
						{ discount.discountType } )
					</td>
					<td>
						<div className="booking-discount">
							{ formatPrice( discount.discountAmount, state.data.l10n.currency ) }
						</div>
					</td>
				</tr>
			) }
			<tr>
				<td colspan={ data.attendee_fields.length + 1 }>
					<b>{ __( 'Full Price', 'events' ) }</b>
				</td>

				<td>
					<div className="booking-full-price">
						<b>{ formatPrice( price, state.data.l10n.currency ) }</b>
					</div>
				</td>
			</tr>
		</>
	);
};

export default FullPrice;
