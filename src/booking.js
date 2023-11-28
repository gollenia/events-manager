import { createRoot } from '@wordpress/element';
import Booking from './booking/index.js';
const rootElement = document.getElementById( 'booking_app' );

const bookingButton = document.getElementById( 'booking_button' );

if ( rootElement ) {
	const root = createRoot( document.getElementById( 'booking_app' ) );

	root.render( <Booking post={ rootElement.dataset.post } open={ false } />, root );

	bookingButton.addEventListener( 'click', () => {
		root.render( <Booking post={ rootElement.dataset.post } open={ true } />, root );
	} );
}
