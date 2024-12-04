/**
 * Wordpress dependencies
 */
import apiFetch from '@wordpress/api-fetch';
import { RichText, useBlockProps } from '@wordpress/block-editor';
import { useState } from '@wordpress/element';
import { __, _n, sprintf } from '@wordpress/i18n';

/**
 * Internal dependencies
 */

import Inspector from './inspector.js';

/**
 * @param {Props} props
 * @return {JSX.Element} Element
 */
const edit = ( props ) => {
	const [ spaces, setSpaces ] = useState( 0 );

	const {
		attributes: {
			roundImage,
			format,
			description,
			showNumber,
			warningText,
			warningThreshold,
			okText,
			bookedUpText,
		},
		setAttributes,
		context: { postType },
	} = props;

	if ( postType !== 'event' ) return <></>;

	apiFetch( {
		path: `/events/v2/bookinginfo/${ props.context.postId }`,
	} ).then( ( data ) => {
		if ( ! data.data?.available_spaces ) return;
		setSpaces( data.data.available_spaces );
	} );

	const blockProps = useBlockProps( { className: 'event-details-item' } );

	return (
		<div { ...blockProps }>
			<Inspector { ...props } />

			<div className="event-details__item">
				<div className="event-details__icon">
					<i className="material-icons material-symbols-outlined">
						{ spaces === 0
							? 'sentiment_dissatisfied'
							: spaces > warningThreshold
							? 'groups'
							: 'report_problem' }
					</i>
				</div>
				<div>
					<RichText
						tagName="h5"
						className="event-details_title description-editable"
						placeholder={ __( 'Free Spaces', 'events' ) }
						value={ description }
						onChange={ ( value ) => {
							setAttributes( { description: value } );
						} }
					/>
					<span className="event-details_audience description-editable">
						{ showNumber && spaces > warningThreshold ? (
							spaces
						) : (
							<>
								{ spaces <= warningThreshold && spaces > 0 && (
									<span className="event-details_warning">
										{ warningText
											? sprintf( warningText, showNumber ? spaces : __( 'few', 'events' ) )
											: sprintf(
													_n(
														'Only %s space left',
														'Only %s spaces left',
														showNumber ? spaces : __( 'few', 'events' ),
														'events'
													),
													showNumber ? spaces : __( 'few', 'events' )
											  ) }
									</span>
								) }
								{ spaces === 0 && (
									<span className="event-details_warning">
										{ bookedUpText ? bookedUpText : __( 'Booked up', 'events' ) }
									</span>
								) }
								{ spaces > warningThreshold && (
									<span className="event-details_ok">
										{ okText ? okText : __( 'Enough free spaces left', 'events' ) }
									</span>
								) }
							</>
						) }
					</span>
				</div>
			</div>
		</div>
	);
};

export default edit;
