/**
 * Wordpress dependencies
 */
import { useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

import { select } from '@wordpress/data';

import { RichText } from '@wordpress/block-editor';
import { useEntityProp } from '@wordpress/core-data';
import { useSelect } from '@wordpress/data';
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

	const [ meta, setMeta ] = useEntityProp( 'postType', postType, 'meta' );

	const {
		attributes: { showPortrait, description, showLink, customSpeakerId, url, linkTo },
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

	const link = ( () => {
		switch ( linkTo ) {
			case 'mail':
				return `mailto:${ speaker?.meta?._email }`;
			case 'call':
				return `tel:${ speaker?.meta?._phone }`;
			case 'public':
				return speaker?.link;
			case 'custom':
				return url;
			default:
				return null;
		}
	} )();

	const LinkIcon = ( () => {
		const socialMediaIcons = [ 'facebook', 'instagram', 'youtube', 'github' ];

		if ( linkTo === 'custom' ) {
			for ( const icon of socialMediaIcons ) {
				if ( url.includes( icon ) ) {
					return icon;
				}
			}
		}

		return linkTo === 'custom' ? 'link' : linkTo;
	} )();
	const image = speaker?._embedded?.[ 'wp:featuredmedia' ]?.[ 0 ]?.source_url ?? null;

	const blockProps = useBlockProps( { className: 'event-details-item' } );

	return (
		<div { ...blockProps }>
			<Inspector { ...props } />

			<div className="event-details__icon">
				{ showPortrait && image ? (
					<img src={ image } />
				) : (
					<i className="material-icons">{ speaker?.gender ?? 'male' }</i>
				) }
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
						<i className="material-icons">{ LinkIcon }</i>
					</a>
				</div>
			) }
		</div>
	);
};

export default edit;
