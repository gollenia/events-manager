import { useRef, useState } from '@wordpress/element';

export type TextAreaProps = {
	label: string;
	placeholder: string;
	name: string;
	required: boolean;
	width: number;
	disabled: boolean;
	rows: number;
	formTouched?: boolean;
	customErrorMessage?: string;
	onChange: (value: string) => void;
};

const TextArea = (props: TextAreaProps) => {
	const {
		label,
		placeholder,
		name,
		required,
		width,
		rows,
		disabled,
		onChange,
		customErrorMessage,
	} = props;

	const textInputRef = useRef<HTMLTextAreaElement>(null);
	const [touched, setTouched] = useState(false);
	const onChangeHandler = (event: any) => {
		onChange(event.target.value);
	};

	const isTouched = touched || props.formTouched;

	const classes = [
		'ctx-form-field',
		'textarea',
		'input--width-' + width,
		required ? 'input--required' : '',
		!textInputRef?.current?.validity.valid && isTouched ? 'error' : '',
	].join(' ');

	return (
		<div
			className={classes}
			style={{
				gridColumn: `span ${width}`,
			}}
		>
			<label>{label}</label>
			<textarea
				name={name}
				required={required}
				disabled={disabled}
				rows={rows}
				onBlur={() => setTouched(true)}
				ref={textInputRef}
				placeholder={placeholder}
				onChange={onChangeHandler}
			></textarea>
			{!textInputRef?.current?.validity.valid &&
				isTouched &&
				textInputRef.current?.validationMessage && (
					<span className="error-message">
						{customErrorMessage ||
							textInputRef.current?.validationMessage}
					</span>
				)}
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
