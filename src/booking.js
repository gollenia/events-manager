import ReactDOM from 'react-dom';
import Booking from './booking/index.js';
const root = document.getElementById( 'booking_app' );

if ( root ) {
	document.addEventListener( 'DOMContentLoaded', () => {
		ReactDOM.render( <Booking />, root );
	} );
}
