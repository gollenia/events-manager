import { __ } from '@wordpress/i18n';

type Props = {
	label: string;
	placeholder: string;
	name: string;
	required: boolean;
	width: number;
	options: Array< string >;
	hasEmptyOption: boolean;
	help: string;
	hint: string;
	disabled: boolean;
	multiSelect: boolean;
	onChange: ( value: string ) => void;
};

const Select = ( props: Props ) => {
	const {
		onChange,
		options,
		hasEmptyOption,
		help,
		hint,
		disabled,
		placeholder,
		multiSelect,
		required,
		label,
		name,
	} = props;

	const classes = [ 'select', 'grid__column--span-' + props.width, props.required ? 'select--required' : '' ].join(
		' '
	);

	const selectOptions = () => {
		if ( ! Array.isArray( options ) ) {
			return Object.entries( options ).map( ( [ key, name ], index ) => {
				return (
					<option key={ index } value={ key }>
						{ name }
					</option>
				);
			} );
		}

		return options.map( ( option, index ) => {
			return <option key={ index }>{ option }</option>;
		} );
	};

	const onChangeHandler = ( event: React.ChangeEvent< HTMLSelectElement > ) => {
		onChange( event.target.value );
	};

	return (
		<div className={ classes }>
			<label>{ label }</label>
			<select
				name={ name }
				required={ required }
				onChange={ onChangeHandler }
				autoComplete={ hint }
				disabled={ disabled }
				multiple={ multiSelect }
				defaultValue={ placeholder }
			>
				{ hasEmptyOption && (
					<option disabled selected>
						{ help ?? __( 'Make a selection', 'gutenberg-form' ) }
					</option>
				) }
				{ selectOptions() }
			</select>
		</div>
	);
};

Select.defaultProps = {
	label: '',
	placeholder: '',
	name: '',
	required: false,
	width: 6,
	region: 'world',
};

export default Select;
