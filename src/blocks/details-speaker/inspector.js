import { InspectorControls } from '@wordpress/block-editor';
import { CheckboxControl, ComboboxControl, Icon, PanelBody, SelectControl, TextControl } from '@wordpress/components';
import { store } from '@wordpress/core-data';
import { __ } from '@wordpress/i18n';
import icons from './icons';

import { useSelect } from '@wordpress/data';

const Inspector = ( props ) => {
	const { attributes, setAttributes } = props;

	const { showLink, customSpeakerId, url, linkTo, showPortrait } = attributes;

	const { speakers, hasResolved } = useSelect( ( select ) => {
		const query = {
			per_page: -1,
			_embed: true,
			meta: { _email: 'true' },
		};

		const selectorArgs = [ 'postType', 'event-speaker', query ];
		return {
			speakers: select( store ).getEntityRecords( ...selectorArgs ),
			hasResolved: select( store ).hasFinishedResolution( 'getEntityRecords', selectorArgs ),
		};
	}, [] );

	const speakerSelection = hasResolved
		? speakers.map( ( speaker ) => {
				return {
					label: speaker.title.raw,
					value: speaker.id,
					media:
						speaker &&
						speaker._embedded &&
						speaker._embedded[ 'wp:featuredmedia' ] &&
						speaker._embedded[ 'wp:featuredmedia' ][ 0 ].media_details.sizes?.thumbnail?.source_url,
				};
		  } )
		: [ { name: __( 'Loading...', 'events' ), key: 'loading' } ];

	return (
		<InspectorControls>
			<PanelBody title={ __( 'Appearance', 'events' ) } initialOpen={ true }>
				<CheckboxControl
					label={ __( 'Show Portrait if possible', 'events' ) }
					checked={ showPortrait }
					onChange={ ( value ) => setAttributes( { showPortrait: value } ) }
				/>
				<CheckboxControl
					label={ __( 'Show Link', 'events' ) }
					checked={ showLink }
					onChange={ ( value ) => setAttributes( { showLink: value } ) }
				/>
				<SelectControl
					label={ __( 'Link to', 'events' ) }
					value={ linkTo }
					onChange={ ( value ) => setAttributes( { linkTo: value } ) }
					options={ [
						{ label: __( 'E-Mail', 'events' ), value: 'mail' },
						{ label: __( 'Phone', 'events' ), value: 'call' },
						{ label: __( 'Website', 'events' ), value: 'public' },
						{ label: __( 'Custom URL', 'events' ), value: 'custom' },
					] }
					disabled={ ! showLink }
				/>

				<TextControl
					label={ __( 'Custom URL', 'events' ) }
					value={ url }
					onChange={ ( value ) => setAttributes( { url: value } ) }
					disabled={ linkTo !== 'custom' || ! showLink }
				/>
			</PanelBody>
			<PanelBody title={ __( 'Data', 'events' ) } initialOpen={ true }>
				<ComboboxControl
					label={ __( 'Select a speaker', 'events' ) }
					value={ customSpeakerId }
					onChange={ ( value ) => {
						setAttributes( { customSpeakerId: value } );
					} }
					options={ speakerSelection }
					__experimentalRenderItem={ ( { item } ) => {
						return (
							<div className="events-speaker-item">
								{ item.media ? (
									<img className="icon-round" width="24px" height="24px" src={ item.media } />
								) : (
									<Icon className="icon-round" icon={ icons.person } height={ 24 } width={ 24 } />
								) }
								{ item.label }
							</div>
						);
					} }
				/>
			</PanelBody>
		</InspectorControls>
	);
};

export default Inspector;
