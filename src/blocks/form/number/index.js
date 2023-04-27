/**
 * Internal dependencies
 */
import metadata from './block.json';
import Edit from './edit';
import './editor.scss';
import icons from './icons';

/**
 * Wordpress dependencies
 */
import { __ } from '@wordpress/i18n';

const { name, title, description } = metadata;

const settings = {
	...metadata,
	title: __( title, 'gutenberg-form' ),
	description: __( description, 'gutenberg-form' ),
	icon: icons.icon,
	edit: Edit,
	save: () => {
		return null;
	},
};

export { name, settings };
