import apiFetch from '@wordpress/api-fetch';
import { __ } from '@wordpress/i18n';
import { useEffect, useState } from 'react';
import CardView from './CardView';
import ListView from './ListView';
import TableView from './Table';
import './style.scss';

function Upcoming( props ) {
	const {
		showImages,
		showCategory,
		showLocation,
		showBookedUp,
		userStylePicker,
		bookedUpWarningThreshold,
		view,
		limit,
		order,
		selectedCategory,
		selectedLocation,
		selectedTags,
		scope,
		filterPosition,
		excerptLength,
		textAlignment,
		showAudience,
		showSpeaker,
		altText,
		showCategoryFilter,
		showTagFilter,
		showSearch,
		excludeCurrent,
	} = props.attributes;

	const [ events, setEvents ] = useState( [] );
	const [ categories, setCategories ] = useState( {} );
	const [ status, setStatus ] = useState( 'LOADING' );
	const [ tags, setTags ] = useState( {} );
	const [ filterMobileVisible, setFilterMobileVisible ] = useState( false );
	const [ customView, setCustomView ] = useState( '' );
	const [ filter, setFilter ] = useState( {
		category: 0,
		tags: [],
		string: '',
	} );

	const changeFilter = ( key, value ) => {
		setFilter( { ...filter, [ key ]: value } );
	};

	const toggleTag = ( tag ) => {
		let tagFilter = filter.tags;
		if ( tagFilter.includes( tag ) ) {
			tagFilter.splice( tagFilter.indexOf( tag ), 1 );
			changeFilter( 'tags', tagFilter );
			return;
		}

		tagFilter.push( tag );
		changeFilter( 'tags', tagFilter );
	};

	const getUrl = ( params = '' ) => {
		const base = window.eventBlocksLocalization?.rest_url;
		if ( base === undefined ) return;
		if ( params === '' ) return base;
		return base + ( base.includes( '?' ) ? '&' : '?' ) + params;
	};

	useEffect( () => {
		const params = [
			limit > 0 ? `limit=${ limit }` : false,
			'order=' + order,
			selectedCategory ? `category=${ selectedCategory.join( ',' ) }` : false,
			selectedTags?.length ? `tag=${ selectedTags.join( ',' ) }` : false,
			scope != '' ? `scope=${ scope }` : false,
			selectedLocation ? `location=${ selectedLocation }` : false,
			excludeCurrent && window.eventBlocksLocalization?.current_id
				? `exclude=${ window.eventBlocksLocalization?.current_id }`
				: false,
		]
			.filter( Boolean )
			.join( '&' );
		console.log( getUrl( params ) );
		apiFetch( { url: getUrl( params ) } ).then( ( posts ) => {
			setEvents( posts );
			console.log( posts );
			let categories = {};
			let tags = {};
			posts.map( ( event ) => {
				if ( ! event.category ) return;
				if ( categories[ event.category.id ] == undefined ) categories[ event.category.id ] = event.category;

				for ( let tag in event.tags ) {
					if ( tags[ tag ] == undefined ) {
						tags[ tag ] = event.tags[ tag ];
					}
				}
			} );

			setTags( tags );
			setCategories( categories );
			setStatus( 'LOADED' );
		} );
	}, [] );

	const getFilteredEvents = ( sort = '' ) => {
		let filtered = events;
		if ( filter.category == 0 && filter.string == '' && filter.tags.length == 0 ) return filtered;

		if ( filter.category !== 0 ) {
			filtered = filtered.filter( ( item ) => {
				return item.category?.id == filter.category;
			} );
		}

		if ( filter.string !== '' ) {
			filtered = filtered.filter( ( item ) => {
				return item.title.toLowerCase().includes( filter.string );
			} );
		}

		if ( filter.tags.length > 0 ) {
			filtered = filtered.filter( ( item ) => {
				let result = false;
				for ( let key of filter.tags ) {
					if ( key in item.tags ) result = true;
				}
				return result;
			} );
		}

		if ( sort == 'asc' ) {
			filtered.sort( ( a, b ) => {
				return new Date( a.start ) - new Date( b.start );
			} );
		}

		return filtered;
	};

	const currentView = customView != '' ? customView : view;

	const showFilters = showCategoryFilter || showTagFilter || showSearch;

	if ( events.length == 0 && status == 'LOADED' ) {
		return <div>{ altText }</div>;
	}

	return (
		<div className={ `upcoming__events ${ showFilters ? 'has-filters' : '' } event-filters-${ filterPosition }` }>
			{ showFilters && (
				<aside className={ `event-filters` }>
					<div className="event-filters-header">
						{ showSearch && (
							<div class="filter__search">
								<div class="input">
									<label>{ __( 'Search', 'events-manager' ) }</label>
									<input
										type="text"
										onChange={ ( event ) => {
											changeFilter( 'string', event.target.value );
										} }
									/>
								</div>
							</div>
						) }

						{ userStylePicker && (
							<div className="view-switcher">
								<button
									onClick={ () => setCustomView( 'cards' ) }
									className={ currentView == 'cards' ? 'button active' : 'button' }
								>
									<i className="material-icons material-symbols-outlined">grid_view</i>
								</button>
								<button
									onClick={ () => setCustomView( 'list' ) }
									className={ currentView == 'list' ? 'button active' : 'button' }
								>
									<i className="material-icons material-symbols-outlined">view_agenda</i>
								</button>
								<button
									onClick={ () => setCustomView( 'table' ) }
									className={ currentView == 'table' ? 'button active' : 'button' }
								>
									<i className="material-icons material-symbols-outlined">format_list_bulleted</i>
								</button>
							</div>
						) }

						<div className="event-filter-toggle">
							<button
								className="button"
								onClick={ () => setFilterMobileVisible( ! filterMobileVisible ) }
							>
								<i className="material-icons material-symbols-outlined">filter_list</i>
							</button>
						</div>
					</div>
					<div
						className={
							filterMobileVisible ? 'event-filters-advanced' : 'event-filters-advanced mobile-hidden'
						}
					>
						{ showCategoryFilter && Object.keys( categories ).length > 0 && (
							<div>
								<h5 className="event-filters-title">{ __( 'Select category', 'events-manager' ) }</h5>
								<div className="event-filter-pills">
									<button
										class={ filter.category == 0 ? 'active' : '' }
										onClick={ () => {
											changeFilter( 'category', 0 );
										} }
									>
										{ __( 'All', 'events-manager' ) }
									</button>
									{ Object.keys( categories ).map( ( item, index ) => (
										<button
											className={ filter.category == parseInt( item ) ? 'active' : '' }
											onClick={ () => {
												changeFilter( 'category', parseInt( item ) );
											} }
										>
											{ categories[ item ].name }
										</button>
									) ) }
								</div>
							</div>
						) }
						{ showTagFilter && Object.keys( tags ).length > 0 && (
							<div className="">
								<h5 className="event-filters-title">{ __( 'Select tags', 'events-manager' ) }</h5>

								{ Object.keys( tags ).map( ( item, index ) => (
									<div className="filter__box checkbox">
										<label>
											<input
												type="checkbox"
												name={ item }
												onClick={ () => toggleTag( item ) }
												checked={ filter.tags.includes( item ) }
											/>
											{ tags[ item ].name }
										</label>
									</div>
								) ) }
							</div>
						) }
					</div>
				</aside>
			) }
			<>
				{ currentView == 'cards' && (
					<CardView attributes={ props.attributes } events={ getFilteredEvents() } status={ status } />
				) }
				{ currentView == 'list' && (
					<ListView attributes={ props.attributes } events={ getFilteredEvents() } status={ status } />
				) }
				{ currentView == 'mini' && (
					<TableView attributes={ props.attributes } events={ getFilteredEvents() } status={ status } />
				) }
			</>
		</div>
	);
}

export default Upcoming;
