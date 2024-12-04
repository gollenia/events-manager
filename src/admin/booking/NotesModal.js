import { Button, Modal, SelectControl, TextareaControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import React from 'react';

const NotesModal = ( { store, showModal, setShowModal } ) => {
	const [ state, dispatch ] = store;
	const data = state.data;
	const [ shadowNote, setShadowNote ] = React.useState( state.data.booking.note );

	const onSave = ( note ) => {
		dispatch( {
			type: 'SET_NOTE',
			payload: note,
		} );
		setShowModal( false );
	};

	return (
		<>
			{ showModal && (
				<Modal
					title={ __( 'Note', 'events' ) }
					onRequestClose={ () => {
						setShowModal( false );
					} }
				>
					<div className="booking-notes">
						<SelectControl
							label={ __( 'Type', 'events' ) }
							value={ shadowNote?.type }
							onChange={ ( value ) => setShadowNote( { ...shadowNote, type: value } ) }
							defaultValue="warning"
							options={ [
								{ value: 'error', label: __( 'Error', 'events' ) },
								{ value: 'warning', label: __( 'Warning', 'events' ) },
								{ value: 'info', label: __( 'Info', 'events' ) },
								{ value: 'success', label: __( 'Success', 'events' ) },
							] }
						/>

						<TextareaControl
							label={ __( 'Text', 'events' ) }
							value={ shadowNote?.text }
							onChange={ ( value ) => setShadowNote( { ...shadowNote, text: value } ) }
						/>
					</div>
					<div className="modal-actions">
						<Button
							onClick={ () => {
								setShowModal( false );
							} }
							variant="secondary"
						>
							Cancel
						</Button>
						<Button
							onClick={ () => {
								onSave( shadowNote );
							} }
							variant="primary"
						>
							OK
						</Button>
					</div>
				</Modal>
			) }
		</>
	);
};

export default NotesModal;
