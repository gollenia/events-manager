/**
 * Wordpress dependencies
 */
import { useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

import apiFetch from '@wordpress/api-fetch';

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
		attributes: { icon, roundImage, format },
		postId,
		postType,
	} = props;

	if ( postType !== 'event' ) return <></>;

	const blockProps = useBlockProps();

	apiFetch( { path: `/events/v2/bookinginfo/${ postId }` } ).then( ( data ) => {
		console.log( data );
	} );

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
