import { InspectorControls } from '@wordpress/block-editor';
import { TextControl, ToggleControl, RangeControl, PanelBody } from '@wordpress/components';
import { __ } from '@wordpress/i18n'; 



const Inspector = (props) => {
	
	const {
		attributes: {
			width,
			required,
			fieldid,
			pattern,
			label,
			name,
			help,
			error
		},
		setAttributes,
		
	} = props;

	const lockFieldId = 'user_email' === fieldid;

  	return (
		<InspectorControls>
		<PanelBody
			title={__('Data', 'events')}
			initialOpen={true}
		>
			<ToggleControl
				label={ __("Required", 'events')}
				checked={ required }
				disabled={lockFieldId}
				onChange={ (value) => setAttributes({ required: value }) }
			/>
			<TextControl
				label={__("Help", "events")}
				help={__("Help text for the input field", 'events')}
				value={ help }
				onChange={ (value) => setAttributes({ help: value })}
			/>
			<TextControl
				label={__("Error message", "events")}
				help={__("Text to display when the user types in invalid or insufficient data", 'events')}
				value={ error }
				onChange={ (value) => setAttributes({ error: value })}
			/>
		</PanelBody>
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
