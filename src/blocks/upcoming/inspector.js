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
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';
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
		categoryList,
		tagsFieldValue,
		locationList,
		tagNames,
		setAttributes,
	} = props;

	const postType = window.eventBlocksLocalization?.post_type;

	const locationViewOptions = [
		{ value: '', label: __( "Don't show", 'events-manager' ) },
		{ value: 'name', label: __( 'Name', 'events-manager' ) },
		{ value: 'city', label: __( 'City', 'events-manager' ) },
		{ value: 'country', label: __( 'Country', 'events-manager' ) },
		{ value: 'state', label: __( 'State', 'events-manager' ) },
	];

	const speakerViewOptions = [
		{ value: '', label: __( "Don't show", 'events-manager' ) },
		{ value: 'name', label: __( 'Name only', 'events-manager' ) },
		{ value: 'image', label: __( 'Name and image', 'events-manager' ) },
	];

	const scopeOptions = [
		{ value: 'future', label: __( 'Future', 'events-manager' ) },
		{ value: 'past', label: __( 'Past', 'events-manager' ) },
		{ value: 'today', label: __( 'Today', 'events-manager' ) },
		{ value: 'tomorrow', label: __( 'Tomorrow', 'events-manager' ) },
		{ value: 'month', label: __( 'This month', 'events-manager' ) },
		{ value: 'next-month', label: __( 'Next month', 'events-manager' ) },
	];

	const orderListViewOptions = [
		{ value: 'ASC', label: __( 'Ascending', 'events-manager' ) },
		{ value: 'DESC', label: __( 'Descending', 'events-manager' ) },
	];

	return (
		<InspectorControls>
			<PanelBody title={ __( 'Data', 'events-manager' ) } initialOpen={ true }>
				<SelectControl
					multiple
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
						setAttributes( { selectedLocation: parseInt( value ) } );
					} }
				/>

				<SelectControl
					label={ __( 'Scope', 'events-manager' ) }
					value={ scope }
					options={ scopeOptions }
					onChange={ ( value ) => {
						setAttributes( { scope: value } );
					} }
				/>

				<SelectControl
					label={ __( 'Sorting', 'events-manager' ) }
					value={ order }
					options={ orderListViewOptions }
					onChange={ ( value ) => {
						setAttributes( { order: value } );
					} }
				/>

				<RangeControl
					label={ __( 'Limit', 'events-manager' ) }
					max={ 100 }
					min={ 1 }
					value={ limit }
					onChange={ ( value ) => {
						setAttributes( { limit: value } );
					} }
				/>
				{ postType === 'event' && (
					<CheckboxControl
						label={ __( 'Exclude current event', 'events-manager' ) }
						checked={ excludeCurrent }
						onChange={ ( value ) => setAttributes( { excludeCurrent: value } ) }
						help={ __( 'If applicable, exclude the current event from the list', 'events-manager' ) }
					/>
				) }
			</PanelBody>
			<PanelBody title={ __( 'Filter', 'events-manager' ) }>
				<CheckboxControl
					label={ __( 'Show category filter', 'events-manager' ) }
					checked={ showCategoryFilter }
					onChange={ ( value ) => setAttributes( { showCategoryFilter: value } ) }
				/>
				<CheckboxControl
					label={ __( 'Show tag filter', 'events-manager' ) }
					checked={ showTagFilter }
					onChange={ ( value ) => setAttributes( { showTagFilter: value } ) }
				/>
				<CheckboxControl
					label={ __( 'Show search bar', 'events-manager' ) }
					checked={ showSearch }
					onChange={ ( value ) => setAttributes( { showSearch: value } ) }
				/>
				<RadioControl
					label={ __( 'Position', 'events-manager' ) }
					help={ __( 'May not apply on mobile phones', 'events-manager' ) }
					options={ [
						{ label: __( 'Top', 'events-manager' ), value: 'top' },
						{ label: __( 'Side', 'events-manager' ), value: 'side' },
					] }
					selected={ filterPosition }
					onChange={ ( value ) => setAttributes( { filterPosition: value } ) }
				/>
			</PanelBody>

			<PanelBody title={ __( 'Appearance', 'events-manager' ) } initialOpen={ true }>
				<label className="components-base-control__label" htmlFor="inspector-range-control-4">
					{ __( 'Style', 'events-manager' ) }
				</label>
				<br />

				<div className="styleSelector">
					<Button
						onClick={ () => setAttributes( { view: 'mini' } ) }
						className={ view == 'mini' ? 'active' : '' }
					>
						<Icon size="64" className="icon" icon={ icons.mini } />
						<div>{ __( 'Table', 'events-manager' ) }</div>
					</Button>
					<Button
						onClick={ () => setAttributes( { view: 'list' } ) }
						className={ view == 'list' ? 'active' : '' }
					>
						<Icon size="64" className="icon" icon={ icons.list } />
						<div>{ __( 'List', 'events-manager' ) }</div>
					</Button>
					<Button
						onClick={ () => setAttributes( { view: 'cards' } ) }
						className={ view == 'cards' ? 'active' : '' }
					>
						<Icon size="64" className="icon" icon={ icons.cards } />
						<div>{ __( 'Cards', 'events-manager' ) }</div>
					</Button>
				</div>

				<CheckboxControl
					label={ __( 'Let user select style', 'events-manager' ) }
					checked={ userStylePicker }
					onChange={ ( value ) => setAttributes( { userStylePicker: value } ) }
				/>

				<SelectControl
					label={ __( 'Location', 'events-manager' ) }
					value={ showLocation }
					options={ locationViewOptions }
					onChange={ ( value ) => {
						setAttributes( { showLocation: value } );
					} }
				/>

				<SelectControl
					label={ __( 'Show Speaker', 'events-manager' ) }
					value={ showSpeaker }
					options={ speakerViewOptions }
					onChange={ ( value ) => {
						setAttributes( { showSpeaker: value } );
					} }
				/>

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
						label={ __( 'Show audience', 'events-manager' ) }
						checked={ showAudience }
						onChange={ ( value ) => setAttributes( { showAudience: value } ) }
					/>
				</PanelRow>

				<PanelRow>
					<CheckboxControl
						label={ __( 'Show image', 'events-manager' ) }
						checked={ showImages }
						onChange={ ( value ) => setAttributes( { showImages: value } ) }
					/>
				</PanelRow>

				<PanelRow>
					<CheckboxControl
						label={ __( 'Show category', 'events-manager' ) }
						checked={ showCategory }
						onChange={ ( value ) => setAttributes( { showCategory: value } ) }
					/>
				</PanelRow>
				<CheckboxControl
					label={ __( 'Show if event is booked up or nearly booked up', 'events-manager' ) }
					checked={ showBookedUp }
					onChange={ ( value ) => setAttributes( { showBookedUp: value } ) }
				/>
				<RangeControl
					label={ __( 'Warning threshold', 'events-manager' ) }
					value={ bookedUpWarningThreshold }
					onChange={ ( value ) => setAttributes( { bookedUpWarningThreshold: value } ) }
					min={ 0 }
					max={ 10 }
					help={ __(
						'Show a warning that the event is nearly booked up when only this number of spaces are left',
						'events-manager'
					) }
				/>
			</PanelBody>
		</InspectorControls>
	);
};

export default Inspector;
