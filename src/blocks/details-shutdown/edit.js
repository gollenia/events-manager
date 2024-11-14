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

	const endFormatted = () => {
		return meta[ '_event_rsvp_end' ] ? formatDate( meta[ '_event_rsvp_end' ] ) : '';
	};

	const start = new Date( meta[ '_event_rsvp_start' ] );
	const end = new Date( meta[ '_event_rsvp_end' ] );
	const now = new Date();

	const bookingEnded = end < now;
	const bookingStarted = start < now;

	const startFormatted = () => {
		return meta[ '_event_rsvp_start' ] ? formatDate( meta[ '_event_rsvp_start' ] ) : '';
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
						placeholder={ __( 'Booking end', 'events-manager' ) }
						value={ description }
						onChange={ ( value ) => {
							setAttributes( { description: value } );
						} }
					/>
					<span className="event-details_audience description-editable">
						{ bookingEnded
							? 'Booking ended'
							: bookingStarted
							? `Booking ends ${ endFormatted() }`
							: 'Booking starts at ' + startFormatted() }
					</span>
				</div>
			</div>
		</div>
	);
};

export default edit;
