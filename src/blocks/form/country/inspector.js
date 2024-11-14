import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, RangeControl, SelectControl, TextControl, ToggleControl } from '@wordpress/components';
import { useEffect, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

const Inspector = ( props ) => {
	const {
		attributes: { width, required, error, region, help },
		setAttributes,
	} = props;

	const [ regions, setRegions ] = useState( [] );

	const fetchRegions = async () => {
		const response = await fetch( 'https://countries.kids-team.com/regions/de' );
		const data = await response.json();
		const items = Object.entries( data ).map( ( [ key, value ] ) => {
			return {
				value: key,
				label: value,
			};
		} );
		setRegions( items );
	};

	useEffect( () => {
		fetchRegions();
	}, [] );

	return (
		<InspectorControls>
			<PanelBody title={ __( 'Data', 'events-manager' ) } initialOpen={ true }>
				<ToggleControl
					label={ __( 'Required', 'events-manager' ) }
					checked={ required }
					onChange={ ( value ) => setAttributes( { required: value } ) }
				/>

				<SelectControl
					label={ __( 'Region', 'events-manager' ) }
					value={ region }
					options={ regions }
					onChange={ ( value ) => setAttributes( { region: value } ) }
				/>

				<TextControl
					label={ __( 'Empty option', 'events-manager' ) }
					help={ __( 'Text to display when no country is selected', 'events-manager' ) }
					value={ help }
					onChange={ ( value ) => setAttributes( { help: value } ) }
				/>
			</PanelBody>
			<PanelBody title={ __( 'Appearance', 'events-manager' ) } initialOpen={ true }>
				<RangeControl
					label={ __( 'Width', 'events-manager' ) }
					help={ __( 'Number of columns the input field will occupy', 'events-manager' ) }
					value={ width }
					max={ 6 }
					min={ 1 }
					onChange={ ( value ) => setAttributes( { width: value } ) }
				/>
			</PanelBody>
		</InspectorControls>
	);
};

export default Inspector;
