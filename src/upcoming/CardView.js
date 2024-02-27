/**
 * External Dependencies
 */
import { __ } from '@wordpress/i18n';

/**
 * Internal Dependencies
 */
import { formatDateRange } from './formatDate';
import truncate from './truncate';

function EventCards( props ) {
	const {
		attributes: {
			showImages,
			showCategory,
			showLocation,
			showBookedUp,
			bookedUpWarningThreshold,
			excerptLength,
			showAudience,
			showSpeaker,
			animateOnScroll,
			animationType,
		},
		events,
	} = props;

	const className = [
		'event-grid',
		animateOnScroll ? 'ctx-animate-children' : '',
		animationType ? `ctx-${ animationType }` : '',
	].join( ' ' );

	return (
		<ul className={ className }>
			{ events.map( ( item, index ) => {
				const location =
					item.location && [ 'city', 'name' ].includes( showLocation ) ? item.location[ showLocation ] : '';

				const bookingWarning = () => {
					if ( ! showBookedUp || ! item.bookings.has_bookings ) return <></>;
					if ( item.bookings?.spaces > bookedUpWarningThreshold ) return <></>;

					if ( item.bookings?.spaces > 0 ) {
						return (
							<span className="event-card-pill event-card-pill-warning">
								{ __( 'Nearly Booked up', 'events' ) }
							</span>
						);
					}

					return (
						<span className="event-card-pill event-card-pill-error">{ __( 'Booked up', 'events' ) }</span>
					);
				};

				return (
					<li className="event-card" key={ index }>
						{ showImages && (
							<a href={ item.link } className="event-card-image">
								<img src={ item.image?.sizes?.large?.url } />
							</a>
						) }
						<div className="event-card-content">
							{ item.category && showCategory && (
								<span class="event-card-label">{ item.category.name }</span>
							) }
							<a href={ item.link }>
								<h2 className="event-catd-title">{ item.title }</h2>
							</a>
							<h4 class="event-card-subtitle">{ formatDateRange( item.start, item.end ) }</h4>
							<p className="event-card-text">{ truncate( item.excerpt, excerptLength ) }</p>
							{ ( showAudience || showSpeaker || showLocation || showBookedUp ) && (
								<div class="event-card-footer">
									{ showAudience && item.audience?.length > 0 && (
										<span className="event-audience">{ item.audience }</span>
									) }
									{ showSpeaker == 'name' && item.speaker?.id && (
										<span className="event-speaker">{ item.speaker.name }</span>
									) }
									{ showLocation && item.location?.ID && (
										<span className="event-location">{ location }</span>
									) }
									{ showBookedUp && item.bookings && bookingWarning() }
								</div>
							) }
						</div>
					</li>
				);
			} ) }
		</ul>
	);
}

export default EventCards;
