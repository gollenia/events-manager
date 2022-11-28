/**
 * Wordpress dependencies
 */
 import { AlignmentToolbar, BlockControls, useBlockProps } from '@wordpress/block-editor';
import { store as coreStore } from '@wordpress/core-data';
import { select, useSelect } from '@wordpress/data';
import { __ } from '@wordpress/i18n';


 
 /**
  * Internal dependencies
  */
 import Inspector from './inspector.js';
 
 /**
  * @param {Props} props
  * @return {JSX.Element} Element
  */
 const edit = (props) => {
 
	 const {
		 attributes: {
			 columnsLarge,
			 showImages,
			 dropShadow,
			 style,
			 selectedCategory,
			 isRootElement,
			 textAlignment,
			 roundImages,
			 selectedTags,
		 },
		 setAttributes,
		 
	 } = props;
 
	const parentClientId = select( 'core/block-editor' ).getBlockHierarchyRootClientId( props.clientId );
		
	setAttributes({isRootElement: parentClientId == props.clientId})
 
	 const categoryList = useSelect( ( select ) => {
		 const { getEntityRecords } = select( coreStore );
		 const query = { hide_empty: true, per_page: -1 };
		 const list = getEntityRecords( 'taxonomy', 'event-categories', query );
		 let categoryOptionsArray = [{value: 0, label: ""}];
		 if (!list) {
			 return categoryOptionsArray;
		 }
		 
		 list.map( ( category ) => {
			 categoryOptionsArray.push( { value: category.id, label: category.name } );
		 })
		 return categoryOptionsArray
	 }, [] );
 
 
	 const tagList = useSelect( ( select ) => {
		 const { getEntityRecords } = select( coreStore );
		 const query = { hide_empty: true, per_page: -1 };
		 const list = getEntityRecords( 'taxonomy', 'event-tags', query );
		 
		 if (!list) {
			 return null
		 }
		 return list;	
		 
	 }, [] );
 
	 const locationList = useSelect( ( select ) => {
		 const { getEntityRecords } = select( coreStore );
		 const query = { per_page: -1 };
		 const list = getEntityRecords( 'postType', 'location', query );
		 
		 let locationOptionsArray = [{value: 0, label: ""}];
		 if (!list) {
			 return locationOptionsArray;
		 }
		 
		 list.map( ( location ) => {
			 locationOptionsArray.push( { value: location.id, label: location.title.raw } );
		 })
		 return locationOptionsArray	
		 
	 }, [] );

	 const currentEvent = useSelect( ( select ) => {
		const { getEntityRecords } = select( coreStore );
		let query = { per_page: -1 };
		if(selectedCategory !== 0) {
			query["event-categories"] = selectedCategory
		}
		if(selectedTags !== []) {
			query["event-tags"] = selectedTags
		}
		

		const events = getEntityRecords( 'postType', 'event', query );
		
		
		if (!events) {
			return false;
		}
		let mostRecent = events[0];
		events.forEach(event => {
			let currentEventDate = new Date(mostRecent.meta._event_start_date)
			let nextEventDate = new Date(event.meta._event_start_date)
			if(nextEventDate > currentEventDate) {
				mostRecent = event
			}
		});
		const img = getEntityRecords('postType', 'attachment', { include: [ mostRecent.featured_media ] })
		
		if(img) {
			mostRecent.image = img[0].media_details.sizes.large.source_url;
		}

		return mostRecent
	}, [selectedCategory, selectedTags] );

	
 
	 let tagNames = [];
	 let tagsFieldValue = [];
	 if ( tagList !== null ) {
		 tagNames = tagList.map( ( tag ) => tag.name );
 
		 tagsFieldValue = selectedTags.map( ( tagId ) => {
			 let wantedTag = tagList.find( ( tag ) => {
				 return tag.id === tagId;
			 } );
			 if ( wantedTag === undefined || ! wantedTag ) {
				 return false;
			 }
			 return wantedTag.name;
		 } );
	 }
 
	 const blockProps = useBlockProps({
		 className: [
			 "ctx-featured-event",
			 isRootElement ? "is-root" : false,
			 dropShadow ? "hover" : false,
			 "style-" + style,
			 "text-" + textAlignment,
			 roundImages ? "round-images" : false
		 ].filter(Boolean).join(" ")
	 });
 
	 return (
		 <>
			 <Inspector
				 { ...props }
				 tagList={tagList}
				 categoryList={categoryList}
				 tagsFieldValue={tagsFieldValue}
				 tagNames={tagNames}
				 locationList={locationList}
			 />
			 <BlockControls>
				 <AlignmentToolbar
					 value={ textAlignment }
					 onChange={ (event) => setAttributes({ textAlignment: event }) }
				 />
			 </BlockControls>
			 <div >
					 { currentEvent && 
					 	<div { ...blockProps } style={{backgroundImage: `url(${currentEvent.image})`}}>
							<div className='overlay'>
							<h1>{currentEvent.title.raw}</h1>
							<p>{currentEvent.excerpt.raw}</p>
							<p>{currentEvent.meta._event_start_date}</p>
							</div>
						 </div>
 					 }
					  { !currentEvent &&
						<h2>{__("No events found", "events-manager")}</h2>
					  }
			 </div>
		 </>
	 );
 
 }
 
 
 export default edit;