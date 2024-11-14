import './admin.scss';
import './jquery-ui.min.scss';

jQuery( document ).ready( function ( $ ) {
	//Meta Box Options
	let open_close = $(
		'<a href="#" style="display:block; float:right; clear:right; margin:10px;">' + EM.open_text + '</a>'
	);
	$( '#em-options-title' ).before( open_close );
	open_close.on( 'click', function ( e ) {
		e.preventDefault();
		if ( $( this ).text() == EM.close_text ) {
			$( '.postbox' ).addClass( 'closed' );
			$( this ).text( EM.open_text );
		} else {
			$( '.postbox' ).removeClass( 'closed' );
			$( this ).text( EM.close_text );
		}
	} );

	//Navigation Tabs
	$( '.tabs-active .nav-tab-wrapper .nav-tab' ).on( 'click', function () {
		let el = $( this );
		let elid = el.attr( 'id' );
		$( '.em-menu-group' ).hide();
		$( '.' + elid ).show();
		$( '.postbox' ).addClass( 'closed' );
		open_close.text( EM.open_text );
	} );
	$( '.nav-tab-wrapper .nav-tab' ).on( 'click', function () {
		$( '.nav-tab-wrapper .nav-tab' ).removeClass( 'nav-tab-active' ).blur();
		$( this ).addClass( 'nav-tab-active' );
	} );
	let navUrl = document.location.toString();

	$( '.nav-tab-link' ).on( 'click', function () {
		$( $( this ).attr( 'rel' ) ).trigger( 'click' );
	} ); //links to mimick tabs
	$( 'input[type="submit"]' ).on( 'click', function () {
		let el = $( this ).parents( '.postbox' ).first();
		let docloc = document.location.toString().split( '#' );
		let newloc = docloc[ 0 ];
		if ( docloc.length > 1 ) {
			let nav_tab = docloc[ 1 ].split( '+' );
			let tab_path = nav_tab[ 0 ];
			if ( el.attr( 'id' ) ) {
				tab_path = tab_path + '+' + el.attr( 'id' ).replace( 'em-opt-', '' );
			}
			newloc = newloc + '#' + tab_path;
		}
		document.location = newloc;
	} );
} );

const bookingEditRoot = document.getElementById( 'booking-edit' );

import { BookingAdmin } from './admin/booking/booking-admin';
BookingAdmin();
