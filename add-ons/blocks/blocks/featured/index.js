/**
 * Internal dependencies
 */
 import Edit from './edit';
 import icon from './icon';
 import metadata from './block.json';
 
 /**
  * Wordpress dependencies
  */
 const { __ } = wp.i18n; 
 import { registerBlockType } from '@wordpress/blocks';
 

 import './editor.scss';
 
 /**
  * Block constants
  */
 registerBlockType( 
	 metadata, {
	 title: "Featured Event",
	 icon,
	 keywords: [
		 'events-manager',
		 __( 'events', 'events-manager' ),
		 __( 'list', 'events-manager' ),
	 ],
	 edit: Edit,
	 save() { return null; }
	 }
  );