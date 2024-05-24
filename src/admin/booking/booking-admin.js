import React from 'react';
import ReactDOM from 'react-dom';
import BookingEditor from './BookingEditor';
console.log( 'BookingAdmin' );
function BookingAdmin() {
	document.addEventListener( 'DOMContentLoaded', () => {
		const rootElement = document.getElementById( 'booking-admin' );
		if ( ! rootElement ) return;

		const bookingId = rootElement.dataset.id || 0;
		if ( ! bookingId ) return;

		const bookingEditor = ReactDOM.createRoot( rootElement );

		bookingEditor.render( <BookingEditor bookingId={ bookingId } /> );
	} );
}
export { BookingAdmin };
