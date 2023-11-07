/**
 * Wordpress dependencies
 */
import { useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

import { RichText } from '@wordpress/block-editor';
import { Icon } from '@wordpress/components';
import { useEntityProp } from '@wordpress/core-data';
import { select, useSelect } from '@wordpress/data';
/**
 * Internal dependencies
 */

import icon from './icon';
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
		attributes: { roundImage, format, description, showAddress, showZip, showCity, showCountry },
	} = props;

	const query = {
		per_page: 1,
		_embed: true,
		metaKey: '_location_id',
		metaValue: meta._location_id ?? 0,
	};

	const post = useSelect( ( select ) => select( 'core' ).getEntityRecords( 'postType', 'location', query ) );

	const location = post?.length ? post[ 0 ]?.meta : null;

	const blockProps = useBlockProps();

	return (
		<div { ...blockProps }>
			<Inspector { ...props } />

			<div className="event-details__item">
				<div className="event-details__icon">
					<Icon icon={ icon } size={ 32 } roundImage={ roundImage } />
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
					<div className="event-details_audience description-editable">
						{ location?._location_address && showAddress && <div>{ location?._location_address }</div> }
						<div>
							{ location?._location_postcode && showZip && <span>{ location?._location_postcode }</span> }
							{ location?._location_town && showCity && <span>{ location?._location_town }</span> }
						</div>
						{ location?._location_country && showCountry && <div>{ location?._location_country }</div> }
					</div>
				</div>
			</div>
		</div>
	);
};

export default edit;
