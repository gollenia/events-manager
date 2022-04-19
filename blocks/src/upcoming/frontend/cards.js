/**
* External Dependencies
*/
import React from 'react'

/**
* Internal Dependencies
*/
import truncate from './truncate';
import { formatDateRange } from './formatDate';


function card(props) {

	const {
		showImages,
		showCategory,
		showLocation,
		excerptLength,
		textAlignment,
		showAudience,
		showSpeaker,
		item
	} = props;

	const location = (item.location && ['city', 'name'].includes(showLocation)) ? item.location[showLocation] : ''

	return (
		<div className={"card card--image-top card--primary card--shadow card--hover bg-white card--" + textAlignment}>
			{ showImages && <a href={item.link} className="card__image">
				<img src={item.image?.sizes?.large?.url} />	
				{ showSpeaker == 'image' && item.speaker && <span className='card__image-label'><img src={item.speaker.image?.sizes?.thumbnail?.url}/>{item.speaker.name}</span>}
			</a> }
			<div className='card__content'>
				<a href={item.link} className="card__hidden-link"></a>
				{ item.category && showCategory && <span class="card__label">{item.category.name}</span> }
				<h2 className="card__title">{item.title}</h2>
				<h4 class="card__subtitle text--primary">{formatDateRange(item.start, item.end)}</h4>
				<p className="card__text">{truncate(item.excerpt, excerptLength)}</p>
				{ (showAudience || showSpeaker || showLocation) && 
					<div class="card__footer card__subtitle card__pills">
						{ showAudience && item.audience && <span className='card__pill event__audience'>{item.audience}</span>}
						{ showSpeaker == 'name' && item.speaker.id && <span className='card__pill event__audience'>{item.speaker.name}</span>}
						{ showLocation && item.location && <span className='card__pill event__audience'>{location}</span>}
					</div>
				}
			</div>
		</div>
	)
}

export default card 