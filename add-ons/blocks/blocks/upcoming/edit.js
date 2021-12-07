/**
 * Internal dependencies
 */


import { InspectorControls, AlignmentToolbar, BlockControls, useBlockProps } from '@wordpress/block-editor';
import { TextControl, ToggleControl, RangeControl, PanelBody, PanelRow, SelectControl, FormTokenField  } from '@wordpress/components';
import { Icon, Button} from '@wordpress/components'
import { format } from '@wordpress/date'
import icons from './icons.js'
import { withState } from '@wordpress/compose';
import ServerSideRender from '@wordpress/server-side-render';

import { get } from 'lodash';

/**
 * Wordpress dependencies
 */
import { __ } from '@wordpress/i18n'; 
import { useSelect } from '@wordpress/data';


import { store as coreStore } from '@wordpress/core-data';
import { boolean } from 'joi';


const CATEGORIES_LIST_QUERY = {
	per_page: -1,
};
const USERS_LIST_QUERY = {
	per_page: -1,
};


export default function Edit({ attributes, setAttributes }) {

	const {
		limit,
		columnsSmall,
		columnsMedium,
		columnsLarge,
		showImages,
		dropShadow,
		imageSize,
		style,
		textAlignment,
		showCategory,
		showLocation,
		roundImages,
		excerptLength,
		selectedCategory,
		selectedLocation,
		selectedTags,
		fromDate,
		toDate, 
		order,
		orderBy
	} = attributes;

	const categoryList = useSelect( ( select ) => {
		const { getEntityRecords } = select( coreStore );
		const query = { hide_empty: true };
		const list = getEntityRecords( 'taxonomy', 'event-categories', query );
		let categoryOptionsArray = [{value: 0, label: ""}];
		if (!list) {
			return categoryOptionsArray;
		}
		
		list.map( ( category ) => {
			categoryOptionsArray.push( { value: category.id, label: category.name } );
		})
		return categoryOptionsArray
	}, [] );

	const tagList = useSelect( ( select ) => {
		const { getEntityRecords } = select( coreStore );
		const query = { hide_empty: true };
		const list = getEntityRecords( 'taxonomy', 'event-tags', query );
		
		if (!list) {
			return null
		}
		return list;	
		
	}, [] );


	const locationList = useSelect( ( select ) => {
		const { getEntityRecords } = select( coreStore );
		const query = { per_page: -1 };
		const list = getEntityRecords( 'postType', 'location', query );
		
		let locationOptionsArray = [{value: 0, label: ""}];
		if (!list) {
			return locationOptionsArray;
		}
		
		list.map( ( location ) => {
			locationOptionsArray.push( { value: location.id, label: location.title.raw } );
		})
		return locationOptionsArray	
		
	}, [] );

	
	 
	let tagNames = [];
	let tagsFieldValue = [];
	if ( tagList !== null ) {
		tagNames = tagList.map( ( tag ) => tag.name );

		tagsFieldValue = selectedTags.map( ( tagId ) => {
			let wantedTag = tagList.find( ( tag ) => {
				return tag.id === tagId;
			} );
			if ( wantedTag === undefined || ! wantedTag ) {
				return false;
			}
			return wantedTag.name;
		} );
	}

	const blockProps = useBlockProps({
		className: [
			"columns-" + columnsLarge,
			showImages ? "hasImage" : false,
			dropShadow ? "hover" : false,
			"style-" + style,
			"text-" + textAlignment,
			roundImages ? "round-images" : false
		].filter(Boolean).join(" ")
	});

	const locationViewOptions = [
		{ value: "", label: __("", "events-manager") },
		{ value: "city", label: __("City", "events-manager") },
		{ value: "name", label: __("Name", "events-manager") },
	]

	

	const inspectorControls = (
		<InspectorControls>
				<PanelBody
					title={__('Data', 'events-manager')}
					initialOpen={true}
				>
					<SelectControl
						label={__('Category', 'events-manager')}
						value={ selectedCategory }
						options={ categoryList }
						onChange={ (value) => { setAttributes( { selectedCategory: value } ); } }
					/>
					<FormTokenField
						label={__('Tags', 'events-manager')}
						value={ tagsFieldValue }
						suggestions={ tagNames }
						onChange={ (selectedTags) => {
							let selectedTagsArray = [];
							selectedTags.map(
								( tagName ) => {
									const matchingTag = tagList.find( ( tag ) => {
										return tag.name === tagName;

									} );
									if ( matchingTag !== undefined ) {
										selectedTagsArray.push( matchingTag.id );
									}
								}
							)

							setAttributes( { selectedTags: selectedTagsArray } );

						} }
						__experimentalExpandOnFocus={true}
					/>
					<TextControl
						label={__("From:", "events-manager")}
						value={ fromDate }
						type="date"
						onChange={(value) => { setAttributes( { fromDate: value } ) }}
					/>
					<TextControl
						label={__("To:", "events-manager")}
						value={ toDate }
						type="date"
						onChange={(value) => { setAttributes( { toDate: value } ) }}
					/>
					<SelectControl
						label={__('Location', 'events-manager')}
						value={ selectedLocation }
						options={ locationList }
						onChange={ ( value ) => {
							setAttributes( { selectedLocation: value } );
						} }
					/>
					
				</PanelBody>
				<PanelBody
					title={__('Appearance', 'events-manager')}
					initialOpen={true}
				>
					
					<RangeControl
						label={__("Columns on small screens", 'events-manager')}
						max={ 6 }
						min={ 1 }
						help={__("ex. Smartphones", 'events-manager')}
						onChange={(value) => {setAttributes( { columnsSmall: value })}}
						value={ columnsSmall }
					/>
				
			
					<RangeControl
						label={__("Columns on medium screens", 'events-manager')}
						max={ 6 }
						min={ 1 }
						help={__("Tablets and smaller screens", 'events-manager')}
						onChange={(value) => {setAttributes( { columnsMedium: value })}}
						value={ columnsMedium }
					/>
		
					<RangeControl
						label={__("Columns on large screens", 'events-manager')}
						max={ 6 }
						min={ 1 }
						help={__("Desktop screens", 'events-manager')}
						onChange={(value) => {setAttributes( { columnsLarge: value })}}
						value={ columnsLarge }
					/>
					
				</PanelBody>

				<PanelBody
					title={__('Events', 'events-manager')}
					initialOpen={true}
				>
					<PanelRow>
						<ToggleControl
							label={ __("Hover-effect", 'events-manager')}
							checked={ dropShadow }
							onChange={ (value) => setAttributes({ dropShadow: value }) }
						/>
					</PanelRow>
					
					
						<label className="components-base-control__label" htmlFor="inspector-range-control-4">{__("Style", "events-manager")}</label><br />
						<div className="styleSelector">
								<Button onClick={ () => setAttributes({ style: "mini" }) } className={style == "mini" ? "active" : ""}>
									<Icon size="64" className="icon" icon={icons.mini}/>
									<div>{__("Minimal", "events-manager")}</div>
								</Button>
								<Button onClick={ () => setAttributes({ style: "list" }) } className={style == "list" ? "active" : ""}>
									<Icon size="64" className="icon" icon={icons.list}/>
									<div>{__("List", "events-manager")}</div>
								</Button>
								<Button onClick={ () => setAttributes({ style: "cards" }) } className={style == "cards" ? "active" : ""}>
									<Icon size="64" className="icon" icon={icons.cards}/>
									<div>{__("Cards", "events-manager")}</div>
								</Button>
						</div>
						
					
					{ showImages &&
					<Fragment>
						<PanelRow>
							<ToggleControl
								label={ __("Round images", 'events-manager')}
								checked={ roundImages }
								onChange={ (value) => setAttributes({ roundImages: value }) }
							/>
						</PanelRow>
						<PanelRow>
							<ToggleControl
								label={ __("Show category", 'events-manager')}
								checked={ showCategory }
								onChange={ (value) => setAttributes({ showCategory: value }) }
							/>
						</PanelRow>
						<PanelRow>
						<SelectControl
							label={__('Location', 'events-manager')}
							value={ showLocation }
							options={ locationViewOptions }
							onChange={ ( value ) => {
								setAttributes( { showLocation: value } );
							} }
						/>
						</PanelRow>
						
						
					</Fragment>
					}
					<RangeControl
						label={__("Length of preview text", 'events-manager')}
						max={ 200 }
						min={ 0 }
						help={__("Number of words", 'events-manager')}
						onChange={(value) => {setAttributes( { excerptLength: value })}}
						value={ excerptLength }
					/>
				</PanelBody>
			</InspectorControls>
	)

	return (
		
		<>
			{ inspectorControls }
			<BlockControls>
				<AlignmentToolbar
					value={ textAlignment }
					onChange={ (event) => setAttributes({ textAlignment: event }) }
				/>
			</BlockControls>
			<div { ...blockProps }>
				
			<ServerSideRender
        		block="events-manager/upcoming"
    		/>
				
			</div>
		</>
	);

}