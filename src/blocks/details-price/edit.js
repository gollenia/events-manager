/**
 * Wordpress dependencies
 */
import apiFetch from '@wordpress/api-fetch';
import { RichText, useBlockProps } from '@wordpress/block-editor';
import { useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */

import Inspector from './inspector.js';

/**
 * @param {Props} props
 * @return {JSX.Element} Element
 */
const edit = ( props ) => {
	const { roundImage, format, description, overwritePrice } = props.attributes;
	const { postType, postId } = props.context;
	const setAttributes = props.setAttributes;
	const [ price, setPrice ] = useState( '' );

	if ( postType !== 'event' ) return <></>;

	const blockProps = useBlockProps( { className: 'event-details-item' } );

	apiFetch( { path: `/events/v2/bookinginfo/${ postId }` } ).then( ( data ) => {
		setPrice( data.data?.formatted_price.format );
	} );

	return (
		<div { ...blockProps }>
			<Inspector { ...props } />

			<div className="event-details__item">
				<div className="event-details__icon">
					<i className="material-icons">savings</i>
				</div>
				<div>
					<RichText
						tagName="h5"
						className="event-details_title description-editable"
						placeholder={ __( 'Price', 'events' ) }
						value={ description }
						onChange={ ( value ) => {
							setAttributes( { description: value } );
						} }
					/>
					<RichText
						tagName="span"
						className="event-details_audience description-editable"
						placeholder={ price ?? __( 'Free', 'events' ) }
						value={ overwritePrice }
						onChange={ ( value ) => {
							setAttributes( { overwritePrice: value } );
						} }
					/>
				</div>
			</div>
		</div>
	);
};

export default edit;
