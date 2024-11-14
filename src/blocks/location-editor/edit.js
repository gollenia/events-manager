/**
 * Wordpress dependencies
 */
import { useBlockProps } from '@wordpress/block-editor';
import { Flex, FlexItem, SelectControl, TextControl } from '@wordpress/components';
import { useEntityProp } from '@wordpress/core-data';
import { useSelect } from '@wordpress/data';
import { useEffect, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import 'leaflet/dist/leaflet.css';
import { CircleMarker, MapContainer, TileLayer, useMap } from 'react-leaflet';
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
	if ( postType !== 'location' ) return <></>;
	const [ meta, setMeta ] = useEntityProp( 'postType', postType, 'meta' );

	const blockProps = useBlockProps( {
		className: [ 'location-edit' ].filter( Boolean ).join( ' ' ),
	} );

	const [ countries, setCountries ] = useState( [] );

	useEffect( () => {
		if ( ! meta._location_country ) {
			setMeta( {
				...meta,
				_location_country: window.EM.country,
			} );
		}
	}, [] );

	const fetchCountries = async () => {
		const response = await fetch( 'https://countries.kids-team.com/countries/world/' + 'de' );
		const data = await response.json();
		const items = Object.entries( data ).map( ( [ key, value ] ) => {
			return {
				value: key,
				label: value,
			};
		} );
		items.unshift( {
			value: '',
			label: __( 'Select Country', 'events-manager' ),
		} );
		setCountries( items );
	};

	useEffect( () => {
		fetchCountries();
		if ( ! meta._location_country ) {
			{
				countries.map( ( country, index ) => {
					if ( ! country ) return <></>;
					return (
						<option key={ index } value={ country.value } selected={ placeholder == country.value }>
							{ country.label }
						</option>
					);
				} );
			}
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

	function ChangeView( { center, zoom } ) {
		const map = useMap();
		map.setView( center, zoom );
		return null;
	}

	return (
		<div>
			<div className="location-edit__admin">
				<TextControl
					label={ __( 'Address', 'events-manager' ) }
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
							label={ __( 'ZIP Code', 'events-manager' ) }
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
							label={ __( 'City', 'events-manager' ) }
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

				<SelectControl
					label={ __( 'Country', 'events-manager' ) }
					value={ meta._location_country }
					options={ countries }
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
					label={ __( 'State', 'events-manager' ) }
					value={ meta._location_state }
					onChange={ ( value ) => {
						setMeta( {
							...meta,
							_location_state: value,
						} );
					} }
				/>

				<TextControl
					label={ __( 'URL', 'events-manager' ) }
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
					<ChangeView center={ position } zoom={ 16 } />
					<TileLayer
						attribution='&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
						url="https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png"
					/>
					<CircleMarker center={ position } radius={ 10 } color="#992244" />
				</MapContainer>
			</div>
		</div>
	);
};

export default edit;
