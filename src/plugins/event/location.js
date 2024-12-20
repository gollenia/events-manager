/**
 * Adds a metabox for the page color settings
 */

/**
 * WordPress dependencies
 */
import { ComboboxControl, Icon } from '@wordpress/components';
import { select, useSelect } from '@wordpress/data';
import { PluginDocumentSettingPanel } from '@wordpress/editor';

import { store as coreStore, useEntityProp } from '@wordpress/core-data';
import { __ } from '@wordpress/i18n';
import icons from './icons';

const locationSelector = () => {
	const postType = select( 'core/editor' ).getCurrentPostType();

	if ( postType !== 'event' ) return <></>;

	const [ meta, setMeta ] = useEntityProp( 'postType', postType, 'meta' );

	const locationList = useSelect( ( select ) => {
		const { getEntityRecords } = select( coreStore );
		const query = { per_page: -1, _embed: true };
		const result = getEntityRecords( 'postType', 'location', query );

		let locations = [];
		if ( ! result ) {
			return locations;
		}

		result.map( ( location ) => {

			
			locations.push( {
				value: location.location_id,
				label: location.title.raw,
				media:
					location &&
					location._embedded &&
					location._embedded[ 'wp:featuredmedia' ] &&
					location._embedded[ 'wp:featuredmedia' ][ 0 ].media_details.sizes?.thumbnail?.source_url,
			} );
		} );

		return locations;
	}, [] );

	console.log( meta._location_id );

	return (
		<PluginDocumentSettingPanel
			name="events-location-settings"
			title={ __( 'Location', 'events' ) }
			className="events-location-settings"
		>
			<ComboboxControl
				label={ __( 'Select a location', 'events' ) }
				value={ meta._location_id }
				onChange={ ( value ) => {
					setMeta( { _location_id: value || 0 } );
				} }
				options={ locationList }
				allowReset={ true }
				__experimentalRenderItem={ ( { item } ) => {
					if ( item.value == 0 ) return <></>;
					return (
						<div className="events-speaker-item">
							{ item.media ? (
								<img className="icon-round" width="24px" height="24px" src={ item.media } />
							) : (
								<Icon className="icon-round" icon={ icons.location } height={ 20 } width={ 20 } />
							) }
							{ item.label }
						</div>
					);
				} }
			/>
		</PluginDocumentSettingPanel>
	);
};

export default locationSelector;
