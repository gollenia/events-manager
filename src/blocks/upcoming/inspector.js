import { InspectorControls } from '@wordpress/block-editor';
import {
	Button,
	CheckboxControl,
	FormTokenField,
	Icon,
	PanelBody,
	PanelRow,
	RadioControl,
	RangeControl,
	SelectControl,
	TextControl
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { useMemo, useState } from 'react';
import icons from './icons.js';

const Inspector = ( props ) => {
	const {
		attributes: {
			limit,
			columnsSmall,
			columnsMedium,
			columnsLarge,
			showImages,
			view,
			scope,
			showCategory,
			showLocation,
			excerptLength,
			selectedCategory,
			selectedLocation,
			order,
			userStylePicker,
			showAudience,
			showSpeaker,
			showTagFilter,
			showCategoryFilter,
			showSearch,
			filterPosition,
			showBookedUp,
			bookedUpWarningThreshold,
			excludeCurrent,
		},
		tagList,
		availableCategories,
		tagsFieldValue,
		locationList,
		tagNames,
		setAttributes,
	} = props;

	const [categoryQuery, setCategoryQuery] = useState( '' );

	const postType = window.eventBlocksLocalization?.post_type;

	const locationViewOptions = [
		{ value: '', label: __( "Don't show", 'events' ) },
		{ value: 'name', label: __( 'Name', 'events' ) },
		{ value: 'city', label: __( 'City', 'events' ) },
		{ value: 'country', label: __( 'Country', 'events' ) },
		{ value: 'state', label: __( 'State', 'events' ) },
	];

	const speakerViewOptions = [
		{ value: '', label: __( "Don't show", 'events' ) },
		{ value: 'name', label: __( 'Name only', 'events' ) },
		{ value: 'image', label: __( 'Name and image', 'events' ) },
	];

	const scopeOptions = [
		{ value: 'future', label: __( 'Future', 'events' ) },
		{ value: 'past', label: __( 'Past', 'events' ) },
		{ value: 'today', label: __( 'Today', 'events' ) },
		{ value: 'tomorrow', label: __( 'Tomorrow', 'events' ) },
		{ value: 'month', label: __( 'This month', 'events' ) },
		{ value: 'next-month', label: __( 'Next month', 'events' ) },
	];

	const orderListViewOptions = [
		{ value: 'ASC', label: __( 'Ascending', 'events' ) },
		{ value: 'DESC', label: __( 'Descending', 'events' ) },
	];

	console.log(availableCategories)

	const filteredCategories = useMemo( () => {
		
			if ( ! categoryQuery || categoryQuery.length < 3 ) {
				return availableCategories;
			}
	
			return availableCategories.filter( ( category ) => {
				return category.name.toLowerCase().includes( categoryQuery.toLowerCase() );
			} );
		}
	, [ categoryQuery, availableCategories ] );

	return (
		<InspectorControls>
			<PanelBody title={ __( 'Data', 'events' ) } initialOpen={ true }>
				{ filteredCategories.length > 0 && ( <>
				{ filteredCategories.length > 10 && (
				<TextControl 
					label={ __( 'Category', 'events' ) }
					value={ categoryQuery }
					onChange={ ( value ) => setCategoryQuery( value ) }
				/> ) }
				{ filteredCategories.map( ( category ) => {
					return (
						<CheckboxControl
							key={ category.id }
							label={ category.name }
							checked={ selectedCategory.includes( category.id ) }
							onChange={ ( value ) => {
								setAttributes( { selectedCategory: value ? [ ...selectedCategory, category.id ] : selectedCategory.filter( ( id ) => id !== category.id ) } );
							} }
						/>
					);
				} ) } </> ) }
				<FormTokenField
					label={ __( 'Tags', 'events' ) }
					value={ tagsFieldValue }
					suggestions={ tagNames }
					onChange={ ( selectedTags ) => {
						let selectedTagsArray = [];
						selectedTags.map( ( tagName ) => {
							const matchingTag = tagList.find( ( tag ) => {
								return tag.name === tagName;
							} );
							if ( matchingTag !== undefined ) {
								selectedTagsArray.push( matchingTag.id );
							}
						} );

						setAttributes( { selectedTags: selectedTagsArray } );
					} }
					__experimentalExpandOnFocus={ true }
				/>

				<SelectControl
					label={ __( 'Location', 'events' ) }
					value={ selectedLocation }
					options={ locationList }
					onChange={ ( value ) => {
						setAttributes( { selectedLocation: parseInt( value ) } );
					} }
				/>

				<SelectControl
					label={ __( 'Scope', 'events' ) }
					value={ scope }
					options={ scopeOptions }
					onChange={ ( value ) => {
						setAttributes( { scope: value } );
					} }
				/>

				<SelectControl
					label={ __( 'Sorting', 'events' ) }
					value={ order }
					options={ orderListViewOptions }
					onChange={ ( value ) => {
						setAttributes( { order: value } );
					} }
				/>

				<RangeControl
					label={ __( 'Limit', 'events' ) }
					max={ 100 }
					min={ 1 }
					value={ limit }
					onChange={ ( value ) => {
						setAttributes( { limit: value } );
					} }
				/>
				{ postType === 'event' && (
					<CheckboxControl
						label={ __( 'Exclude current event', 'events' ) }
						checked={ excludeCurrent }
						onChange={ ( value ) => setAttributes( { excludeCurrent: value } ) }
						help={ __( 'If applicable, exclude the current event from the list', 'events' ) }
					/>
				) }
			</PanelBody>
			<PanelBody title={ __( 'Filter', 'events' ) }>
				<CheckboxControl
					label={ __( 'Show category filter', 'events' ) }
					checked={ showCategoryFilter }
					onChange={ ( value ) => setAttributes( { showCategoryFilter: value } ) }
				/>
				<CheckboxControl
					label={ __( 'Show tag filter', 'events' ) }
					checked={ showTagFilter }
					onChange={ ( value ) => setAttributes( { showTagFilter: value } ) }
				/>
				<CheckboxControl
					label={ __( 'Show search bar', 'events' ) }
					checked={ showSearch }
					onChange={ ( value ) => setAttributes( { showSearch: value } ) }
				/>
				<RadioControl
					label={ __( 'Position', 'events' ) }
					help={ __( 'May not apply on mobile phones', 'events' ) }
					options={ [
						{ label: __( 'Top', 'events' ), value: 'top' },
						{ label: __( 'Side', 'events' ), value: 'side' },
					] }
					selected={ filterPosition }
					onChange={ ( value ) => setAttributes( { filterPosition: value } ) }
				/>
			</PanelBody>

			<PanelBody title={ __( 'Appearance', 'events' ) } initialOpen={ true }>
				<label className="components-base-control__label" htmlFor="inspector-range-control-4">
					{ __( 'Style', 'events' ) }
				</label>
				<br />

				<div className="styleSelector">
					<Button
						onClick={ () => setAttributes( { view: 'mini' } ) }
						className={ view == 'mini' ? 'active' : '' }
					>
						<Icon size="64" className="icon" icon={ icons.mini } />
						<div>{ __( 'Table', 'events' ) }</div>
					</Button>
					<Button
						onClick={ () => setAttributes( { view: 'list' } ) }
						className={ view == 'list' ? 'active' : '' }
					>
						<Icon size="64" className="icon" icon={ icons.list } />
						<div>{ __( 'List', 'events' ) }</div>
					</Button>
					<Button
						onClick={ () => setAttributes( { view: 'cards' } ) }
						className={ view == 'cards' ? 'active' : '' }
					>
						<Icon size="64" className="icon" icon={ icons.cards } />
						<div>{ __( 'Cards', 'events' ) }</div>
					</Button>
				</div>

				<CheckboxControl
					label={ __( 'Let user select style', 'events' ) }
					checked={ userStylePicker }
					onChange={ ( value ) => setAttributes( { userStylePicker: value } ) }
				/>

				<SelectControl
					label={ __( 'Location', 'events' ) }
					value={ showLocation }
					options={ locationViewOptions }
					onChange={ ( value ) => {
						setAttributes( { showLocation: value } );
					} }
				/>

				<SelectControl
					label={ __( 'Show Speaker', 'events' ) }
					value={ showSpeaker }
					options={ speakerViewOptions }
					onChange={ ( value ) => {
						setAttributes( { showSpeaker: value } );
					} }
				/>

				<RangeControl
					label={ __( 'Length of preview text', 'events' ) }
					max={ 200 }
					min={ 0 }
					help={ __( 'Number of words', 'events' ) }
					onChange={ ( value ) => {
						setAttributes( { excerptLength: value } );
					} }
					value={ excerptLength }
				/>
				<PanelRow>
					<CheckboxControl
						label={ __( 'Show audience', 'events' ) }
						checked={ showAudience }
						onChange={ ( value ) => setAttributes( { showAudience: value } ) }
					/>
				</PanelRow>

				<PanelRow>
					<CheckboxControl
						label={ __( 'Show image', 'events' ) }
						checked={ showImages }
						onChange={ ( value ) => setAttributes( { showImages: value } ) }
					/>
				</PanelRow>

				<PanelRow>
					<CheckboxControl
						label={ __( 'Show category', 'events' ) }
						checked={ showCategory }
						onChange={ ( value ) => setAttributes( { showCategory: value } ) }
					/>
				</PanelRow>
				<CheckboxControl
					label={ __( 'Show if event is booked up or nearly booked up', 'events' ) }
					checked={ showBookedUp }
					onChange={ ( value ) => setAttributes( { showBookedUp: value } ) }
				/>
				<RangeControl
					label={ __( 'Warning threshold', 'events' ) }
					value={ bookedUpWarningThreshold }
					onChange={ ( value ) => setAttributes( { bookedUpWarningThreshold: value } ) }
					min={ 0 }
					max={ 10 }
					help={ __(
						'Show a warning that the event is nearly booked up when only this number of spaces are left',
						'events'
					) }
				/>
			</PanelBody>
		</InspectorControls>
	);
};

export default Inspector;
