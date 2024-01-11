import { __ } from '@wordpress/i18n';
import React, { useEffect, useState } from 'react';
import SVG from 'react-inlinesvg';
import { formatCurrency } from './modules/priceUtils';

/**
 * Show offline payment instructions
 */
const Success = ( props ) => {
	const { state, dispatch } = props;

	const { request, response, data } = state;

	if ( ! response.booking.result ) return null;

	if ( data.event.is_free ) {
		return (
			<>
				<p dangerouslySetInnerHTML={ { __html: response.booking.message } }></p>
			</>
		);
	}

	const [ paymentInfo, setPaymentInfo ] = useState( {
		success: false,
		error: false,
	} );

	const gateway = data.available_gateways[ request.gateway ];

	useEffect( () => {
		fetch( `/wp-admin/admin-ajax.php?action=em_payment_info&booking_id=${ response.booking.booking_id }` )
			.then( ( apiResponse ) => apiResponse.json() )
			.then( ( apiResponse ) => {
				if ( ! apiResponse ) {
					setPaymentInfo( {
						success: false,
						error: 'Could not load payment info',
					} );
				}
				setPaymentInfo( apiResponse );
				return;
			} );
	}, [] );

	if ( paymentInfo.error ) {
		console.log( 'error: ', paymentInfo.error );
	}

	if ( Object.keys( paymentInfo ).length == 0 ) {
		return (
			<div>
				<h4>{ gateway.title }</h4>
				<p dangerouslySetInnerHTML={ { __html: gateway.html } }></p>
			</div>
		);
	}

	const formatIBAN = ( iban ) => {
		if ( ! iban ) return '';
		return iban.replace( /\s/g, '' ).replace( /(.{4})/g, '$1 ' );
	};

	return (
		<div style={ { paddingBottom: '1rem' } }>
			<h4>{ gateway.title }</h4>
			<div className="grid md:grid--columns-3 xl:grid--columns-4 grid--gap-12">
				<div>
					<div className="iban-scan">
						<div className="card__content">
							<h4>{ __( 'Scan to pay', 'events' ) }</h4>
							<SVG
								className="w-full"
								src={ `/wp-admin/admin-ajax.php?action=em_qr_code&booking_id=${ response.booking.booking_id }` }
							></SVG>
						</div>
					</div>
				</div>
				<div className="md:grid__column--span-2 xl:grid__column--span-3">
					<p dangerouslySetInnerHTML={ { __html: gateway.html } }></p>
					<table className="table--dotted">
						<tr>
							<th className="text-left">{ __( 'Bank', 'events' ) }</th>
							<td>{ paymentInfo.bank }</td>
						</tr>
						<tr>
							<th className="text-left">{ __( 'IBAN', 'events' ) }</th>
							<td>{ formatIBAN( paymentInfo.iban ) }</td>
						</tr>
						<tr>
							<th className="text-left">{ __( 'BIC', 'events' ) }</th>
							<td>{ paymentInfo.bic }</td>
						</tr>
						<tr>
							<th className="text-left">{ __( 'Beneficial', 'events' ) }</th>
							<td>{ paymentInfo.beneficiary }</td>
						</tr>
						<tr>
							<th className="text-left">{ __( 'Purpose', 'events' ) }</th>
							<td>{ paymentInfo.purpose }</td>
						</tr>
						<tr>
							<th className="text-left">{ __( 'Amount', 'events' ) }</th>
							<td>{ formatCurrency( paymentInfo.amount, data.l10n.locale, data.l10n.currency ) }</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	);
};

export default Success;
