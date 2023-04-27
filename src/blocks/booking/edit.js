/**
 * Wordpress dependencies
 */
import { RichText, useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import { colord } from 'colord';

/**
 * Internal dependencies
 */
import Inspector from './inspector.js';

/**
 * @param {Props} props
 * @return {JSX.Element} Element
 */
const edit = ( props ) => {
	const {
		attributes: { buttonTitle, iconRight },
		setAttributes,
		buttonColor,
		className,
	} = props;

	const blockProps = useBlockProps( {
		className: [ 'ctx-event-booking' ].filter( Boolean ).join( ' ' ),
	} );

	const backgroundColor =
		buttonColor.color == undefined || buttonColor.color == '' ? 'var(--primary)' : buttonColor.color;
	const textColor = buttonColor == undefined || colord( buttonColor.color ).isLight() ? '#000000' : '#ffffff';

	const isOutline = blockProps.className?.includes( 'is-style-outline' );

	const style = {
		...blockProps.style,
		backgroundColor: isOutline ? 'transparent' : backgroundColor,
		boxShadow: isOutline ? 'inset 0px 0px 0px 2px ' + backgroundColor : 'none',
		color: isOutline ? backgroundColor : textColor,
	};

	const buttonClasses = [ className || false, 'ctx-button', iconRight ? 'reverse' : false ]
		.filter( Boolean )
		.join( ' ' );

	return (
		<div { ...blockProps }>
			<Inspector { ...props } />
			<span style={ style } className={ buttonClasses }>
				<RichText
					tagName="span"
					value={ buttonTitle }
					onChange={ ( value ) => setAttributes( { buttonTitle: value } ) }
					placeholder={ __( 'Registration', 'events' ) }
					allowedFormats={ [ 'core/bold', 'core/italic' ] }
				/>
			</span>
		</div>
	);
};

export default edit;
