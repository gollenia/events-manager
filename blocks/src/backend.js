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

let blocks = [
	upcoming,
	featured
];

if (window.eventBlocksLocalization?.post_type === 'event') {
	blocks = [...blocks, details, booking];
}

console.log(blocks);
console.log(window.eventBlocksLocalization?.post_type);


export const registerBlocks = () => {
	blocks.forEach(registerBlock);
};

registerBlocks();

