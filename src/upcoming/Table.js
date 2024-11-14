/**
 * External Dependencies
 */
import { __ } from '@wordpress/i18n';

/**
 * Internal Dependencies
 */
import { formatDate, formatDateRange } from './formatDate';
import truncate from './truncate';

function Table( props ) {
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
		<table className="event-table" cellPadding={ 0 } cellSpacing={ 0 }>
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
					<tr
						className="event-row"
						key={ index }
						onClick={ () => {
							window.location = item.link;
						} }
					>
						<td class="event-table-date">
							<div className="description__date">
								<span className="date__day--numeric">
									{ formatDate( item.start, { day: 'numeric' } ) }
								</span>
								<span className="date__day--short">
									{ formatDate( item.start, { weekday: 'short' } ) }
								</span>
								<span className="date__day--long">
									{ formatDate( item.start, { weekday: 'long' } ) }
								</span>
								<span className="date__month--long">
									{ formatDate( item.start, { month: 'long' } ) }
								</span>
								<span className="date__month--numeric">
									{ formatDate( item.start, { month: 'numeric' } ) }
								</span>
								<span className="date__month--short">
									{ formatDate( item.start, { month: 'short' } ) }
								</span>
							</div>
						</td>

						<td class="event-table-title">
							<a href={ item.link }>
								<b className="event-table-title">{ item.title }</b>
							</a>
							<div className="event-table-subtitle">{ formatDateRange( item.start, item.end ) }</div>
						</td>
						{ showCategory && <td class="event-table-label">{ item.category.name }</td> }

						<td className="event-table-text">{ truncate( item.excerpt, excerptLength ) }</td>

						{ showAudience && <td className="event-table-text event-table-audience">{ item.audience }</td> }
						{ showSpeaker && (
							<td className="event-table-text event-table-speaker">{ item.speaker.name }</td>
						) }
						{ showLocation && <td className="event-table-text event-table-location">{ location }</td> }
						{ showBookedUp && <td>{ item.bookings && bookingWarning() } </td> }
					</tr>
				);
			} ) }
		</table>
	);
}

export default Table;
