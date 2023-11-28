const PhoneInput = ( props ) => {
	const { value, label, placeholder, name, required, width, onChange, disabled } = props;

	const onChangeHandler = ( event ) => {
		onChange( event.target.value );
	};

	const classes = [ 'input', 'grid__column--span-' + width, required ? 'input--required' : '' ].join( ' ' );

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

PhoneInput.defaultProps = {
	label: '',
	placeholder: '',
	name: '',
	required: false,
	width: 6,
};

export default PhoneInput;
