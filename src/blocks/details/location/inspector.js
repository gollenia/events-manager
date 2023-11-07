import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, ToggleControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

const Inspector = ( props ) => {
	const { attributes, setAttributes } = props;
	const { showAddress, showCity, showZip, showCountry } = attributes;

	return (
		<InspectorControls>
			<PanelBody title={ __( 'Appearance', 'ctx-blocks' ) } initialOpen={ true }>
				<ToggleControl
					label={ __( 'Show Address', 'ctx-blocks' ) }
					checked={ showAddress }
					onChange={ ( value ) => setAttributes( { showAddress: value } ) }
				/>
				<ToggleControl
					label={ __( 'Show City', 'ctx-blocks' ) }
					checked={ showCity }
					onChange={ ( value ) => setAttributes( { showCity: value } ) }
				/>
				<ToggleControl
					label={ __( 'Show Zip', 'ctx-blocks' ) }
					checked={ showZip }
					onChange={ ( value ) => setAttributes( { showZip: value } ) }
				/>
				<ToggleControl
					label={ __( 'Show Country', 'ctx-blocks' ) }
					checked={ showCountry }
					onChange={ ( value ) => setAttributes( { showCountry: value } ) }
				/>
			</PanelBody>
		</InspectorControls>
	);
};

export default Inspector;
