import { __ } from '@wordpress/i18n';
import React, { useEffect, useState } from 'react';

const ErrorFallback = ( { error, resetErrorBoundary } ) => {
	const [ errorSent, setErrorSent ] = useState( false );

	const request = {
		error,
	};

	const url = new URL( window.booking_data.rest_url );
	url.search = new URLSearchParams( request ).toString();
	
	useEffect( () => {
		fetch( url )
			.then( ( response ) => response.json() )
			.then( ( response ) => {
				console.log( response );
				if ( response.result ) {
					setErrorSent( true );
					return;
				}
			} );
	}, [] );
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
