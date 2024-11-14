import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, RangeControl, TextControl, ToggleControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

const Inspector = ( props ) => {
	const {
		attributes: { width, required, pattern, label, rows, name, help, error },
		setAttributes,
	} = props;

	return (
		<InspectorControls>
			<PanelBody title={ __( 'Data', 'events-manager' ) } initialOpen={ true }>
				<ToggleControl
					label={ __( 'Required', 'events-manager' ) }
					checked={ required }
					onChange={ ( value ) => setAttributes( { required: value } ) }
				/>

				<TextControl
					label={ __( 'Pattern', 'events-manager' ) }
					help={ __( 'Help text for the input field', 'events-manager' ) }
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
				<RangeControl
					label={ __( 'Height', 'events-manager' ) }
					help={ __( 'Number of text rows', 'events-manager' ) }
					value={ rows }
					onChange={ ( value ) => setAttributes( { rows: value } ) }
					min={ 1 }
					max={ 12 }
				/>
			</PanelBody>
		</InspectorControls>
	);
};

export default Inspector;
