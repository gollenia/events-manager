import Upcoming from './upcoming';

import ReactDOM from 'react-dom';

document.addEventListener( 'DOMContentLoaded', () => {
	const upcomingBlocks = document.getElementsByClassName( 'events-upcoming-block' );
	if ( ! upcomingBlocks ) return;
	Array.from( upcomingBlocks ).forEach( ( element ) => {
		const attributes = JSON.parse( element.dataset.attributes );
		ReactDOM.render( <Upcoming attributes={ { ...attributes } } />, element );
	} );
} );
