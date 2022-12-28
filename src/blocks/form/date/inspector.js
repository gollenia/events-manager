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
			return `${ __( 'at best', 'events' ) } ${ maxAge.result } }`;
		}
		if ( minAge !== 0 && maxAge === 0 ) {
			return `${ __( 'at least', 'events' ) } ${ minAge.result } }`;
		}
		if ( minAge === maxAge ) {
			return `${ minAge.result }`;
		}
		return (
			<>
				{ __( 'from', 'events' ) } { maxAge.result }
				<br />
				{ __( 'to', 'events' ) } { minAge.result }
			</>
		);
	};

	return (
		<InspectorControls>
			<PanelBody title={ __( 'Data', 'events' ) } initialOpen={ true }>
				<ToggleControl
					label={ __( 'Required', 'events' ) }
					checked={ required }
					onChange={ ( value ) => setAttributes( { required: value } ) }
				/>

				<TextControl
					label={ __( 'Help', 'events' ) }
					help={ __( 'Help text for the date field', 'events' ) }
					value={ help }
					onChange={ ( value ) => setAttributes( { help: value } ) }
				/>
				<TextControl
					label={ __( 'Error message', 'events' ) }
					help={ __( 'Text to display when the user types in invalid or insufficient data', 'events' ) }
					value={ error }
					onChange={ ( value ) => setAttributes( { error: value } ) }
				/>
				<TextControl
					label={ __( 'Lowest Date', 'events' ) }
					help={ __( 'e.g. maximal age for an attendee', 'events' ) }
					value={ min }
					onChange={ ( value ) => setAttributes( { min: value } ) }
					type="date"
				/>
				<TextControl
					label={ __( 'Highest Date', 'events' ) }
					help={ __( 'e.g. minimal age for an attendee', 'events' ) }
					value={ max }
					onChange={ ( value ) => setAttributes( { max: value } ) }
					type="date"
				/>
				<p className="age-info">
					<TextControl
						label={ __( 'Reference Date', 'events' ) }
						help={ __( 'Only for testing', 'events' ) }
						value={ referenceDate }
						onChange={ ( value ) => {
							setReferenceDate( value );
						} }
						type="date"
					/>
					{ ageInfo() }
				</p>
			</PanelBody>
			<PanelBody title={ __( 'Appearance', 'events' ) } initialOpen={ true }>
				<RangeControl
					label={ __( 'Width', 'events' ) }
					help={ __( 'Number of columns the input field will occupy', 'events' ) }
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
