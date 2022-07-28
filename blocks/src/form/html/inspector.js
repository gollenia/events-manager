import { InspectorControls } from '@wordpress/block-editor';
import { CheckboxControl, TextControl, ToggleControl, RangeControl, PanelBody, PanelRow, SelectControl, FormTokenField, Icon, Button } from '@wordpress/components';
import { __ } from '@wordpress/i18n'; 



const Inspector = (props) => {
	
	const {
		attributes: {
			width
		},
		setAttributes,
		
	} = props;

  	return (
		<InspectorControls>
		
		<PanelBody
			title={__('Appearance', 'events')}
			initialOpen={true}
		>
			<RangeControl
				label={ __("Width", 'events')}
				help={__("Number of columns the input field will occupy", 'events')}
				value={ width }
				max={4}
				min={1}
				onChange={ (value) => setAttributes({ width: value }) }
			/>
			
		</PanelBody>
		
		</InspectorControls>
  	);
};

export default Inspector;
