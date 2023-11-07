/**
 * Wordpress dependencies
 */
import { useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

import { select } from '@wordpress/data';

import { useEntityProp } from '@wordpress/core-data';
/**
 * Internal dependencies
 */

import Inspector from './inspector.js';

/**
 * @param {Props} props
 * @return {JSX.Element} Element
 */
const edit = ( props ) => {
	const postType = select( 'core/editor' ).getCurrentPostType();

	if ( postType !== 'event' ) return <></>;

	const [ meta, setMeta ] = useEntityProp( 'postType', postType, 'meta' );

	const {
		attributes: { icon, roundImage, format },
	} = props;

	const blockProps = useBlockProps();

	return (
		<div { ...blockProps }>
			<Inspector { ...props } />

			<div className="ctx:event-details__item">
				<i className="material-icons">schedule</i>
				<div>
					<h5>{ __( 'Time', 'events' ) }</h5>
				</div>
			</div>
		</div>
	);
};

export default edit;
