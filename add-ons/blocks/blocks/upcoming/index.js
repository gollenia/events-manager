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

import './style.scss';
import './editor.scss';

/**
 * Block constants
 */
const { name, category, attributes } = metadata;

const settings = {
	title: __( 'Upcoming Events', 'events-manager' ),
	description: __( 'Shows a list or cards of upcoming events', 'events-manager' ),
	icon: icons.posts,
	apiVersion: 2,
	keywords: [
		'events-manager',
		__( 'events', 'events-manager' ),
		__( 'list', 'events-manager' ),
	],
	attributes,
	edit: Edit,
	save() { return null; }
};


export { name, category, metadata, settings };