const { React, useMemo } = require( 'react' );
import { __ } from '@wordpress/i18n';
import Gateway from './gateway';
import { formatCurrency } from './modules/priceUtils';

function Summary( { state, dispatch } ) {
	const { data, response, request, wizard } = state;

	const [ TICKETS, REGISTRATION, PAYMENT, SUCCESS ] = [
		wizard.step == 0,
		wizard.step == 1,
		wizard.step == 2,
		wizard.step == 3,
	];

	const ticketCount = request.tickets.length;

	const ticketPrice = ( key, k = 0 ) => {
		return (
			data.available_tickets[ key ].price *
			request.tickets.reduce( ( n, ticket ) => {
				return n + ( ticket.id == data.available_tickets[ key ].id );
			}, 0 )
		);
	};

	const countTicketsById = ( id ) => {
		let count = state.request.tickets.reduce( ( n, ticket ) => {
			return n + ( ticket.id == id );
		}, 0 );
		return count;
	};

	const calculateFullPrice = () => {
		let sum = 0;
		for ( let ticket in data.available_tickets ) {
			sum += ticketPrice( ticket, 6 );
		}

		if ( ! response.coupon.success ) return sum;
		return response.coupon.percent
			? sum - ( sum / 100 ) * parseInt( response.coupon.discount )
			: sum - parseInt( response.coupon.discount );
	};

	const TICKETS_MISSING =
		( TICKETS && request.tickets.length == 0 ) ||
		( REGISTRATION && data.attendee_fields.length == 0 && request.tickets.length == 0 );

	const fullPrice = useMemo( () => calculateFullPrice(), [ response.coupon, ticketCount ] );

	return (
		<>
			<div className="list ticket-summary">
				{ Object.keys( data.available_tickets ).map( ( id, key ) => (
					<div className="list__item" key={ key }>
						<div className="list__content">
							<div className="list__title">{ data.available_tickets[ id ].name }</div>
							<div className="list__subtitle">{ data.available_tickets[ id ].description }</div>
							<div className="list__subtitle">
								{ __( 'Base price:', 'events-manager' ) }{ ' ' }
								{ formatCurrency(
									data.available_tickets[ id ].price,
									data.l10n.locale,
									data.l10n.currency
								) }
							</div>
						</div>

						<div className="list__actions">
							<span className="button button--pseudo nowrap">
								{ formatCurrency( ticketPrice( id ), data.l10n.locale, data.l10n.currency ) }
							</span>
							{ data.attendee_fields.length == 0 && (
								<div className="number-picker">
									<button
										className="button button--primary button--icon"
										onClick={ () => dispatch( { type: 'REMOVE_TICKET', payload: { id } } ) }
										disabled={ data.available_tickets[ id ].min == countTicketsById( id ) }
									></button>
									<input value={ countTicketsById( data.available_tickets[ id ].id ) } />
									<button
										className="button button--primary button--icon"
										onClick={ () => dispatch( { type: 'ADD_TICKET', payload: id } ) }
										disabled={ data.available_tickets[ id ].max == countTicketsById( id ) }
									></button>
								</div>
							) }
							{ data.attendee_fields.length > 0 && wizard.step == 0 && (
								<>
									<button
										className={ `button button--primary button--icon ${
											TICKETS_MISSING ? 'button--breathing' : ''
										}` }
										onClick={ () => dispatch( { type: 'ADD_TICKET', payload: id } ) }
									>
										<i className="material-icons material-symbols-outlined">add_circle</i>
									</button>
								</>
							) }
						</div>
					</div>
				) ) }
				{ response.coupon.success && (
					<div className="list__item">
						<div className="list__content">
							<div className="list__title">
								{ response.coupon.description || __( 'Coupon', 'events-manager' ) }
							</div>
						</div>
						<div className="list__actions">
							<b className="button button--pseudo nowrap">
								{ response.coupon.percent
									? response.coupon.discount + ' %'
									: formatCurrency( response.coupon.discount, data.l10n.locale, data.l10n.currency ) }
							</b>
							{ data.attendee_fields.length == 0 && (
								<div className="number-picker invisible">
									<button className="button button--primary button--icon"></button>
									<input />
									<button className="button button--primary button--icon"></button>
								</div>
							) }
						</div>
					</div>
				) }
				<div className="list__item">
					<div className="list__content">
						<div className="list__title">
							<b>{ __( 'Full price', 'events-manager' ) }</b>
						</div>
					</div>
					<div className="list__actions">
						<b className="button button--pseudo nowrap">
							{ formatCurrency( fullPrice, data.l10n.locale, data.l10n.currency ) }
						</b>
						{ data.attendee_fields.length == 0 && (
							<div className="number-picker invisible">
								<button className="button button--primary button--icon"></button>
								<input />
								<button className="button button--primary button--icon"></button>
							</div>
						) }
						{ wizard.step == 0 && (
							<button className="button button--primary button--icon invisible">
								<i className="material-icons material-symbols-outlined">add_circle</i>
							</button>
						) }
					</div>
				</div>
			</div>
			{ wizard.step == 2 && (
				<div>
					<Gateway state={ state } />
				</div>
			) }
		</>
	);
}

export default Summary;
