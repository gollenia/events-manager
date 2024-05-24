import { useRef, useState } from 'react';

export type ComboboxProps = {
	label: string;
	placeholder: string;
	name: string;
	required: boolean;
	width: number;
	options: Array<string>;
	hasEmptyOption?: boolean;
	help: string;
	hint: string;
	disabled: boolean;
	multiple: boolean;
	customError: string;
	customErrorMessage?: string;
	onChange: (value: string) => void;
};

const Combobox = (props: ComboboxProps) => {
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
		width,
		customErrorMessage,
	} = props;

	const classes = [
		'ctx-form-field',
		'select',
		'input--width-' + width,
		props.required ? 'select--required' : '',
	].join(' ');

	const inputRef = useRef<HTMLInputElement>(null);

	const [inputField, setInputField] = useState<string>('');
	const [selection, setSelection] = useState<number>(-1);
	const [listSelect, setListSelect] = useState<number>(-1);

	const dropdownSelect = (value: string) => {
		const index = options.findIndex((option) => option === value);
		setSelection(index);
		setInputField('');
		if (onChange) {
			onChange(value);
		}
	};

	const filteredOptions = () => {
		if (inputField.length === 0) return options;
		if (inputField.slice(-1) === '*') {
			return options.filter((option) =>
				option
					.toLowerCase()
					.startsWith(inputField.slice(0, -1).toLowerCase())
			);
		}
		return options.filter((option) =>
			option.toLowerCase().includes(inputField.toLowerCase())
		);
	};

	const keyPress = (event: any) => {
		if (event.key === 'ArrowDown') {
			setListSelect(listSelect + 1);
		}
		if (event.key === 'ArrowUp' && listSelect !== -1)
			setListSelect(listSelect - 1);
		if (event.key === 'Enter') {
			dropdownSelect(filteredOptions()[listSelect]);
			inputRef.current?.blur();
		}
		if (event.key === 'Escape') {
			setListSelect(-1);
			inputRef.current?.blur();
		}
	};

	const nullSelect = () => {
		setListSelect(-1);
		dropdownSelect('');
	};

	return (
		<div
			style={{
				gridColumn: `span ${width}`,
			}}
			className={'combobox ' + classes}
			onKeyDown={(event) => keyPress(event)}
		>
			<input
				ref={inputRef}
				type="text"
				onMouseOver={() => {
					setListSelect(-1);
				}}
				onClick={() => {}}
				placeholder={
					selection !== -1 ? options[selection] : placeholder
				}
				value={inputField}
				onChange={(event) => setInputField(event.target.value)}
			/>
			<ul>
				{help !== '' && (
					<li
						className={listSelect === -1 ? 'selected' : ''}
						onMouseDown={(event) => {
							nullSelect();
						}}
					>
						{help}
					</li>
				)}
				{filteredOptions().map((option, index) => {
					return (
						<li
							className={listSelect === index ? 'selected' : ''}
							onMouseDown={() => {
								dropdownSelect(option);
							}}
							key={index}
						>
							{option}
						</li>
					);
				})}
				{filteredOptions().length === 0 && (
					<li className="muted">No Result</li>
				)}
			</ul>
		</div>
	);
};

Combobox.defaultProps = {
	label: '',
	placeholder: '',
	name: '',
	options: [],
	required: false,
	width: 6,
	region: 'world',
};

export default Combobox;
