import { InspectorControls } from '@wordpress/block-editor';
import {
	CheckboxControl,
	FormTokenField,
	PanelBody,
	PanelRow,
	RangeControl,
	SelectControl,
	ToggleControl,
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';

const Inspector = ( props ) => {
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
			showSpeaker,
		},
		tagList,
		categoryList,
		tagsFieldValue,
		locationList,
		tagNames,
		setAttributes,
	} = props;

	const locationViewOptions = [
		{ value: '', label: __( '', 'events-manager' ) },
		{ value: 'city', label: __( 'City', 'events-manager' ) },
		{ value: 'name', label: __( 'Name', 'events-manager' ) },
	];

	const orderListViewOptions = [
		{ value: 'ASC', label: __( 'Ascending', 'events-manager' ) },
		{ value: 'DESC', label: __( 'Descending', 'events-manager' ) },
	];

	return (
		<InspectorControls>
			<PanelBody title={ __( 'Data', 'events-manager' ) } initialOpen={ true }>
				<SelectControl
					label={ __( 'Category', 'events-manager' ) }
					value={ selectedCategory }
					options={ categoryList }
					onChange={ ( value ) => {
						setAttributes( { selectedCategory: value } );
					} }
				/>
				<FormTokenField
					label={ __( 'Tags', 'events-manager' ) }
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
					label={ __( 'Location', 'events-manager' ) }
					value={ selectedLocation }
					options={ locationList }
					onChange={ ( value ) => {
						setAttributes( { selectedLocation: value } );
					} }
				/>
			</PanelBody>

			<PanelBody title={ __( 'Appearance', 'events-manager' ) } initialOpen={ true }>
				<PanelRow>
					<ToggleControl
						label={ __( 'Drop shadow', 'events-manager' ) }
						checked={ dropShadow }
						onChange={ ( value ) => setAttributes( { dropShadow: value } ) }
					/>
				</PanelRow>

				<PanelRow>
					<SelectControl
						label={ __( 'Location', 'events-manager' ) }
						value={ showLocation }
						options={ locationViewOptions }
						onChange={ ( value ) => {
							setAttributes( { showLocation: value } );
						} }
					/>
				</PanelRow>
				<RangeControl
					label={ __( 'Length of preview text', 'events-manager' ) }
					max={ 200 }
					min={ 0 }
					help={ __( 'Number of words', 'events-manager' ) }
					onChange={ ( value ) => {
						setAttributes( { excerptLength: value } );
					} }
					value={ excerptLength }
				/>
				<PanelRow>
					<CheckboxControl
						label={ __( 'Show Audience', 'events-manager' ) }
						checked={ showAudience }
						onChange={ ( value ) => setAttributes( { showAudience: value } ) }
					/>
				</PanelRow>
				<PanelRow>
					<CheckboxControl
						label={ __( 'Show Speaker', 'events-manager' ) }
						checked={ showSpeaker }
						onChange={ ( value ) => setAttributes( { showSpeaker: value } ) }
					/>
				</PanelRow>

				<PanelRow>
					<CheckboxControl
						label={ __( 'Show category', 'events-manager' ) }
						checked={ showCategory }
						onChange={ ( value ) => setAttributes( { showCategory: value } ) }
					/>
				</PanelRow>
			</PanelBody>
		</InspectorControls>
	);
};

export default Inspector;
