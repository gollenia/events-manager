/**
 * External Dependencies
 */
import { __ } from '@wordpress/i18n';
import React from 'react';

/**
 * Internal Dependencies
 */
import { formatDateRange } from './formatDate';
import truncate from './truncate';

function card( props ) {
	const {
		showImages,
		showCategory,
		showLocation,
		showBookedUp,
		bookedUpWarningThreshold,
		excerptLength,
		textAlignment,
		showAudience,
		showSpeaker,
		item,
	} = props;

	const bookingWarning = () => {
		if ( ! showBookedUp || ! item.bookings.has_bookings ) return <></>;
		if ( item.bookings?.spaces > bookedUpWarningThreshold ) return <></>;

		if ( item.bookings?.spaces > 0 ) {
			return <span className="pills__item pills__item--warning">{ __( 'Nearly Booked up', 'events' ) }</span>;
		}

		return <span className="pills__item pills__item--error">{ __( 'Booked up', 'events' ) }</span>;
	};

	const cardFooter = () => {
		if ( ! showAudience && ! showSpeaker && ! showLocation && ! showBookedUp ) return <></>;
		return (
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
		);
	};

	const location = item.location && [ 'city', 'name' ].includes( showLocation ) ? item.location[ showLocation ] : '';

	return (
		<div
			className={ 'card card--image-top card--primary card--shadow card--hover bg-white card--' + textAlignment }
		>
			{ showImages && (
				<a href={ item.link } className="card__image">
					<img src={ item.image?.sizes?.large?.url } />
				</a>
			) }
			<div className="card__content">
				<a href={ item.link } className="card__hidden-link"></a>
				{ item.category && showCategory && <span class="card__label">{ item.category.name }</span> }
				<h2 className="card__title">{ item.title }</h2>
				<h4 class="card__subtitle text--primary">{ formatDateRange( item.start, item.end ) }</h4>
				<p className="card__text">{ truncate( item.excerpt, excerptLength ) }</p>
				{ cardFooter() }
			</div>
		</div>
	);
}

export default card;
