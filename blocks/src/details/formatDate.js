/**
 * Formats two dates to a date range
 * @param {Date} start 
 * @param {Date} end 
 * @returns string formatted date
 */
function formatDateRange(start, end = false) {


	const locale = window.eventBlockLocale?.lang;
	
	start = new Date(start * 1000);
	end = end ? new Date(end * 1000) : start;

	const sameDay = start.getFullYear() === end.getFullYear() &&
		start.getMonth() === end.getMonth() &&
		start.getDate() === end.getDate();
	

	let dateFormat = {
		year: 'numeric',
		month: 'long',
		day: 'numeric',
		
	};

	if(sameDay) {
		dateFormat = {
			year: 'numeric',
			month: 'long',
			day: 'numeric',
			hour: 'numeric',
    		minute: 'numeric'
		};
	}

	const dateFormatObject  = new Intl.DateTimeFormat(locale, dateFormat);
	
	return dateFormatObject.formatRange(start, end);
	
	
}

/**
 * format date by goiven format object
 * @param {Date} date 
 * @param {object} format 
 * @returns string formated date
 */
function formatDate(date, format) {
	const locale = window.eventBlockLocale.lang;
	const dateFormatObject  = new Intl.DateTimeFormat(locale, format);
	return dateFormatObject.format(date);
}


function formatTime(time) {
	const locale = window.eventBlockLocale.lang;

	const timeFormat = {
		hour: 'numeric',
		minute: 'numeric'
	};

	const timeFormatObject  = new Intl.DateTimeFormat(locale, timeFormat);
	return timeFormatObject.format(time * 1000);
}

export { formatDateRange, formatDate, formatTime };