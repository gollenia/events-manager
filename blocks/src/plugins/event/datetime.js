/**
 * Adds a metabox for the page color settings
 */

/**
 * WordPress dependencies
 */
 import { PluginDocumentSettingPanel } from '@wordpress/edit-post';
 import { SelectControl, TextControl, PanelRow, CheckboxControl } from '@wordpress/components';
 import { useSelect, select } from '@wordpress/data';
 import './datetime.scss'
 
 import { store as coreStore, useEntityProp } from '@wordpress/core-data';
 import { __ } from '@wordpress/i18n';
 
  
 const datetimeSelector = () => {
 
	 const postType = select( 'core/editor' ).getCurrentPostType()
 
	 if(postType !== 'event') return <></>;
 
	 const [ meta, setMeta ] = useEntityProp( 'postType', postType, 'meta' );
 
	
	 return (
		 <PluginDocumentSettingPanel
			 name="events-datetime-settings"
			 title={__('Time and Date', 'events')}
			 className="events-datetime-settings"
		 >
		<div className="em-date-row">
		<h3>{__("Date", "events")}</h3>
		<PanelRow>
		<label for="em-from-date">{__("From", "events")}</label>
		<TextControl
			value={ meta._event_start_date }
			onChange={(value) => {setMeta({_event_start_date: value})}}
			name="em-from-date"
			type="date"
		/>
		</PanelRow>
		<PanelRow>
		<label for="em-to-date">{__("To", "events")}</label>
		<TextControl
			value={ meta._event_end_date }
			onChange={(value) => {setMeta({_event_end_date: value})}}
			name="em-to-date"
			type="date"
		/>
		</PanelRow>
		</div>
		<h3>{__("Time", "events")}</h3>
		<PanelRow
			className="em-time-row"
		>
		
		<TextControl
			value={ meta._event_start_time }
			onChange={(value) => {setMeta({_event_start_time: value})}}
			label={__("Starts at", "events")}
			disabled={meta._event_all_day}
			type="time"
		/>
		
		<TextControl
			value={ meta._event_end_time }
			onChange={(value) => {setMeta({_event_end_time: value})}}
			disabled={meta._event_all_day}
			label={__("Ends at", "events")}
			type="time"
		/>
		</PanelRow>
		<CheckboxControl 
			checked={ meta._event_all_day }
			onChange={(value) => {setMeta({_event_all_day: value})}}
			label={__("All day", "events")}
		/>
 
		 </PluginDocumentSettingPanel>
	 );
 };
 
 export default datetimeSelector;
 