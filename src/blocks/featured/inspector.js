import { InspectorControls } from '@wordpress/block-editor';
import { CheckboxControl, FormTokenField, PanelBody, PanelRow, RangeControl, SelectControl, ToggleControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';



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
			
			<SelectControl
				label={__('Location', 'events')}
				value={ selectedLocation }
				options={ locationList }
				onChange={ ( value ) => {
					setAttributes( { selectedLocation: value } );
				} }
			/>

			
		</PanelBody>

		<PanelBody
			title={__('Appearance', 'events')}
			initialOpen={true}
		>
			<PanelRow>
				<ToggleControl
					label={ __("Drop shadow", 'events')}
					checked={ dropShadow }
					onChange={ (value) => setAttributes({ dropShadow: value }) }
				/>
			</PanelRow>
			
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
