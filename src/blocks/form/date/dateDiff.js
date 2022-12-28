import { __ } from '@wordpress/i18n';

function dateDiff( date1, date2 ) {
	const date1_time_stamp = new Date( date1 ).getTime();
	const date2_time_stamp = new Date( date2 ).getTime();

	const calc =
		date1_time_stamp > date2_time_stamp
			? new Date( date1_time_stamp - date2_time_stamp )
			: new Date( date2_time_stamp - date1_time_stamp );

	const calcFormat = `${ calc.getDate() }-${ calc.getMonth() + 1 }-${ calc.getFullYear() }`.split( '-' );

	//Subtract each member of our array from the default date
	const days_passed = Number( Math.abs( calcFormat[ 0 ] ) - 1 );
	const months_passed = Number( Math.abs( calcFormat[ 1 ] ) - 1 );
	const years_passed = Number( Math.abs( calcFormat[ 2 ] ) - 1970 );

	//Convert to days and sum together
	const total_days = years_passed * 365 + months_passed * 30.417 + days_passed;

	//display result with custom text
	const result =
		( years_passed == 1
			? `${ years_passed } ${ __( 'year', 'events' ) } `
			: years_passed > 1
			? `${ years_passed } ${ __( 'years', 'events' ) } `
			: '' ) +
		( months_passed == 1
			? `${ months_passed } ${ __( 'month', 'events' ) } `
			: months_passed > 1
			? `${ months_passed } ${ __( 'months', 'events' ) } `
			: '' ) +
		( days_passed == 1
			? `${ days_passed } ${ __( 'day', 'events' ) }`
			: days_passed > 1
			? `${ days_passed } ${ __( 'days', 'events' ) }`
			: '' );

	//return the result
	return {
		total_days: Math.round( total_days ),
		years: years_passed,
		months: months_passed,
		days: days_passed,
		result: result.trim(),
	};
}

export default dateDiff;
