import { useRef } from 'react';

type TextInputProps = {
	label: string;
	placeholder: string;
	name: string;
	required: boolean;
	width: number;
	value: string;
	disabled: boolean;
	onChange: ( value: string ) => void;
};

const TextInput = ( props: TextInputProps ) => {
	const {
		value,
		label,
		placeholder,
		name,
		required,
		width,
		onChange,
		disabled,
	} = props;
	const textInputRef = useRef( null );

	const onChangeHandler = (
		event: React.ChangeEvent< HTMLInputElement >
	) => {
		onChange( event.target.value );
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
				disabled={ disabled }
				required={ required }
				placeholder={ placeholder }
				type="tel"
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
