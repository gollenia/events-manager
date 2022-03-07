import { useState, useEffect, React } from 'react'
import apiFetch from '@wordpress/api-fetch';
import { __ } from '@wordpress/i18n'; 

import EventCard from './cards'
import EventList from './list'
import DescriptionItem from './descriptionItem'


function Upcoming(props) {
	if(!document.event_block_data) return (<></>)
	const { 

			columnsSmall,
			columnsMedium,
			columnsLarge,
			showImages,
			roundImages,
			imageSize,
			showCategory,
			showLocation,
			style,
			limit,
			order,
			selectedCategory,
			selectedLocation,
			selectedTags,
			scope,
			excerptLength,
			textAlignment,
			showAudience,
			showSpeaker,
			showCategoryFilter,
			showTagFilter,
			showSearch,
			filterStyle,
			filterPosition,
		
	} = document.event_block_data[props.block]

	const [ events, setEvents ] = useState([])
	const [ categories, setCategories ] = useState({})
	const [ tags, setTags ] = useState({})
	const [ filter, setFilter ] = useState({
		category: 0,
		tags: [],
		string: ""
	})

	const changeFilter = (key, value) => {
		setFilter({...filter, [key]: value});
	}

	const toggleTag = (tag) => {
		let tagFilter = filter.tags;
		if(tagFilter.includes(tag)) { 
			tagFilter.splice(tagFilter.indexOf(tag),1) 
			changeFilter('tags', tagFilter)
			return;
		}

		tagFilter.push(tag);
		changeFilter('tags', tagFilter)
	}
	
	useEffect(() => {

		const params = [
			limit > 0 ? `limit=${limit}` : false,
			'order=' + order,
			selectedCategory != 0 ? `category=${selectedCategory.join(',')}` : false,
			selectedTags.length ? `tag=${selectedTags.join(',')}` : false,
			scope != '' ? `scope=${scope}` : false,
			selectedLocation ? `location=${selectedLocation}` : false
		].filter(Boolean).join("&");

		const url = '/events/v2/events?' + params;
		
		apiFetch( { path: url } ).then( ( posts ) => {
			setEvents(posts)
			
			let categories = {};
			let tags = {};
			posts.map((event) => {
				if(!event.category) return;
				if (categories[event.category.id] == undefined) categories[event.category.id] = event.category;

				for(let tag in event.tags) {
					
					if (tags[tag] == undefined) {
						console.log("adding tag " + tag)
						tags[tag] = event.tags[tag];
					}
				}
				
			});

			setTags(tags);
			setCategories(categories);
		} );

		
	}, [])

	const getFilteredEvents = () => {
		let filtered = events;
		if(filter.category == 0 && filter.string == "" && filter.tags.length == 0) return filtered;
		
		if( filter.category !== 0 ) {
			filtered = filtered.filter(item => {return item.category?.id == filter.category})
		}

		if( filter.string !== "" ) {
			filtered = filtered.filter(item => {return item.title.toLowerCase().includes(filter.string)})
		}
		
		if( filter.tags.length > 0) {
			filtered = filtered.filter(item => {
				let result = false;
				for(let key of filter.tags) { if(key in item.tags) result = true }
				return result;
			})
		}

		return filtered;
	}

	const containerClass = [
		style == 'mini' ? 'description' : 'grid',
		style == 'mini' && !showImages ? 'description--dates' : false,
		'grid--gap-12',
		filterPosition ? 'grid__column--span-3' : false,
		'xl:grid--columns-' + columnsLarge,
		'md:grid--columns-' + columnsMedium,
		'grid--columns-' + columnsSmall,
	].filter(Boolean).join(" ");
	
	return (
		<div className={filterPosition == 'side' ? 'grid grid--columns-4 grid--gap-12' : ''}>
			<aside className='filters'>
			{ showSearch && <div class="filter__search"><div class="input"><label>{__('Search', 'events')}</label><input type="text" onChange={(event) => {changeFilter('string',event.target.value)} }/></div></div>}
			{ showCategoryFilter && 
				<div className='filter'>
					<span className='filter__title'>{__("Select category", "events")}</span>
					<a class={'filter__pill ' + (filter.category == 0 ? 'filter__pill--active' : '')} onClick={() => {changeFilter('category',0)} }>{__('All', 'events')}</a>
					{ Object.keys(categories).map((item, index) => (
						<a 
							className={'filter__pill ' + (filter.category == parseInt(item) ? 'filter__pill--active' : '')} 
							onClick={() => {changeFilter('category', parseInt(item))}}
						>
						{categories[item].name}
						</a>
					))}
				</div> }
				{ showTagFilter && 
				<div className={'filter ' + (filterPosition == 'side' ? 'filter--columns' : '')}>
					<span className='filter__title'>{__("Select tags", "events")}</span>
					
					{ Object.keys(tags).map((item, index) => (
						<div className="filter__box checkbox">
						<label><input type="checkbox" name={item} onClick={(event) => {toggleTag(item)}} checked={filter.tags.includes(item)}/>
							{tags[item].name}
						</label>
						</div>
						
					))}
				</div> }
			
			</aside>
			<div className={containerClass}>
			{ getFilteredEvents().map((item, index) => (
				<>
					{ style == 'cards' && <EventCard
						item={item}
						showImages={showImages}
						showCategory={showCategory}
						showLocation={showLocation}
						excerptLength={excerptLength}
						textAlignment={textAlignment}
						showAudience={showAudience}
						showSpeaker={showSpeaker}
					/> }
					{ style == 'list' && <EventList
						item={item}
						showImages={showImages}
						showCategory={showCategory}
						showLocation={showLocation}
						excerptLength={excerptLength}
						textAlignment={textAlignment}
						showAudience={showAudience}
						showSpeaker={showSpeaker}
					/> }
					{ style == 'mini' && <DescriptionItem
						item={item}
						showImages={showImages}
						showCategory={showCategory}
						showLocation={showLocation}
						excerptLength={excerptLength}
						textAlignment={textAlignment}
						showAudience={showAudience}
						showSpeaker={showSpeaker}
					/> }
				</>
			))}
			</div>
		</div>
		
	)
}

export default Upcoming