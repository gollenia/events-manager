import { useRef, useState } from '@wordpress/element';

type Props = {
	label: string;
	width: number;
	disabled: boolean;
	required: boolean;
	defaultChecked: boolean;
	type: 'checkbox' | 'toggle';
	name: string;
	customErrorMessage: string;
	value: boolean;
	help: string;
	toggle: boolean;
	formTouched: boolean;
	onChange: ( value: any ) => void;
};

const Checkbox = ( props: Props ) => {
	const { label, width, onChange, type, value, help, toggle, customErrorMessage, name } = props;

	const inputRef = useRef< HTMLInputElement >( null );
	const [ touched, setTouched ] = useState( false );

	const onChangeHandler = ( event: any ) => {
		onChange( event.target.checked );
	};

	const setInvalidity = ( event: any ) => {
		if ( ! props.customErrorMessage ) return;
		event.target.setCustomValidity( props.customErrorMessage );
	};

	const isTouched = props.formTouched || touched;

	const reset = () => {
		if ( ! inputRef.current ) return;
		inputRef.current.checked = false;
	};

	const classes = [
		'ctx-form-field',
		toggle ? 'toggle' : 'checkbox',
		! inputRef?.current?.validity.valid && isTouched ? 'error' : '',
	].join( ' ' );

	const Label = () => {
		const labelText = help ?? label;

		return <span dangerouslySetInnerHTML={ { __html: labelText } }></span>;
	};

	return (
		<div
			className={ classes }
			style={ {
				gridColumn: `span ${ width }`,
			} }
		>
			<label>
				<div className="toggle__control">
					<input
						disabled={ props.disabled }
						required={ props.required }
						ref={ inputRef }
						onClick={ () => setTouched( true ) }
						checked={ value }
						type="checkbox"
						onChange={ onChangeHandler }
						onInvalid={ setInvalidity }
					/>
					{ toggle && <span className="toggle__switch"></span> }
				</div>
				<Label />
			</label>
			{ isTouched && ! inputRef?.current?.validity.valid && inputRef.current?.validationMessage && (
				<span className="error-message">{ customErrorMessage || inputRef.current?.validationMessage }</span>
			) }
		</div>
	);
};

Checkbox.defaultProps = {
	label: '',
	help: '',
	width: 6,
	disabled: false,
	required: false,
	defaultChecked: false,
	type: 'checkbox',
};

export default Checkbox;
export type { Props as CheckboxProps };
