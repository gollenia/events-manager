import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, RangeControl, TextControl, ToggleControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

const Inspector = ( props ) => {
	const {
		attributes: { width, required, fieldid, pattern, label, name, help, error },
		setAttributes,
	} = props;

	const lockFieldId = 'user_email' === fieldid;

	return (
		<InspectorControls>
			<PanelBody title={ __( 'Data', 'events-manager' ) } initialOpen={ true }>
				<ToggleControl
					label={ __( 'Required', 'events-manager' ) }
					checked={ required }
					disabled={ lockFieldId }
					onChange={ ( value ) => setAttributes( { required: value } ) }
				/>
				<TextControl
					label={ __( 'Help', 'events-manager' ) }
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
					max={ 6 }
					min={ 1 }
					onChange={ ( value ) => setAttributes( { width: value } ) }
				/>
			</PanelBody>
		</InspectorControls>
	);
};

export default Inspector;
