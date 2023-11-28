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
			<PanelBody title={ __( 'Behaviour', 'ctx-blocks' ) } initialOpen={ true }>
				<CheckboxControl
					label={ __( 'Show Number', 'ctx-blocks' ) }
					checked={ showNumber }
					onChange={ ( value ) => setAttributes( { showNumber: value } ) }
				/>

				<RangeControl
					label={ __( 'Warning Threshold', 'ctx-blocks' ) }
					help={ __( 'Number of spaces when the warning message is shown', 'ctx-blocks' ) }
					value={ warningThreshold }
					onChange={ ( value ) => setAttributes( { warningThreshold: value } ) }
					min={ 1 }
					max={ 10 }
				/>

				<TextControl
					label={ _n( 'Message when only few spaces are left', 'ctx-blocks' ) }
					help={ __( 'Use %s as placeholder for the number of spaces left', 'ctx-blocks' ) }
					value={ warningText }
					onChange={ ( value ) => setAttributes( { warningText: value } ) }
					placeholder={ sprintf(
						_n( 'Only %s space left', 'Only %s spaces left', warningThreshold, 'ctx-blocks' ),
						warningThreshold
					) }
				/>

				<TextControl
					label={ __( 'Message when enough spaces are free', 'ctx-blocks' ) }
					value={ okText }
					onChange={ ( value ) => setAttributes( { okText: value } ) }
					disabled={ showNumber }
					placeholder={ __( 'Enough free spaces left', 'ctx-blocks' ) }
				/>

				<TextControl
					label={ __( 'Message when event is booked up', 'ctx-blocks' ) }
					value={ bookedUpText }
					onChange={ ( value ) => setAttributes( { bookedUpText: value } ) }
					disabled={ showNumber }
					placeholder={ __( 'Booked up', 'ctx-blocks' ) }
				/>
			</PanelBody>
		</InspectorControls>
	);
};

export default Inspector;
