const MailInput = ( props ) => {
	const { value, label, placeholder, name, required, help, disabled, width, min, max, onChange } = props;

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
				autoComplete={ help ? 'bday' : 'off' }
				type="date"
				max={ max }
				min={ min }
				onChange={ onChangeHandler }
			/>
		</div>
	);
};

MailInput.defaultProps = {
	label: '',
	placeholder: '',
	name: '',
	required: false,
	width: 6,
	help: true,
	min: '',
	max: '',
};

export default MailInput;
