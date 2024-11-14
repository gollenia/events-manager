import { PanelBody, TextControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import React from 'react';

const SetSpaces = ( props ) => {
	const { meta, setMeta } = props;
	return (
		<PanelBody title={ __( 'Spaces Settings', 'events-manager' ) } initialOpen={ true }>
			<TextControl
				label={ __( 'Bookable Spaces', 'events-manager' ) }
				value={ meta._event_spaces }
				type="number"
				onChange={ ( value ) => {
					setMeta( { _event_spaces: value } );
				} }
				disabled={ ! meta._event_rsvp }
			/>

			<TextControl
				label={ __( 'Maximum bookable spaces per booking', 'events-manager' ) }
				value={ meta._event_spaces_max }
				type="number"
				onChange={ ( value ) => {
					setMeta( { _event_spaces_max: value } );
				} }
				disabled={ ! meta._event_rsvp }
			/>
		</PanelBody>
	);
};

export default SetSpaces;
