import { __ } from '@wordpress/i18n';

/*
 *   Simple renderer for a given gateway
 */
const Gateway = ( props ) => {
	const { state, dispatch } = props;
	const { request, data } = state;

	const { title, html, name, methods } = data.available_gateways[ request.gateway ];

	function createMarkup() {
		return { __html: html };
	}

	return (
		<div>
			<h5>{ __( 'Payment', 'events' ) }</h5>
			<h5>{ title }</h5>
			<p dangerouslySetInnerHTML={ createMarkup() }></p>
			<div className="description">
				{ methods !== undefined &&
					Object.keys( methods ).map( ( method ) => {
						return (
							<li className={ `description__item ${ method }` } key={ method }>
								<img src={ '/wp-content/plugins/events-mollie/assets/methods/' + method + '.svg' } />{ ' ' }
								{ methods[ method ] }
							</li>
						);
					} ) }
			</div>
		</div>
	);
};

export default Gateway;
