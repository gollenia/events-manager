import { Button, Flex, FlexItem, TextControl, TextareaControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import React from 'react';

const TicketEditor = ( props ) => {
	const { ticket, onUpdate, onCancel, setTicket } = props;

	if ( ! ticket ) return null;
	return (
		<>
			<Flex direction="column">
				<FlexItem>
					<TextControl
						label={ __( 'Name', 'events' ) }
						type="text"
						value={ ticket.ticket_name }
						onChange={ ( value ) => setTicket( { ...ticket, ticket_name: value } ) }
					/>
				</FlexItem>
				<FlexItem>
					<TextareaControl
						label={ __( 'Description', 'events' ) }
						value={ ticket.ticket_description }
						onChange={ ( value ) =>
							setTicket( {
								...ticket,
								ticket_description: value,
							} )
						}
					/>
				</FlexItem>
				<Flex justify="flex-start">
					<FlexItem style={ { flex: 1 } }>
						<TextControl
							label={ __( 'Price', 'events' ) }
							type="text"
							width="100%"
							pattern="[0-9]+(\.[0-9][0-9]?)?"
							value={ ticket.ticket_price }
							onChange={ ( value ) =>
								setTicket( {
									...ticket,
									ticket_price: value,
								} )
							}
						/>
					</FlexItem>
					<FlexItem style={ { flex: 1 } }>
						<TextControl
							label={ __( 'Spaces', 'events' ) }
							type="number"
							min={ 0 }
							width="100%"
							value={ ticket.ticket_spaces }
							onChange={ ( value ) =>
								setTicket( {
									...ticket,
									ticket_spaces: value,
								} )
							}
						/>
					</FlexItem>
				</Flex>
				<Flex>
					<FlexItem style={ { flex: 1 } }>
						<TextControl
							label={ __( 'Minimum bookable', 'events' ) }
							type="number"
							min={ 0 }
							width="100%"
							value={ ticket.ticket_min }
							onChange={ ( value ) => setTicket( { ...ticket, ticket_min: value } ) }
						/>
					</FlexItem>
					<FlexItem style={ { flex: 1 } }>
						<TextControl
							label={ __( 'Maximum bookable', 'events' ) }
							type="number"
							min={ 1 }
							width="100%"
							value={ ticket.ticket_max }
							onChange={ ( value ) => setTicket( { ...ticket, ticket_max: value } ) }
						/>
					</FlexItem>
				</Flex>
				<FlexItem>
					<TextControl
						label={ __( 'Start date', 'events' ) }
						type="date"
						value={ ticket.ticket_start }
						onChange={ ( value ) => setTicket( { ...ticket, ticket_start: value } ) }
					/>
				</FlexItem>
				<FlexItem>
					<TextControl
						label={ __( 'Order', 'events' ) }
						type="number"
						value={ ticket.ticket_order }
						onChange={ ( value ) => setTicket( { ...ticket, ticket_order: value } ) }
					/>
				</FlexItem>
			</Flex>
			<Flex justify="flex-end" style={ { marginTop: '1rem' } }>
				<FlexItem>
					<Button
						variant="secondary"
						onClick={ () => {
							onCancel();
						} }
					>
						{ __( 'Cancel', 'events' ) }
					</Button>
				</FlexItem>
				<FlexItem>
					<Button
						variant="primary"
						disabled={ ! ticket.ticket_name || ! ticket.dicket_description }
						onClick={ () => {
							onUpdate();
						} }
					>
						{ __( 'Save', 'events' ) }
					</Button>
				</FlexItem>
			</Flex>
		</>
	);
};

export default TicketEditor;
