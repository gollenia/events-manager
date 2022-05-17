// yet a sipmple wrapper for the event data

const getEventData = () => {
	if(window.eventData === undefined) return false;
	return window.booking_data
}

const eventData = window.booking_data || false;
export default eventData;