import { useState } from 'react';

const BookingTicket = ( props ) => {
	const { ticket, index, onUpdate, onDelete } = props;

	const [ editing, setEditing ] = useState( false );
	const [ data, setData ] = useState( ticket );

	console.log( data );
	return (
		<>
			{ editing || data.is_new ? (
				<tr>
					<td>
						<input
							type="text"
							value={ data.ticket_name }
							onChange={ ( event ) => setData( { ...data, ticket_name: event.target.value } ) }
						/>
					</td>
					<td>
						<input
							type="text"
							value={ data.ticket_description }
							onChange={ ( event ) => setData( { ...data, ticket_description: event.target.value } ) }
						/>
					</td>
					<td>
						<input
							type="text"
							value={ data.ticket_price }
							onChange={ ( event ) => setData( { ...data, ticket_price: event.target.value } ) }
						/>
					</td>
					<td>
						<input
							type="text"
							value={ data.ticket_spaces }
							onChange={ ( event ) => setData( { ...data, ticket_spaces: event.target.value } ) }
						/>
					</td>
					<td>
						<input
							type="text"
							value={ data.ticket_min }
							onChange={ ( event ) => setData( { ...data, ticket_min: event.target.value } ) }
						/>
					</td>
					<td>
						<input
							type="text"
							value={ data.ticket_max }
							onChange={ ( event ) => setData( { ...data, ticket_max: event.target.value } ) }
						/>
					</td>
					<td>
						<input
							type="text"
							value={ data.ticket_start }
							onChange={ ( event ) => setData( { ...data, ticket_start: event.target.value } ) }
						/>
					</td>
					
					<td>
						<button onClick={ () => setEditing( false ) }>Cancel</button>
						<button
							onClick={ () => {
								onUpdate( index, data );
								setEditing( false );
							} }
						>
							Save
						</button>
					</td>
				</tr>
			) : (
				<tr>
					<td>{ data.ticket_name }</td>
					<td>{ data.ticket_description }</td>
					<td>{ data.ticket_price }</td>
					<td>{ data.ticket_spaces }</td>
					<td>{ data.ticket_min }</td>
					<td>{ data.ticket_max }</td>
					<td>{ data.ticket_start }</td>
					<td>
						<button onClick={ () => setEditing( true ) }>Edit</button>
						<button onClick={ () => onDelete( ticket.ticket_id ) }>Delete</button>
					</td>
				</tr>
			) }
		</>
	);
};

export default BookingTicket;
