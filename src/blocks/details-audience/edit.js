/**
 * Wordpress dependencies
 */
import { RichText, useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

import { select } from '@wordpress/data';

import { useEntityProp } from '@wordpress/core-data';
/**
 * Internal dependencies
 */

import Inspector from './inspector.js';

/**
 * @param {Props} props
 * @return {JSX.Element} Element
 */
const edit = ( props ) => {
	const postType = select( 'core/editor' ).getCurrentPostType();

	if ( postType !== 'event' ) return <></>;

	const [ meta, setMeta ] = useEntityProp( 'postType', postType, 'meta' );

	const {
		attributes: { roundImage, description },
		setAttributes,
	} = props;

	const blockProps = useBlockProps( { className: 'event-details-item' } );

	return (
		<div { ...blockProps }>
			<Inspector { ...props } />

			<div className="event-details__item">
				<div className="event-details__icon">
					<i className="material-icons material-symbols-outlined">family_restroom</i>
				</div>
				<div>
					<RichText
						tagName="h5"
						className="event-details_title description-editable"
						placeholder={ __( 'Audience', 'events-manager' ) }
						value={ description }
						onChange={ ( value ) => {
							setAttributes( { description: value } );
						} }
					/>
					<RichText
						tagName="span"
						className="event-details_audience description-editable"
						placeholder={ __( 'Enter audience', 'events-manager' ) }
						value={ meta._event_audience }
						onChange={ ( value ) => {
							setMeta( { _event_audience: value } );
						} }
					/>
				</div>
			</div>
		</div>
	);
};

export default edit;
