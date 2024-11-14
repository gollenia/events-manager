const { React } = require( 'react' );
import { __ } from '@wordpress/i18n';
import PropTypes from 'prop-types';
import Coupon from './coupon';

import InputField from '../__experimantalForm/InputField';
import Summary from './summary';

const Payment = ( props ) => {
	const {
		state: { request, response, data },
		state,
		dispatch,
	} = props;

	const gatewayOptions = () => {
		const result = {};
		if ( data?.available_gateways == undefined ) return result;
		Object.keys( data.available_gateways ).forEach( ( id ) => {
			result[ id ] = data.available_gateways[ id ].title;
		} );
		return result;
	};

	return (
		<div className="grid xl:grid--columns-2 grid--gap-12">
			<div>
				<Summary state={ state } dispatch={ dispatch } />
			</div>
			<div>
				<form className="form--trap form grid xl:grid--columns-6 grid--gap-8">
					{ data?.event?.has_coupons && <Coupon state={ state } dispatch={ dispatch } /> }
					{ Object.keys( data.available_gateways ).length > 1 && (
						<InputField
							label={ __( 'Payment Method', 'events-manager' ) }
							options={ gatewayOptions() }
							name={ 'gateway' }
							onChange={ ( event ) => {
								dispatch( { type: 'SET_GATEWAY', payload: event } );
							} }
							type={ 'select' }
							value={ request.gateway }
							locale={ data.l10n.locale }
						/>
					) }
					{ data.allow_donation && (
						<div classNAme="donation">
							<h4>{ __( 'Donation', 'events-manager' ) }</h4>
							<p dangerouslySetInnerHTML={ { __html: data.l10n.donation } }></p>
							<InputField
								onChange={ ( event ) => {
									dispatch( {
										type: 'SET_FIELD',
										payload: { form: 'registration', field: 'donation_ok', value: event },
									} );
								} }
								type={ 'checkbox' }
								value={ request.registration.data_privacy_consent }
								name={ 'data_privacy_consent' }
								help={ __( 'Yes, I want to donate', 'events-manager' ) }
								locale={ data.l10n.locale }
							/>
							<InputField
								label={ __( 'Amount', 'events-manager' ) }
								onChange={ ( event ) => {
									dispatch( {
										type: 'SET_FIELD',
										payload: { form: 'registration', field: 'donation', value: event },
									} );
								} }
								type={ 'text' }
								pattern={ '^[0-9]+(.[0-9]{1,2})?$' }
								value={ request.donation }
								name={ 'donation' }
								locale={ data.l10n.locale }
							/>
						</div>
					) }
					{ data?.l10n?.consent && (
						<InputField
							onChange={ ( event ) => {
								dispatch( {
									type: 'SET_FIELD',
									payload: { form: 'registration', field: 'data_privacy_consent', value: event },
								} );
							} }
							type={ 'checkbox' }
							value={ request.registration.data_privacy_consent }
							name={ 'data_privacy_consent' }
							help={ data?.l10n?.consent }
							locale={ data.l10n.locale }
						/>
					) }

					{ response.error != '' && (
						<div
							class="alert bg-error text-white"
							dangerouslySetInnerHTML={ { __html: response.error } }
						></div>
					) }
				</form>
			</div>
		</div>
	);
};

Payment.propTypes = {
	gateways: PropTypes.array,
	coupons: PropTypes.array,
	eventData: PropTypes.object,
	nonce: PropTypes.string,
};

export default Payment;
