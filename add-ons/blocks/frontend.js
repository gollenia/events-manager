import Upcoming from './blocks/upcoming/frontend'

import React from "react";
import ReactDOM from "react-dom";

document.addEventListener('DOMContentLoaded', () => {
	const rootElements = document.getElementsByClassName('events-upcoming-block');
	if(!rootElements) return
	Array.from(rootElements).forEach(element => {
		ReactDOM.render( <Upcoming block={element.dataset.id}/>, element );
	});
	
})