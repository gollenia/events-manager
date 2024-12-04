/**
 * Adds a metabox for the page color settings
 */

/**
 * WordPress dependencies
 */
import { Button, CheckboxControl, Flex, Modal, SelectControl } from '@wordpress/components';
import { select, useSelect } from '@wordpress/data';
import { useEffect, useState } from 'react';

import { PluginDocumentSettingPanel } from '@wordpress/editor';
import BookTicket from './BookingTicket';
import './booking.scss';

import apiFetch from '@wordpress/api-fetch';
import { store as coreStore, useEntityProp } from '@wordpress/core-data';
import { __ } from '@wordpress/i18n';

const bookingOptions = () => {
	const postType = select( 'core/editor' ).getCurrentPostType();
	const currentPost = select( 'core/editor' ).getCurrentPost();
	const postId = currentPost.id;

	const [ isOpen, setIsOpen ] = useState( false );
	const [ tickets, setTickets ] = useState( [] );
	if ( postType !== 'event' ) return <></>;

	useEffect( () => {
		apiFetch( { path: 'events/v2/tickets?post_id=' + postId } ).then( ( response ) => {
			setTickets( response );
		} );
	}, [] );

	const [ meta, setMeta ] = useEntityProp( 'postType', postType, 'meta' );

	const bookingFormList = useSelect( ( select ) => {
		const { getEntityRecords } = select( coreStore );
		const query = { per_page: -1 };
		const list = getEntityRecords( 'postType', 'bookingform', query );

		let formsArray = [ { value: 0, label: '' } ];
		if ( ! list ) {
			return formsArray;
		}

		list.map( ( form ) => {
			formsArray.push( { value: form.id, label: form.title.raw } );
		} );

		return formsArray;
	}, [] );

	const attendeeFormList = useSelect( ( select ) => {
		const { getEntityRecords } = select( coreStore );
		const query = { per_page: -1 };
		const list = getEntityRecords( 'postType', 'attendeeform', query );

		let formsArray = [ { value: 0, label: '' } ];
		if ( ! list ) {
			return formsArray;
		}

		list.map( ( form ) => {
			formsArray.push( { value: form.id, label: form.title.raw } );
		} );

		return formsArray;
	}, [] );

	const closeModal = () => {
		setIsOpen( false );
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

	const onUpdate = ( index, ticket ) => {
		apiFetch( {
			path: 'events/v2/ticket',
			method: 'POST',
			data: { post_id: postId, id: ticket.ticket_id, ...ticket },
		} )
			.then( ( response ) => {
				console.log( response );
			} )
			.catch( ( error ) => {
				console.log( error );
			} );
	};

	console.log( tickets );

	return (
		<>
			{ isOpen && (
				<Modal title="This is my modal" onRequestClose={ closeModal }>
					<Flex>
						<SelectControl
							label={ __( 'Registration Form', 'events' ) }
							value={ meta._booking_form }
							onChange={ ( value ) => {
								setMeta( { _booking_form: value } );
							} }
							disabled={ ! meta._event_rsvp }
							options={ bookingFormList }
							disableCustomColors={ true }
						/>

						<SelectControl
							label={ __( 'Attendee Form', 'events' ) }
							value={ meta._attendee_form }
							onChange={ ( value ) => {
								setMeta( { _attendee_form: value } );
							} }
							disabled={ ! meta._event_rsvp }
							options={ attendeeFormList }
							disableCustomColors={ true }
						/>
					</Flex>
					<CheckboxControl
						label={ __( 'Allow Donation', 'events' ) }
						checked={ meta._event_rsvp_donation }
						onChange={ ( value ) => {
							setMeta( { _event_rsvp_donation: value } );
						} }
						disabled={ ! meta._event_rsvp }
					/>
					<p>In a future Release of events manager, this will display the ticket editor</p>
					<table>
						<tr>
							<th>Name</th>
							<th>Description</th>
							<th>Price</th>
							<th>Spaces</th>
							<th>Min</th>
							<th>Max</th>
							<th>Start</th>
						</tr>
						{ tickets.map( ( ticket, index ) => (
							<BookTicket ticket={ ticket } index={ index } onDelete={ onDelete } onUpdate={ onUpdate } />
						) ) }
					</table>
					<Button
						variant="secondary"
						onClick={ () => {
							setTickets( [
								...tickets,
								{
									ticket_id: 0,
									ticket_name: __( 'New Ticket', 'events' ),
									ticket_description: '',
									ticket_price: 0,
									ticket_spaces: meta._event_spaces,
									ticket_min: 0,
									ticket_max: 0,
									ticket_start: '',
									is_new: true,
								},
							] );
						} }
					>
						{ __( 'Add Ticket', 'events' ) }
					</Button>
				</Modal>
			) }
			<PluginDocumentSettingPanel
				name="events-booking-options"
				title={ __( 'Bookings', 'events' ) }
				className="events-form-settings"
			>
				v
				<Button variant="primary" onClick={ () => setIsOpen( true ) } disabled={ ! meta._event_rsvp }>
					{ __( 'Manage Tickets', 'events' ) }
				</Button>
			</PluginDocumentSettingPanel>
		</>
	);
};

export default bookingOptions;
