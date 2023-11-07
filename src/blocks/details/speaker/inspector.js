import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, ToggleControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

const Inspector = ( props ) => {
	const { attributes, setAttributes } = props;

	const { showMail } = attributes;

	return (
		<InspectorControls>
			<PanelBody title={ __( 'Appearance', 'ctx-blocks' ) } initialOpen={ true }>
				<ToggleControl
					label={ __( 'Show Mail', 'ctx-blocks' ) }
					checked={ showMail }
					onChange={ ( value ) => setAttributes( { showMail: value } ) }
				/>
			</PanelBody>
		</InspectorControls>
	);
};

export default Inspector;
