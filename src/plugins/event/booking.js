/**
 * Adds a metabox for the page color settings
 */

/**
 * WordPress dependencies
 */
import { PluginDocumentSettingPanel } from '@wordpress/edit-post';
import { SelectControl, Button, ToggleControl, PanelRow } from '@wordpress/components';
import { useSelect, select } from '@wordpress/data';
import './booking.scss';

import { store as coreStore, useEntityProp } from '@wordpress/core-data';
import { __ } from '@wordpress/i18n';

 
const bookingOptions = () => {

	const postType = select( 'core/editor' ).getCurrentPostType()

	if(postType !== 'event') return <></>;

	const [ meta, setMeta ] = useEntityProp( 'postType', postType, 'meta' );


	const bookingFormList = useSelect( ( select ) => {
		const { getEntityRecords } = select( coreStore );
		const query = { per_page: -1 };
		const list = getEntityRecords( 'postType', 'bookingform', query );
		
		let formsArray = [{value: 0, label: ""}];
		if (!list) {
			return formsArray
		}
		
		list.map( ( form ) => {
			formsArray.push( { value: form.id, label: form.title.raw } );
		})
		
		return formsArray	
		
	}, [] );
	console.log(meta)

	const attendeeFormList = useSelect( ( select ) => {
		const { getEntityRecords } = select( coreStore );
		const query = { per_page: -1 };
		const list = getEntityRecords( 'postType', 'attendeeform', query );
		
		let formsArray = [{value: 0, label: ""}];
		if (!list) {
			return formsArray
		}
		
		list.map( ( form ) => {
			formsArray.push( { value: form.id, label: form.title.raw } );
		})
		
		return formsArray	
	}, [] );

	return (
		<PluginDocumentSettingPanel
			name="events-booking-options"
			title={__('Bookings', 'events')}
			className="events-form-settings"
		>

		<SelectControl
			label={__('Registration Form', 'events')}
			value={meta._booking_form}
			onChange={(value) => {setMeta({_booking_form: value})}}
			options={bookingFormList}
			
			disableCustomColors={true}
	   />

		<SelectControl
			label={__('Attendee Form', 'events')}
			value={meta._attendee_form}
			
			onChange={(value) => {setMeta({_attendee_form: value})}}
			options={attendeeFormList}
			disableCustomColors={true}
	   />
		
		</PluginDocumentSettingPanel>
	);
};

export default bookingOptions;
