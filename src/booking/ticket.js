//import { InputField } from '@contexis/wp-react-form';
import InputField from '../__experimantalForm/InputField';

/*
 *	Renders a single ticket with it's form fields
 *  and a delete button
 *
 */
const Ticket = ( props ) => {
	const { state, dispatch, ticket, index } = props;

	const { attendee_fields } = state.data;

	return (
		<div className="booking-ticket">
			<div className="booking-ticket-title">
				<h4>{ ticket.name }</h4>
				<button
					href=""
					className="button button--danger button--pop"
					onClick={ () => dispatch( { type: 'REMOVE_TICKET', payload: { index } } ) }
					disabled={ ticket.min >= index + 1 }
				>
					<i className="material-icons material-symbols-outlined">delete</i>
				</button>
			</div>
			<div className="booking-ticket-form">
				{ attendee_fields.map( ( field, key ) => {
					return (
						<InputField
							{ ...field }
							name={ field.fieldid }
							key={ key }
							type={ field.type }
							value={ ticket.fields[ field.fieldid ] }
							onChange={ ( value ) => {
								dispatch( {
									type: 'SET_FIELD',
									payload: { form: 'ticket', index, field: field.fieldid, value: value },
								} );
							} }
							locale={ state.data.l10n.locale }
						/>
					);
				} ) }
			</div>
		</div>
	);
};

export default Ticket;
