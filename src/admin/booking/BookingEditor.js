import { useEffect, useReducer } from 'react';

import { Button } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import AttendeeTable from './AttendeeTable.js';
import BookingDetails from './BookingDetails.js';
import Note from './Note.js';
import NotesModal from './NotesModal.js';
import Payment from './Payment.js';
import Registration from './Registration.js';
import TicketModal from './TicketModal.js';
import initialState from './state/initialState.js';
import reducer from './state/reducer.js';
import saveBooking from './state/saveBooking.js';
import './style.scss';

const BookingEditor = ( { bookingId } ) => {
	const store = useReducer( reducer, initialState );
	const [ state, dispatch ] = store;
	const [ showNotesModal, setShowNotesModal ] = React.useState( false );

	const { data } = state;

	useEffect( () => {
		fetch( `/wp-json/events/v2/booking/${ bookingId }` )
			.then( ( response ) => response.json() )
			.then( ( data ) => {
				dispatch( { type: 'SET_DATA', payload: data } );
				dispatch( { type: 'SET_STATE', payload: 'loaded' } );
			} );
	}, [] );

	useEffect( () => {
		if ( state.sendState === 'unsaved' ) {
			window.onbeforeunload = confirmExit;
		}
		function confirmExit() {
			return 'show warning';
		}
	}, [ state.sendState ] );

	if ( state.state === 'loading' || ! data ) {
		return <p>{ __( 'Loading Data...', 'events' ) }</p>;
	}
	console.log( 'state', state );
	return (
		<div>
			<Note store={ store } />

			<BookingDetails store={ store } />

			<Registration store={ store } />

			<AttendeeTable store={ store } />

			<Payment store={ store } />

			<div className="booking-actions">
				<Button
					onClick={ () => {
						saveBooking( bookingId, state, dispatch );
					} }
					variant={ state.sendState === 'unsaved' ? 'primary' : 'secondary' }
					className={ state.sendState === 'error' ? 'error' : '' }
				>
					{ __( 'Save' ) }
				</Button>
			</div>

			<TicketModal
				store={ store }
				onSave={ ( ticket, index ) => {
					dispatch( { type: 'SET_CURRENT_TICKET', payload: 999 } );
					dispatch( { type: 'SET_TICKET', payload: { ticket, index } } );
				} }
				onCancel={ () => {
					dispatch( { type: 'SET_CURRENT_TICKET', payload: 999 } );
				} }
			/>

			<NotesModal store={ store } showModal={ showNotesModal } setShowModal={ setShowNotesModal } />
		</div>
	);
};

export default BookingEditor;
