import { InspectorControls } from '@wordpress/block-editor';
import {
	CheckboxControl,
	PanelBody,
	RangeControl,
	TextareaControl,
	TextControl,
	ToggleControl,
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';

const Inspector = ( props ) => {
	const {
		attributes: { width, required, help, error, options, hasEmptyOption },
		setAttributes,
	} = props;

	return (
		<InspectorControls>
			<PanelBody title={ __( 'Data', 'events-manager' ) } initialOpen={ true }>
				<ToggleControl
					label={ __( 'Required', 'events-manager' ) }
					checked={ required }
					onChange={ ( value ) => setAttributes( { required: value, hasEmptyOption: value } ) }
				/>
				<CheckboxControl
					label={ __( 'Empty option', 'events-manager' ) }
					help={ __( 'An empty option ist shown and selected as default', 'events-manager' ) }
					checked={ hasEmptyOption }
					disabled={ required }
					onChange={ ( value ) => setAttributes( { hasEmptyOption: value } ) }
				/>
				<TextControl
					label={ __( 'Help', 'events-manager' ) }
					help={ __( 'Alternate text for the empty option', 'events-manager' ) }
					value={ help }
					disabled={ ! hasEmptyOption }
					onChange={ ( value ) => setAttributes( { help: value } ) }
				/>
				<TextControl
					label={ __( 'Error message', 'events-manager' ) }
					help={ __( 'Text to inform the user that a choice has to be made', 'events-manager' ) }
					value={ error }
					onChange={ ( value ) => setAttributes( { error: value } ) }
				/>
				<TextareaControl
					label={ __( 'Options', 'events-manager' ) }
					value={ options.join( '\n' ) }
					onChange={ ( value ) => setAttributes( { options: value.split( '\n' ) } ) }
					help={ __( 'Options for the select control. Each line represents one option', 'events-manager' ) }
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
