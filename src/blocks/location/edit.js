/**
 * Wordpress dependencies
 */
import { useBlockProps } from '@wordpress/block-editor';
import { Flex, FlexItem, TextControl } from '@wordpress/components';
import { useEntityProp } from '@wordpress/core-data';
import { useSelect } from '@wordpress/data';
import { useEffect } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import 'leaflet/dist/leaflet.css';
import { CircleMarker, MapContainer, TileLayer } from 'react-leaflet';
/**
 * Internal dependencies
 */

/**
 * @param {Props} props
 * @return {JSX.Element} Element
 */
const edit = ( props ) => {
	const { context } = props;

	const postType = useSelect( ( select ) => select( 'core/editor' ).getCurrentPostType(), [] );

	const [ meta, setMeta ] = useEntityProp( 'postType', postType, 'meta' );

	const blockProps = useBlockProps( {
		className: [ 'location-edit' ].filter( Boolean ).join( ' ' ),
	} );

	useEffect( () => {
		if ( ! meta._location_latitude || ! meta._location_longitude ) {
			getGeoPosition();
		}
	}, [] );
	const position = [ meta._location_latitude, meta._location_longitude ];
	const getGeoPosition = () => {
		if ( ! meta._location_address || ! meta._location_town ) return;

		const location = fetch(
			`https://nominatim.openstreetmap.org/search?format=json&addressdetails=1&q=${ meta._location_address } ${ meta._location_town } ${ meta._location_postcode } ${ meta._location_country }`
		)
			.then( ( response ) => response.json() )
			.then( ( data ) => {
				if ( data[ 0 ] ) {
					setMeta( {
						...meta,
						_location_latitude: data[ 0 ].lat,
						_location_longitude: data[ 0 ].lon,
					} );
				}
			} );
	};
	return (
		<div>
			<div className="location-edit__admin">
				<TextControl
					label={ __( 'Address', 'events' ) }
					value={ meta._location_address }
					onBlur={ ( value ) => {
						getGeoPosition();
					} }
					onChange={ ( value ) => {
						setMeta( {
							...meta,
							_location_address: value,
						} );
					} }
				/>
				<Flex>
					<FlexItem isBlock>
						<TextControl
							label={ __( 'ZIP Code', 'events' ) }
							value={ meta._location_postcode }
							onBlur={ ( value ) => {
								getGeoPosition();
							} }
							onChange={ ( value ) => {
								setMeta( {
									...meta,
									_location_postcode: value,
								} );
							} }
						/>
					</FlexItem>
					<FlexItem isBlock>
						<TextControl
							label={ __( 'City', 'events' ) }
							value={ meta._location_town }
							onBlur={ ( value ) => {
								getGeoPosition();
							} }
							onChange={ ( value ) => {
								setMeta( {
									...meta,
									_location_town: value,
								} );
							} }
						/>
					</FlexItem>
				</Flex>

				<TextControl
					label={ __( 'Country', 'events' ) }
					value={ meta._location_country }
					onBlur={ ( value ) => {
						getGeoPosition();
					} }
					onChange={ ( value ) => {
						setMeta( {
							...meta,
							_location_country: value,
						} );
					} }
				/>

				<TextControl
					label={ __( 'State', 'events' ) }
					value={ meta._location_state }
					onChange={ ( value ) => {
						setMeta( {
							...meta,
							_location_state: value,
						} );
					} }
				/>

				<TextControl
					label={ __( 'URL', 'events' ) }
					value={ meta._location_url }
					onChange={ ( value ) => {
						setMeta( {
							...meta,
							_location_url: value,
						} );
					} }
				/>
			</div>
			<div className="ctx-map-container">
				<MapContainer center={ position } zoom={ 16 } scrollWheelZoom={ false }>
					<TileLayer
						attribution='&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
						url="https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png"
					/>
					<CircleMarker center={ position } radius={ 10 } color="#007cba" />
				</MapContainer>
			</div>
		</div>
	);
};

export default edit;
