/**
 * Wordpress dependencies
 */
import { InspectorControls, AlignmentToolbar, BlockControls, useBlockProps } from '@wordpress/block-editor';
import { CheckboxControl, TextControl, ToggleControl, RangeControl, PanelBody, PanelRow, SelectControl, FormTokenField, Dashicon, Icon, Button } from '@wordpress/components';
import { __experimentalNumberControl as NumberControl } from '@wordpress/components'
import { __ } from '@wordpress/i18n'; 
import { useSelect } from '@wordpress/data';
import { store as coreStore } from '@wordpress/core-data';

/**
 * Internal dependencies
 */
import icons from './icons.js'

/**
 * @param {Props} props
 * @return {JSX.Element} Element
 */
const EditUpcoming = ({ attributes, setAttributes }) => {

	const {
		limit,
		columnsSmall,
		columnsMedium,
		columnsLarge,
		showImages,
		dropShadow,
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
		showAudience,
		showSpeaker
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
		{ value: "", label: __("", 'events') },
		{ value: "city", label: __("City", 'events') },
		{ value: "name", label: __("Name", 'events') },
	]

	const orderListViewOptions = [
		{ value: "ASC", label: __("Ascending", 'events')},
		{ value: "DESC", label: __("Descending", 'events')}
	]

	const inspectorControls = (
		<InspectorControls>
				<PanelBody
					title={__('Data', 'events')}
					initialOpen={true}
				>
					<SelectControl
						label={__('Category', 'events')}
						value={ selectedCategory }
						options={ categoryList }
						onChange={ (value) => { setAttributes( { selectedCategory: value } ); } }
					/>
					<FormTokenField
						label={__('Tags', 'events')}
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
						label={__("From:", 'events')}
						value={ fromDate }
						type="date"
						onChange={(value) => { setAttributes( { fromDate: value } ) }}
					/>
					<TextControl
						label={__("To:", 'events')}
						value={ toDate }
						type="date"
						onChange={(value) => { setAttributes( { toDate: value } ) }}
					/>
					<SelectControl
						label={__('Location', 'events')}
						value={ selectedLocation }
						options={ locationList }
						onChange={ ( value ) => {
							setAttributes( { selectedLocation: value } );
						} }
					/>

					<SelectControl
						label={__('Sorting', 'events')}
						value={ order }
						options={ orderListViewOptions }
						onChange={ ( value ) => {
							setAttributes( { order: value } );
						} }
					/>

					<NumberControl
						label={__('Limit', 'events')}
						value={ limit }
						onChange={ ( value ) => {
							setAttributes( { limit: value } );
						} }
					/>
					
				</PanelBody>
				<PanelBody
					title={__('Design', 'events')}
					initialOpen={true}
				>
					
					<RangeControl
						label={__("Columns on small screens", 'events')}
						max={ 6 }
						min={ 1 }
						help={__("ex. Smartphones", 'events')}
						onChange={(value) => {setAttributes( { columnsSmall: value })}}
						value={ columnsSmall }
					/>
				
			
					<RangeControl
						label={__("Columns on medium screens", 'events')}
						max={ 6 }
						min={ 1 }
						help={__("Tablets and smaller screens", 'events')}
						onChange={(value) => {setAttributes( { columnsMedium: value })}}
						value={ columnsMedium }
					/>
		
					<RangeControl
						label={__("Columns on large screens", 'events')}
						max={ 6 }
						min={ 1 }
						help={__("Desktop screens", 'events')}
						onChange={(value) => {setAttributes( { columnsLarge: value })}}
						value={ columnsLarge }
					/>
					
				</PanelBody>

				<PanelBody
					title={__('Appearance', 'events')}
					initialOpen={true}
				>
					<PanelRow>
						<ToggleControl
							label={ __("Hover-effect", 'events')}
							checked={ dropShadow }
							onChange={ (value) => setAttributes({ dropShadow: value }) }
						/>
					</PanelRow>
					
					
					<label className="components-base-control__label" htmlFor="inspector-range-control-4">{__("Style", 'events')}</label><br />
					<div className="styleSelector">
							<Button onClick={ () => setAttributes({ style: "mini" }) } className={style == "mini" ? "active" : ""}>
								<Icon size="64" className="icon" icon={icons.mini}/>
								<div>{__("Minimal", 'events')}</div>
							</Button>
							<Button onClick={ () => setAttributes({ style: "list" }) } className={style == "list" ? "active" : ""}>
								<Icon size="64" className="icon" icon={icons.list}/>
								<div>{__("List", 'events')}</div>
							</Button>
							<Button onClick={ () => setAttributes({ style: "cards" }) } className={style == "cards" ? "active" : ""}>
								<Icon size="64" className="icon" icon={icons.cards}/>
								<div>{__("Cards", 'events')}</div>
							</Button>
					</div>
						
					
					{ showImages &&
						
						<PanelRow>
							<CheckboxControl
								label={ __("Round images", 'events')}
								checked={ roundImages }
								onChange={ (value) => setAttributes({ roundImages: value }) }
							/>
						</PanelRow>

					}
					<PanelRow>	
						<SelectControl
								label={__('Location', 'events')}
								value={ showLocation }
								options={ locationViewOptions }
								onChange={ ( value ) => {
									setAttributes( { showLocation: value } );
								} }
						/>
					</PanelRow>
					<RangeControl
						label={__("Length of preview text", 'events')}
						max={ 200 }
						min={ 0 }
						help={__("Number of words", 'events')}
						onChange={(value) => {setAttributes( { excerptLength: value })}}
						value={ excerptLength }
					/>
					<PanelRow>
						<CheckboxControl
							label={ __("Show Audience", 'events')}
							checked={ showAudience }
							onChange={ (value) => setAttributes({ showAudience: value }) }
						/>
					</PanelRow>
					<PanelRow>
						<CheckboxControl
							label={ __("Show Speaker", 'events')}
							checked={ showSpeaker }
							onChange={ (value) => setAttributes({ showSpeaker: value }) }
						/>
					</PanelRow>
					
					<PanelRow>
						<CheckboxControl
							label={ __("Show category", 'events')}
							checked={ showCategory }
							onChange={ (value) => setAttributes({ showCategory: value }) }
						/>
					</PanelRow>
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
				
			<div className="components-placeholder is-large">
                <div className="components-placeholder__label">
                    <span className="block-editor-block-icon has-colors">
					<Dashicon icon="calendar-alt" />
			
                    </span>{__("Upcoming Events", "events")}</div>
                <div className="components-placeholder__instructions">{__("See for settings in the inspector. The result can be seen in the frontend", "events")}</div>
            </div>
				
			</div>
		</>
	);

}

export default EditUpcoming;