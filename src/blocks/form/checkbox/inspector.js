import { InspectorControls } from '@wordpress/block-editor';
import { Button, Icon, PanelBody, RangeControl, TextControl, ToggleControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import icons from './icons.js';

const Inspector = ( props ) => {
	const {
		attributes: { width, required, pattern, label, options, style, help, error },
		setAttributes,
	} = props;

	return (
		<InspectorControls>
			<PanelBody title={ __( 'Data', 'events-manager' ) } initialOpen={ true }>
				<ToggleControl
					label={ __( 'Required', 'events-manager' ) }
					checked={ required }
					onChange={ ( value ) => setAttributes( { required: value } ) }
				/>

				<TextControl
					label={ __( 'Error message', 'events-manager' ) }
					help={ __( 'Text to inform the user that this checkbox must be checked', 'events-manager' ) }
					value={ error }
					onChange={ ( value ) => setAttributes( { error: value } ) }
				/>
			</PanelBody>
			<PanelBody title={ __( 'Appearance', 'events-manager' ) } initialOpen={ true }>
				<div>
					<label className="components-base-control__label" htmlFor="inspector-range-control-4">
						{ __( 'Style', 'events-manager' ) }
					</label>
					<div className="styleSelector">
						<Button
							onClick={ () => setAttributes( { style: 'checkbox' } ) }
							className={ style == 'checkbox' ? 'active' : '' }
						>
							<Icon size="64" className="icon" icon={ icons.checkbox } />
							<div>{ __( 'Box', 'events-manager' ) }</div>
						</Button>
						<Button
							onClick={ () => setAttributes( { style: 'toggle' } ) }
							className={ style == 'toggle' ? 'active' : '' }
						>
							<Icon size="64" className="icon" icon={ icons.toggle } />
							<div>{ __( 'Toggle', 'events-manager' ) }</div>
						</Button>
					</div>
				</div>

				<RangeControl
					label={ __( 'Width', 'events-manager' ) }
					help={ __( 'Number of columns the input field will occupy', 'events-manager' ) }
					value={ width }
					max={ 6 }
					min={ 1 }
					onChange={ ( value ) => setAttributes( { width: value } ) }
				/>
			</PanelBody>
		</InspectorControls>
	);
};

export default Inspector;
