type TextInputProps = {
	label: string;
	placeholder: string;
	name: string;
	required: boolean;
	help: string;
	pattern: string;
	width: number;
	disabled: boolean;
	error: string;
	value: string;
	onChange: ( value: string ) => void;
};

const TextInput = ( props: TextInputProps ) => {
	const {
		value,
		label,
		placeholder,
		name,
		pattern,
		required,
		width,
		disabled,
		help,
		onChange,
	} = props;

	const getPattern = () => {
		if ( ! pattern ) return;
		switch ( pattern ) {
			case 'numbers':
				return '[0-9\\s]+';
			case 'letters':
				return '[a-zA-Z\\u00C0-\\u024F\\s]+';
			case 'letters-dots-dashes':
				return '[a-zA-Z\\u00C0-\\u024F\\-\\.\\s]+';
			case 'alphanumeric':
				return '[a-zA-Z0-9\\u00C0-\\u024F\\s]+';
			case 'alphanumeric-dots-dashes':
				return '[a-zA-Z0-9\\u00C0-\\u024F\\-\\.\\s]+';
		}
	};

	const onChangeHandler = (
		event: React.ChangeEvent< HTMLInputElement >
	) => {
		onChange( event.target.value );
	};

	const setInvalidity = ( event: any ) => {
		if ( ! props.error ) return;
		console.log( event );
		event.target.setCustomValidity( props.error );
	};

	const classes = [
		'input',
		'grid__column--span-' + width,
		required ? 'input--required' : '',
	].join( ' ' );

	return (
		<div className={ classes }>
			<label>{ label }</label>
			<input
				value={ value }
				name={ name }
				autoComplete={ help }
				required={ required }
				onInvalid={ setInvalidity }
				disabled={ disabled }
				placeholder={ placeholder }
				pattern={ getPattern() }
				type="text"
				onChange={ onChangeHandler }
			/>
		</div>
	);
};

TextInput.defaultProps = {
	label: '',
	placeholder: '',
	name: '',
	required: false,
	width: 6,
};

export default TextInput;
