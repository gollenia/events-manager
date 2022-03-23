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

const { name, title } = metadata;

const settings = {
	...metadata,
	title: __( title, 'ctx-blocks' ),
	icon: icons.posts,
	edit: Edit,
	save: () => { return null }
};

export { name, settings };