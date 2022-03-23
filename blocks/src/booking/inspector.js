import { InspectorControls, PanelColorSettings } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n'; 



const Inspector = (props) => {
	
	const {
		buttonColor,
        setButtonColor
	} = props;

  	return (
		<InspectorControls>

			<PanelColorSettings
				title={__('Color Settings', 'ctx-blocks')}
				colorSettings={[
					{
						label: __('Set a background color for the button', 'ctx-blocks'),
						onChange: setButtonColor ,
						value: buttonColor.color,
						disableCustomColors: true,
					},
				]}
			/>
		</InspectorControls>
  	);
};

export default Inspector;
