/**
 * Internal dependencies
 */
import Inspector from './inspector';
import Toolbar from './toolbar';
/**
 * WordPress dependencies
 */
import {
	getColorClassName,
	useBlockProps,
	__experimentalUseBorderProps as useBorderProps,
	useInnerBlocksProps,
	withColors,
} from '@wordpress/block-editor';
import { compose } from '@wordpress/compose';

import { useRef } from '@wordpress/element';

function ItemEdit( { ...props } ) {
	const {
		attributes: { icon, url, urlIcon, imageUrl },
		iconColor,
		customIconColor,
		customIconBackgroundColor,
		iconBackgroundColor,
		className,
		setAttributes,
		backgroundColor,
	} = props;

	const imageRef = useRef();

	const onSelectMedia = ( media ) => {
		if ( ! media || ! media.url ) {
			setAttributes( { imageUrl: undefined, imageId: undefined } );
			return;
		}
		setAttributes( {
			imageUrl: media.sizes?.thumbnail?.url ?? media.url,
			imageId: media.id,
		} );
	};

	const classes = [ className, 'event-details__item' ].filter( Boolean ).join( ' ' );

	const blockProps = useBlockProps( {
		className: classes,
	} );

	const borderProps = useBorderProps( props.attributes );
	const imageStyle = {
		...borderProps.style,
		color: iconColor?.color ?? customIconColor ?? 'none',
		backgroundColor: iconBackgroundColor?.color ?? customIconBackgroundColor ?? 'none',
	};

	const imageClasses = [
		'event-details__icon',
		getColorClassName( 'color', iconColor ),
		getColorClassName( 'background-color', iconBackgroundColor ),
	].join( ' ' );

	const TEMPLATE = [
		[
			'core/heading',
			{
				placeholder: 'Title',
				level: 4,
				className: 'event-details_title',
				style: { spacing: { margin: { top: '0px', bottom: '0px' } } },
			},
		],
		[ 'core/paragraph', { placeholder: 'Description', className: 'event-details_text' } ],
	];
	const innerBlockProps = useInnerBlocksProps(
		{ className: 'event-details__item-content' },
		{
			template: TEMPLATE,
			allowedBlocks: [ 'core/paragraph', 'core/heading' ],
		}
	);

	return (
		<>
			<div
				{ ...blockProps }
				style={ {
					...blockProps.style,
					backgroundColor: 'none !important',
				} }
			>
				<Inspector { ...props } />
				<Toolbar { ...props } onSelectMedia={ onSelectMedia } />
				<div className={ imageClasses } style={ imageStyle }>
					{ imageUrl && <img src={ imageUrl } ref={ imageRef } /> }

					{ ! imageUrl && <i className="material-icons material-symbols-outlined">{ icon }</i> }
				</div>

				<div className="event-details__item-content" { ...innerBlockProps }></div>
				{ url && (
					<div class="event-details__item-action">
						<b>
							<i class="material-icons  material-symbols-outlined">{ urlIcon }</i>
						</b>
					</div>
				) }
			</div>
		</>
	);
}

export default compose( [
	withColors( {
		iconColor: 'icon-color',
		iconBackgroundColor: 'background-color',
	} ),
] )( ItemEdit );
