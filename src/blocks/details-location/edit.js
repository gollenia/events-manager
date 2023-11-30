/**
 * Wordpress dependencies
 */
import { useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

import { RichText } from '@wordpress/block-editor';
import { useEntityProp } from '@wordpress/core-data';
import { select, useSelect } from '@wordpress/data';
/**
 * Internal dependencies
 */

import Inspector from './inspector.js';

/**
 * @param {Props} props
 * @return {JSX.Element} Element
 */
const edit = ( props ) => {
	const postType = select( 'core/editor' ).getCurrentPostType();

	if ( postType !== 'event' ) return <></>;

	const [ meta, setMeta ] = useEntityProp( 'postType', postType, 'meta' );

	const {
		attributes: { roundImage, format, description, showAddress, showZip, showCity, showCountry, showTitle },
		setAttributes,
	} = props;

	const query = {
		per_page: 1,
		_embed: true,
		metaKey: '_location_id',
		metaValue: meta._location_id ?? 0,
	};

	const post = useSelect( ( select ) => select( 'core' ).getEntityRecords( 'postType', 'location', query ) );

	const location = post?.length ? post[ 0 ] : null;

	const blockProps = useBlockProps( { className: 'event-details-item' } );

	console.log( location );

	return (
		<div { ...blockProps }>
			<Inspector { ...props } />

			<div className="event-details__item">
				<div className="event-details__icon">
					<i className="material-icons">place</i>
				</div>
				<div>
					<RichText
						tagName="h5"
						className="event-details_title description-editable"
						placeholder={ __( 'Location', 'events' ) }
						value={ description }
						onChange={ ( value ) => {
							setAttributes( { description: value } );
						} }
					/>
					{ location ? (
						<div className="event-details_audience description-editable">
							{ showTitle && <div>{ location.title.rendered }</div> }
							{ location?.meta?._location_address && showAddress && (
								<div>{ location.meta._location_address }</div>
							) }
							<div>
								{ location?.meta?._location_postcode && showZip && (
									<span>{ location.meta._location_postcode } </span>
								) }
								{ location?.meta?._location_town && showCity && (
									<span>{ location.meta._location_town }</span>
								) }
							</div>
							{ location?.meta?._location_country && showCountry && (
								<div>{ location.meta._location_country }</div>
							) }
						</div>
					) : (
						<div className="event-details_audience description-editable">
							{ showTitle && <div>{ __( 'No location selected', 'events' ) }</div> }
						</div>
					) }
				</div>
			</div>
		</div>
	);
};

export default edit;
