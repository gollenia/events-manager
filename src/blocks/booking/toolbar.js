import { AlignmentControl, BlockControls } from '@wordpress/block-editor';

import { ToolbarButton } from '@wordpress/components';

import { useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { pullLeft, pullRight, seen, unseen } from '@wordpress/icons';

const Toolbar = ( props ) => {
	const [ isEditingURL, setIsEditingURL ] = useState( false );

	const {
		attributes: { iconRight, iconOnly, icon },
		setAttributes,
	} = props;

	return (
		<>
			<BlockControls group="block">
				<AlignmentControl
					value={ iconRight ? 'right' : 'left' }
					onChange={ ( event ) => {
						setAttributes( {
							iconRight: event === 'right',
						} );
					} }
					alignmentControls={ [
						{
							icon: pullLeft,
							title: __( 'Align icon left', 'ctx-blocks' ),
							align: 'left',
						},
						{
							icon: pullRight,
							title: __( 'Align icon right', 'ctx-blocks' ),
							align: 'right',
						},
					] }
					label={ __( 'Icon alignment', 'ctx-blocks' ) }
				/>
				{ icon && (
					<ToolbarButton
						name="iconOnly"
						icon={ iconOnly ? unseen : seen }
						title={ __( 'Hide text', 'ctx-blocks' ) }
						isActive={ iconOnly }
						onClick={ () => setAttributes( { iconOnly: ! iconOnly } ) }
					/>
				) }
			</BlockControls>
		</>
	);
};

export default Toolbar;
