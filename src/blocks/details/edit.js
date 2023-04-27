/**
 * Wordpress dependencies
 */
import { useBlockProps } from '@wordpress/block-editor';
import { useSelect } from '@wordpress/data';
import { useEffect, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

import { select } from '@wordpress/data';

import { useEntityProp } from '@wordpress/core-data';
/**
 * Internal dependencies
 */
import { formatDate, formatDateRange, formatTime } from './formatDate';
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
		attributes: {
			showLocation,
			showAudience,
			showDate,
			showBookingEnd,
			showTime,
			showSpeaker,
			showPrice,
			audienceIcon,
			audienceDescription,
			speakerIcon,
			priceOverwrite,
			showBookedUp,
		},
	} = props;

	const [ event, setEvent ] = useState( false );

	const post_id = useSelect( ( select ) => {
		return select( 'core/editor' ).getCurrentPostId();
	}, [] );

	const getUrl = ( params = '' ) => {
		const base = window.eventBlocksLocalization?.rest_url;
		if ( base === undefined ) return;
		return base + ( base.includes( '?' ) ? '&' : '?' ) + params;
	};

	useEffect( () => {
		const url = getUrl( `post_id=${ post_id }` );
		fetch( url )
			.then( ( response ) => response.json() )
			.then( ( data ) => setEvent( data[ 0 ] ) );
	}, [] );

	const blockProps = useBlockProps( {
		className: [ 'ctx:event-details' ].filter( Boolean ).join( ' ' ),
	} );

	console.log( meta );

	const startFormatted = () => {
		return event?.start && event?.end ? formatDateRange( event.start, event.end ) : '';
	};

	const timeFormatted = () => {
		return event?.start ? formatTime( event.start ) : '';
	};

	return (
		<div { ...blockProps }>
			<Inspector { ...props } />
			<div className="ctx:event-details__wrapper">
				{ showAudience && (
					<div className="ctx:event-details__item">
						<i className="material-icons">{ audienceIcon }</i>
						<div>
							<h5>{ audienceDescription != '' ? audienceDescription : __( 'Audience', 'events' ) }</h5>
							{ event?.audience ?? __( 'no data' ) }
						</div>
					</div>
				) }
				{ showLocation && (
					<div className="ctx:event-details__item">
						<i className="material-icons">place</i>
						<div>
							<h5>{ __( 'Location', 'events' ) }</h5>
							{ event?.location?.address }
						</div>
					</div>
				) }
				{ showDate && (
					<div className="ctx:event-details__item">
						<i className="material-icons">today</i>
						<div>
							<h5>{ __( 'Date', 'events' ) }</h5>
							{ startFormatted() }
						</div>
					</div>
				) }
				{ showTime && (
					<div className="ctx:event-details__item">
						<i className="material-icons">schedule</i>
						<div>
							<h5>{ __( 'Time', 'events' ) }</h5>
							{ timeFormatted() }
						</div>
					</div>
				) }
				{ showSpeaker && (
					<div className="ctx:event-details__item">
						{ speakerIcon == '' && (
							<img
								className="ctx:event-details__image"
								src={ event?.speaker?.image?.sizes?.thumbnail?.url }
							/>
						) }{ ' ' }
						{ ! speakerIcon == '' && <i className="material-icons">{ speakerIcon }</i> }
						<div>
							<h5>{ __( 'Speaker', 'events' ) }</h5>
							{ event?.speaker?.name }
						</div>
					</div>
				) }
				{ showPrice && (
					<div className="ctx:event-details__item">
						<i className="material-icons">euro</i>
						<div>
							<h5>{ __( 'Price', 'events' ) }</h5>
							{ priceOverwrite != '' ? priceOverwrite : event?.price?.format }
						</div>
					</div>
				) }
				{ showBookingEnd && event?.bookingEnd && (
					<div className="ctx:event-details__item">
						<i className="material-icons">event</i>
						<div>
							<h5>{ __( 'Booking end', 'events' ) }</h5>
							{ formatDate( event?.bookingEnd ) }
						</div>
					</div>
				) }
				{ showBookedUp && event?.bookings?.has_bookings && (
					<div className="ctx:event-details__item">
						<i className="material-icons">report_problem</i>
						<div>
							<h5>{ __( 'Warning', 'events' ) }</h5>
							{ __( 'This warning is shown, if few or no bookings are available', 'events' ) }
						</div>
					</div>
				) }
			</div>
		</div>
	);
};

export default edit;
