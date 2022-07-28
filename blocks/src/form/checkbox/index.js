/**
 * Internal dependencies
 */
import Edit from './edit';
import icons from './icons';
import metadata from './block.json';
import './editor.scss';

/**
 * Wordpress dependencies
 */
import { __ } from '@wordpress/i18n'; 

const { name, title, description } = metadata;

const settings = {
	...metadata,
	title: __( title, 'events' ),
	description: __( description, 'events' ),
	icon: icons.checkbox,
	edit: Edit,
	save: () => { return null }
};

export { name, settings };