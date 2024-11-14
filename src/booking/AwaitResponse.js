import { __ } from '@wordpress/i18n';
import React from 'react';
import { STATES } from './modules/constants';

const AwaitResponse = ( { state } ) => {
	const modal = state.modal;
	return (
		<aside>
			<div className="spinning-loader"></div>
			<h3>{ __( 'Please wait', 'events' ) }</h3>
			<h4>{ __( 'Your booking is beeing processed.', 'events' ) }</h4>
			{ modal.orderState > 1 && (
				<div className="alert alert--warning">
					{ modal.orderState == STATES.LOADING &&
						__( 'Please hang on a little longer. This can take a few seconds.', 'events' ) }
					{ modal.orderState == STATES.DELAY &&
						__( 'The request is lasting longer than expected. We try our best', 'events' ) }
					{ modal.orderState == STATES.HUGE_DELAY &&
						__(
							'Something seems to be wrong. Maybe your internet connection is interrupted or our server is overloaded. Please try again later',
							'events'
						) }
				</div>
			) }
		</aside>
	);
};

export default AwaitResponse;
