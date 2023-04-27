// yet a sipmple wrapper for the event data
declare global {
	interface Window {
		booking_data: any;
	}
}

const eventData = window.booking_data || false;
export default eventData;
