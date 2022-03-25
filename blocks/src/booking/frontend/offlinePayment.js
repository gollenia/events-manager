import React, { useEffect, useState } from 'react'
import { __ } from '@wordpress/i18n';
import SVG, { Props as SVGProps } from 'react-inlinesvg';

const OfflinePayment = (props) => {

	const {
		eventData: {
            strings
        },
        currentGateway: {
            id, title, methods, html
        },
		bookingId
    } = props

	if(!bookingId) return <></>;

	const [paymentInfo, setPaymentInfo] = useState({});

	useEffect(() => {
		fetch(`/wp-admin/admin-ajax.php?action=em_payment_info&booking_id=${bookingId}`).then((response) => response.json()).then((response) => {
			if(!response) {
				return false;
			}
			setPaymentInfo(response);
			return;
        })		
	}, []);

	if(Object.keys(paymentInfo).length == 0) {
		return (
			<div>
				<h4>{title}</h4>
				<p dangerouslySetInnerHTML={{__html: html}}></p>
			</div>
		);
	}

	return (
		<div>
			<h4>{title}</h4>
			<div className="grid md:grid--columns-3 xl:grid--columns-4 grid--gap-12">
				<div>
					<div className="card card--no-image card--shadow bg-white card--center">
						
						<div className="card__content">
							<div className="card__title">{__("Scan to pay", "events")}</div>
							<SVG className="w-full" src={`/wp-admin/admin-ajax.php?action=em_qr_code&booking_id=${bookingId}`}></SVG>
						</div>
					</div>
				</div>
				<div className='md:grid__column--span-2 xl:grid__column--span-3'>
					<p dangerouslySetInnerHTML={{__html: html}}></p>
					<table className="table--dotted">
						<tr><th className='text-left'>{__('Bank', 'events')}</th><td>{paymentInfo.bank}</td></tr>
						<tr><th className='text-left'>{__('IBAN', 'events')}</th><td>{paymentInfo.iban}</td></tr>
						<tr><th className='text-left'>{__('BIC', 'events')}</th><td>{paymentInfo.bic}</td></tr>
						<tr><th className='text-left'>{__('Beneficial', 'events')}</th><td>{paymentInfo.beneficiary}</td></tr>
						<tr><th className='text-left'>{__('Purpose', 'events')}</th><td>{paymentInfo.purpose}</td></tr>
						<tr><th className='text-left'>{__('Amount', 'events')}</th><td>{paymentInfo.amount} {strings.currency}</td></tr>
					</table>
				</div>
			</div>
		</div>
	);

}

export default OfflinePayment
