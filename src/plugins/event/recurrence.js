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
import { PluginDocumentSettingPanel } from '@wordpress/edit-post';

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
		console.log( days );
		if ( days.includes( day ) )
			days = days.filter( function ( value, index, arr ) {
				return value != day;
			} );
		else days.push( day );
		console.log( days );
		setMeta( { _recurrence_byday: days.join( ',' ) } );
	};

	const dayArray = [
		{ label: __( 'Sun', 'events' ), value: '0' },
		{ label: __( 'Mon', 'events' ), value: '1' },
		{ label: __( 'Tue', 'events' ), value: '2' },
		{ label: __( 'Wed', 'events' ), value: '3' },
		{ label: __( 'Thu', 'events' ), value: '4' },
		{ label: __( 'Fri', 'events' ), value: '5' },
		{ label: __( 'Sat', 'events' ), value: '6' },
	];

	const longDayArray = [
		{ label: __( 'Sunday', 'events' ), value: '0' },
		{ label: __( 'Monday', 'events' ), value: '1' },
		{ label: __( 'Tuesday', 'events' ), value: '2' },
		{ label: __( 'Wednesday', 'events' ), value: '3' },
		{ label: __( 'Thursday', 'events' ), value: '4' },
		{ label: __( 'Friday', 'events' ), value: '5' },
		{ label: __( 'Saturday', 'events' ), value: '6' },
	];

	console.log( 'meta', meta );

	return (
		<>
			<PluginDocumentSettingPanel
				name="events-datetime-settings"
				title={ __( 'Time and Date', 'events' ) }
				className="events-datetime-settings"
			>
				<div className="em-date-row">
					<h3>{ __( 'Date', 'events' ) }</h3>
					<PanelRow>
						<label for="em-from-date">{ __( 'First time', 'events' ) }</label>
						<div>
							<TextControl
								value={ meta._event_start_date ? meta._event_start_date : getNow() }
								onChange={ ( value ) => {
									setMeta( { _event_start_date: value } );
									if ( ! meta._event_end_date ) {
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
						<label for="em-to-date">{ __( 'Last time', 'events' ) }</label>
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
						className="em-time-input"
						value={ meta._event_start_time }
						onChange={ ( value ) => {
							setMeta( { _event_start_time: value } );
						} }
						label={ __( 'Start', 'events' ) }
						disabled={ meta._event_all_day }
						type="time"
					/>

					<TextControl
						className="em-time-input"
						value={ meta._event_end_time }
						onChange={ ( value ) => {
							setMeta( { _event_end_time: value } );
						} }
						disabled={ meta._event_all_day }
						label={ __( 'End', 'events' ) }
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
			<PluginDocumentSettingPanel
				name="events-recurrence-settings"
				title={ __( 'Recurrence', 'events' ) }
				className="events-recurrence-settings"
			>
				<SelectControl
					label={ __( 'Recurring', 'events' ) }
					options={ [
						{ label: __( 'None', 'events' ), value: '' },
						{ label: __( 'Daily', 'events' ), value: 'daily' },
						{ label: __( 'Weekly', 'events' ), value: 'weekly' },
						{ label: __( 'Monthly', 'events' ), value: 'monthly' },
						{ label: __( 'Yearly', 'events' ), value: 'yearly' },
					] }
					value={ meta._recurrence_freq }
					onChange={ ( value ) => {
						setMeta( { _recurrence_freq: value } );
					} }
				/>

				{ meta._recurrence_freq == 'weekly' && (
					<PanelRow className="mt-4">
						{ dayArray.map( ( day, index ) => (
							<CheckboxControl
								key={ index }
								checked={ meta._recurrence_byday.includes( day.value ) }
								onChange={ ( value ) => {
									toggleDay( day.value );
								} }
								label={ day.label }
							/>
						) ) }
					</PanelRow>
				) }
				<NumberControl
					className="mt-4"
					label={ __( 'Interval', 'events' ) }
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
								label={ __( 'Every', 'events' ) }
								options={ [
									{ label: __( 'First', 'events' ), value: '1' },
									{ label: __( 'Second', 'events' ), value: '2' },
									{ label: __( 'Third', 'events' ), value: '3' },
									{ label: __( 'Fourth', 'events' ), value: '4' },
									{ label: __( 'Fifth', 'events' ), value: '5' },
									{ label: __( 'Last', 'events' ), value: '-1' },
								] }
								value={ meta._recurrence_byweekno }
								onChange={ ( value ) => {
									setMeta( { _recurrence_byweekno: value } );
								} }
							/>

							<SelectControl
								label={ __( 'Weekday', 'events' ) }
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
