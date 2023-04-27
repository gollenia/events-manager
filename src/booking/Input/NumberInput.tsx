import React, { useRef, useState } from 'react';

type NumberInputProps = {
	label: string;
	placeholder: number;
	name: string;
	required: boolean;
	width: number;
	min: number;
	max: number;
	range: boolean;
	disabled: boolean;
	hasTicks: boolean;
	hasLabels: boolean;
	onChange: ( value: string ) => void;
};

const NumberInput = ( props: NumberInputProps ) => {
	const {
		label,
		placeholder,
		name,
		required,
		width,
		min,
		max,
		disabled,
		range,
		hasTicks,
		hasLabels,
		onChange,
	} = props;

	const [ rangeValue, setRangeValue ] = useState( placeholder );
	const rangeRef = useRef( null );

	const onChangeHandler = (
		event: React.ChangeEvent< HTMLInputElement >
	) => {
		setRangeValue( parseInt( event.target.value ) );
		onChange( event.target.value );
	};

	const classes = [
		range ? 'range' : 'input',
		'range--ticks',
		'grid__column--span-' + width,
		required ? 'input--required' : '',
	].join( ' ' );

	const rangeStyle = {
		backgroundSize:
			( ( rangeValue - min ) * 100 ) / ( max - min ) + '% 100%',
	};

	console.log( min, max, rangeValue );

	return (
		<>
			{ range ? (
				<div className={ classes }>
					<label>{ label }</label>
					<div className="range__set">
						<div className="range__control">
							<input
								value={ rangeValue }
								name={ name }
								required={ required }
								disabled={ disabled }
								type="range"
								max={ max }
								min={ min }
								style={ rangeStyle }
								ref={ rangeRef }
								onChange={ onChangeHandler }
							/>
							{ hasTicks && (
								<div className="range__ticks">
									{ [ ...Array( max - min + 1 ) ].map(
										( e, i ) => {
											return (
												<div
													className="range__tick"
													key={ i }
												></div>
											);
										}
									) }
								</div>
							) }
							{ hasLabels && (
								<div className="range__labels">
									<span className="range__label">
										{ min }
									</span>
									<span className="range__label">
										{ max }
									</span>
								</div>
							) }
						</div>
						<span className="range__value">{ rangeValue }</span>
					</div>
				</div>
			) : (
				<div className={ classes }>
					<label>{ label }</label>
					<input
						value={ rangeValue }
						name={ name }
						required={ required }
						disabled={ disabled }
						type="number"
						max={ max }
						min={ min }
						ref={ rangeRef }
						onChange={ onChangeHandler }
					/>
				</div>
			) }
		</>
	);
};

NumberInput.defaultProps = {
	label: '',
	placeholder: 0,
	name: '',
	required: false,
	width: 6,
	min: 0,
	max: 100,
	style: 'input',
	hasLabels: false,
	hasTicks: false,
};

export default NumberInput;
