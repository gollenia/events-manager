import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, PanelRow } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

const Inspector = ( props ) => {
	const { attributes, setAttributes } = props;

	return (
		<InspectorControls>
			<PanelBody title={ __( 'Appearance', 'events' ) } initialOpen={ true }>
				<PanelRow></PanelRow>
			</PanelBody>
		</InspectorControls>
	);
};

export default Inspector;
