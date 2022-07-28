/**
 * Adds a metabox for the page color settings
 */

/**
 * WordPress dependencies
 */
 import { PluginDocumentSettingPanel } from '@wordpress/edit-post';
 import { SelectControl, TextControl, PanelRow, CheckboxControl } from '@wordpress/components';
 import { useSelect, select } from '@wordpress/data';
 
 
 import { store as coreStore, useEntityProp } from '@wordpress/core-data';
 import { __ } from '@wordpress/i18n';
 
  
 const locationSelector = () => {
 
	 const postType = select( 'core/editor' ).getCurrentPostType()
 
	 if(postType !== 'event') return <></>;
 
	 const [ meta, setMeta ] = useEntityProp( 'postType', postType, 'meta' );
 
	 const locationList = useSelect( ( select ) => {
		 const { getEntityRecords } = select( coreStore );
		 const query = { per_page: -1 };
		 const list = getEntityRecords( 'postType', 'location', query );
		 
		 let formsArray = [{value: 0, label: ""}];
		 if (!list) {
			 return formsArray
		 }
		 
		 list.map( ( form ) => {
			
			 formsArray.push( { value: form.location_id, label: form.title.raw } );
		 })
		 
		 return formsArray	
		 
	 }, [] );
 
	
	 return (
		 <PluginDocumentSettingPanel
			 name="events-location-settings"
			 title={__('Location', 'events')}
			 className="events-location-settings"
		 >

		 <SelectControl
			 label={__('Select a location', 'events')}
			 value={meta._location_id}
			 onChange={(value) => {setMeta({_location_id: value})}}
			 options={locationList}
		/>
		
 
		 </PluginDocumentSettingPanel>
	 );
 };
 
 export default locationSelector;
 