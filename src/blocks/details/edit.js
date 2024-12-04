/**
 * Internal dependencies
 */
import Inspector from './inspector';

/**
 * WordPress dependencies
 */
import { useBlockProps, useInnerBlocksProps } from '@wordpress/block-editor';

export default function Edit( { ...props } ) {
	const allowedBlocks = [
		'events/details-audience',
		'events/details-time',
		'events/details-speaker',
		'events/details-spaces',
		'events/details-shutdown',
		'events/details-date',
		'events/details-item',
		'events/details-price',
		'events/details-audience',
		'events/details-location',
	];

	const {
		attributes: { dividers },
		className,
	} = props;

	const classes = [ 'event-details', className, dividers ? 'has-divider' : false ].filter( Boolean ).join( ' ' );

	const template = [
		[ 'events/details-audience' ],
		[ 'events/details-date' ],
		[ 'events/details-time' ],
		[ 'events/details-speaker' ],
		[ 'events/details-location' ],
		[ 'events/details-price' ],
		[ 'events/details-spaces' ],
		[ 'events/details-shutdown' ],
	];

	const blockProps = useBlockProps( { className: classes } );

	const innerBlocksProps = useInnerBlocksProps( {}, { allowedBlocks, template, templateLock: false } );

	return (
		<div { ...blockProps }>
			<Inspector { ...props } />
			<div { ...innerBlocksProps }></div>
		</div>
	);
}
