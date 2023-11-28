import { __ } from '@wordpress/i18n';
import { useEffect, useState } from 'react';

const Country = ( props ) => {
	const { onChange, help, disabled, placeholder, required, name, label, locale } = props;

	const classes = [ 'select', 'grid__column--span-' + props.width, props.required ? 'select--required' : '' ].join(
		' '
	);

	const lang = locale ? locale.split( '-' )[ 0 ] : 'en';

	const [ countries, setCountries ] = useState( [] );
	const [ selectedCountry, setSelectedCountry ] = useState( placeholder );

	const fetchCountries = async () => {
		const response = await fetch( `https://countries.kids-team.com/countries/${ props.region }/${ lang }` );
		const data = await response.json();

		const countryList = Object.entries( data ).map( ( [ key, name ], index ) => {
			return { value: key, label: name };
		} );

		setCountries( countryList );
	};

	useEffect( () => {
		fetchCountries();
	}, [] );

	const onChangeHandler = ( event ) => {
		setSelectedCountry( event.target.value );
		onChange( event.target.value );
	};

	return (
		<div className={ classes }>
			<label>{ label }</label>
			<select name={ name } required={ required } onChange={ onChangeHandler } disabled={ disabled }>
				<option value="" disabled selected={ selectedCountry == '' }>
					{ help ?? __( 'Select country', 'gutenberg-form' ) }
				</option>
				{ countries.map( ( country, index ) => {
					return (
						<option key={ index } value={ country.value } selected={ selectedCountry == country.value }>
							{ country.label }
						</option>
					);
				} ) }
			</select>
		</div>
	);
};

Country.defaultProps = {
	label: '',
	placeholder: '',
	name: '',
	required: false,
	width: 6,
	region: 'world',
};

export default Country;
