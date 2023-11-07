/**
 * Wordpress dependencies
 */
import { useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

import { select } from '@wordpress/data';

import { RichText } from '@wordpress/block-editor';
import { Icon } from '@wordpress/components';
import { useEntityProp } from '@wordpress/core-data';
import { useSelect } from '@wordpress/data';
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

	const [ meta, setMeta ] = useEntityProp( 'postType', postType, 'meta' );

	const {
		attributes: { roundImage, showPortrait, description, showMail },
		setAttributes,
	} = props;

	const speaker = useSelect(
		( select ) => {
			const { getEntityRecords } = select( 'core' );
			console.log( meta._speaker_id );
			if ( ! meta._speaker_id ) return null;
			const query = {
				per_page: 1,
				include: [ meta._speaker_id ],
				_embed: true,
				meta: { _email: 'true' },
			};
			const speakers = getEntityRecords( 'postType', 'event-speaker', query );
			if ( speakers?.length ) return speakers[ 0 ];
		},
		[ meta._event_speaker ]
	);

	const image = speaker?._embedded?.[ 'wp:featuredmedia' ]?.[ 0 ]?.source_url ?? null;
	console.log( image );

	const blockProps = useBlockProps();

	return (
		<div { ...blockProps }>
			<Inspector { ...props } />

			<div className="event-details__item">
				<div className="event-details__icon">
					{ showPortrait && image ? (
						<img src={ image } />
					) : (
						<Icon icon={ icon } size={ 32 } roundImage={ roundImage } />
					) }
				</div>
				<div>
					<RichText
						tagName="h5"
						className="event-details_title description-editable"
						placeholder={ __( 'Speaker', 'events' ) }
						value={ description }
						onChange={ ( value ) => {
							setAttributes( { description: value } );
						} }
					/>
					<span className="event-details_audience">{ speaker?.title?.rendered }</span>
				</div>
				{ showMail && (
					<div className="event-details__action">
						<a href="#">
							<Icon icon="email" size={ 32 } />
						</a>
					</div>
				) }
			</div>
		</div>
	);
};

export default edit;
