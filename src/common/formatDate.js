/**
 * Formats two dates to a date range
 * @param {Date} start
 * @param {Date} end
 * @returns string formatted date
 */
function formatDateRange( start, end = false ) {
	const locale = window.eventBlocksLocalization?.locale;

	if ( ! start ) return '';
	start = new Date( start );
	end = end ? new Date( end ) : start;

	const sameDay =
		start.getFullYear() === end.getFullYear() &&
		start.getMonth() === end.getMonth() &&
		start.getDate() === end.getDate();

	let dateFormat = {
		year: 'numeric',
		month: 'long',
		day: 'numeric',
	};

	if ( sameDay ) {
		dateFormat = {
			year: 'numeric',
			month: 'long',
			day: 'numeric',
			hour: 'numeric',
			minute: 'numeric',
		};
	}

	const dateFormatObject = new Intl.DateTimeFormat( locale, dateFormat );

	return dateFormatObject.formatRange( start, end );
}

/**
 * format date by goiven format object
 * @param {Date} date
 * @param {object} format
 * @returns string formated date
 */
function formatDate( date, format = false ) {
	if ( ! format ) format = { year: 'numeric', month: 'long', day: 'numeric' };
	console.log( date );
	const dateObject = new Date( date );
	console.log( dateObject );
	const locale = window.eventBlocksLocalization?.locale;
	const dateFormatObject = new Intl.DateTimeFormat( locale, format );
	return dateFormatObject.format( dateObject );
}

function formatTime( start, end = false ) {
	if ( ! start ) return;
	const locale = window.eventBlocksLocalization?.locale;

	const timeFormat = {
		hour: 'numeric',
		minute: 'numeric',
	};

	const startDate = new Date( start );

	const timeFormatObject = new Intl.DateTimeFormat( locale, timeFormat );
	console.log( timeFormatObject.format( startDate ) );
	return timeFormatObject.format( startDate );
}

export { formatDate, formatDateRange, formatTime };
