/**
 * WordPress dependencies
 */
import { registerBlockType } from '@wordpress/blocks';

/**
 * Blocks dependencies.
 */

import * as upcoming from './upcoming';
import * as featured from './featured';
import * as details from './details';
import * as booking from './booking/index.js';


const registerBlock = (block) => {
	if (!block) return;
	const { name, settings } = block;
	registerBlockType( name, settings );
};


export const registerBlocks = () => {
	[
		upcoming,
		featured,
		details,
		booking		
	].forEach(registerBlock);
};


registerBlocks();

