import { __ } from '@wordpress/i18n';

/*
 *   Show information about the currently selected gateway
 */
const Gateway = ( props ) => {
	const { state, dispatch } = props;
	const { request, data } = state;

	const { title, html, name, methods, description } = data.available_gateways[ request.gateway ];

	function createMarkup() {
		return { __html: description };
	}

	return (
		<div>
			<h2>{ __( 'Payment', 'events-manager' ) }</h2>
			<h4>{ title }</h4>
			<p dangerouslySetInnerHTML={ createMarkup() }></p>
			<div className="list">
				{ methods !== undefined &&
					Object.keys( methods ).map( ( method ) => {
						return (
							<li className={ `list__item ${ method }` } key={ method }>
								<img
									style={ { height: 'auto' } }
									className="list__image"
									src={ methods[ method ].image }
								/>
								<div className="list__content">
									<span className="list__title">{ methods[ method ].name }</span>
									<span className="list__subtitle">{ methods[ method ].description }</span>
								</div>
							</li>
						);
					} ) }
			</div>
		</div>
	);
};

export default Gateway;
