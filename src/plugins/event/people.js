/**
 * Adds a metabox for the page color settings
 */

/**
 * WordPress dependencies
 */
import { ComboboxControl, Icon, TextControl } from '@wordpress/components';
import { select, useSelect } from '@wordpress/data';
import { PluginDocumentSettingPanel } from '@wordpress/edit-post';

import { store as coreStore, useEntityProp } from '@wordpress/core-data';
import { __ } from '@wordpress/i18n';
import icons from './icons';
import './speaker.scss';

const peopleSelector = () => {
	const postType = select( 'core/editor' ).getCurrentPostType();

	if ( postType !== 'event' ) return <></>;

	const [ meta, setMeta ] = useEntityProp( 'postType', postType, 'meta' );

	const speakerList = useSelect( ( select ) => {
		const { getEntityRecords } = select( coreStore );
		const query = { per_page: -1 };
		const list = getEntityRecords( 'postType', 'event-speaker', query );

		let speakerArray = [];
		if ( ! list ) {
			return speakerArray;
		}

		list.map( ( speaker ) => {
			speakerArray.push( {
				value: speaker.id,
				label: speaker.title.raw,
				media:
					speaker &&
					speaker._embedded &&
					speaker._embedded[ 'wp:featuredmedia' ] &&
					speaker._embedded[ 'wp:featuredmedia' ][ 0 ].media_details.sizes?.thumbnail?.source_url,
			} );
		} );

		return speakerArray;
	}, [] );

	return (
		<PluginDocumentSettingPanel
			name="events-location-settings"
			title={ __( 'Persons', 'events' ) }
			className="events-location-settings"
		>
			<ComboboxControl
				label={ __( 'Select a speaker', 'events' ) }
				value={ meta._speaker_id }
				onChange={ ( value ) => {
					setMeta( { _speaker_id: value } );
				} }
				options={ speakerList }
				__experimentalRenderItem={ ( { item } ) => {
					if ( item.value == 0 ) return <></>;
					return (
						<div className="events-manager-speaker-item">
							{ item.media ? (
								<img className="icon-round" width="24px" height="24px" src={ item.media } />
							) : (
								<Icon className="icon-round" icon={ icons.person } height={ 24 } width={ 24 } />
							) }
							{ item.label }
						</div>
					);
				} }
			/>

			<TextControl
				label={ __( 'Audience', 'events' ) }
				value={ meta._event_audience }
				onChange={ ( value ) => {
					setMeta( { _event_audience: value } );
				} }
			/>
		</PluginDocumentSettingPanel>
	);
};

export default peopleSelector;
