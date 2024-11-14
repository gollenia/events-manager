/**
 * Adds a metabox for the page color settings
 */

/**
 * WordPress dependencies
 */
import {
	CheckboxControl,
	__experimentalNumberControl as NumberControl,
	PanelRow,
	SelectControl,
	TextControl,
} from '@wordpress/components';
import { select } from '@wordpress/data';
import { PluginDocumentSettingPanel } from '@wordpress/editor';

import './datetime.scss';

import { useEntityProp } from '@wordpress/core-data';
import { __ } from '@wordpress/i18n';

const datetimeSelector = () => {
	const postType = select( 'core/editor' ).getCurrentPostType();

	if ( postType !== 'event-recurring' ) return <></>;

	const [ meta, setMeta ] = useEntityProp( 'postType', postType, 'meta' );

	const getNow = () => {
		let endDate = new Date();
		return endDate.toISOString().split( 'T' )[ 0 ];
	};

	const toggleDay = ( day ) => {
		let days = meta._recurrence_byday ? meta._recurrence_byday.split( ',' ) : [];

		if ( days.includes( day ) )
			days = days.filter( function ( value, index, arr ) {
				return value != day;
			} );
		else days.push( day );
		setMeta( { _recurrence_byday: days.join( ',' ) } );
	};

	const dayArray = [
		{ label: __( 'Sun', 'events-manager' ), value: '0' },
		{ label: __( 'Mon', 'events-manager' ), value: '1' },
		{ label: __( 'Tue', 'events-manager' ), value: '2' },
		{ label: __( 'Wed', 'events-manager' ), value: '3' },
		{ label: __( 'Thu', 'events-manager' ), value: '4' },
		{ label: __( 'Fri', 'events-manager' ), value: '5' },
		{ label: __( 'Sat', 'events-manager' ), value: '6' },
	];

	const longDayArray = [
		{ label: __( 'Sunday', 'events-manager' ), value: '0' },
		{ label: __( 'Monday', 'events-manager' ), value: '1' },
		{ label: __( 'Tuesday', 'events-manager' ), value: '2' },
		{ label: __( 'Wednesday', 'events-manager' ), value: '3' },
		{ label: __( 'Thursday', 'events-manager' ), value: '4' },
		{ label: __( 'Friday', 'events-manager' ), value: '5' },
		{ label: __( 'Saturday', 'events-manager' ), value: '6' },
	];

	const addOneDay = ( date ) => {
		let newDate = new Date( date );
		newDate.setDate( newDate.getDate() + 1 );
		console.log( newDate.toISOString().split( 'T' )[ 0 ] );
		return newDate.toISOString().split( 'T' )[ 0 ];
	};

	const minEndDate = meta._event_start_date ? addOneDay( meta._event_start_date ) : getNow();

	return (
		<>
			<PluginDocumentSettingPanel
				name="events-datetime-settings"
				title={ __( 'Time and Date', 'events-manager' ) }
				className="events-datetime-settings"
			>
				<div className="em-date-row">
					<h3>{ __( 'Date', 'events-manager' ) }</h3>
					<PanelRow>
						<label for="em-from-date">{ __( 'First time', 'events-manager' ) }</label>
						<div>
							<TextControl
								value={ meta._event_start_date ? meta._event_start_date : getNow() }
								onChange={ ( value ) => {
									setMeta( { _event_start_date: value } );
									if ( ! meta._event_end_date || meta._event_end_date < value ) {
										setMeta( { _event_end_date: addOneDay( value ) } );
									}
								} }
								name="em-from-date"
								type="date"
								className="em-date-input"
							/>
						</div>
					</PanelRow>
					<PanelRow>
						<label for="em-to-date">{ __( 'Last time', 'events-manager' ) }</label>
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
				<h3>{ __( 'Time', 'events-manager' ) }</h3>
				<PanelRow className="em-time-row">
					<TextControl
						className="em-time-input"
						value={ meta._event_start_time }
						onChange={ ( value ) => {
							setMeta( { _event_start_time: value } );
						} }
						label={ __( 'Start', 'events-manager' ) }
						disabled={ meta._event_all_day }
						type="time"
					/>

					<TextControl
						className="em-time-input"
						value={ meta._event_end_time }
						onChange={ ( value ) => {
							setMeta( { _event_end_time: value } );
						} }
						min={ minEndDate }
						disabled={ meta._event_all_day }
						label={ __( 'End', 'events-manager' ) }
						type="time"
					/>
				</PanelRow>

				<CheckboxControl
					checked={ meta._event_all_day == 1 }
					onChange={ ( value ) => {
						setMeta( { _event_all_day: value ? 1 : 0 } );
					} }
					label={ __( 'All day', 'events-manager' ) }
				/>
			</PluginDocumentSettingPanel>
			<PluginDocumentSettingPanel
				name="events-recurrence-settings"
				title={ __( 'Recurrence', 'events-manager' ) }
				className="events-recurrence-settings"
			>
				<SelectControl
					label={ __( 'Recurring', 'events-manager' ) }
					options={ [
						{ label: __( 'None', 'events-manager' ), value: '' },
						{ label: __( 'Daily', 'events-manager' ), value: 'daily' },
						{ label: __( 'Weekly', 'events-manager' ), value: 'weekly' },
						{ label: __( 'Monthly', 'events-manager' ), value: 'monthly' },
						{ label: __( 'Yearly', 'events-manager' ), value: 'yearly' },
					] }
					value={ meta._recurrence_freq }
					onChange={ ( value ) => {
						setMeta( { _recurrence_freq: value } );
					} }
				/>

				{ meta._recurrence_freq == 'weekly' && (
					<div className="mt-4">
						{ longDayArray.map( ( day, index ) => (
							<CheckboxControl
								key={ index }
								checked={ meta._recurrence_byday.includes( day.value ) }
								onChange={ ( value ) => {
									toggleDay( day.value );
								} }
								label={ day.label }
							/>
						) ) }
					</div>
				) }
				<NumberControl
					className="mt-4"
					label={ __( 'Interval', 'events-manager' ) }
					value={ meta._recurrence_interval }
					min={ 1 }
					onChange={ ( value ) => {
						setMeta( { _recurrence_interval: value } );
					} }
				/>
				{ meta._recurrence_freq == 'monthly' && (
					<>
						<PanelRow className="mt-4">
							<SelectControl
								label={ __( 'Every', 'events-manager' ) }
								options={ [
									{ label: __( 'First', 'events-manager' ), value: '1' },
									{ label: __( 'Second', 'events-manager' ), value: '2' },
									{ label: __( 'Third', 'events-manager' ), value: '3' },
									{ label: __( 'Fourth', 'events-manager' ), value: '4' },
									{ label: __( 'Fifth', 'events-manager' ), value: '5' },
									{ label: __( 'Last', 'events-manager' ), value: '-1' },
								] }
								value={ meta._recurrence_byweekno }
								onChange={ ( value ) => {
									setMeta( { _recurrence_byweekno: value } );
								} }
							/>

							<SelectControl
								label={ __( 'Weekday', 'events-manager' ) }
								options={ longDayArray }
								value={ meta._recurrence_byday }
								onChange={ ( value ) => {
									setMeta( { _recurrence_byday: value } );
								} }
							/>
						</PanelRow>
					</>
				) }
			</PluginDocumentSettingPanel>
		</>
	);
};

export default datetimeSelector;
