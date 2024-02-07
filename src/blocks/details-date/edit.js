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
import { formatDateRange } from './../../common/formatDate';
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
		attributes: { roundImage, format, description },
		setAttributes,
	} = props;

	const blockProps = useBlockProps( { className: 'event-details-item' } );

	const startFormatted = () => {
		return meta?._event_start_date && meta?._event_end_date
			? formatDateRange( meta?._event_start_date, meta?._event_end_date )
			: '';
	};

	return (
		<div { ...blockProps }>
			<Inspector { ...props } />

			<div className="event-details__item">
				<div className="event-details__icon">
					<i className="material-icons material-symbols-outlined">event</i>
				</div>
				<div>
					<RichText
						tagName="h5"
						className="event-details_title description-editable"
						placeholder={ __( 'Date', 'events' ) }
						value={ description }
						onChange={ ( value ) => {
							setAttributes( { description: value } );
						} }
					/>
					<span className="event-details_audience description-editable">{ startFormatted() }</span>
				</div>
			</div>
		</div>
	);
};

export default edit;
