const { React, useState } = require( 'react' );
import { __ } from '@wordpress/i18n';

function Coupon( { state, dispatch } ) {
	const { data } = state;

	const [ INIT, READY, LOADING, SUCCESS, ERROR ] = [ 0, 1, 2, 3, 4 ];

	const [ status, setStatus ] = useState( INIT );

	const checkCouponCode = async () => {
		setStatus( LOADING );
		const params = {
			event_id: data.event.event_id,
			code: state.request.coupon,
		};

		const url = new URL( data.rest_url + 'events/v2/check_coupon' );
		url.search = new URLSearchParams( params ).toString();

		await fetch( url, {} )
			.then( ( response ) => response.json() )
			.then( ( response ) => {
				setStatus( LOADING );
				if ( response.success ) {
					dispatch( { type: 'COUPON_RESPONSE', payload: response } );
					setStatus( SUCCESS );
					return;
				}
				setStatus( ERROR );
				setTimeout( () => {
					setStatus( READY );
				}, 3000 );
			} );
	};

	const setCouponCode = ( code ) => {
		setStatus( code == '' ? INIT : READY );
		dispatch( { type: 'SET_COUPON', payload: code } );
	};

	const buttonClass = [
		'button',
		status < LOADING ? 'button--primary' : 'button--icon',
		status == LOADING ? 'button--loading' : false,
		status == ERROR ? 'button--error' : false,
		status == SUCCESS ? 'button--success' : false,
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
					type="text"
					label="coupon"
					name="coupon_code"
				/>
			</div>
			<button
				type="button"
				disabled={ status == INIT }
				onClick={ () => {
					checkCouponCode();
				} }
				className={ buttonClass }
			>
				{ status < LOADING && __( 'Check coupon', 'events' ) }
				{ status == ERROR && <i class="material-icons material-symbols-outlined">close</i> }
				{ status == SUCCESS && <i class="material-icons material-symbols-outlined">done</i> }
			</button>
		</div>
	);
}

export default Coupon;
