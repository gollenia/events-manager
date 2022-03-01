import { InspectorControls } from '@wordpress/block-editor';
import { CheckboxControl, TextControl, ToggleControl, RangeControl, PanelBody, PanelRow, SelectControl, FormTokenField, Icon, Button, RadioControl } from '@wordpress/components';
import { __experimentalNumberControl as NumberControl } from '@wordpress/components'
import { __ } from '@wordpress/i18n'; 
import icons from './icons.js'


const Inspector = (props) => {
	
	const {
		attributes: {
			limit,
			columnsSmall,
			columnsMedium,
			columnsLarge,
			showImages,
			style,
			scope,
			showCategory,
			showLocation,
			excerptLength,
			selectedCategory,
			selectedLocation,
			order,
			showAudience,
			showSpeaker,
			showTagFilter,
			showCategoryFilter,
			showSearch,
			filterStyle,
			filterPosition,
		},
		tagList,
		categoryList,
		tagsFieldValue,
		locationList,
		tagNames,
		setAttributes
	} = props;

	const locationViewOptions = [
		{ value: "", label: __("Don't show", 'events') },
		{ value: "name", label: __("Name", 'events') },
		{ value: "city", label: __("City", 'events') },
		{ value: "country", label: __("Country", 'events') },
		{ value: "state", label: __("State", 'events') },
	]

	const speakerViewOptions = [
		{ value: "", label: __("Don't show", 'events') },
		{ value: "name", label: __("Name only", 'events') },
		{ value: "image", label: __("Name and image", 'events') },
	]

	const scopeOptions = [
		{ value: "future", label: __("Future", 'events') },
		{ value: "past", label: __("Past", 'events') },
		{ value: "today", label: __("Today", 'events') },
		{ value: "tomorrow", label: __("Tomorrow", 'events') },
		{ value: "month", label: __("This month", 'events') },
		{ value: "next-month", label: __("Next month", 'events') }
	];

	const orderListViewOptions = [
		{ value: "ASC", label: __("Ascending", 'events')},
		{ value: "DESC", label: __("Descending", 'events')}
	]

  	return (
		<InspectorControls>
		<PanelBody
			title={__('Data', 'events')}
			initialOpen={true}
		>
			<SelectControl
				multiple
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
			
			<SelectControl
				label={__('Location', 'events')}
				value={ selectedLocation }
				options={ locationList }
				onChange={ ( value ) => {
					setAttributes( { selectedLocation: parseInt(value) } );
				} }
			/>

			<SelectControl
				label={__('Scope', 'events')}
				value={ scope }
				options={ scopeOptions }
				onChange={ ( value ) => {
					setAttributes( { scope: value } );
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
			title={__('Filter', 'events')}
		>
			<CheckboxControl
				label={ __("Show category filter", 'events')}
				checked={ showCategoryFilter }
				onChange={ (value) => setAttributes({ showCategoryFilter: value }) }
			/>
			<CheckboxControl
				label={ __("Show tag filter", 'events')}
				checked={ showTagFilter }
				onChange={ (value) => setAttributes({ showTagFilter: value }) }
			/>
			<CheckboxControl
				label={ __("Show search bar", 'events')}
				checked={ showSearch }
				onChange={ (value) => setAttributes({ showSearch: value }) }
			/>
			<RadioControl 
				label={ __('Position', 'events')}
				help={ __('May not apply on mobile phones', 'events') }
				options={[
					{ label: __("Top", "events"), value: "top"},
					{ label: __("Side", "events"), value: "side"}
				]}
				selected={filterPosition}
				onChange={ (value) => setAttributes({ filterPosition: value }) }

			/>
		</PanelBody>
		<PanelBody
			title={__('Design', 'events')}
			initialOpen={false}
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
				
		
			<SelectControl
					label={__('Location', 'events')}
					value={ showLocation }
					options={ locationViewOptions }
					onChange={ ( value ) => {
						setAttributes( { showLocation: value } );
					} }
			/>

			<SelectControl
				label={__('Show Speaker', 'events')}
				value={ showSpeaker }
				options={ speakerViewOptions }
				onChange={ ( value ) => {
					setAttributes( { showSpeaker: value } );
				} }
			/>
			
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
					label={ __("Show audience", 'events')}
					checked={ showAudience }
					onChange={ (value) => setAttributes({ showAudience: value }) }
				/>
			</PanelRow>

			<PanelRow>
				<CheckboxControl
					label={ __("Show image", 'events')}
					checked={ showImages }
					onChange={ (value) => setAttributes({ showImages: value }) }
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
  	);
};

export default Inspector;
