import { __ } from '@wordpress/i18n';
import { React, useState } from 'react';
import { STATES } from './modules/constants';

function Coupon( { state, dispatch } ) {
	const { data } = state;

	const [ status, setStatus ] = useState( STATES.IDLE );

	const checkCouponCode = async () => {
		setStatus( STATES.LOADING );
		const params = {
			event_id: data.event.event_id,
			code: state.request.coupon,
		};

		const url = new URL( data.rest_url + 'events/v2/check_coupon' );
		url.search = new URLSearchParams( params ).toString();

		await fetch( url, {} )
			.then( ( response ) => response.json() )
			.then( ( response ) => {
				setStatus( STATES.LOADING );
				if ( response.success ) {
					dispatch( { type: 'COUPON_RESPONSE', payload: response } );
					setStatus( STATES.SUCCESS );
					return;
				}
				setStatus( STATES.ERROR );
				setTimeout( () => {
					setStatus( STATES.IDLE );
				}, 3000 );
			} );
	};

	const setCouponCode = ( code ) => {
		setStatus( STATES.IDLE );
		dispatch( { type: 'SET_COUPON', payload: code } );
	};

	const buttonClass = [
		'button',
		status < STATES.LOADING ? 'button--primary' : 'button--icon',
		status == STATES.LOADING ? 'button--loading' : false,
		status == STATES.ERROR ? 'button--error' : false,
		status == STATES.SUCCESS ? 'button--success' : false,
		status == STATES.IDLE && state.request.coupon != '' ? 'button--breathing' : false,
	]
		.filter( Boolean )
		.join( ' ' );

	return (
		<div className="input-group grid__column--span-6">
			<div className="input input-group__main">
				<label>{ __( 'Coupon code', 'events' ) }</label>
				<input
					value={ state.request.coupon }
					onChange={ ( event ) => {
						setCouponCode( event.target.value );
					} }
					disabled={ status === STATES.LOADING || status === STATES.SUCCESS }
					type="text"
					label="coupon"
					name="coupon_code"
				/>
			</div>
			<button
				type="button"
				disabled={ state.request.coupon.length < 1 }
				onClick={ () => {
					checkCouponCode();
				} }
				className={ buttonClass }
			>
				{ status < STATES.LOADING && __( 'Check coupon', 'events' ) }
				{ status == STATES.ERROR && <i class="material-icons material-symbols-outlined">close</i> }
				{ status == STATES.SUCCESS && <i class="material-icons material-symbols-outlined">done</i> }
			</button>
		</div>
	);
}

export default Coupon;
