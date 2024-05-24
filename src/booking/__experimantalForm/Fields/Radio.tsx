import { useState } from 'react';

export type RadioProps = {
	label: string;
	placeholder: string;
	name: string;
	required: boolean;
	width: number;
	options?: Array< string >;
	disabled: boolean;
	onChange: ( value: string ) => void;
	admin: boolean;
};

const Radio = ( props: RadioProps ) => {
	const { onChange, options, name, disabled, placeholder, width, required, admin } = props;

	const classes = [
		'ctx-form-field',
		'radio',
		'input--width-' + width,
		props.required ? 'select--required' : '',
	].join( ' ' );

	const [ selection, setSelection ] = useState( placeholder );

	const onChangeHandler = ( event: any ) => {
		setSelection( event.target.value );
		onChange( event.target.value );
	};

	const firstOptionInitiallyChecked = required && ! selection;

	if ( admin )
		return (
			<tr>
				<td>
					<label>{ props.label }</label>
				</td>
				<td>
					<fieldset name={ props.name }>
						<legend>{ props.label }</legend>
						{ options &&
							options.map( ( option, index ) => {
								return (
									<label key={ index } htmlFor={ name + index }>
										<input
											checked={ selection === option }
											onChange={ ( value ) => {
												onChangeHandler( value );
											} }
											disabled={ disabled }
											type="radio"
											value={ option }
											name={ name }
											id={ name + index }
										/>
										{ option }
									</label>
								);
							} ) }
					</fieldset>
				</td>
			</tr>
		);

	return (
		<div
			className={ classes }
			style={ {
				gridColumn: `span ${ width }`,
			} }
		>
			<fieldset name={ props.name }>
				<legend>{ props.label }</legend>
				{ options &&
					options.map( ( option, index ) => {
						return (
							<label key={ index } htmlFor={ name + index }>
								<input
									checked={ selection === option }
									onChange={ ( value ) => {
										onChangeHandler( value );
									} }
									disabled={ disabled }
									type="radio"
									value={ option }
									name={ name }
									id={ name + index }
								/>
								{ option }
							</label>
						);
					} ) }
			</fieldset>
		</div>
	);
};

Radio.defaultProps = {
	label: '',
	placeholder: '',
	name: '',
	options: [],
	required: false,
	width: 6,
	region: 'world',
};

export default Radio;
