import { InspectorControls } from '@wordpress/block-editor';
import { CheckboxControl, TextControl, ToggleControl, RangeControl, PanelBody, PanelRow, SelectControl, FormTokenField, Icon, Button } from '@wordpress/components';
import { __ } from '@wordpress/i18n'; 



const Inspector = (props) => {
	
	const {
		attributes: {
			showLocation,
			showAudience,
			showDate,
			showTime,
			showSpeaker,
			showPrice,
			audienceDescription,
			audienceIcon,
			locationLink,
			speakerDescription,
			priceOverwrite,
			speakerIcon,
			speakerLink,
			bookedUpWarningThreshold,
			showBookedUp
		},
		setAttributes,
		
	} = props;

  	return (
		<InspectorControls>
		<PanelBody
			title={__('Audience', 'events')}
			initialOpen={true}
		>
			<ToggleControl
				label={ __("Show Audience", 'events')}
				checked={ showAudience }
				onChange={ (value) => setAttributes({ showAudience: value }) }
			/>
			<TextControl
				disabled={!showAudience}
				label={__("Description", "events")}
				help={__("If empty, \"Audience\" is shown", 'events')}
				value={ audienceDescription }
				onChange={ (value) => setAttributes({ audienceDescription: value })}
			/>
			<SelectControl
				disabled={!showAudience}
				label={ __("Icon", 'events')}
				value={ audienceIcon }
				options={ [
					{ value: 'groups', label: __('People', 'events') },
					{ value: 'male', label: __('Männlich', 'events') },
					{ value: 'female', label: __('Weiblich', 'events') }
				] }
				onChange={ (value) => setAttributes({ audienceIcon: value }) }
			/>
		</PanelBody>
		<PanelBody
			title={__('Location', 'events')}
			initialOpen={true}
		>
			<ToggleControl
				label={ __("Show Location", 'events')}
				checked={ showLocation }
				onChange={ (value) => setAttributes({ showLocation: value }) }
			/>
			<CheckboxControl
				disabled={!showLocation}
				label={ __("Add Link", 'events')}
				checked={ locationLink }
				onChange={ (value) => setAttributes({ locationLink: value }) }
			/>
		</PanelBody>
		<PanelBody
			title={__('Date and Time', 'events')}
			initialOpen={true}
		>
			<ToggleControl
				label={ __("Show Date", 'events')} 
				checked={ showDate }
				onChange={ (value) => setAttributes({ showDate: value }) }
			/>
			<ToggleControl
				label={ __("Show Time", 'events')}
				checked={ showTime }
				onChange={ (value) => setAttributes({ showTime: value }) }
			/>
		</PanelBody>
		<PanelBody
			title={__('Speaker', 'events')}
			initialOpen={true}
		>
			<ToggleControl
				label={ __("Show Speaker", 'events')}
				checked={ showSpeaker }
				onChange={ (value) => setAttributes({ showSpeaker: value }) }
			/>
			<TextControl
				disabled={!showSpeaker}
				label={__("Description", "events")}
				help={__("If empty, \"Speaker\" is shown", 'events')}
				value={ speakerDescription }
				onChange={ (value) => setAttributes({ speakerDescription: value })}
			/>
			<SelectControl
				disabled={!showSpeaker}
				label={ __("Icon", 'events')}
				value={ speakerIcon }
				options={ [
					{ value: '', label: __('Photo of speaker', 'events') },
					{ value: 'face', label: __('Face', 'events') },
					{ value: 'support_agent', label: __('Online Speaker', 'events') }
				] }
				onChange={ (value) => setAttributes({ speakerIcon: value }) }
			/>
			<CheckboxControl
				disabled={!showSpeaker}
				label={ __("Add email link", 'events')}
				checked={ speakerLink }
				onChange={ (value) => setAttributes({ speakerLink: value }) }
			/>
		</PanelBody>
		<PanelBody
			title={__('Price', 'events')}
			initialOpen={true}
		>
			<ToggleControl
				label={ __("Show Price", 'events')}
				checked={ showPrice }
				onChange={ (value) => setAttributes({ showPrice: value }) }
			/>
			<TextControl
				disabled={!showPrice}
				label={__("Overwrite Price", "events")}
				help={__("If empty, the first ticket's price is used", 'events')}
				value={ priceOverwrite }
				onChange={ (value) => setAttributes({ priceOverwrite: value })}
			/>
		</PanelBody>
		<PanelBody
			title={__('Booked up warning', 'events')}
			initialOpen={true}
		>
			<ToggleControl
				label={ __("Show if event is booked up or nearly booked up", 'events')}
				checked={ showBookedUp }
				onChange={ (value) => setAttributes({ showBookedUp: value }) }
			/>
			<RangeControl
				label={ __("Warning threshold", 'events')}
				value={ bookedUpWarningThreshold }
				onChange={ (value) => setAttributes({ bookedUpWarningThreshold: value }) }
				min={ 0 }
				max={ 10 }
				help={ __("Show a warning that the event is nearly booked up when only this number of spaces are left", 'events')}
			/>
		</PanelBody>
		</InspectorControls>
  	);
};

export default Inspector;
