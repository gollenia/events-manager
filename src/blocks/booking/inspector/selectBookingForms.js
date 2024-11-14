import { PanelBody, SelectControl } from '@wordpress/components';
import { store as coreStore } from '@wordpress/core-data';
import { useSelect } from '@wordpress/data';

import { __ } from '@wordpress/i18n';
import React from 'react';

const SelectBookingForms = ( props ) => {
	const { meta, setMeta } = props;
	const bookingFormList = useSelect( ( select ) => {
		const { getEntityRecords } = select( coreStore );
		const query = { per_page: -1 };
		const list = getEntityRecords( 'postType', 'bookingform', query );

		let formsArray = [ { value: 0, label: '' } ];
		if ( ! list ) {
			return formsArray;
		}

		list.map( ( form ) => {
			formsArray.push( { value: form.id, label: form.title.raw } );
		} );

		return formsArray;
	}, [] );

	const attendeeFormList = useSelect( ( select ) => {
		const { getEntityRecords } = select( coreStore );
		const query = { per_page: -1 };
		const list = getEntityRecords( 'postType', 'attendeeform', query );

		let formsArray = [ { value: 0, label: '' } ];
		if ( ! list ) {
			return formsArray;
		}

		list.map( ( form ) => {
			formsArray.push( { value: form.id, label: form.title.raw } );
		} );

		return formsArray;
	}, [] );

	return (
		<PanelBody title={ __( 'Booking Forms', 'events-manager' ) } initialOpen={ true }>
			<SelectControl
				label={ __( 'Registration Form', 'events-manager' ) }
				value={ meta._booking_form }
				onChange={ ( value ) => {
					setMeta( { _booking_form: value } );
				} }
				disabled={ ! meta._event_rsvp }
				options={ bookingFormList }
				disableCustomColors={ true }
			/>

			<SelectControl
				label={ __( 'Attendee Form', 'events-manager' ) }
				value={ meta._attendee_form }
				onChange={ ( value ) => {
					setMeta( { _attendee_form: value } );
				} }
				disabled={ ! meta._event_rsvp }
				options={ attendeeFormList }
				disableCustomColors={ true }
			/>
		</PanelBody>
	);
};

export default SelectBookingForms;
