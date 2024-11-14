/**
 * Adds a metabox for the page color settings
 */

/**
 * WordPress dependencies
 */
import { SelectControl, TextControl } from '@wordpress/components';
import { select } from '@wordpress/data';
import { PluginDocumentSettingPanel } from '@wordpress/editor';

import { useEntityProp } from '@wordpress/core-data';
import { __ } from '@wordpress/i18n';

const personalData = () => {
	const postType = select( 'core/editor' ).getCurrentPostType();

	if ( postType !== 'event-speaker' ) return <></>;

	const [ meta, setMeta ] = useEntityProp( 'postType', postType, 'meta' );

	return (
		<PluginDocumentSettingPanel
			name="events-location-settings"
			title={ __( 'Personal Information', 'events-manager' ) }
			className="events-location-settings"
		>
			<SelectControl
				label={ __( 'Gender', 'events-manager' ) }
				value={ meta._gender }
				onChange={ ( value ) => {
					setMeta( { _gender: value } );
				} }
				options={ [
					{
						label: __( 'Male', 'events-manager' ),
						value: 'male',
					},
					{
						label: __( 'Female', 'events-manager' ),
						value: 'female',
					},
				] }
			/>

			<TextControl
				type="date"
				label={ __( 'Birthday', 'events-manager' ) }
				value={ meta._birthday }
				onChange={ ( value ) => {
					setMeta( { _birthday: value } );
				} }
			/>
		</PluginDocumentSettingPanel>
	);
};

export default personalData;
