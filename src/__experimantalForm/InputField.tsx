import Checkbox from './Fields/Checkbox';
import Combobox from './Fields/Combobox';
import Country from './Fields/Country';
import Hidden from './Fields/Hidden';
import HTMLBlock from './Fields/HtmlBlock';
import Input from './Fields/Input';
import NumberInput from './Fields/Number';
import Radio from './Fields/Radio';
import Select from './Fields/Select';
import Submit from './Fields/Submit';
import TextArea from './Fields/TextArea';

type InputType =
	| 'text'
	| 'email'
	| 'url'
	| 'color'
	| 'tel'
	| 'password'
	| 'search'
	| 'date'
	| 'week'
	| 'month'
	| 'time'
	| 'datetime-local'
	| 'number'
	| 'select'
	| 'combobox'
	| 'radio'
	| 'textarea'
	| 'checkbox'
	| 'country'
	| 'html'
	| 'hidden'
	| 'range'
	| 'file'
	| 'toggle'
	| 'combobox'
	| 'options';

const InputField: any = (props: any) => {
	const { type, onChange, disabled } = props;

	const renderField: any = () => {
		switch (type) {
			case 'select':
				return (
					<Select
						{...props}
						onChange={onChange}
						disabled={disabled}
					/>
				);
			case 'combobox':
				return (
					<Combobox
						{...props}
						onChange={onChange}
						disabled={disabled}
					/>
				);
			case 'radio':
			case 'options':
				return (
					<Radio {...props} onChange={onChange} disabled={disabled} />
				);
			case 'textarea':
				return (
					<TextArea
						{...props}
						onChange={onChange}
						disabled={disabled}
					/>
				);
			case 'checkbox':
			case 'toggle':
				return (
					<Checkbox
						{...props}
						onChange={onChange}
						disabled={disabled}
					/>
				);
			case 'country':
				return (
					<Country
						{...props}
						onChange={onChange}
						disabled={disabled}
					/>
				);
			case 'combobox':
				return (
					<Combobox
						{...props}
						onChange={onChange}
						disabled={disabled}
					/>
				);
			case 'number':
			case 'range':
			case 'numberpicker':
				return (
					<NumberInput
						{...props}
						onChange={onChange}
						disabled={disabled}
					/>
				);
			case 'html':
				return <HTMLBlock {...props} />;
			case 'hidden':
				return <Hidden {...props} />;

			case 'submit':
				return (
					<Submit
						{...props}
						onChange={onChange}
						disabled={disabled}
						type="submit"
					/>
				);

			default:
				return (
					<Input {...props} onChange={onChange} disabled={disabled} />
				);
		}
	};
	return <>{renderField(props)}</>;
};

export default InputField;
export type { InputType };
