import { InspectorControls, URLInput } from '@wordpress/block-editor';
import { CheckboxControl, PanelBody } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

const Inspector = ( props ) => {
	const { attributes, setAttributes } = props;
	const { showAddress, showCity, showZip, showCountry, showLink, showTitle, url } = attributes;

	return (
		<InspectorControls>
			<PanelBody title={ __( 'Data', 'events' ) } initialOpen={ true }>
				<CheckboxControl
					label={ __( 'Show Title', 'events' ) }
					checked={ showTitle }
					onChange={ ( value ) => setAttributes( { showTitle: value } ) }
				/>
				<CheckboxControl
					label={ __( 'Show Address', 'events' ) }
					checked={ showAddress }
					onChange={ ( value ) => setAttributes( { showAddress: value } ) }
				/>
				<CheckboxControl
					label={ __( 'Show Zip', 'events' ) }
					checked={ showZip }
					onChange={ ( value ) => setAttributes( { showZip: value } ) }
				/>
				<CheckboxControl
					label={ __( 'Show City', 'events' ) }
					checked={ showCity }
					onChange={ ( value ) => setAttributes( { showCity: value } ) }
				/>

				<CheckboxControl
					label={ __( 'Show Country', 'events' ) }
					checked={ showCountry }
					onChange={ ( value ) => setAttributes( { showCountry: value } ) }
				/>
			</PanelBody>
			<PanelBody title={ __( 'Behaviour', 'events' ) } initialOpen={ true }>
				<CheckboxControl
					label={ __( 'Show Link', 'events' ) }
					checked={ showLink }
					onChange={ ( value ) => setAttributes( { showLink: value } ) }
				/>
				<URLInput
					label={ __( 'Custom Link', 'events' ) }
					value={ url }
					onChange={ ( value ) => setAttributes( { url: value } ) }
				/>
			</PanelBody>
		</InspectorControls>
	);
};

export default Inspector;
