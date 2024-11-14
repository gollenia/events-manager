import { Button, Panel, PanelBody } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import AdminField from './AdminField';

const Registration = ( { store } ) => {
	const [ state, dispatch ] = store;
	const data = state.data;

	return (
		<>
			<div className="flex-header">
				<h2>{ __( 'Registration', 'events-manager' ) }</h2>
				<Button onClick={ () => setShowNotesModal( true ) } variant="secondary">
					{ data.booking?.note?.text == ''
						? __( 'Add Note', 'events-manager' )
						: __( 'Edit Note', 'events-manager' ) }
				</Button>
			</div>
			<Panel>
				<PanelBody header="Registration">
					{ data.registration_fields.map( ( field ) => (
						<AdminField
							{ ...field }
							key={ field.fieldid }
							label={ field.label }
							value={ data.registration[ field.fieldid ] }
							onChange={ ( value ) =>
								dispatch( {
									type: 'SET_FIELD',
									payload: { form: 'registration', field: field.fieldid, value },
								} )
							}
						/>
					) ) }
				</PanelBody>
			</Panel>
		</>
	);
};

export default Registration;
