import apiFetch from '@wordpress/api-fetch';

const BookingsTable = () => {
	const setNewStatus = ( row, statusText, status ) => {
		const statusCell = row.querySelector( '.em-bookings-col-booking_status' );

		let label = document.createElement( 'span' );
		let icon = document.createElement( 'i' );

		const icons = [
			'pending',
			'check_circle',
			'check_circle',
			'block',
			'pan_tool',
			'overview',
			'overview',
			'credit_card_clock',
		];

		icon.classList.add( 'material-symbols-outlined' );
		icon.innerHTML = icons[ status ];
		label.classList.add( [ `em-label em-label-${ status }` ] );
		label.innerHTML = statusText;
		label.prepend( icon );
		statusCell.innerHTML = label.outerHTML;
	};
	document.addEventListener( 'DOMContentLoaded', () => {
		const table = document.querySelector( '.em-bookings-table' );
		const tableBody = table.querySelector( 'tbody' );
		console.log( tableBody );
		document.addEventListener( 'click', ( event ) => {
			if ( event.target.classList.contains( 'em-bookings-action' ) ) {
				const data = event.target.dataset;
				if ( ! data.action ) {
					return;
				}
				const id = data.bookingId;
				console.log( id );
				apiFetch( {
					path: `/events/v2/booking/${ id }`,
					method: 'PUT',
					data: { action: data.action },
				} ).then( ( response ) => {
					console.log( response );
					const row = event.target.closest( 'tr' );
					if ( data.action === 'delete' ) {
						row.remove();
						return;
					}
					row.classList.add( data.action );
					setNewStatus( row, response.status_text, response.status );
				} );
			}
		} );
	} );
};

export { BookingsTable };
