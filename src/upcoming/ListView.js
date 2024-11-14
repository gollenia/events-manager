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
			textAlignment,
			showAudience,
			showSpeaker,
		},
		events,
	} = props;

	return (
		<div className="event-list">
			{ events.map( ( item, index ) => {
				const location =
					item.location && [ 'city', 'name' ].includes( showLocation ) ? item.location[ showLocation ] : '';

				const bookingWarning = () => {
					if ( ! showBookedUp || ! item.bookings.has_bookings ) return <></>;
					if ( item.bookings?.spaces > bookedUpWarningThreshold ) return <></>;

					if ( item.bookings?.spaces > 0 ) {
						return (
							<span className="pills__item pills__item--warning">
								{ __( 'Nearly Booked up', 'events-manager' ) }
							</span>
						);
					}

					return (
						<span className="pills__item pills__item--error">{ __( 'Booked up', 'events-manager' ) }</span>
					);
				};

				return (
					<div className="event-card" key={ index }>
						{ showImages && (
							<a href={ item.link } className="event-card-image">
								<img src={ item.image?.sizes?.large?.url } />
							</a>
						) }
						<div className="event-card-content">
							{ item.category && showCategory && (
								<span class="event-card-label">{ item.category.name }</span>
							) }
							<h5 class="event-card-subtitle">{ formatDateRange( item.start, item.end ) }</h5>
							<a href={ item.link }>
								<h4 className="event-card-title">{ item.title }</h4>
							</a>

							<p className="event-card-text">{ truncate( item.excerpt, excerptLength ) }</p>
							{ ( showAudience || showSpeaker || showLocation || showBookedUp ) && (
								<div class="card__footer card__subtitle pills pills--small">
									{ showAudience && item.audience?.length > 0 && (
										<span className="pills__item event__audience">{ item.audience }</span>
									) }
									{ showSpeaker == 'name' && item.speaker?.id && (
										<span className="pills__item event__speaker">{ item.speaker.name }</span>
									) }
									{ showLocation && item.location?.ID && (
										<span className="pills__item event__location">{ location }</span>
									) }
									{ showBookedUp && item.bookings && bookingWarning() }
								</div>
							) }
						</div>
					</div>
				);
			} ) }
		</div>
	);
}

export default EventCards;
