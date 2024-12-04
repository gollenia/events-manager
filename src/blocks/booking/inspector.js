import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import SelectBookingForms from './inspector/selectBookingForms';
import SetBooking from './inspector/setBooking';
import SetExtras from './inspector/setExtras';
import SetSpaces from './inspector/setSpaces';

const Inspector = ( props ) => {
	const {
		buttonColor,
		setButtonColor,
		setAttributes,
		meta,
		setMeta,
		attributes: { buttonIcon, iconRight, bookNow, customButtonColor },
	} = props;

	return (
		<>
			<InspectorControls>
				<SetBooking { ...props } meta={ meta } setMeta={ setMeta } />
				<SelectBookingForms { ...props } meta={ meta } setMeta={ setMeta } />
				<SetSpaces { ...props } meta={ meta } setMeta={ setMeta } />
				<SetExtras { ...props } meta={ meta } setMeta={ setMeta } />
			</InspectorControls>
			<InspectorControls group="styles">
				<PanelBody title={ __( 'Button Settings', 'events' ) } initialOpen={ true }>
					<TextControl
						label={ __( 'Button Icon', 'events' ) }
						value={ buttonIcon }
						onChange={ ( value ) => {
							setAttributes( { buttonIcon: value } );
						} }
					/>
				</PanelBody>
			</InspectorControls>
		</>
	);
};

export default Inspector;
