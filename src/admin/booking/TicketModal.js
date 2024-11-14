import { Button, Modal, SelectControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import React, { useEffect } from 'react';
import AdminField from './AdminField';

const TicketModal = ( { store, onCancel, onSave } ) => {
	const [ state, dispatch ] = store;
	const ticket = state.currentTicket != 999 ? state.data.attendees[ state.currentTicket ] : null;
	const { attendee_fields, available_tickets } = state.data;
	const { currentTicketIndex } = state;

	const [ shadowTicket, setShadowTicket ] = React.useState( ticket );

	useEffect( () => {
		setShadowTicket( ticket );
	}, [ ticket ] );

	const ticketOptions = () => {
		const options = Object.keys( available_tickets ).map( ( key ) => {
			return { label: available_tickets[ key ].name, value: available_tickets[ key ].id };
		} );

		return options;
	};

	return (
		<>
			{ ticket && (
				<Modal onRequestClose={ onCancel }>
					<div className="events-ticket-modal-content">
						<h2>{ __( 'Edit Ticket', 'events-manager' ) }</h2>
						<div>
							<SelectControl
								label="Ticket Type"
								type="select"
								value={ shadowTicket?.id }
								onChange={ ( value ) => {
									const newTicket = { ...shadowTicket, id: parseInt( value ) };
									setShadowTicket( newTicket );
								} }
								options={ ticketOptions() }
							/>
						</div>
						<div>
							{ attendee_fields.map( ( field ) => {
								return (
									<AdminField
										admin={ true }
										{ ...field }
										key={ field.fieldid }
										label={ field.label }
										value={ shadowTicket?.fields[ field.fieldid ] }
										onChange={ ( value ) => {
											const newTicket = {
												...shadowTicket,
												fields: { ...shadowTicket.fields, [ field.fieldid ]: value },
											};
											setShadowTicket( newTicket );
										} }
									/>
								);
							} ) }
						</div>
						<div className="modal-actions">
							<Button
								onClick={ () => {
									onCancel();
								} }
								variant="secondary"
							>
								Cancel
							</Button>
							<Button
								onClick={ () => {
									onSave( shadowTicket, currentTicketIndex );
								} }
								variant="primary"
							>
								OK
							</Button>
						</div>
					</div>
				</Modal>
			) }
		</>
	);
};

export default TicketModal;
