/**
 * External dependencies
 */

/**
 * WordPress dependencies
 */
import { useBlockProps, useInnerBlocksProps } from '@wordpress/block-editor';

export default function save( { attributes } ) {
	const { dividers, style } = attributes;

	const gapStyle = ! attributes.style?.spacing?.blockGap
		? {}
		: {
				gap: style.spacing?.blockGap.replaceAll( '|', '--' ).replace( ':', '(--wp--' ) + ')',
		  };

	const className = [ 'event-details', dividers ? 'has-dividers' : false ].filter( Boolean ).join( ' ' );

	const blockProps = useBlockProps.save( { className, style: gapStyle } );
	const innerBlocksProps = useInnerBlocksProps.save( blockProps );

	return <div { ...innerBlocksProps } />;
}
