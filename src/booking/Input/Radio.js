import { useState } from 'react';

const Radio = ( props ) => {
	const { onChange, options, name, disabled, placeholder } = props;

	const classes = [ 'radio', 'grid__column--span-' + props.width, props.required ? 'select--required' : '' ].join(
		' '
	);

	const [ selection, setSelection ] = useState( placeholder );

	const onChangeHandler = ( event ) => {
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
