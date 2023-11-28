import { useRef } from 'react';

const TextArea = ( props ) => {
	const { value, label, placeholder, name, required, width, rows, disabled, onChange } = props;
	const textInputRef = useRef( null );

	const onChangeHandler = ( event ) => {
		onChange( event.target.value );
	};

	const classes = [ 'textarea', 'grid__column--span-' + width, required ? 'input--required' : '' ].join( ' ' );

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
