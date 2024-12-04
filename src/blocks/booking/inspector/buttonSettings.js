import { PanelBody, TextControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

const ButtonSettings = ( props ) => {
	const { buttonIcon, setAttributes } = props;
	return (
		<PanelBody title={ __( 'Button Settings', 'events' ) } initialOpen={ true }>
			<TextControl
				label={ __( 'Button Icon', 'events' ) }
				value={ buttonIcon }
				onChange={ ( value ) => {
					setAttributes( { buttonIcon: value } );
				} }
			/>
		</PanelBody>
	);
};

export default ButtonSettings;
