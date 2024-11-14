import { CheckboxControl, SelectControl, TextControl, TextareaControl } from '@wordpress/components';
import React, { useEffect, useState } from 'react';

const AdminField = ( { type, label, value, onChange, help, error, required, ...props } ) => {
	const [ countries, setCountries ] = useState( [] );

	const browserLanguage = navigator.language.split( '-' )[ 0 ];

	useEffect( () => {
		if ( type !== 'country' ) {
			return;
		}
		fetch( `https://countries.kids-team.com/countries/${ props.region ?? 'world' }/${ browserLanguage }` )
			.then( ( response ) => response.json() )
			.then( ( data ) => {
				const countryList = Object.entries( data ).map( ( [ key, name ], index ) => {
					return { value: key, label: name };
				} );

				setCountries( countryList );
			} );
	}, [] );

	if ( type === 'text' || type === 'email' || type === 'number' || type === 'password' ) {
		return (
			<TextControl
				label={ label }
				value={ value }
				onChange={ onChange }
				help={ help }
				error={ error }
				type={ type }
				required={ required }
				{ ...props }
			/>
		);
	}

	if ( type === 'textarea' ) {
		return (
			<TextareaControl
				label={ label }
				value={ value }
				onChange={ onChange }
				help={ help }
				error={ error }
				required={ required }
				{ ...props }
			/>
		);
	}

	if ( type === 'select' || type === 'radio' ) {
		const mappedOptions = props.options.map( ( option ) => {
			return { label: option, value: option };
		} );

		return (
			<SelectControl
				label={ label }
				value={ value }
				onChange={ onChange }
				onFocus={ onChange }
				help={ help }
				error={ error }
				required={ required }
				options={ mappedOptions }
				defaultValue={ mappedOptions[ 0 ].value }
			/>
		);
	}

	if ( type === 'country' ) {
		return (
			<SelectControl
				label={ label }
				value={ value }
				onChange={ onChange }
				help={ help }
				error={ error }
				required={ required }
				options={ countries }
			/>
		);
	}

	if ( type === 'checkbox' ) {
		return (
			<CheckboxControl
				label={ help ?? label }
				value={ value }
				onChange={ onChange }
				required={ required }
				checked={ value }
				type="checkbox"
			/>
		);
	}

	if ( type === 'date' ) {
		return (
			<TextControl
				label={ label }
				value={ value }
				onChange={ onChange }
				help={ help }
				error={ error }
				type="date"
				required={ required }
				{ ...props }
			/>
		);
	}
};

export default AdminField;
