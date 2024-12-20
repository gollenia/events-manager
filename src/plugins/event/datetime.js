/**
 * Adds a metabox for the page color settings
 */

/**
 * WordPress dependencies
 */
import { CheckboxControl, TextControl } from '@wordpress/components';
import { dispatch, select, useDispatch } from '@wordpress/data';

import { PluginDocumentSettingPanel } from '@wordpress/editor';
import './datetime.scss';

import { useEntityProp } from '@wordpress/core-data';
import { __ } from '@wordpress/i18n';

const datetimeSelector = () => {
	const postType = select( 'core/editor' ).getCurrentPostType();
	const { lockPostSaving, unlockPostSaving } = useDispatch( 'core/editor' );

	if ( postType !== 'event' ) return <></>;

	const [ meta, setMeta ] = useEntityProp( 'postType', postType, 'meta' );

	if ( ! meta._event_start_date || ! meta._event_end_date ) {
		wp.data.dispatch( 'core/notices' ).createNotice(
			'warning',
			'Do not forget about a date your post!',
			{ 
				id: 'rudr-featured-img', 
				isDismissible: false 
			}
		);
        dispatch( 'core/editor' ).lockPostSaving( 'requiredValueLock' );
    } else {
		dispatch( 'core/notices' ).removeNotice( 'rudr-featured-img' );
        unlockPostSaving( 'requiredValueLock' );
    }

	const getNextHour = ( offset = 0, time = false ) => {
		let nextHourDate = time ? new Date( '01/01/1970 ' + time ) : new Date();
		nextHourDate.setHours( nextHourDate.getHours() + offset );
		nextHourDate.setMinutes( 0 );
		nextHourDate.setSeconds( 0 );
		nextHourDate.setMilliseconds( 0 );
		nextHourDate.setMinutes( nextHourDate.getMinutes() - nextHourDate.getTimezoneOffset() );
		return nextHourDate.toISOString().split( 'T' )[ 1 ].split( '.' )[ 0 ];
	};

	const compareTime = ( start, end ) => {
		const startDate = new Date( '01/01/1970 ' + start );
		const endDate = new Date( '01/01/1970 ' + end );
		return startDate > endDate;
	};

	const getNow = () => {
		let endDate = new Date();
		return endDate.toISOString().split( 'T' )[ 0 ];
	};

	if ( ! meta._event_start_date ) {
		setMeta( { _event_start_date: getNow(), _event_start_time: getNextHour() } );
	}

	if ( ! meta._event_end_date ) {
		setMeta( { _event_end_date: getNow(), _event_end_time: getNextHour( 1 ) } );
	}
	

	return (
		<PluginDocumentSettingPanel
			name="events-datetime-settings"
			title={ __( 'Time and Date', 'events' ) }
			className="events-datetime-settings"
		>
		
					
					
			<TextControl
				label={ __( 'Starts at', 'events' ) }
				value={ meta._event_start_date + 'T' + meta._event_start_time }
				onChange={ ( value ) => {
					const date = value.split( 'T' )[ 0 ];
					const time = value.split( 'T' )[ 1 ];
					setMeta( { _event_start_date: date, _event_start_time: time } );
					if( ! meta._event_end_date || compareTime( time, meta._event_end_time ) ) {
						setMeta( { _event_end_date: date, _event_end_time: getNextHour( 1, time ) } );
					}
				} }
				min={ getNow() + 'T' + getNextHour() }
				step={300}
				name="em-from-date"
				type="datetime-local"
				
			/>
					
				
		
			<TextControl
				label={ __( 'Ends at', 'events' ) }
				value={ meta._event_end_date + 'T' + meta._event_end_time }
				onChange={ ( value ) => {
					const date = value.split( 'T' )[ 0 ];
					const time = value.split( 'T' )[ 1 ];
					setMeta( { _event_end_date: date, _event_end_time: time } );
				} }
				step={300}
				min={ meta._event_start_date + 'T' + meta._event_start_time }
				name="em-to-date"
				type="datetime-local"
				
			/>
			
			<CheckboxControl
				checked={ meta._event_all_day == 1 }
				onChange={ ( value ) => {
					setMeta( { _event_all_day: value ? 1 : 0 } );
				} }
				label={ __( 'All day', 'events' ) }
			/>
		</PluginDocumentSettingPanel>
	);
};

export default datetimeSelector;
