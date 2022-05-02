/**
 * Internal dependencies
 */
import Edit from './edit';
import icon from './icon';
import metadata from './block.json';
import './editor.scss';

/**
 * Wordpress dependencies
 */
import { __ } from '@wordpress/i18n'; 
import { withColors } from '@wordpress/block-editor';

const { name, title, description } = metadata;

const settings = {
	...metadata,
	title: __( title, 'events' ),
	description: __( description, 'events' ),
	icon,
	edit: withColors({buttonColor: 'buttonColor'})(Edit),
	save: () => { return null }
};

export { name, settings };