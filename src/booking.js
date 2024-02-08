import { createRoot } from 'react-dom';
import Booking from './booking/index.js';
import { publish } from './booking/modules/events.js';
const rootElement = document.getElementById( 'booking_app' );

const bookingButtons = document.getElementsByClassName( 'wp-block-events-manager-booking' );

if ( rootElement ) {
	const root = createRoot( document.getElementById( 'booking_app' ) );

	root.render( <Booking post={ rootElement.dataset.post } open={ false } />, root );

	for ( let item of bookingButtons ) {
		item.addEventListener( 'click', () => {
			publish( 'showBooking', true );
		} );
	}
}
