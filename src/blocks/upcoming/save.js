import { useBlockProps } from '@wordpress/block-editor';

function SaveUpcoming( props ) {
	const blockProps = useBlockProps.save( { className: 'events-upcoming-block' } );
	console.log( blockProps );
	const attributes = props.attributes;
	const jsonAttributes = JSON.stringify( attributes );
	return <div { ...blockProps } data-attributes={ jsonAttributes }></div>;
}

export default SaveUpcoming;
