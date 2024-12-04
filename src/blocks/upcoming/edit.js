/**
 * Wordpress dependencies
 */
import { AlignmentToolbar, BlockControls, RichText, useBlockProps } from '@wordpress/block-editor';
import { store as coreStore } from '@wordpress/core-data';
import { useSelect } from '@wordpress/data';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import Inspector from './inspector.js';

/**
 * @param {Props} props
 * @return {JSX.Element} Element
 */
const EditUpcoming = ( props ) => {
	const {
		attributes: { textAlignment, selectedTags, altText },
		setAttributes,
	} = props;

	const categoryList = useSelect( ( select ) => {
		const { getEntityRecords } = select( coreStore );
		const query = { hide_empty: true };
		const list = getEntityRecords( 'taxonomy', 'event-categories', query );

		let categoryOptionsArray = [ { value: 0, label: '' } ];
		if ( ! list ) {
			return categoryOptionsArray;
		}

		list.map( ( category ) => {
			categoryOptionsArray.push( { value: category.id, label: category.name } );
		} );
		return categoryOptionsArray;
	}, [] );

	const tagList = useSelect( ( select ) => {
		const { getEntityRecords } = select( coreStore );
		const query = { hide_empty: true };
		const list = getEntityRecords( 'taxonomy', 'event-tags', query );

		if ( ! list ) {
			return null;
		}
		return list;
	}, [] );

	const locationList = useSelect( ( select ) => {
		const { getEntityRecords } = select( coreStore );
		const query = { per_page: -1 };
		const list = getEntityRecords( 'postType', 'location', query );

		let locationOptionsArray = [ { value: 0, label: '' } ];
		if ( ! list ) {
			return locationOptionsArray;
		}

		list.map( ( location ) => {
			locationOptionsArray.push( { value: location.location_id, label: location.title.raw } );
		} );

		return locationOptionsArray;
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

	const blockProps = useBlockProps();

	return (
		<>
			<Inspector
				{ ...props }
				tagList={ tagList }
				categoryList={ categoryList }
				tagsFieldValue={ tagsFieldValue }
				tagNames={ tagNames }
				locationList={ locationList }
			/>
			<BlockControls>
				<AlignmentToolbar
					value={ textAlignment }
					onChange={ ( event ) => setAttributes( { textAlignment: event } ) }
				/>
			</BlockControls>
			<div { ...blockProps }>
				<div className="components-placeholder is-large">
					<div className="components-placeholder__label">{ __( 'Upcoming Events', 'events' ) }</div>

					<div className="components-placeholder__instructions">
						<RichText
							tagName="p"
							placeholder={ __( 'Set a text here to show when no events are visible', 'events' ) }
							value={ altText }
							onChange={ ( value ) => {
								setAttributes( { altText: value } );
							} }
						/>
					</div>
				</div>
			</div>
		</>
	);
};

export default EditUpcoming;
