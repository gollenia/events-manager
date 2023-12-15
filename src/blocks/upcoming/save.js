import { useBlockProps } from '@wordpress/block-editor';

function SaveUpcoming( props ) {
	const blockProps = useBlockProps.save( { className: 'events-upcoming-block' } );

	const classes = [ 'events-upcoming-block' ].join( ' ' );

	const attributes = props.attributes;
	const jsonAttributes = JSON.stringify( attributes );
	return <div { ...blockProps } className={ classes } data-attributes={ jsonAttributes }></div>;
}

export default SaveUpcoming;
