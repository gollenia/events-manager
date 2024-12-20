import { Button, CheckboxControl, PanelBody, TextControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import React from 'react';
import TicketModal from './ticketModal';

const SetBooking = ( props ) => {
	const { meta, setMeta } = props;
	const [ showTickets, setShowTickets ] = React.useState( false );
	
	
	console.log(window?.eventBlocksLocalization?.bookings_enabled);

	return (
		<PanelBody title={ __( 'Booking Settings', 'events' ) } initialOpen={ true }>

			{ !window?.eventBlocksLocalization?.bookings_enabled?.is_enabled ? (
			<div className="inspector-message inspector-message--error">
				<div><b>{ __( 'Bookings are disabled', 'events' ) }</b></div>
				<span>{ window?.eventBlocksLocalization?.bookings_enabled?.message }</span>
			</div>
			) : null }

			<CheckboxControl
				label={ __( 'Enable Bookings', 'events' ) }
				checked={ meta._event_rsvp }
				onChange={ ( value ) => {
					setMeta( { _event_rsvp: value } );
				} }
				disabled={ ! window?.eventBlocksLocalization?.bookings_enabled?.is_enabled }
			/>

			<TextControl
				label={ __( 'Booking Start Date', 'events' ) }
				value={ meta._event_rsvp_start }
				type="datetime-local"
				onChange={ ( value ) => {
					setMeta( { _event_rsvp_start: value } );
				} }
				disabled={ ! meta._event_rsvp }
			/>

			<TextControl
				label={ __( 'Booking End Date', 'events' ) }
				value={ meta._event_rsvp_end }
				type="datetime-local"
				onChange={ ( value ) => {
					setMeta( { _event_rsvp_end: value } );
				} }
				disabled={ ! meta._event_rsvp }
			/>

			<Button
				onClick={ () => setShowTickets( ! showTickets ) }
				variant="secondary"
				disabled={ ! meta._event_rsvp }
			>
				{ __( 'Edit Tickets', 'events' ) }
			</Button>
			<TicketModal
				{ ...props }
				meta={ meta }
				setMeta={ setMeta }
				showTickets={ showTickets }
				setShowTickets={ setShowTickets }
			/>
		</PanelBody>
	);
};

export default SetBooking;
