import { useRef, useState } from '@wordpress/element';

type InputFieldTypes =
	| 'text'
	| 'email'
	| 'url'
	| 'color'
	| 'tel'
	| 'password'
	| 'search'
	| 'datetime-local'
	| 'select'
	| 'radio'
	| 'textarea'
	| 'checkbox'
	| 'country'
	| 'html'
	| 'hidden'
	| 'range'
	| 'file'
	| 'toggle'
	| 'combobox'
	| 'options'
	| 'date'
	| 'week'
	| 'month'
	| 'number'
	| 'year';

type InputProps = {
	label: string;
	placeholder: string;
	name: string;
	required: boolean;
	autoComplete: string;
	pattern: string | null;
	width: number;
	disabled: boolean;
	customError: string;
	defaultValue: string;
	min?: number;
	max?: number;
	customErrorMessage?: string;
	type: InputFieldTypes;
	formTouched: boolean;
	onChange: ( value: any ) => void;
	admin: boolean;
	value: string;
};

const TextInput = ( props: InputProps ) => {
	const [ touched, setTouched ] = useState( false );
	const inputRef = useRef< HTMLInputElement >( null );

	const { label, required, width, onChange, pattern, min, max, customErrorMessage, admin, value } = props;

	const onChangeHandler = ( event: any ) => {
		onChange( event.target.value );
	};

	const onKeyPressHandler = ( event: any ) => {
		if ( ! pattern ) return;

		let regex = new RegExp( pattern, 'gu' );

		if ( regex.test( event.data ) === false ) {
			event.preventDefault();
		}
	};

	const setInvalidity = ( event: any ) => {
		if ( ! props.customError ) return;
		event.target.setCustomValidity( props.customError );
	};

	const isTouched = props.formTouched || touched;

	const classes = [
		'ctx-form-field',
		'input',
		'input--width-' + width,
		required ? 'input--required' : '',
		! inputRef?.current?.validity.valid && isTouched ? 'error' : '',
	].join( ' ' );

	if ( admin )
		return (
			<tr>
				<td>
					<label>{ label }</label>
				</td>
				<td>
					<input
						placeholder={ props.placeholder }
						name={ props.name }
						required={ required }
						onBlur={ () => setTouched( true ) }
						type={ props.type }
						autoComplete={ props.autoComplete }
						disabled={ props.disabled }
						pattern={ props.pattern ? props.pattern : undefined }
						defaultValue={ props.defaultValue }
						value={ value }
						ref={ inputRef }
						onInvalid={ setInvalidity }
						onChange={ onChangeHandler }
						onBeforeInput={ onKeyPressHandler }
						minLength={ min }
						maxLength={ max ? max : undefined }
						className="regular-text"
					/>
					{ ! inputRef?.current?.validity.valid && isTouched && inputRef.current?.validationMessage && (
						<span className="error-message">
							{ customErrorMessage || inputRef.current?.validationMessage }
						</span>
					) }
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
			<label>{ label }</label>
			<input
				placeholder={ props.placeholder }
				name={ props.name }
				required={ required }
				onBlur={ () => setTouched( true ) }
				type={ props.type }
				autoComplete={ props.autoComplete }
				disabled={ props.disabled }
				pattern={ props.pattern ? props.pattern : undefined }
				defaultValue={ props.defaultValue }
				ref={ inputRef }
				onInvalid={ setInvalidity }
				onChange={ onChangeHandler }
				onBeforeInput={ onKeyPressHandler }
				minLength={ min }
				maxLength={ max ? max : undefined }
			/>
			{ ! inputRef?.current?.validity.valid && isTouched && inputRef.current?.validationMessage && (
				<span className="error-message">{ customErrorMessage || inputRef.current?.validationMessage }</span>
			) }
		</div>
	);
};

TextInput.defaultProps = {
	label: '',
	placeholder: '',
	name: '',
	required: false,
	width: 6,
	type: 'text',
	pattern: null,
};

export default TextInput;
export type { InputFieldTypes, InputProps };
