import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, RangeControl, TextControl, ToggleControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { useState } from 'react';
import dateDiff from './dateDiff';

const Inspector = ( props ) => {
	const {
		attributes: { width, required, min, max, help, error },
		setAttributes,
	} = props;

	const [ referenceDate, setReferenceDate ] = useState( Date() );

	const minAge = dateDiff( min, referenceDate );
	const maxAge = dateDiff( max, referenceDate );

	const ageInfo = () => {
		if ( minAge === 0 && maxAge !== 0 ) {
			return `${ __( 'at best', 'events-manager' ) } ${ maxAge.result } }`;
		}
		if ( minAge !== 0 && maxAge === 0 ) {
			return `${ __( 'at least', 'events-manager' ) } ${ minAge.result } }`;
		}
		if ( minAge === maxAge ) {
			return `${ minAge.result }`;
		}
		return (
			<>
				{ __( 'from', 'events-manager' ) } { maxAge.result }
				<br />
				{ __( 'to', 'events-manager' ) } { minAge.result }
			</>
		);
	};

	return (
		<InspectorControls>
			<PanelBody title={ __( 'Data', 'events-manager' ) } initialOpen={ true }>
				<ToggleControl
					label={ __( 'Required', 'events-manager' ) }
					checked={ required }
					onChange={ ( value ) => setAttributes( { required: value } ) }
				/>

				<TextControl
					label={ __( 'Help', 'events-manager' ) }
					help={ __( 'Help text for the date field', 'events-manager' ) }
					value={ help }
					onChange={ ( value ) => setAttributes( { help: value } ) }
				/>
				<TextControl
					label={ __( 'Error message', 'events-manager' ) }
					help={ __(
						'Text to display when the user types in invalid or insufficient data',
						'events-manager'
					) }
					value={ error }
					onChange={ ( value ) => setAttributes( { error: value } ) }
				/>
				<TextControl
					label={ __( 'Lowest Date', 'events-manager' ) }
					help={ __( 'e.g. maximal age for an attendee', 'events-manager' ) }
					value={ min }
					onChange={ ( value ) => setAttributes( { min: value } ) }
					type="date"
				/>
				<TextControl
					label={ __( 'Highest Date', 'events-manager' ) }
					help={ __( 'e.g. minimal age for an attendee', 'events-manager' ) }
					value={ max }
					onChange={ ( value ) => setAttributes( { max: value } ) }
					type="date"
				/>
				<p className="age-info">
					<TextControl
						label={ __( 'Reference Date', 'events-manager' ) }
						help={ __( 'Only for testing', 'events-manager' ) }
						value={ referenceDate }
						onChange={ ( value ) => {
							setReferenceDate( value );
						} }
						type="date"
					/>
					{ ageInfo() }
				</p>
			</PanelBody>
			<PanelBody title={ __( 'Appearance', 'events-manager' ) } initialOpen={ true }>
				<RangeControl
					label={ __( 'Width', 'events-manager' ) }
					help={ __( 'Number of columns the input field will occupy', 'events-manager' ) }
					value={ width }
					max={ 4 }
					min={ 1 }
					onChange={ ( value ) => setAttributes( { width: value } ) }
				/>
			</PanelBody>
		</InspectorControls>
	);
};

export default Inspector;
