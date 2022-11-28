import { InspectorControls, PanelColorSettings } from '@wordpress/block-editor';
import { CheckboxControl, PanelBody, TextControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';



const Inspector = (props) => {
	
	const {
		buttonColor,
        setButtonColor,
		setAttributes,
		attributes: {
			buttonIcon,
			buttonIconSuffix,
			bookNow
		}
	} = props;

  	return (
		<InspectorControls>

			<PanelColorSettings
				title={__('Color Settings', 'events')}
				colorSettings={[
					{
						label: __('Set a background color for the button', 'events'),
						onChange: setButtonColor ,
						value: buttonColor.color,
						disableCustomColors: true,
					},
				]}
			/>
			<PanelBody
				title={__('Button Settings', 'events')}
				initialOpen={true}
			>
				<TextControl 
					label={__('\"Book now\" Button', 'events')}
					value={bookNow}
					onChange={(value) => { setAttributes({ bookNow: value }) }}
				/>
				<TextControl 
					label={__('Button Icon', 'events')}
					value={buttonIcon}
					onChange={(value) => { setAttributes({ buttonIcon: value }) }}
				/>
				<CheckboxControl 
					label={__('Button Icon Suffix', 'events')}
					checked={buttonIconSuffix}
					onChange={(value) => { setAttributes({ buttonIconSuffix: value }) }}
				/>
			</PanelBody>
		</InspectorControls>
  	);
};

export default Inspector;
