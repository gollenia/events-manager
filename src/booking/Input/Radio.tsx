import { useState } from 'react';

type Props = {
	label: string;
	placeholder: string;
	name: string;
	required: boolean;
	width: number;
	options: Array< string >;
	disabled: boolean;
	onChange: ( value: string ) => void;
};

const Radio = ( props: Props ) => {
	const { onChange, options, name, disabled, placeholder } = props;

	const classes = [
		'radio',
		'grid__column--span-' + props.width,
		props.required ? 'select--required' : '',
	].join( ' ' );

	const [ selection, setSelection ] = useState( placeholder );

	const onChangeHandler = (
		event: React.ChangeEvent< HTMLInputElement >
	) => {
		setSelection( event.target.value );
		onChange( event.target.value );
	};

	console.log( selection );

	return (
		<div className={ classes }>
			<fieldset name={ props.name }>
				<legend>{ props.label }</legend>
				{ options.map( ( option, index ) => {
					return (
						<label key={ index } htmlFor={ name + index }>
							<input
								checked={ selection === option }
								onChange={ ( value ) => {
									onChangeHandler( value );
								} }
								disabled={ disabled }
								type="radio"
								value={ option }
								name={ name }
								id={ name + index }
							/>
							{ option }
						</label>
					);
				} ) }
			</fieldset>
		</div>
	);
};

Radio.defaultProps = {
	label: '',
	placeholder: '',
	name: '',
	required: false,
	width: 6,
	region: 'world',
};

export default Radio;
