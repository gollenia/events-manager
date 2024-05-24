import React from 'react';

const TicketModal = ( { visible, ticket, attendeeFields, onCancel, onSave } ) => {
	const [ shadowTicket, setShadowTicket ] = React.useState( ticket );

	return (
		<div className={ `events-ticket-modal${ visible ? ' open' : '' }` }>
			<div className="events-ticket-modal-content">
				<h2>Add Attendee</h2>
				<table className="form-table">
					{ attendeeFields.map( ( field ) => {
						return (
							<InputField
								admin={ true }
								{ ...field }
								key={ field.fieldid }
								label={ field.label }
								value={ shadowTicket.fields[ field.fieldid ] }
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
				</table>
				<div className="modal-actions">
					<button
						onClick={ () => {
							onCancel();
						} }
					>
						Cancel
					</button>
					<button
						onClick={ () => {
							onSave( shadowTicket );
						} }
					>
						OK
					</button>
				</div>
			</div>
		</div>
	);
};

export default TicketModal;
