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
import { InnerBlocks } from '@wordpress/block-editor';

const { name, title, description } = metadata;

const settings = {
	...metadata,
	title: __( title, 'events' ),
	description: __( description, 'events' ),
	icon,
	edit: Edit,
	save: () => { return ( <InnerBlocks.Content /> ); }
};

export { name, settings };


