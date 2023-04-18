/**
 * WordPress dependencies
 */
import { registerBlockType } from '@wordpress/blocks';
import { registerPlugin } from '@wordpress/plugins';

/**
 * Blocks dependencies.
 */
import * as booking from './blocks/booking/index.js';
import * as details from './blocks/details';
import * as featured from './blocks/featured';
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
import * as locationEditor from './blocks/location';
import * as upcoming from './blocks/upcoming';

import bookingOptions from './plugins/event/booking';
import datetimeSelector from './plugins/event/datetime';
import locationSelector from './plugins/event/location';
import peopleSelector from './plugins/event/people';

const registerBlock = (block) => {
	if (!block) return;
	const { name, settings } = block;
	registerBlockType(name, settings);
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
	formHTML,
	locationEditor,
];

let plugins = [];

registerPlugin('plugin-location-datetime', {
	icon: null,
	render: locationSelector,
});

registerPlugin('plugin-select-datetime', {
	icon: null,
	render: datetimeSelector,
});

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
