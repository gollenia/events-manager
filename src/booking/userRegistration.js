import React from 'react';
import InputField from './inputField';
import Summary from './summary';

const UserRegistration = ( props ) => {
	const { countTickets, state, dispatch } = props;

	const { error } = state.response;
	const { registration } = state.request;

	const { data, request, response } = state;

	if ( ! data.registration_fields ) return <></>;
	return (
		<div className="grid xl:grid--columns-2 grid--gap-12">
			<Summary { ...props } />
			<div>
				<form className="form--trap form grid xl:grid--columns-6 grid--gap-8" id="user-registration-form">
					{ data.registration_fields.map( ( field, key ) => (
						<InputField
							key={ key }
							field={ field }
							value={ state.request.registration[ field.fieldid ] }
							onChange={ ( event ) => {
								dispatch( {
									type: 'SET_FIELD',
									payload: { form: 'registration', field: field.fieldid, value: event },
								} );
							} }
						/>
					) ) }
					{ data.event.is_free && data.l10n.consent && (
						<InputField
							onChange={ ( event ) => {
								dispatch( {
									type: 'SET_FIELD',
									payload: { form: 'registration', field: 'data_privacy_consent', value: event },
								} );
							} }
							value={ request.registration.data_privacy_consent }
							field={ {
								name: 'data_privacy_consent',
								help: data.l10n.consent,
								type: 'checkbox',
							} }
						/>
					) }

					{ data.event.is_free && error != '' && (
						<div class="alert bg-error text-white" dangerouslySetInnerHTML={ { __html: error } }></div>
					) }
				</form>
			</div>
		</div>
	);
};

export default UserRegistration;
