import eventData from './eventData';

const fullPrice = ( coupon ) => {
	let sum = 0;

	for ( let ticket in eventData.tickets ) {
		sum += ticketPrice( ticket );
	}

	if ( ! coupon.success ) return sum;
	return coupon.percent ? sum - parseInt( coupon.discount ) : sum - ( sum / 100 ) * parseInt( coupon.discount );
};

const formatCurrency = ( price ) => {
	return new Intl.NumberFormat( eventData.l10n.locale, {
		style: 'currency',
		currency: eventData.l10n.currency,
	} ).format( price );
};

const ticketPrice = ( key, appState ) => {
	return (
		eventData.available_tickets[ key ].price *
		appState.request.tickets.reduce( ( n, ticket ) => {
			return n + ( ticket.id == eventData.available_tickets[ key ].id );
		}, 0 )
	);
};

export { fullPrice, ticketPrice, formatCurrency };
