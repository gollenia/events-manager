const MailInput = ( props ) => {
	const { value, label, placeholder, name, required, width, disabled, onChange } = props;

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
				type="email"
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
};

export default MailInput;
