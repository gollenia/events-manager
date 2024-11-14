import { Notice } from '@wordpress/components';
import React from 'react';

const Note = ( { store } ) => {
	const [ state, dispatch ] = store;
	const data = state.data;

	if ( ! data.booking.note ) {
		return <></>;
	}

	return (
		<>
			{ data.booking?.note?.text != '' && (
				<div className="note-container">
					<Notice
						status={ data.booking?.note?.type }
						isDismissible={ true }
						onRemove={ () => dispatch( { type: 'SET_NOTE', payload: { text: '', type: 'warning' } } ) }
					>
						{ data.booking?.note?.text }
					</Notice>
				</div>
			) }
		</>
	);
};

export default Note;
