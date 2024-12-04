import { __ } from '@wordpress/i18n';
import { useState } from 'react';
import { formatPrice } from '../../../common/formatPrice';

const TicketRow = ( props ) => {
	const { ticket, index, onDelete, onSelect, onDuplicate } = props;

	const [ editing, setEditing ] = useState( false );

	return (
		<tr>
			<td>{ ticket.ticket_order }</td>
			<td>
				<b>{ ticket.ticket_name }</b>

				<div className="row-actions">
					<a className="edit" onClick={ () => onSelect( index ) }>
						{ __( 'Edit', 'events' ) }
					</a>
					&nbsp;|&nbsp;
					<a className="view" onClick={ () => onDuplicate( index ) }>
						{ __( 'Duplicate', 'events' ) }
					</a>
					&nbsp;|&nbsp;
					<a className="submitdelete" onClick={ () => onDelete( ticket.ticket_id ) }>
						{ __( 'Delete', 'events' ) }
					</a>
				</div>
			</td>
			<td>{ ticket.ticket_description }</td>
			<td>{ formatPrice( ticket.ticket_price, eventBlocksLocalization.currency ) }</td>
			<td>{ ticket.ticket_spaces }</td>
			<td>{ ticket.ticket_min }</td>
			<td>{ ticket.ticket_max }</td>
		</tr>
	);
};

export default TicketRow;
