/**
 * External Dependencies
 */
import React from 'react';

/**
 * Internal Dependencies
 */
import { formatDate, formatDateRange } from './formatDate';

export default function descriptionItem( props ) {
	const {
		showImages,
		showCategory,
		showLocation,
		excerptLength,
		textAlignment,
		showAudience,
		showSpeaker,
		item,
	} = props;

	const location = item.location && [ 'city', 'name' ].includes( showLocation ) ? item.location[ showLocation ] : '';

	return (
		<a href={ item.link } className={ 'description__item ' + textAlignment }>
			{ showImages && <img className="description__image" src={ item.image?.sizes?.large?.url } /> }
			{ ! showImages && (
				<div className="description__date">
					<span className="date__day--numeric">{ formatDate( item.start, { day: 'numeric' } ) }</span>
					<span className="date__day--short">{ formatDate( item.start, { weekday: 'short' } ) }</span>
					<span className="date__day--long">{ formatDate( item.start, { weekday: 'long' } ) }</span>
					<span className="date__month--long">{ formatDate( item.start, { month: 'long' } ) }</span>
					<span className="date__month--numeric">{ formatDate( item.start, { month: 'numeric' } ) }</span>
					<span className="date__month--short">{ formatDate( item.start, { month: 'short' } ) }</span>
				</div>
			) }
			<div className="description__text">
				<div className="description__title">{ item.title }</div>
				<div class="description__data">
					{ formatDateRange( item.start, item.end ) }
					<br />
					<div class="description__subtitle">
						{ showAudience && item.audience && <span>{ item.audience }</span> }
						{ showSpeaker == 'name' && item.speaker && <span>{ item.speaker.name }</span> }
						{ showLocation && item.location && <span className="">{ location }</span> }
					</div>
				</div>
			</div>
		</a>
	);
}
