/**
 * Wordpress dependencies
 */
import { RichText, useBlockProps } from '@wordpress/block-editor';
import { Icon } from '@wordpress/components';
import { useEffect, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import icon from './icon.js';
import Inspector from './inspector.js';

/**
 * @param {Props} props
 * @return {JSX.Element} Element
 */
const edit = ( props ) => {
	const {
		attributes: { width, required, placeholder, label, fieldid, region },
		setAttributes,
	} = props;

	const [ countries, setCountries ] = useState( [] );

	const fetchCountries = async () => {
		const response = await fetch( 'https://countries.kids-team.com/countries/' + region + '/' + 'de' );
		const data = await response.json();
		const items = Object.entries( data ).map( ( [ key, value ] ) => {
			return {
				value: key,
				label: value,
			};
		} );
		setCountries( items );
	};

	useEffect( () => {
		fetchCountries();
	}, [ region ] );

	const validFieldId = () => {
		const validPattern = new RegExp( '([a-zA-Z0-9_]){3,40}' );
		return validPattern.test( fieldid );
	};

	const setFieldId = ( value ) => {
		value = value.toLowerCase();
		value = value.replace( /\s/g, '-' );
		setAttributes( { fieldid: value.toLowerCase() } );
	};

	const blockProps = useBlockProps( {
		className: [
			'ctx:form-field',
			'ctx:form-field--' + width,
			validFieldId() == false || label === '' ? 'ctx:form-field--error' : '',
		]
			.filter( Boolean )
			.join( ' ' ),
	} );

	return (
		<div { ...blockProps }>
			<Inspector { ...props } />

			<div className="ctx:form-field__caption">
				<div className="ctx:form-field__info">
					<Icon icon={ icon } />
					<div className="ctx:form-field__description">
						<span>
							<RichText
								tagName="span"
								className="ctx:form-details__label"
								value={ label }
								placeholder={ __( 'Label', 'events-manager' ) }
								onChange={ ( value ) => setAttributes( { label: value } ) }
							/>

							<span>{ required ? '*' : '' }</span>
						</span>
						<span className="ctx:form-field__label">{ __( 'Label for the field', 'events-manager' ) }</span>
					</div>
				</div>

				<div className="ctx:form-field__name">
					<RichText
						tagName="p"
						className="ctx:form-details__label"
						value={ fieldid }
						placeholder={ __( 'Slug', 'events-manager' ) }
						onChange={ ( value ) => setFieldId( value ) }
					/>
					{ validFieldId() == false && (
						<span className="ctx:form-field__error-message">
							{ __( 'Please type in a unique itentifier for the field', 'events-manager' ) }
						</span>
					) }
					{ validFieldId() && (
						<span className="ctx:form-field__label">{ __( 'Unique identifier', 'events-manager' ) }</span>
					) }
				</div>
			</div>
			<select
				onChange={ ( event ) => {
					setAttributes( { placeholder: event.target.value } );
				} }
			>
				{ countries.map( ( country, index ) => {
					if ( ! country ) return <></>;
					return (
						<option key={ index } value={ country.value } selected={ placeholder == country.value }>
							{ country.label }
						</option>
					);
				} ) }
			</select>
		</div>
	);
};

export default edit;
