import React from 'react';

type Props = {
	label: string;
	help: string;
	width: number;
	disabled: boolean;
	required: boolean;
	placeholder: boolean;
	toggle: boolean;
	onChange: ( value: boolean ) => void;
};

const Checkbox = ( props: Props ) => {
	const { help, width, onChange, disabled, placeholder, required, toggle } = props;

	const onChangeHandler = ( event: React.ChangeEvent< HTMLInputElement > ) => {
		onChange( event.target.checked );
	};

	const classes = [ toggle ? 'toggle' : 'checkbox', 'grid__column--span-' + width ].join( ' ' );

	return (
		<>
			{ toggle ? (
				<div className={ classes }>
					<label>
						<div className="toggle__control">
							<input
								defaultChecked={ placeholder }
								type="checkbox"
								required={ required }
								onChange={ onChangeHandler }
								disabled={ disabled }
							/>
							<span className="toggle__switch"></span>
						</div>
						<span dangerouslySetInnerHTML={ { __html: help } }></span>
					</label>
				</div>
			) : (
				<div className={ classes }>
					<label>
						<input
							defaultChecked={ placeholder }
							type="checkbox"
							required={ required }
							onChange={ onChangeHandler }
							disabled={ disabled }
						/>
						<span dangerouslySetInnerHTML={ { __html: help } }></span>
					</label>
				</div>
			) }
		</>
	);
};

Checkbox.defaultProps = {
	label: '',
	help: '',
	width: 6,
};

export default Checkbox;
