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
import { InnerBlocks } from '@wordpress/block-editor';

const { name, title, description } = metadata;

const settings = {
	...metadata,
	icon,
	edit: Edit,
	save: () => {
		return <InnerBlocks.Content />;
	},
};

export { name, settings };
