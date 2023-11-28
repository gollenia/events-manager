/**
 * Adds a metabox for the page color settings
 */

/**
 * WordPress dependencies
 */
import { TextControl } from '@wordpress/components';
import { select } from '@wordpress/data';
import { PluginDocumentSettingPanel } from '@wordpress/edit-post';

import { useEntityProp } from '@wordpress/core-data';
import { __ } from '@wordpress/i18n';

const peopleSelector = () => {
	const postType = select( 'core/editor' ).getCurrentPostType();

	if ( postType !== 'event' ) return <></>;

	const [ meta, setMeta ] = useEntityProp( 'postType', postType, 'meta' );

	return (
		<PluginDocumentSettingPanel
			name="events-location-settings"
			title={ __( 'Persons', 'events' ) }
			className="events-location-settings"
		>
			<TextControl
				type="email"
				label={ __( 'E-Mail', 'events' ) }
				value={ meta._email }
				onChange={ ( value ) => {
					setMeta( { _email: value } );
				} }
			/>

			<TextControl
				type="tel"
				label={ __( 'Telephone', 'events' ) }
				value={ meta._tel }
				onChange={ ( value ) => {
					setMeta( { _tel: value } );
				} }
			/>
		</PluginDocumentSettingPanel>
	);
};

export default peopleSelector;
