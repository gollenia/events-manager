import { __ } from '@wordpress/i18n';
import React from 'react';
import SVG from 'react-inlinesvg';
import { formatCurrency } from './modules/priceUtils';

/**
 * Show offline payment instructions
 */
const Success = ( props ) => {
	const { state, dispatch } = props;

	const { request, response, data } = state;

	const { booking } = response;
	const { gateway } = booking;

	if ( ! response.booking.booking_id ) return null;

	if ( data.event.is_free ) {
		return (
			<>
				<p dangerouslySetInnerHTML={ { __html: response.booking.message } }></p>
			</>
		);
	}

	if ( gateway.url ) {
		return (
			<div>
				<h4>{ gateway.title }</h4>
				<p dangerouslySetInnerHTML={ { __html: gateway.message } }></p>
			</div>
		);
	}

	const formatIBAN = ( iban ) => {
		if ( ! iban ) return '';
		return iban.replace( /\s/g, '' ).replace( /(.{4})/g, '$1 ' );
	};

	return (
		<div style={ { padding: '1rem' } }>
			<h2>{ gateway.title }</h2>
			<div className="grid md:grid--columns-3 xl:grid--columns-4 grid--gap-12">
				<div>
					<div className="iban-scan">
						<div className="card__content">
							<h2>{ __( 'Scan to pay', 'events-manager' ) }</h2>
							<h4>{ __( 'Scan the QR-Code with your phone to start payment', 'events-manager' ) }</h4>
							<SVG
								className="w-full"
								src={ `/wp-admin/admin-ajax.php?action=em_qr_code&booking_id=${ booking.booking_id }` }
							></SVG>
						</div>
					</div>
				</div>
				<div className="md:grid__column--span-2 xl:grid__column--span-3">
					<p dangerouslySetInnerHTML={ { __html: gateway.message } }></p>
					<table className="table--dotted">
						<tr>
							<th className="text-left">{ __( 'Bank', 'events-manager' ) }</th>
							<td>{ gateway.bank }</td>
						</tr>
						<tr>
							<th className="text-left">{ __( 'IBAN', 'events-manager' ) }</th>
							<td>{ formatIBAN( gateway.iban ) }</td>
						</tr>
						<tr>
							<th className="text-left">{ __( 'BIC', 'events-manager' ) }</th>
							<td>{ gateway.bic }</td>
						</tr>
						<tr>
							<th className="text-left">{ __( 'Beneficial', 'events-manager' ) }</th>
							<td>{ gateway.beneficiary }</td>
						</tr>
						<tr>
							<th className="text-left">{ __( 'Purpose', 'events-manager' ) }</th>
							<td>{ gateway.purpose }</td>
						</tr>
						<tr>
							<th className="text-left">{ __( 'Amount', 'events-manager' ) }</th>
							<td>{ formatCurrency( gateway.amount, data.l10n.locale, data.l10n.currency ) }</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	);
};

export default Success;
