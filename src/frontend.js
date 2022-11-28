import Upcoming from './upcoming'

import React from "react";
import ReactDOM from "react-dom";

document.addEventListener('DOMContentLoaded', () => {
	const upcomingBlocks = document.getElementsByClassName('events-upcoming-block');
	if(!upcomingBlocks) return
	Array.from(upcomingBlocks).forEach(element => {
		ReactDOM.render( <Upcoming block={element.dataset.id}/>, element );
	});
})