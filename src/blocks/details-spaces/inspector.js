import { InspectorControls } from '@wordpress/block-editor';
import { CheckboxControl, PanelBody, RangeControl, TextControl } from '@wordpress/components';
import { __, _n, sprintf } from '@wordpress/i18n';

const Inspector = ( props ) => {
	const {
		attributes: { warningText, warningThreshold, okText, showNumber, bookedUpText },
		setAttributes,
	} = props;

	return (
		<InspectorControls>
			<PanelBody title={ __( 'Behaviour', 'events' ) } initialOpen={ true }>
				<CheckboxControl
					label={ __( 'Show Number', 'events' ) }
					checked={ showNumber }
					onChange={ ( value ) => setAttributes( { showNumber: value } ) }
				/>

				<RangeControl
					label={ __( 'Warning Threshold', 'events' ) }
					help={ __( 'Number of spaces when the warning message is shown', 'events' ) }
					value={ warningThreshold }
					onChange={ ( value ) => setAttributes( { warningThreshold: value } ) }
					min={ 1 }
					max={ 40 }
				/>

				<TextControl
					label={ _n( 'Message when only few spaces are left', 'events' ) }
					help={ __( 'Use %s as placeholder for the number of spaces left', 'events' ) }
					value={ warningText }
					onChange={ ( value ) => setAttributes( { warningText: value } ) }
					placeholder={ sprintf(
						_n( 'Only %s space left', 'Only %s spaces left', warningThreshold, 'events' ),
						warningThreshold
					) }
				/>

				<TextControl
					label={ __( 'Message when enough spaces are free', 'events' ) }
					value={ okText }
					onChange={ ( value ) => setAttributes( { okText: value } ) }
					disabled={ showNumber }
					placeholder={ __( 'Enough free spaces left', 'events' ) }
				/>

				<TextControl
					label={ __( 'Message when event is booked up', 'events' ) }
					value={ bookedUpText }
					onChange={ ( value ) => setAttributes( { bookedUpText: value } ) }
					disabled={ showNumber }
					placeholder={ __( 'Booked up', 'events' ) }
				/>
			</PanelBody>
		</InspectorControls>
	);
};

export default Inspector;
