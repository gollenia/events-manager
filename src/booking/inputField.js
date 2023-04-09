const React = require( 'react' );
import { __ } from '@wordpress/i18n';

const InputField = ( props ) => {
	const {
		field: { type, name, label, required = false, pattern = false, placeholder = '', options, width = 4 },
		field,
		value,
	} = props;

	const columns = [ 0, 2, 3, 4, 6 ];

	const getLabel = () => {
		const parser = new DOMParser();
		return parser.parseFromString( `<!doctype html><body>${ label }`, 'text/html' ).body.textContent;
	};

	const createMarkup = ( textString ) => {
		return { __html: textString };
	};

	const handleChange = ( event ) => {
		props.onChange( event.target.value );
	};

	const handleOptionChange = ( value ) => {
		props.onChange( value );
	};

	const handleCheckboxChange = ( event ) => {
		props.onChange( event.target.checked );
	};

	const selectOptions = () => {
		if ( type !== 'select' ) return [];
		if ( options.length === 0 ) return [];

		if ( ! Array.isArray( options ) ) {
			const result = [];
			Object.entries( options ).forEach( ( entry ) => {
				const [ key, label ] = entry;
				result.push(
					<option selected={ value == key } key={ key } value={ key }>
						{ label }
					</option>
				);
			} );
			return result;
		}
		return options.map( ( option, index ) => {
			return (
				<option selected={ value == option } key={ index }>
					{ option }
				</option>
			);
		} );
	};

	const radioOptions = () => {
		if ( type !== 'radio' ) return [];
		if ( options.length === 0 ) return [];
		return options.map( ( option, index ) => {
			if ( typeof option === 'object' ) {
				return (
					<div className="radio" key={ option.key }>
						<label htmlFor={ option.key }>
							<input
								onChange={ () => {
									handleOptionChange( option );
								} }
								type="radio"
								name={ `${ name }[${ option.key }]` }
								checked={ option.name == value }
							/>
							{ option.name }
						</label>
					</div>
				);
			}

			return (
				<div key={ index }>
					<label htmlFor={ index }>
						<input
							onChange={ () => {
								handleOptionChange( option );
							} }
							type="radio"
							name={ `${ name }[${ index }]` }
							checked={ value ? option == value : placeholder == index }
						/>
						{ option }
					</label>
				</div>
			);
		} );
	};

	switch ( type ) {
		case 'html':
			console.log( 'html', value );
			const content = createMarkup( value );
			if ( ! content.__html ) return <div>{ value }</div>;
			return (
				<div
					className={ `input-html grid__column--span-${ columns[ width ] }` }
					dangerouslySetInnerHTML={ content }
				></div>
			);
		case 'select':
			return (
				<div
					className={ `select grid__column--span-${ columns[ width ] } ${
						required ? ' input--required' : ''
					}` }
				>
					<label>{ getLabel() }</label>
					<select onChange={ handleChange } name={ name } required={ required }>
						{ field.hasEmptyOption && (
							<option value="">{ props.help ? props.help : __( 'Please select', 'events' ) }</option>
						) }
						{ selectOptions() }
					</select>
				</div>
			);
		case 'radio':
			return (
				<div
					className={ `radio grid__column--span-${ columns[ width ] } ${
						required ? ' input--required' : ''
					}` }
				>
					<label>{ getLabel() }</label>
					<fieldset className="radio">{ radioOptions() }</fieldset>
				</div>
			);
		case 'checkbox':
			return (
				<div className={ `checkbox grid__column--span-${ columns[ width ] }` }>
					<label>
						<input
							onChange={ ( event ) => {
								handleCheckboxChange( event );
							} }
							type="checkbox"
							name={ name }
							required={ required }
							checked={ value || placeholder }
						/>
						<span dangerouslySetInnerHTML={ { __html: field.help } }></span>
					</label>
				</div>
			);
		case 'date':
			return (
				<div
					className={ `input grid__column--span-${ columns[ width ] } ${
						required ? ' input--required' : ''
					}` }
				>
					<label>{ getLabel() }</label>
					<input
						onChange={ ( event ) => {
							handleChange( event );
						} }
						type={ type }
						name={ name }
						min={ field.min }
						max={ field.max }
						required={ required }
						value={ value }
					/>
				</div>
			);
		case 'textarea':
			return (
				<div
					className={ `textarea grid__column--span-${ columns[ width ] } ${
						required ? ' input--required' : ''
					}` }
				>
					<label>{ getLabel() }</label>
					<textarea
						placeholder={ placeholder }
						onChange={ handleChange }
						name={ name }
						value={ value }
						required={ required }
					></textarea>
				</div>
			);
		default:
			return (
				<div
					className={ `input grid__column--span-${ columns[ width ] } ${
						required ? ' input--required' : ''
					}` }
				>
					<label>{ getLabel() }</label>
					<input
						onChange={ ( event ) => {
							handleChange( event );
						} }
						type={ type }
						name={ name }
						required={ required }
						placeholder={ placeholder }
						value={ value }
						pattern={ pattern }
					/>
				</div>
			);
	}
};

export default InputField;
