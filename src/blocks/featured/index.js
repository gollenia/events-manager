/**
 * Internal dependencies
 */
import metadata from './block.json';
import Edit from './edit';
import './editor.scss';
import icon from './icon';

/**
 * Wordpress dependencies
 */
import { __ } from '@wordpress/i18n';

const { name, title } = metadata;

const settings = {
	...metadata,
	title: __( title, 'events' ),
	icon,
	edit: Edit,
	save: () => {
		return null;
	},
};

export { name, settings };
