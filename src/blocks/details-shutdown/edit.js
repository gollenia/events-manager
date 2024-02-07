/**
 * Wordpress dependencies
 */
import { RichText, useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

import { useEntityProp } from '@wordpress/core-data';
import { formatDate } from '../../common/formatDate.js';
/**
 * Internal dependencies
 */

import Inspector from './inspector.js';
/**
 * @param {Props} props
 * @return {JSX.Element} Element
 */
const edit = ( props ) => {
	const {
		attributes: { roundImage, format, description },
		setAttributes,
	} = props;

	const postType = props.context.postType;

	if ( postType !== 'event' ) return <></>;

	const [ meta, setMeta ] = useEntityProp( 'postType', postType, 'meta' );

	const blockProps = useBlockProps( { className: 'event-details-item' } );

	const startFormatted = () => {
		return meta[ '_event_rsvp_date' ] ? formatDate( meta[ '_event_rsvp_date' ] ) : '';
	};

	return (
		<div { ...blockProps }>
			<Inspector { ...props } />

			<div className="event-details__item">
				<div className="event-details__icon">
					<i className="material-icons material-symbols-outlined">event_busy</i>
				</div>
				<div>
					<RichText
						tagName="h5"
						className="event-details_title description-editable"
						placeholder={ __( 'Booking end', 'events' ) }
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
