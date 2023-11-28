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
import icons from './icons.js';
import Inspector from './inspector.js';

/**
 * @param {Props} props
 * @return {JSX.Element} Element
 */
const edit = ( props ) => {
	const postType = select( 'core/editor' ).getCurrentPostType();

	const [ meta, setMeta ] = useEntityProp( 'postType', postType, 'meta' );

	const {
		attributes: { showPortrait, description, showLink, customSpeakerId, url },
		setAttributes,
	} = props;

	const id = customSpeakerId || meta._speaker_id;

	const speaker = useSelect(
		( select ) => {
			const { getEntityRecord } = select( 'core' );

			if ( ! id ) return [];
			const query = {
				per_page: 1,
				include: [ id ],
				_embed: true,
				meta: { _email: 'true' },
			};
			const speaker = getEntityRecord( 'postType', 'event-speaker', id, query );
			return speaker;
		},
		[ id ]
	);

	const link = url ?? speaker?.meta?.email;

	console.log( speaker );
	const image = speaker?._embedded?.[ 'wp:featuredmedia' ]?.[ 0 ]?.source_url ?? null;

	const blockProps = useBlockProps( { className: 'event-details-item' } );

	return (
		<div { ...blockProps }>
			<Inspector { ...props } />

			<div className="event-details__item">
				<div className="event-details__icon">
					{ showPortrait && image ? <img src={ image } /> : <Icon icon={ icons.info } size={ 32 } /> }
				</div>
				<div className="event-details-text">
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
				{ showLink && link && (
					<div className="event-details-action">
						<a href="#">
							<Icon icon={ link.includes( 'mailto' ) ? icons.mail : icons.website } size={ 24 } />
						</a>
					</div>
				) }
			</div>
		</div>
	);
};

export default edit;
