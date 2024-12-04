import { Button, Flex, FlexItem, Modal } from '@wordpress/components';
import { select } from '@wordpress/data';
import { __ } from '@wordpress/i18n';
import React, { useEffect, useState } from 'react';

import TicketEditor from './ticketEditor';
import TicketRow from './ticketRow';
//import './booking.scss';

import apiFetch from '@wordpress/api-fetch';
const TicketModal = ( props ) => {
	const { showTickets, setShowTickets, meta, setMeta } = props;
	const postType = select( 'core/editor' ).getCurrentPostType();
	const currentPost = select( 'core/editor' ).getCurrentPost();
	const postId = currentPost.id;

	const [ isOpen, setIsOpen ] = useState( false );
	const [ tickets, setTickets ] = useState( [] );
	const [ currentTicket, setCurrentTicket ] = useState( {} );
	if ( postType !== 'event' ) return <></>;

	useEffect( () => {
		//if ( Object.keys( currentTicket ).length == 0 ) return;
		apiFetch( { path: 'events/v2/tickets?post_id=' + postId } ).then( ( response ) => {
			setTickets( response );
		} );
	}, [ currentTicket ] );

	const closeModal = () => {
		setShowTickets( false );
	};

	const onDelete = ( ticket_id ) => {
		const index = tickets.findIndex( ( ticket ) => ticket.ticket_id === ticket_id );
		apiFetch( {
			path: 'events/v2/ticket',
			method: 'DELETE',
			data: { post_id: postId, id: ticket_id },
		} )
			.then( ( response ) => {
				setTickets( tickets.splice( index, 1 ) );
			} )
			.catch( ( error ) => {
				console.log( error );
			} );
	};

	const onUpdate = () => {
		apiFetch( {
			path: 'events/v2/ticket',
			method: currentTicket.ticket_id == 0 ? 'POST' : 'PUT',
			data: { post_id: postId, ...currentTicket },
		} )
			.then( ( response ) => {
				console.log( response );
				setCurrentTicket( {} );
			} )
			.catch( ( error ) => {
				console.log( error );
			} );
	};

	const modalTitle =
		Object.keys( currentTicket ).length > 0
			? currentTicket.ticket_id == 0
				? __( 'New Ticket', 'events' )
				: __( 'Edit Ticket', 'events' )
			: __( 'Tickets', 'events' );

	return (
		<>
			{ showTickets && (
				<Modal title={ modalTitle } onRequestClose={ closeModal } size="large">
					{ Object.keys( currentTicket ).length > 0 ? (
						<TicketEditor
							ticket={ currentTicket }
							setTicket={ setCurrentTicket }
							onUpdate={ onUpdate }
							onCancel={ () => {
								setCurrentTicket( {} );
							} }
						/>
					) : (
						<>
							<table className="wp-list-table widefat striped table-view-list posts">
								<thead>
									<tr>
										<th>#</th>
										<th>{ __( 'Name', 'events' ) }</th>
										<th>{ __( 'Description', 'events' ) }</th>
										<th>{ __( 'Price', 'events' ) }</th>
										<th>{ __( 'Spaces', 'events' ) }</th>
										<th>{ __( 'Min', 'events' ) }</th>
										<th>{ __( 'Max', 'events' ) }</th>
									</tr>
								</thead>
								<tbody>
									{ tickets.map( ( ticket, index ) => (
										<TicketRow
											ticket={ ticket }
											index={ index }
											onDelete={ onDelete }
											onSelect={ ( index ) => {
												setCurrentTicket( tickets[ index ] );
											} }
										/>
									) ) }
								</tbody>
							</table>
							<Flex justify="flex-end" style={ { marginTop: '1rem' } }>
								<FlexItem>
									<Button
										variant="primary"
										onClick={ () => {
											setCurrentTicket( {
												ticket_id: 0,
												ticket_name: __( 'New Ticket', 'events' ),
												ticket_description: '',
												ticket_price: 0,
												ticket_spaces: meta._event_spaces,
												ticket_min: 0,
												ticket_max: 0,
												ticket_order: tickets[ tickets.length ]
													? tickets[ tickets.length - 1 ].ticket_order + 1
													: 1,
												is_new: true,
											} );
										} }
									>
										{ __( 'Add Ticket', 'events' ) }
									</Button>
								</FlexItem>
							</Flex>
						</>
					) }
				</Modal>
			) }
		</>
	);
};

export default TicketModal;
