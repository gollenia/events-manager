/**
 * Wordpress dependencies
 */
import { ToggleControl, RichText, useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n'; 
import { useSelect } from '@wordpress/data';
import { useEffect, useState } from '@wordpress/element';
import { store as coreStore } from '@wordpress/core-data';
import { select } from "@wordpress/data";



/**
 * Internal dependencies
 */
import Inspector from './inspector.js';
import { formatDateRange } from './formatDate';

/**
 * @param {Props} props
 * @return {JSX.Element} Element
 */
const edit = (props) => {

	const {
		attributes: {
			showLocation,
			showAudience,
			showDate,
			showTime,
			showSpeaker,
			showPrice,
			title
		},
		setAttributes,
		
	} = props;

	
	const [event, setEvent] = useState(false);

	const currentEventId = useSelect( ( select ) => {
		
	return select("core/editor").getCurrentPostId()
		
	}, [] );

	useEffect(() => {
		fetch("/wp-json/events/v2/events?post_id=" + currentEventId).then(response => response.json()).then(data => 
			setEvent(data[0])
		)
	}, [])


	const blockProps = useBlockProps({
		className: [
			"ctx-event-details",
		].filter(Boolean).join(" ")
	});

	const startFormatted = () => {
		if(event?.start) {
			return formatDateRange(event.start, event.end)
		}
		
	}

	return (
		<div {...blockProps}>
			<Inspector
				{ ...props }
			/>

			<div >
					<RichText
						tagName="h3"
						label={__("Details", 'events')}
						value={ title }
						onChange={ (value) => setAttributes({ title: value }) }
						placeholder={__("Details", 'events')}
						
					/>
					{ showAudience && <div className='item'><h5>{__('Audience', 'events')}</h5>{event ? event.audience : __('no data')}</div>}
					{ showLocation && <div className='item'><h5>{__('Location', 'events')}</h5>{event?.location.address}</div>}
					{ showDate && <div className='item'><h5>{__('Date', 'events')}</h5>{startFormatted()}</div>}
					{ showTime && <div className='item'><h5>{__('Time', 'events')}</h5>{event.start}</div>}
					{ showSpeaker && <div className='item'><h5>{__('Speaker', 'events')}</h5>{event?.speaker?.name}</div>}
					{ showPrice && <div className='item'><h5>{__('Price', 'events')}</h5>{event?.price}</div>}
			</div>
		</div>
	);

}


export default edit;