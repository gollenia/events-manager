import React, { useEffect, useState } from 'react'
import { __ } from '@wordpress/i18n';

const OfflinePayment = (props) => {

	const {
        currentGateway: {
            id, title, methods, html
        },
		bookingId
    } = props

	if(!bookingId) return <></>;

	const [paymentInfo, setPaymentInfo] = useState({});

	useEffect(() => {
		fetch(`/wp-admin/admin-ajax.php?action=em_payment_info&booking_id=${bookingId}`).then((response) => response.json()).then((response) => {
			console.log(response)
			if(!response) {
				return;
			}
			setPaymentInfo(response);
			return;
        })		
	}, [])

	return (
		<div>
			<h4>{title}</h4>
			<div className="grid md:grid--columns-3 xl:grid--columns-4">
				<div><img src={`/wp-admin/admin-ajax.php?action=em_qr_code&booking_id=${bookingId}`}></img></div>
				<div className='md:grid__column--span-2 xl:grid__column--span-3'>
					<p dangerouslySetInnerHTML={{__html: html}}></p>
					<table>
						<tr><td>{__('Bank', 'em-pro')}</td><td>{paymentInfo.bank}</td></tr>
						<tr><td>{__('IBAN', 'em-pro')}</td><td>{paymentInfo.iban}</td></tr>
						<tr><td>{__('BIC', 'em-pro')}</td><td>{paymentInfo.bic}</td></tr>
						<tr><td>{__('Beneficial', 'em-pro')}</td><td>{paymentInfo.beneficiary}</td></tr>
						<tr><td>{__('Purpose', 'em-pro')}</td><td>{paymentInfo.purpose}</td></tr>
					</table>
				</div>
			</div>
		</div>
	)
}

export default OfflinePayment
