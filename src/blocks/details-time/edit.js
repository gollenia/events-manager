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
import { formatTime } from './../../common/formatDate';
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
		attributes: { roundImage, format },
	} = props;

	const blockProps = useBlockProps( { className: 'event-details-item' } );

	const timeFormatted = () => {
		console.log( meta._event_start_time );
		if ( ! meta ) return;
		const start =
			meta._event_start_date && meta._event_start_time
				? meta?._event_start_date + ' ' + meta?._event_start_time
				: '';
		const end =
			meta?._event_end_date && meta?._event_end_time ? meta?._event_end_date + ' ' + meta?._event_end_time : '';
		return formatTime( start, end );
	};

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
						placeholder={ __( 'Time', 'events' ) }
						value={ description }
						onChange={ ( value ) => {
							setAttributes( { description: value } );
						} }
					/>
					<span className="event-details_audience description-editable">{ timeFormatted() }</span>
				</div>
			</div>
		</div>
	);
};

export default edit;
