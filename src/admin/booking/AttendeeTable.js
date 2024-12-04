import { Button } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import React from 'react';

import { formatPrice } from '../../common/formatPrice.js';
import FullPrice from './FullPrice.js';

const AttendeeTable = ( { store } ) => {
	const [ state, dispatch ] = store;
	const data = state.data;
	return (
		<>
			<div className="flex-header">
				<h2>{ __( 'Attendees', 'events' ) }</h2>
				<Button
					onClick={ () => {
						dispatch( { type: 'ADD_TICKET' } );
					} }
					variant="secondary"
				>
					{ __( 'Add Attendee', 'events' ) }
				</Button>
			</div>
			<table className="widefat">
				<thead>
					<tr>
						<th>{ __( 'Name', 'events' ) }</th>
						{ data.attendee_fields.map( ( field ) => (
							<th>{ field.label }</th>
						) ) }
						<th>{ __( 'Price', 'events' ) }</th>
					</tr>
				</thead>
				<tbody>
					{ data.attendees?.map( ( attendee, index ) => {
						return (
							<tr className="alternate">
								<td>
									{ data.available_tickets[ attendee.ticket_id ]?.name }
									<div class="row-actions">
										<span class="edit">
											<a
												onClick={ () => {
													dispatch( {
														type: 'SET_CURRENT_TICKET',
														payload: index,
													} );
												} }
											>
												{ __( 'Edit' ) }
											</a>{ ' ' }
											|{ ' ' }
										</span>

										<span class="trash">
											<a
												onClick={ () => {
													dispatch( {
														type: 'REMOVE_TICKET',
														payload: { index },
													} );
												} }
												class="submitdelete"
												aria-label="„Kitchen Sink“ in den Papierkorb verschieben"
											>
												{ __( 'Delete' ) }
											</a>
										</span>
									</div>
								</td>
								{ data.attendee_fields.map( ( field ) => (
									<td>{ attendee.fields[ field.fieldid ] }</td>
								) ) }
								<td>
									{ formatPrice(
										data.available_tickets[ attendee.ticket_id ]?.price,
										state.data.l10n.currency
									) }
								</td>
							</tr>
						);
					} ) }
				</tbody>
				<tfoot>
					<FullPrice store={ store } />
				</tfoot>
			</table>
		</>
	);
};

export default AttendeeTable;
