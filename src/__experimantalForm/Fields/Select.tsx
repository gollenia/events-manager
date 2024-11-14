import { useRef, useState } from 'react';

export type SelectProps = {
	label: string;
	placeholder: any;
	name: string;
	required: boolean;
	width: number;
	options?: Array< string > | Object;
	hasEmptyOption?: boolean;
	help?: string;
	hint?: string;
	disabled: boolean;
	multiple?: boolean;
	customError?: string;
	formTouched?: boolean;
	customErrorMessage?: string;
	onChange: ( value: string ) => void;
	value: string;
};

const Select = ( props: SelectProps ) => {
	const {
		onChange,
		options,
		hasEmptyOption,
		help,
		hint,
		disabled,
		placeholder,
		multiple,
		required,
		label,
		name,
		customErrorMessage,
		width,
		value,
	} = props;

	const [ touched, setTouched ] = useState( false );

	const inputRef = useRef< HTMLSelectElement >( null );

	const onChangeHandler = ( event: any ) => {
		onChange( event.target.value );
	};

	const isTouched = props.formTouched || touched;

	const classes = [
		'ctx-form-field',
		'select',
		'input--width-' + width,
		props.required ? 'select--required' : '',
		! inputRef?.current?.validity.valid && isTouched ? 'error' : '',
	].join( ' ' );

	const Options = () => {
		if ( options && Array.isArray( options ) ) {
			return options.map( ( option, index ) => {
				return <option key={ index }>{ option }</option>;
			} );
		}

		if ( options && typeof options === 'object' ) {
			return Object.keys( options ).map( ( key, index ) => {
				return (
					<option key={ index } value={ key }>
						{ options[ key ] }
					</option>
				);
			} );
		}
		return null;
	};

	return (
		<div
			className={ classes }
			style={ {
				gridColumn: `span ${ width }`,
			} }
		>
			<label>{ label }</label>
			<select
				name={ name }
				required={ required }
				onChange={ onChangeHandler }
				onBlur={ () => setTouched( true ) }
				ref={ inputRef }
				autoComplete={ hint }
				disabled={ disabled }
				multiple={ multiple }
				defaultValue={ placeholder }
				value={ value }
			>
				{ hasEmptyOption && (
					<option value="" disabled>
						{ help ?? 'Make a selection' }
					</option>
				) }
				<Options />
			</select>
			{ isTouched && ! inputRef?.current?.validity.valid && inputRef.current?.validationMessage && (
				<span className="error-message">{ customErrorMessage || inputRef.current?.validationMessage }</span>
			) }
		</div>
	);
};

Select.defaultProps = {
	label: '',
	placeholder: '',
	name: '',
	required: false,
	width: 6,
};

export default Select;
