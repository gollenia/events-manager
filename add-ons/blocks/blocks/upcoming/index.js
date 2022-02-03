/**
 * Internal dependencies
 */
import Edit from './edit';
import icons from './icons';
import metadata from './block.json';

/**
 * Wordpress dependencies
 */
const { __ } = wp.i18n; 
import { registerBlockType } from '@wordpress/blocks';

import './style.scss';
import './editor.scss';

/**
 * Block constants
 */
registerBlockType( 
	metadata, {
	title: metadata.title,
	icon: icons.posts,
	keywords: [
		'events-manager',
		__( 'events', 'events-manager' ),
		__( 'list', 'events-manager' ),
	],
	edit: Edit,
	save() { return null; }
	}
 );