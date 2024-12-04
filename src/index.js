/**
 * WordPress dependencies
 */
import { registerBlockType } from '@wordpress/blocks';
import { registerPlugin } from '@wordpress/plugins';

/**
 * Blocks dependencies.
 */
import * as booking from './blocks/booking/index.js';
import * as detailsAudience from './blocks/details-audience/index.js';
import * as detailsDate from './blocks/details-date/index.js';
import * as detailsItem from './blocks/details-item/index.js';
import * as detailsLocation from './blocks/details-location/index.js';
import * as detailsPrice from './blocks/details-price/index.js';
import * as detailsShutdown from './blocks/details-shutdown/index.js';
import * as detailsSpaces from './blocks/details-spaces/index.js';
import * as detailsSpeaker from './blocks/details-speaker/index.js';
import * as detailsTime from './blocks/details-time/index.js';
import * as details from './blocks/details/index.js';

import * as featured from './blocks/featured/index.js';

/**
 * Form dependencies.
 */
import * as formCheckbox from './blocks/form/checkbox/index.js';
import * as formContainer from './blocks/form/container/index.js';
import * as formCountry from './blocks/form/country/index.js';
import * as formDate from './blocks/form/date/index.js';
import * as formEmail from './blocks/form/email/index.js';
import * as formHTML from './blocks/form/html/index.js';
import * as formPhone from './blocks/form/phone/index.js';
import * as formRadio from './blocks/form/radio/index.js';
import * as formSelect from './blocks/form/select/index.js';
import * as formText from './blocks/form/text/index.js';
import * as formTextarea from './blocks/form/textarea/index.js';
import * as locationEditor from './blocks/location-editor/index.js';
import * as upcoming from './blocks/upcoming/index.js';

import datetimeSelector from './plugins/event/datetime.js';
import locationSelector from './plugins/event/location.js';
import peopleSelector from './plugins/event/people.js';
import recurrenceSettings from './plugins/event/recurrence.js';
import contactData from './plugins/speaker/contact.js';
import personalData from './plugins/speaker/personal.js';

const registerBlock = ( block ) => {
	if ( ! block ) return;
	const { name, settings } = block;
	registerBlockType( name, settings );
};

let blocks = [
	upcoming,
	booking,
	featured,
	formContainer,
	formText,
	formEmail,
	formTextarea,
	formDate,
	formCheckbox,
	formSelect,
	formCountry,
	formPhone,
	formRadio,
	formHTML,
	locationEditor,
	details,
	detailsAudience,
	detailsDate,
	detailsLocation,
	detailsSpeaker,
	detailsPrice,
	detailsItem,
	detailsTime,
	detailsShutdown,
	detailsSpaces,
];

let plugins = [];

registerPlugin( 'plugin-select-location', {
	icon: null,
	render: locationSelector,
} );

registerPlugin( 'plugin-select-datetime', {
	icon: null,
	render: datetimeSelector,
} );

registerPlugin( 'plugin-select-people', {
	icon: null,
	render: peopleSelector,
} );

registerPlugin( 'plugin-contact-data', {
	icon: null,
	render: contactData,
} );

registerPlugin( 'plugin-personal-data', {
	icon: null,
	render: personalData,
} );

registerPlugin( 'plugin-recurrence-settings', {
	icon: null,
	render: recurrenceSettings,
} );

const registerBlocks = () => {
	blocks.forEach( registerBlock );
};

registerBlocks();

import { unregisterBlockType } from '@wordpress/blocks';
import domReady from '@wordpress/dom-ready';

domReady( function () {
	if ( window.typenow !== 'event' ) unregisterBlockType( 'events/details' );
	if ( window.typenow !== 'event' ) unregisterBlockType( 'events/booking' );
	if ( window.typenow !== 'location' ) unregisterBlockType( 'events/locationeditor' );
	if ( window.typenow !== 'bookingform' && window.typenow !== 'attendeeform' )
		unregisterBlockType( 'events/form-container' );
} );
