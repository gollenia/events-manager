import { __ } from '@wordpress/i18n';
import React, { useState } from 'react';

const ErrorFallback = ( { error, resetErrorBoundary } ) => {
	const [ errorSent, setErrorSent ] = useState( false );

	const request = {
		error,
	};

	return (
		<div className="alert bg-error" role="alert">
			<h4>{ __( 'An error occured in our booking system.', 'events' ) }</h4>
			<p>
				{ __( 'You may try it later again.', 'events' ) }{ ' ' }
				{ errorSent &&
					__( 'However our admin has been informed and will take care of the problem.', 'events' ) }
			</p>
		</div>
	);
};

export default ErrorFallback;
