import { __ } from '@wordpress/i18n';

/**
 * Formats two dates to a date range
 * @param {Date} start
 * @param {Date} end
 * @returns string formatted date
 */
function formatDateRange( start, end = false ) {
	const locale = window.eventBlocksLocalization?.locale;
	if(start.toString() === 'Invalid Date' || end.toString() === 'Invalid Date') return '';
	
	if ( ! start ) return '';
	if ( start == end ) end = false;
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
			day: 'numeric'
		};
	}

	const dateFormatObject = new Intl.DateTimeFormat( locale, dateFormat );
	let result = '';
	try {
		result = dateFormatObject.formatRange( start, end );
	} catch ( e ) {
		return __('Invalid Date', 'event-blocks');
	}
	return result;
}

/**
 * format date by goiven format object
 * @param {Date} date
 * @param {object} format
 * @returns string formated date
 */
function formatDate( date, format = false ) {
	if ( ! format ) format = { year: 'numeric', month: 'long', day: 'numeric' };

	const dateObject = new Date( date );

	const locale = window.eventBlocksLocalization?.locale;
	const dateFormatObject = new Intl.DateTimeFormat( locale, format );
	return dateFormatObject.format( dateObject );
}

function formatTime( start, end = false ) {
	if ( ! start ) return;
	if ( start == end ) end = false;
	const locale = window.eventBlocksLocalization?.locale;

	const timeFormat = {
		hour: 'numeric',
		minute: 'numeric',
	};

	const startDate = new Date( start );

	const timeFormatObject = new Intl.DateTimeFormat( locale, timeFormat );

	let result = '';
	try {
		result = timeFormatObject.format( startDate );
	} catch ( e ) {
		return __('Invalid Time', 'event-blocks');
	}
	return timeFormatObject.format( startDate );
}

function formatTimeRange( start, end = false ) {
	if ( ! start ) return;
	if ( start == end ) end = false;
	const locale = window.eventBlocksLocalization?.locale;

	const timeFormat = {
		hour: 'numeric',
		minute: 'numeric',
	};

	const startDate = new Date( start );
	const endDate = new Date( end );

	const timeFormatObject = new Intl.DateTimeFormat( locale, timeFormat );

	let result = '';
	try {
		result = timeFormatObject.format( startDate );
		if ( end ) {
			result += ' - ' + timeFormatObject.format( endDate );
		}
	} catch ( e ) {
		return __('Invalid Time', 'event-blocks');
	}
	return result;
}

export { formatDate, formatDateRange, formatTime, formatTimeRange };
