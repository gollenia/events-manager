/**
 * WordPress dependencies
 */
import { registerBlockType } from '@wordpress/blocks';
import { registerPlugin } from '@wordpress/plugins';


/**
 * Blocks dependencies.
 */
import * as upcoming from './upcoming';
import * as featured from './featured';
import * as details from './details';
import * as booking from './booking/index.js';
import * as formContainer from './form/container/index.js';
import * as formText from './form/text/index.js';
import * as formEmail from './form/email/index.js';
import * as formTextarea from './form/textarea/index.js';
import * as formDate from './form/date/index.js';
import * as formCheckbox from './form/checkbox/index.js';
import * as formSelect from './form/select/index.js';
import * as formCountry from './form/country/index.js';
import * as formPhone from './form/phone/index.js';
import * as formRadio from './form/radio/index.js';
import * as formHTML from './form/html/index.js';

import bookingOptions from './plugins/event/booking'
import locationSelector from './plugins/event/location'
import datetimeSelector from './plugins/event/datetime'
import peopleSelector from './plugins/event/people'


const registerBlock = (block) => {
	if (!block) return;
	const { name, settings } = block;
	registerBlockType( name, settings );
};


let blocks = [
	upcoming,
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
	formHTML
];

let plugins = [

]

registerPlugin('plugin-select-people', {
	icon: null,
	render: peopleSelector,
});

registerPlugin('plugin-booking-options', {
	icon: null,
	render: bookingOptions,
});



if (window.eventBlocksLocalization?.post_type === 'event') {
	blocks = [...blocks, details, booking];
}

export const registerBlocks = () => {
	blocks.forEach(registerBlock);
};

registerBlocks();

