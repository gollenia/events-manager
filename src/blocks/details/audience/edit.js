/**
 * Wordpress dependencies
 */
import { RichText, useBlockProps } from '@wordpress/block-editor';
import { Icon } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

import { select } from '@wordpress/data';

import { useEntityProp } from '@wordpress/core-data';
/**
 * Internal dependencies
 */

import icon from './icon.js';
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
		attributes: { roundImage, description },
		setAttributes,
	} = props;

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
						placeholder={ __( 'Audience', 'events' ) }
						value={ description }
						onChange={ ( value ) => {
							setAttributes( { description: value } );
						} }
					/>
					<RichText
						tagName="span"
						className="event-details_audience description-editable"
						placeholder={ __( 'Enter audience', 'events' ) }
						value={ meta._event_audience }
						onChange={ ( value ) => {
							setMeta( { _event_audience: value } );
						} }
					/>
				</div>
			</div>
		</div>
	);
};

export default edit;
