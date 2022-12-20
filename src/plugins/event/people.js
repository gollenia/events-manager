/**
 * Adds a metabox for the page color settings
 */

/**
 * WordPress dependencies
 */
import { SelectControl, TextControl } from '@wordpress/components';
import { select, useSelect } from '@wordpress/data';
import { PluginDocumentSettingPanel } from '@wordpress/edit-post';

import { store as coreStore, useEntityProp } from '@wordpress/core-data';
import { __ } from '@wordpress/i18n';

const peopleSelector = () => {
	const postType = select( 'core/editor' ).getCurrentPostType();

	if ( postType !== 'event' ) return <></>;

	const [ meta, setMeta ] = useEntityProp( 'postType', postType, 'meta' );

	const speakerList = useSelect( ( select ) => {
		const { getEntityRecords } = select( coreStore );
		const query = { per_page: -1 };
		const list = getEntityRecords( 'postType', 'event-speaker', query );

		let speakerArray = [ { value: 0, label: '' } ];
		if ( ! list ) {
			return speakerArray;
		}

		list.map( ( speaker ) => {
			speakerArray.push( { value: speaker.id, label: speaker.title.raw } );
		} );

		return speakerArray;
	}, [] );

	return (
		<PluginDocumentSettingPanel
			name="events-location-settings"
			title={ __( 'Persons', 'events' ) }
			className="events-location-settings"
		>
			<SelectControl
				label={ __( 'Select a speaker', 'events' ) }
				value={ meta._speaker_id }
				onChange={ ( value ) => {
					setMeta( { _speaker_id: value } );
				} }
				options={ speakerList }
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
