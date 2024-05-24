import { useEffect, useReducer } from 'react';

import InputField from '../../__experimantalForm/InputField.tsx';
import initialState from './state/initialState.js';
import reducer from './state/reducer.js';
import './style.scss';
import TicketModal from './TicketModal.js';

const BookingEditor = ( { bookingId } ) => {
	const [ state, dispatch ] = useReducer( reducer, initialState );

	const { data } = state;

	useEffect( () => {
		fetch( `/wp-json/events/v2/booking/${ bookingId }` )
			.then( ( response ) => response.json() )
			.then( ( data ) => {
				dispatch( { type: 'SET_DATA', payload: data.data } );
				dispatch( { type: 'SET_STATE', payload: 'loaded' } );
			} );
	}, [] );

	if ( state.state === 'loading' ) {
		return <p>Loading...</p>;
	}

	console.log( state );

	return (
		<div>
			<h1>{ data.event.title }</h1>
			<p>{ data.event.description }</p>
			<p>{ data.event.date }</p>

			<h2>Booking</h2>
			<p>{ data.booking.id }</p>
			<p>{ data.booking.date }</p>
			<p>{ data.booking.status_array[ data.booking.status ] }</p>

			<h2>Registration</h2>
			<table class="form-table">
				{ data.registrationFields.map( ( field ) => (
					<InputField
						admin={ true }
						{ ...field }
						key={ field.fieldid }
						label={ field.label }
						value={ data.registration[ field.fieldid ] }
						onChange={ ( value ) =>
							dispatch( {
								type: 'SET_FIELD',
								payload: { form: 'registration', field: field.fieldid, value },
							} )
						}
					/>
				) ) }
			</table>

			<h2>Attendees</h2>
			<table className="widefat">
				<thead>
					<tr>
						<th>Name</th>
						{ data.attendeeFields.map( ( field ) => (
							<th>{ field.label }</th>
						) ) }
						<th>Price</th>
					</tr>
				</thead>
				<tbody>
					{ data.attendees?.map( ( attendee, index ) => (
						<tr className="alternate">
							<td>
								{ data.availableTickets[ attendee.ticket ]?.name }
								<div class="row-actions">
									<span class="edit">
										<a
											onClick={ () => {
												dispatch( {
													type: 'SET_MODAL',
													payload: index,
												} );
											} }
											aria-label="„Kitchen Sink“ bearbeiten"
										>
											Bearbeiten
										</a>{ ' ' }
										|{ ' ' }
									</span>

									<span class="trash">
										<a
											href="https://kids-team.internal/wp-admin/post.php?post=2&amp;action=trash&amp;_wpnonce=2ea54cac3a"
											class="submitdelete"
											aria-label="„Kitchen Sink“ in den Papierkorb verschieben"
										>
											Löschen
										</a>
									</span>
								</div>
							</td>
							{ data.attendeeFields.map( ( field ) => (
								<td>{ attendee.fields[ field.fieldid ] }</td>
							) ) }
							<td>{ data.availableTickets[ attendee.ticket ]?.price }</td>
						</tr>
					) ) }
				</tbody>
				<tfoot>
					<tr>
						<td colspan={ data.attendeeFields.length + 1 }>Gesamtsumme</td>

						<td>{ data.booking.price }</td>
					</tr>
				</tfoot>
			</table>

			<button
				onClick={ () => {
					dispatch( { type: 'ADD_TICKET' } );
				} }
			>
				Add Attendee
			</button>

			<button>Save</button>
			<TicketModal
				ticket={ state.data.attendees[ state.modal ] }
				attendeeFields={ state.data.attendeeFields }
				visible={ state.modal !== null }
				onSave={ ( ticket ) => {
					dispatch( { type: 'SET_MODAL', payload: false } );
					dispatch( { type: 'SET_TICKET', payload: { ticket, index } } );
				} }
			/>
		</div>
	);
};

export default BookingEditor;
