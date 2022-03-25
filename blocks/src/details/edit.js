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
			audienceIcon,
			audienceDescription,
			speakerIcon,
			priceOverwrite
		}
		
	} = props;

	
	const [event, setEvent] = useState(false);

	const currentEventId = useSelect( ( select ) => {
		
	return select("core/editor").getCurrentPostId()
		
	}, [] );

	useEffect(() => {
		fetch("/wp-json/events/v2/events?post_id=" + currentEventId).then(response => response.json()).then(data => {
			console.log(data);
			setEvent(data[0])
		})
	}, [])


	const blockProps = useBlockProps({
		className: [
			"ctx:event-details",
		].filter(Boolean).join(" ")
	});

	const startFormatted = () => {
		if(event?.start) {
			return formatDateRange(event.start, event.end)
		}
		
	}


	console.log(event)

	return (
		<div {...blockProps}>
			<Inspector
				{ ...props }
			/>

			<div className='ctx:event-details__wrapper'>
					{ showAudience && <div className='ctx:event-details__item'><i className="material-icons">{audienceIcon}</i><div><h5>{audienceDescription ?? __('Audience', 'events')}</h5>{event?.audience ?? __('no data')}</div></div>}
					{ showLocation && <div className='ctx:event-details__item'><i className="material-icons">place</i><div><h5>{__('Location', 'events')}</h5>{event?.location?.address}</div></div>}
					{ showDate && <div className='ctx:event-details__item'><i className="material-icons">today</i><div><h5>{__('Date', 'events')}</h5>{startFormatted()}</div></div>}
					{ showTime && <div className='ctx:event-details__item'><i className="material-icons">schedule</i><div><h5>{__('Time', 'events')}</h5>{event?.start}</div></div>}
					{ showSpeaker && 
						<div className='ctx:event-details__item'>
							{ speakerIcon == '' && 
								<img className="ctx:event-details__image" src={event?.speaker?.image?.sizes?.thumbnail?.url}/>
							} { !speakerIcon == '' && 
								<i className="material-icons">{speakerIcon}</i>
							}
							
						<div><h5>{__('Speaker', 'events')}</h5>{event?.speaker?.name}</div></div>}
					{ showPrice && 
						<div className='ctx:event-details__item'>
							<i className="material-icons">euro</i>
							<div><h5>{__('Price', 'events')}</h5>{priceOverwrite ?? event?.price}</div>
						</div>}
			</div>
		</div>
	);

}


export default edit;