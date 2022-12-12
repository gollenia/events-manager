/**
 * External Dependencies
 */
import React from 'react';

/**
 * External Dependencies
 */
import { formatDateRange } from './formatDate';
import truncate from './truncate';

function list( props ) {
	const {
		showImages,
		showCategory,
		showLocation,
		excerptLength,
		textAlignment,
		showAudience,
		showSpeaker,
		//showBooking // for later usage
		item,
	} = props;

	const location = item.location && [ 'city', 'name' ].includes( showLocation ) ? item.location[ showLocation ] : '';

	return (
		<div
			className={ 'card card--image-left has-white-background card--shadow card--primary card--' + textAlignment }
		>
			{ showImages && (
				<a href={ item.link } className="card__image">
					<img src={ item.image?.sizes?.large?.url } />
					{ showSpeaker == 'image' && item.speaker && (
						<span className="card__label card__label--image">
							<img src={ item.speaker.image?.sizes?.thumbnail?.url } />
							{ item.speaker.name }
						</span>
					) }
				</a>
			) }
			<div className="card__content">
				{ item.category && showCategory && <span class="card__label">{ item.category.name }</span> }
				<a href={ item.link } className="card__title">
					{ item.title }
				</a>
				<a href={ item.link } class="card__subtitle text--primary">
					{ formatDateRange( item.start, item.end ) }
				</a>
				<a href={ item.link } className="card__text">
					{ truncate( item.excerpt, excerptLength ) }
				</a>
				{ ( showAudience || showSpeaker || showLocation ) && (
					<div class="card__footer card__subtitle card__pills">
						{ showAudience && item.audience && (
							<span className="card__pill event__audience">{ item.audience }</span>
						) }
						{ showSpeaker == 'name' && item.speaker && (
							<span className="card__pill event__audience">{ item.speaker.name }</span>
						) }
						{ showLocation && item.location && (
							<span className="card__pill event__audience">{ location }</span>
						) }
					</div>
				) }
			</div>
		</div>
	);
}

export default list;
