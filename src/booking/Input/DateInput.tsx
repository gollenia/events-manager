type MailInputProps = {
	label: string;
	placeholder: string;
	name: string;
	required: boolean;
	width: number;
	value: string;
	min: string;
	help: boolean;
	max: string;
	disabled: boolean;
	onChange: ( value: string ) => void;
};

const MailInput = ( props: MailInputProps ) => {
	const {
		value,
		label,
		placeholder,
		name,
		required,
		help,
		disabled,
		width,
		min,
		max,
		onChange,
	} = props;

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
