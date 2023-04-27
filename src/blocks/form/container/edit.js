/**
 * Wordpress dependencies
 */
import { Inserter, useBlockProps, useInnerBlocksProps } from '@wordpress/block-editor';
import { select, useSelect } from '@wordpress/data';
import { __ } from '@wordpress/i18n';

export default function Edit( { ...props } ) {
	const { clientId } = props;
	const blockProps = useBlockProps();

	const postType = useSelect( select( 'core/editor' ).getCurrentPostType );
	if ( ! [ 'bookingform', 'attendeeform' ].includes( postType ) ) return <></>;

	document
		.getElementsByClassName( 'edit-post-fullscreen-mode-close' )[ 0 ]
		?.setAttribute( 'href', 'edit.php?post_type=event&page=events-manager-forms' );

	const allowedBlocks = [
		'events-manager/form-text',
		'events-manager/form-email',
		'events-manager/form-textarea',
		'events-manager/form-select',
		'events-manager/form-country',
		'events-manager/form-phone',
		'events-manager/form-radio',
		'events-manager/form-checkbox',
		'events-manager/form-date',
		'events-manager/form-html',
	];

	const innerBlocksProps = useInnerBlocksProps( blockProps, { allowedBlocks, renderAppender: false } );

	function SectionAppender( { rootClientId } ) {
		return (
			<Inserter
				rootClientId={ rootClientId }
				renderToggle={ ( { onToggle, disabled } ) => (
					<a className="components-button is-primary" onClick={ onToggle }>
						{ __( 'Add Field', 'events' ) }
					</a>
				) }
				isAppender
			/>
		);
	}

	return (
		<form autocomplete="off" className="ctx:event-form">
			<div { ...innerBlocksProps } className="ctx:event-form__container"></div>
			<div className="ctx:event-form__appender">
				<SectionAppender rootClientId={ clientId } />
			</div>
		</form>
	);
}
