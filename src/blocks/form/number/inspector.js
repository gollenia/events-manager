import { InspectorControls } from '@wordpress/block-editor';
import {
	Button,
	CheckboxControl,
	Icon,
	PanelBody,
	RangeControl,
	ToggleControl,
	__experimentalNumberControl as NumberControl,
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import icons from './icons.js';

const Inspector = ( props ) => {
	const {
		attributes: {
			width,
			required,
			fieldid,
			range,
			min,
			max,
			step,
			placeholder,
			hasLabels,
			hasTicks,
		},
		setAttributes,
	} = props;

	const lockFieldId = [ 'first_name', 'last_name' ].includes( fieldid );
	console.log( min, max );
	return (
		<InspectorControls>
			<PanelBody
				title={ __( 'Data', 'gutenberg-form' ) }
				initialOpen={ true }
			>
				<ToggleControl
					label={ __( 'Required', 'gutenberg-form' ) }
					checked={ required }
					disabled={ lockFieldId }
					onChange={ ( value ) =>
						setAttributes( { required: value } )
					}
				/>

				<div className="ctx:form__panel-row">
					<NumberControl
						label={ __( 'Minimum value', 'gutenberg-form' ) }
						value={ min }
						onChange={ ( value ) => {
							if ( placeholder < value ) {
								setAttributes( { placeholder: value } );
							}
							setAttributes( { min: value } );
						} }
					/>
					<NumberControl
						label={ __( 'Maximum value', 'gutenberg-form' ) }
						value={ max }
						onChange={ ( value ) => {
							if ( placeholder > value ) {
								setAttributes( { placeholder: max } );
							}
							setAttributes( { max: value } );
						} }
					/>
				</div>
				<NumberControl
					label={ __( 'Step', 'gutenberg-form' ) }
					value={ step }
					max={ max }
					onChange={ ( value ) => setAttributes( { step: value } ) }
				/>
			</PanelBody>
			<PanelBody
				title={ __( 'Appearance', 'gutenberg-form' ) }
				initialOpen={ true }
			>
				<div className="styleSelector">
					<Button
						onClick={ () => setAttributes( { range: false } ) }
						className={ range ? '' : 'active' }
					>
						<Icon
							size="40"
							className="icon"
							icon={ icons.number }
						/>
						<span>{ __( 'Input', 'gutenberg-form' ) }</span>
					</Button>
					<Button
						onClick={ () => setAttributes( { range: true } ) }
						className={ range ? 'active' : '' }
					>
						<Icon size="40" className="icon" icon={ icons.range } />

						<span>{ __( 'Range', 'gutenberg-form' ) }</span>
					</Button>
				</div>

				<CheckboxControl
					label={ __( 'Show labels', 'gutenberg-form' ) }
					checked={ hasLabels }
					onChange={ ( value ) =>
						setAttributes( { hasLabels: value } )
					}
				/>

				<CheckboxControl
					label={ __( 'Show ticks', 'gutenberg-form' ) }
					checked={ hasTicks }
					onChange={ ( value ) =>
						setAttributes( { hasTicks: value } )
					}
				/>

				<RangeControl
					label={ __( 'Width', 'gutenberg-form' ) }
					help={ __(
						'Number of columns the input field will occupy',
						'gutenberg-form'
					) }
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
