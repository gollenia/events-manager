import { __ } from '@wordpress/i18n';
import InputField from './InputField';

/*
 *	Renders a single ticket with it's form fields
 *  and a delete button
 *
 */
const Ticket = ( props ) => {
	const { state, dispatch, ticket, index } = props;

	const { attendee_fields } = state.data;

	return (
		<div className="card card--no-image bg-white my-8 card--shadow">
			<div className="card__content">
				<div className="card__title mb-8">
					{ ticket.name } { ticket.fields?.attendee_name ? __( 'for', 'events' ) : '' }{ ' ' }
					{ ticket.fields?.attendee_name }
				</div>
				<div className="form  grid xl:grid--columns-6 grid--gap-8">
					{ attendee_fields.map( ( field, key ) => {
						return (
							<InputField
								key={ key }
								type={ field.type }
								settings={ field }
								value={ ticket.fields[ field.fieldid ] }
								onChange={ ( value ) =>
									dispatch( {
										type: 'SET_FIELD',
										payload: { form: 'ticket', index, field: field.fieldid, value: value },
									} )
								}
							/>
						);
					} ) }
				</div>
				<div className="card__footer mt-8">
					<div className="card__actions"></div>
					<div className="card__supplemental">
						<button
							href=""
							className="button button--error button--icon button--pop"
							onClick={ () => dispatch( { type: 'REMOVE_TICKET', payload: { index } } ) }
							disabled={ ticket.min >= index + 1 }
						>
							<i class="material-icons">delete</i>
						</button>
					</div>
				</div>
			</div>
		</div>
	);
};

export default Ticket;
