import { InspectorControls } from '@wordpress/block-editor';
import { CheckboxControl, TextControl, ToggleControl, RangeControl, PanelBody, PanelRow, SelectControl, FormTokenField, Icon, Button } from '@wordpress/components';
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
			dropShadow,
			style,
			showCategory,
			showLocation,
			roundImages,
			excerptLength,
			selectedCategory,
			selectedLocation,
			fromDate,
			toDate, 
			order,
			showAudience,
			showSpeaker
		},
		tagList,
		categoryList,
		tagsFieldValue,
		locationList,
		tagNames,
		setAttributes
	} = props;

	const locationViewOptions = [
		{ value: "", label: __("", 'events') },
		{ value: "city", label: __("City", 'events') },
		{ value: "name", label: __("Name", 'events') },
	]

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
  	);
};

export default Inspector;
