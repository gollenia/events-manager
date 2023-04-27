import { useRef } from 'react';

type TextAreaProps = {
	label: string;
	placeholder: string;
	name: string;
	required: boolean;
	width: number;
	value: string;
	disabled: boolean;
	rows: number;
	onChange: ( value: string ) => void;
};

const TextArea = ( props: TextAreaProps ) => {
	const {
		value,
		label,
		placeholder,
		name,
		required,
		width,
		rows,
		disabled,
		onChange,
	} = props;
	const textInputRef = useRef( null );

	const onChangeHandler = (
		event: React.ChangeEvent< HTMLTextAreaElement >
	) => {
		onChange( event.target.value );
	};

	const classes = [
		'textarea',
		'grid__column--span-' + width,
		required ? 'input--required' : '',
	].join( ' ' );

	return (
		<div className={ classes }>
			<label>{ label }</label>
			<textarea
				value={ value }
				name={ name }
				required={ required }
				disabled={ disabled }
				rows={ rows }
				placeholder={ placeholder }
				onChange={ onChangeHandler }
			></textarea>
		</div>
	);
};

TextArea.defaultProps = {
	label: '',
	placeholder: '',
	name: '',
	required: false,
	width: 6,
	rows: 3,
};

export default TextArea;
