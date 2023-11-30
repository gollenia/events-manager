import { InspectorControls, URLInput } from '@wordpress/block-editor';
import { CheckboxControl, PanelBody } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

const Inspector = ( props ) => {
	const { attributes, setAttributes } = props;
	const { showAddress, showCity, showZip, showCountry, showLink, showTitle, url } = attributes;

	return (
		<InspectorControls>
			<PanelBody title={ __( 'Appearance', 'ctx-blocks' ) } initialOpen={ true }>
				<CheckboxControl
					label={ __( 'Show Title', 'ctx-blocks' ) }
					checked={ showTitle }
					onChange={ ( value ) => setAttributes( { showTitle: value } ) }
				/>
				<CheckboxControl
					label={ __( 'Show Address', 'ctx-blocks' ) }
					checked={ showAddress }
					onChange={ ( value ) => setAttributes( { showAddress: value } ) }
				/>
				<CheckboxControl
					label={ __( 'Show Zip', 'ctx-blocks' ) }
					checked={ showZip }
					onChange={ ( value ) => setAttributes( { showZip: value } ) }
				/>
				<CheckboxControl
					label={ __( 'Show City', 'ctx-blocks' ) }
					checked={ showCity }
					onChange={ ( value ) => setAttributes( { showCity: value } ) }
				/>

				<CheckboxControl
					label={ __( 'Show Country', 'ctx-blocks' ) }
					checked={ showCountry }
					onChange={ ( value ) => setAttributes( { showCountry: value } ) }
				/>
			</PanelBody>
			<PanelBody title={ __( 'Appearance', 'ctx-blocks' ) } initialOpen={ true }>
				<CheckboxControl
					label={ __( 'Show Link', 'ctx-blocks' ) }
					checked={ showLink }
					onChange={ ( value ) => setAttributes( { showLink: value } ) }
				/>
				<URLInput
					label={ __( 'Link', 'ctx-blocks' ) }
					value={ url }
					onChange={ ( value ) => setAttributes( { url: value } ) }
				/>
			</PanelBody>
		</InspectorControls>
	);
};

export default Inspector;
