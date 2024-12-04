/**
 * Adds a metabox for the page color settings
 */

/**
 * WordPress dependencies
 */
import { CheckboxControl, PanelRow, TextControl } from '@wordpress/components';
import { select } from '@wordpress/data';
import { PluginDocumentSettingPanel } from '@wordpress/editor';
import './datetime.scss';

import { useEntityProp } from '@wordpress/core-data';
import { __ } from '@wordpress/i18n';

const datetimeSelector = () => {
	const postType = select( 'core/editor' ).getCurrentPostType();

	if ( postType !== 'event' ) return <></>;

	const [ meta, setMeta ] = useEntityProp( 'postType', postType, 'meta' );

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
		setMeta( { _event_start_date: getNow() } );
	}

	if ( ! meta._event_end_date ) {
		setMeta( { _event_end_date: getNow() } );
	}

	if ( ! meta._event_start_time ) {
		setMeta( { _event_start_time: getNextHour( 1 ) } );
	}

	if ( ! meta._event_end_time ) {
		setMeta( { _event_end_time: getNextHour( 2 ) } );
	}

	return (
		<PluginDocumentSettingPanel
			name="events-datetime-settings"
			title={ __( 'Time and Date', 'events' ) }
			className="events-datetime-settings"
		>
			<div className="em-date-row">
				<h3>{ __( 'Date', 'events' ) }</h3>
				<PanelRow>
					<label for="em-from-date">{ __( 'From', 'events' ) }</label>
					<div>
						<TextControl
							value={ meta._event_start_date }
							onChange={ ( value ) => {
								setMeta( { _event_start_date: value } );
								const startDate = new Date( value );
								const endDate = new Date( meta._event_end_date );
								console.log( startDate, endDate );
								if ( startDate > endDate ) {
									setMeta( { _event_end_date: value } );
								}
							} }
							name="em-from-date"
							type="date"
							className="em-date-input"
						/>
					</div>
				</PanelRow>
				<PanelRow>
					<label for="em-to-date">{ __( 'To', 'events' ) }</label>
					<div>
						<TextControl
							value={ meta._event_end_date }
							onChange={ ( value ) => {
								setMeta( { _event_end_date: value } );
							} }
							name="em-to-date"
							min={ meta._event_start_date }
							type="date"
							className="em-date-input"
						/>
					</div>
				</PanelRow>
			</div>
			<h3>{ __( 'Time', 'events' ) }</h3>
			<PanelRow className="em-time-row">
				<TextControl
					value={ meta._event_start_time ? meta._event_start_time : '00:00' }
					onChange={ ( value ) => {
						setMeta( { _event_start_time: value } );
						if ( compareTime( value, meta._event_end_time ) ) {
							setMeta( { _event_end_time: getNextHour( 1, value ) } );
						}
					} }
					label={ __( 'Starts at', 'events' ) }
					disabled={ meta._event_all_day }
					type="time"
				/>

				<TextControl
					value={ meta._event_end_time ? meta._event_end_time : '00:00' }
					onChange={ ( value ) => {
						setMeta( { _event_end_time: value } );
						if ( ! meta._event_end_time ) {
							setMeta( { _event_end_time: value } );
						}
					} }
					disabled={ meta._event_all_day }
					label={ __( 'Ends at', 'events' ) }
					type="time"
				/>
			</PanelRow>
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
