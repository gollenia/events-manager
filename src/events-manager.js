import './events_manager.scss';

jQuery( document ).ready( function ( $ ) {
	//Managing Bookings
	if ( $( '#dbem-bookings-table' ).length > 0 ) {
		//Pagination link clicks
		document.addEventListener( 'click', function ( event ) {
			if ( ! event.target.matches( '#dbem-bookings-table .tablenav-pages a' ) ) return;
			event.preventDefault();

			event.stopImmediatePropagation();
			let el = $( event.target );
			let form = el.parents( '#dbem-bookings-table form.bookings-filter' );
			//get page no from url, change page, submit form
			let match = el.attr( 'href' ).match( /#[0-9]+/ );
			if ( match != null && match.length > 0 ) {
				let pno = match[ 0 ].replace( '#', '' );
				form.find( 'input[name=pno]' ).val( pno );
			} else {
				form.find( 'input[name=pno]' ).val( 1 );
			}

			el.parents( '#dbem-bookings-table' ).find( '.table-wrap' ).first().append( '<div id="em-loading" />' );
			//ajax call
			$.post( EM.ajaxurl, form.serializeArray(), function ( data ) {
				let root = el.parents( '#dbem-bookings-table' ).first();
				root.replaceWith( data );
				//recreate overlays
				$( '#dbem-bookings-table-export input[name=scope]' ).val( root.find( 'select[name=scope]' ).val() );
				$( '#dbem-bookings-table-export input[name=status]' ).val( root.find( 'select[name=status]' ).val() );
				jQuery( document ).triggerHandler( 'em_bookings_filtered', [ data, root, el ] );
			} );
			return false;
		} );
		//Overlay Options
		let em_bookings_settings_dialog = {
			modal: true,
			autoOpen: false,
			minWidth: 500,
			height: 'auto',
			buttons: [
				{
					text: EM.bookings_settings_save,
					click: function ( e ) {
						e.preventDefault();
						e.bubbles = false;
						//we know we'll deal with cols, so wipe hidden value from main
						let match = $( '#em-bookings-table form.bookings-filter [name=cols]' ).val( '' );
						let booking_form_cols = $( 'form#em-bookings-table-settings-form input.em-bookings-col-item' );
						$.each( booking_form_cols, function ( i, item_match ) {
							//item_match = $(item_match);
							if ( item_match.value == 1 ) {
								if ( match.val() != '' ) {
									match.val( match.val() + ',' + item_match.name );
								} else {
									match.val( item_match.name );
								}
							}
						} );
						//submit main form
						$( '#em-bookings-table-settings' ).trigger( 'submitted' ); //hook into this with bind()
						$( '#em-bookings-table form.bookings-filter' ).trigger( 'submit' );
						$( this ).dialog( 'close' );
					},
				},
			],
		};

		let em_bookings_export_dialog = {
			modal: true,
			autoOpen: false,
			minWidth: 700,
			height: 'auto',
			buttons: [
				{
					text: EM.bookings_export_save,
					click: function ( e ) {
						$( this ).children( 'form' ).trigger( 'submit' );
						$( this ).dialog( 'close' );
					},
				},
			],
		};

		if ( $( '#em-bookings-table-settings' ).length > 0 ) {
			//Settings Overlay
			$( '#em-bookings-table-settings' ).dialog( em_bookings_settings_dialog );
			$( document ).on( 'click', '#em-bookings-table-settings-trigger', function ( e ) {
				e.preventDefault();
				$( '#em-bookings-table-settings' ).dialog( 'open' );
			} );
			//Export Overlay
			$( '#em-bookings-table-export' ).dialog( em_bookings_export_dialog );
			$( document ).on( 'click', '#em-bookings-table-export-trigger', function ( e ) {
				e.preventDefault();
				$( '#em-bookings-table-export' ).dialog( 'open' );
			} );
			let export_overlay_show_tickets = function () {
				if ( $( '#em-bookings-table-export-form input[name=show_tickets]' ).is( ':checked' ) ) {
					$( '#em-bookings-table-export-form .em-bookings-col-item-ticket' ).show();
					$(
						'#em-bookings-table-export-form #em-bookings-export-cols-active .em-bookings-col-item-ticket input'
					).val( 1 );
				} else {
					$( '#em-bookings-table-export-form .em-bookings-col-item-ticket' ).hide().find( 'input' ).val( 0 );
				}
			};
			//Sync export overlay with table search field changes
			$( '#em-bookings-table form select' ).each( function ( i, el ) {
				$( el ).on( 'change', function ( e ) {
					let select_el = $( this );
					let input_par = $( '#em-bookings-table-export-form input[name=' + select_el.attr( 'name' ) + ']' );
					let input_par_selected = select_el.find( 'option:selected' );
					input_par.val( input_par_selected.val() );
				} );
			} );

			export_overlay_show_tickets();
			$( '#em-bookings-table-export-form input[name=show_tickets]' ).on( 'click', export_overlay_show_tickets );
			//Sortables
			$( '.em-bookings-cols-sortable' )
				.sortable( {
					connectWith: '.em-bookings-cols-sortable',
					update: function ( event, ui ) {
						if (
							ui.item.parents( 'ul#em-bookings-cols-active, ul#em-bookings-export-cols-active' ).length >
							0
						) {
							ui.item
								.addClass( 'ui-state-highlight' )
								.removeClass( 'ui-state-default' )
								.children( 'input' )
								.val( 1 );
						} else {
							ui.item
								.addClass( 'ui-state-default' )
								.removeClass( 'ui-state-highlight' )
								.children( 'input' )
								.val( 0 );
						}
					},
				} )
				.disableSelection();
			load_ui_css = true;
		}

		//Widgets and filter submissions
		$( document ).on( 'click', '#post-query-submit', function ( e ) {
			let el = $( e.target ).closest( 'form' );
			e.preventDefault();

			//append loading spinner
			el.parents( '#em-bookings-table' ).find( '.table-wrap' ).first().append( '<div id="em-loading" />' );
			//ajax call
			$.post( EM.ajaxurl, el.serializeArray(), function ( data ) {
				let root = el.parents( '#em-bookings-table' ).first();
				root.replaceWith( data );
				//recreate overlays
				$( '#em-bookings-table-export input[name=scope]' ).val( root.find( 'select[name=scope]' ).val() );
				$( '#em-bookings-table-export input[name=status]' ).val( root.find( 'select[name=status]' ).val() );
				jQuery( document ).triggerHandler( 'em_bookings_filtered', [ data, root, el ] );
			} );
		} );
		//Approve/Reject Links
		$( document ).on(
			'click',
			'.em-bookings-approve,.em-bookings-reject,.em-bookings-unapprove,.em-bookings-delete',
			function () {
				let el = $( this );
				if ( el.hasClass( 'em-bookings-delete' ) && ! confirm( EM.booking_delete ) ) return false;
				let url = em_ajaxify( el.attr( 'href' ) );
				let td = el.parents( 'td' ).first();
				td.html( EM.txt_loading );
				td.load( url );
				return false;
			}
		);
	}

	$( document ).on( 'click', 'a.em-cancel-button', function ( e ) {
		e.preventDefault();
		let button = $( this );
		if ( button.text() != EM.bb_cancelled && button.text() != EM.bb_canceling ) {
			button.text( EM.bb_canceling );
			let button_data = button.attr( 'id' ).split( '_' );
			$.ajax( {
				url: EM.ajaxurl,
				dataType: 'jsonp',
				data: {
					booking_id: button_data[ 1 ],
					_wpnonce: button_data[ 2 ],
					action: 'booking_cancel',
				},
				success: function ( response, statusText, xhr, $form ) {
					if ( response.result ) {
						button.text( EM.bb_cancelled );
					} else {
						button.text( EM.bb_cancel_error );
					}
				},
				error: function () {
					button.text( EM.bb_cancel_error );
				},
			} );
		}
		return false;
	} );

	//previously in em-admin.php
	function updateIntervalDescriptor() {
		$( '.interval-desc' ).hide();
		let number = '-plural';
		if ( $( 'input#recurrence-interval' ).val() == 1 || $( 'input#recurrence-interval' ).val() == '' )
			number = '-singular';
		let descriptor = 'span#interval-' + $( 'select#recurrence-frequency' ).val() + number;
		$( descriptor ).show();
	}
	function updateIntervalSelectors() {
		$( 'p.alternate-selector' ).hide();
		$( 'p#' + $( 'select#recurrence-frequency' ).val() + '-selector' ).show();
	}
	function updateShowHideRecurrence() {
		if ( $( 'input#event-recurrence' ).is( ':checked' ) ) {
			$( '#event_recurrence_pattern' ).fadeIn();
			$( '#event-date-explanation' ).hide();
			$( '#recurrence-dates-explanation' ).show();
			$( 'h3#recurrence-dates-title' ).show();
			$( 'h3#event-date-title' ).hide();
		} else {
			$( '#event_recurrence_pattern' ).hide();
			$( '#recurrence-dates-explanation' ).hide();
			$( '#event-date-explanation' ).show();
			$( 'h3#recurrence-dates-title' ).hide();
			$( 'h3#event-date-title' ).show();
		}
	}
	$( '#recurrence-dates-explanation' ).hide();
	$( '#date-to-submit' ).hide();
	$( '#end-date-to-submit' ).hide();

	$( '#localised-date' ).show();
	$( '#localised-end-date' ).show();

	$( '#em-wrapper input.select-all' ).on( 'change', function () {
		if ( $( this ).is( ':checked' ) ) {
			$( 'input.row-selector' ).prop( 'checked', true );
			$( 'input.select-all' ).prop( 'checked', true );
		} else {
			$( 'input.row-selector' ).prop( 'checked', false );
			$( 'input.select-all' ).prop( 'checked', false );
		}
	} );

	updateIntervalDescriptor();
	updateIntervalSelectors();
	updateShowHideRecurrence();
	$( 'input#event-recurrence' ).on( 'change', updateShowHideRecurrence );

	// recurrency elements
	$( 'input#recurrence-interval' ).on( 'keyup', updateIntervalDescriptor );
	$( 'select#recurrence-frequency' ).on( 'change', updateIntervalDescriptor );
	$( 'select#recurrence-frequency' ).on( 'change', updateIntervalSelectors );

	/* Location Type Selection */
	$( '.em-location-types .em-location-types-select' )
		.on( 'change', function () {
			let el = $( this );
			if ( el.val() == 0 ) {
				$( '.em-location-type' ).hide();
			} else {
				let location_type = el.find( 'option:selected' ).data( 'display-class' );
				$( '.em-location-type' ).hide();
				$( '.em-location-type.' + location_type ).show();
				if ( location_type != 'em-location-type-place' ) {
					jQuery( '#em-location-reset a' ).trigger( 'click' );
				}
			}
			if ( el.data( 'active' ) !== '' && el.val() !== el.data( 'active' ) ) {
				$( '.em-location-type-delete-active-alert' ).hide();
				$( '.em-location-type-delete-active-alert' ).show();
			} else {
				$( '.em-location-type-delete-active-alert' ).hide();
			}
		} )
		.trigger( 'change' );

	//Finally, add autocomplete here
	//Autocomplete #TODO: DELETE
	/*
	if ( jQuery( 'div.em-location-data input#location-name' ).length > 0 ) {
		jQuery( 'div.em-location-data input#location-name' )
			.autocomplete( {
				source: EM.locationajaxurl,
				minLength: 2,
				focus: function ( event, ui ) {
					jQuery( 'input#location-id' ).val( ui.item.value );
					return false;
				},
				select: function ( event, ui ) {
					jQuery( 'input#location-id' ).val( ui.item.id ).trigger( 'change' );
					jQuery( 'input#location-name' ).val( ui.item.value );
					jQuery( 'input#location-address' ).val( ui.item.address );
					jQuery( 'input#location-town' ).val( ui.item.town );
					jQuery( 'input#location-state' ).val( ui.item.state );
					jQuery( 'input#location-region' ).val( ui.item.region );
					jQuery( 'input#location-postcode' ).val( ui.item.postcode );
					jQuery( 'input#location-latitude' ).val( ui.item.latitude );
					jQuery( 'input#location-longitude' ).val( ui.item.longitude );
					if ( ui.item.country == '' ) {
						jQuery( 'select#location-country option:selected' ).removeAttr( 'selected' );
					} else {
						jQuery( 'select#location-country option[value="' + ui.item.country + '"]' ).attr(
							'selected',
							'selected'
						);
					}
					jQuery( 'div.em-location-data input' ).css( 'background-color', '#ccc' ).prop( 'readonly', true );
					jQuery( 'div.em-location-data select' )
						.css( 'background-color', '#ccc' )
						.css( 'color', '#666666' )
						.prop( 'disabled', true );
					jQuery( '#em-location-reset' ).show();
					jQuery( '#em-location-search-tip' ).hide();
					jQuery( document ).triggerHandler( 'em_locations_autocomplete_selected', [ event, ui ] );
					return false;
				},
			} )
			.data( 'ui-autocomplete' )._renderItem = function ( ul, item ) {
			let html_val =
				'<a>' +
				em_esc_attr( item.label ) +
				'<br><span style="font-size:11px"><em>' +
				em_esc_attr( item.address ) +
				', ' +
				em_esc_attr( item.town ) +
				'</em></span></a>';
			return jQuery( '<li></li>' ).data( 'item.autocomplete', item ).append( html_val ).appendTo( ul );
		};
		jQuery( '#em-location-reset a' ).on( 'click', function () {
			jQuery( 'div.em-location-data input' ).val( '' ).prop( 'readonly', false );
			jQuery( 'div.em-location-data select' ).prop( 'disabled', false );
			jQuery( 'div.em-location-data option:selected' ).removeAttr( 'selected' );
			jQuery( 'input#location-id' ).val( '' );
			jQuery( '#em-location-reset' ).hide();
			jQuery( '#em-location-search-tip' ).show();
			jQuery( '#em-map' ).hide();
			jQuery( '#em-map-404' ).show();
			if ( typeof marker !== 'undefined' ) {
				marker.setPosition( new google.maps.LatLng( 0, 0 ) );
				infoWindow.close();
				marker.setDraggable( true );
			}
			return false;
		} );
		if ( jQuery( 'input#location-id' ).val() != '0' && jQuery( 'input#location-id' ).val() != '' ) {
			jQuery( 'div.em-location-data input' ).prop( 'readonly', true );
			jQuery( 'div.em-location-data select' ).prop( 'disabled', true );
			jQuery( '#em-location-reset' ).show();
			jQuery( '#em-location-search-tip' ).hide();
		}
	}
	*/
	/* Local JS Timezone related placeholders */
	/* Moment JS Timzeone PH */
	if ( window.moment ) {
		let replace_specials = function ( day, string ) {
			// replace things not supported by moment
			string = string.replace( /##T/g, Intl.DateTimeFormat().resolvedOptions().timeZone );
			string = string.replace( /#T/g, 'GMT' + day.format( 'Z' ) );
			string = string.replace( /###t/g, day.utcOffset() * -60 );
			string = string.replace( /##t/g, day.isDST() );
			string = string.replace( /#t/g, day.daysInMonth() );
			return string;
		};
		$( '.em-date-momentjs' ).each( function () {
			// Start Date
			let el = $( this );
			let day_start = moment.unix( el.data( 'date-start' ) );
			let date_start_string = replace_specials( day_start, day_start.format( el.data( 'date-format' ) ) );
			if ( el.data( 'date-start' ) !== el.data( 'date-end' ) ) {
				// End Date
				let day_end = moment.unix( el.data( 'date-end' ) );
				let day_end_string = replace_specials( day_start, day_end.format( el.data( 'date-format' ) ) );
				// Output
				let date_string = date_start_string + el.data( 'date-separator' ) + day_end_string;
			} else {
				let date_string = date_start_string;
			}
			el.text( date_string );
		} );
		let get_date_string = function ( ts, format ) {
			let date = new Date( ts * 1000 );
			let minutes = date.getMinutes();
			if ( format == 24 ) {
				let hours = date.getHours();
				hours = hours < 10 ? '0' + hours : hours;
				minutes = minutes < 10 ? '0' + minutes : minutes;
				return hours + ':' + minutes;
			} else {
				let hours = date.getHours() % 12;
				let ampm = hours >= 12 ? 'PM' : 'AM';
				if ( hours === 0 ) hours = 12; // the hour '0' should be '12'
				minutes = minutes < 10 ? '0' + minutes : minutes;
				return hours + ':' + minutes + ' ' + ampm;
			}
		};
		$( '.em-time-localjs' ).each( function () {
			let el = $( this );
			let strTime = get_date_string( el.data( 'time' ), el.data( 'time-format' ) );
			if ( el.data( 'time-end' ) ) {
				let separator = el.data( 'time-separator' ) ? el.data( 'time-separator' ) : ' - ';
				strTime = strTime + separator + get_date_string( el.data( 'time-end' ), el.data( 'time-format' ) );
			}
			el.text( strTime );
		} );
	}
	/* Done! */
	jQuery( document ).triggerHandler( 'em_javascript_loaded' );
} );

function em_load_jquery_css() {
	if ( EM.ui_css && jQuery( 'link#jquery-ui-css' ).length == 0 ) {
		let script = document.createElement( 'link' );
		script.id = 'jquery-ui-css';
		script.rel = 'stylesheet';
		script.href = EM.ui_css;
		document.body.appendChild( script );
	}
}

/* Useful function for adding the em_ajax flag to a url, regardless of querystring format */
let em_ajaxify = function ( url ) {
	if ( url.search( 'em_ajax=0' ) != -1 ) {
		url = url.replace( 'em_ajax=0', 'em_ajax=1' );
	} else if ( url.search( /\?/ ) != -1 ) {
		url = url + '&em_ajax=1';
	} else {
		url = url + '?em_ajax=1';
	}
	return url;
};

function em_esc_attr( str ) {
	if ( typeof str !== 'string' ) return '';
	return str.replace( /</gi, '&lt;' ).replace( />/gi, '&gt;' );
}

function em_custom_mails() {
	let trigger_selector = document.querySelectorAll( '.em-nav-item' );
	if ( trigger_selector.length == 0 ) return;
	if ( trigger_selector.length == 1 ) {
		document.querySelector( '.em-nav-side' )?.classList.add( 'hidden' );
		document.querySelector( '.em-nav-content' )?.classList.remove( 'hidden' );
		return;
	}

	//add listener to triggers for groups and subgroups
	trigger_selector.forEach( ( element ) => {
		element.addEventListener( 'click', ( event ) => {
			trigger_selector.forEach( ( item ) => {
				item.classList.remove( 'active' );
			} );
			document.querySelectorAll( '.em-nav-content' )?.forEach( ( e ) => {
				e.classList.add( 'hidden' );
			} );
			event.target.classList.add( 'active' );
			document.querySelector( element.getAttribute( 'rel' ) )?.classList.remove( 'hidden' );
		} );
	} );

	let accordion_items = document.querySelectorAll( '.em-accordion-item' );

	accordion_items.forEach( ( element ) => {
		element.addEventListener( 'click', ( event ) => {
			accordion_items.forEach( ( item ) => {
				item.classList.remove( 'active' );
			} );
			document.querySelectorAll( '.em-accordion-content' )?.forEach( ( e ) => {
				e.classList.add( 'hidden' );
			} );
			event.target.classList.add( 'active' );
			document.getElementById( element.getAttribute( 'rel' ) )?.classList.remove( 'hidden' );
		} );
	} );

	let mail_subgroups = document.querySelectorAll( '.em-subgroup-email' );

	mail_subgroups.forEach( ( element ) => {
		let selector = element.querySelector( '.em-cet-status' );
		let content = element.querySelector( '.em-mail-vals' );
		let indicator = element.querySelector( '.em-mail-status-indicator' );

		selector.addEventListener( 'change', ( event ) => {
			indicator?.setAttribute( 'data-status', event.target.value );
			if ( event.target.value == 1 ) {
				content.classList.remove( 'hidden' );
				return;
			}
			content.classList.add( 'hidden' );
		} );
	} );

	document.getElementById( 'booking-modal-close' )?.addEventListener( 'click', ( event ) => {
		let element = document.getElementById( 'booking-modal' );
		element.style.display = 'none';
	} );

	document.getElementById( 'email-modal-close' )?.addEventListener( 'click', ( event ) => {
		let element = document.getElementById( 'email-modal' );
		element.style.display = 'none';
	} );
}

document.addEventListener( 'DOMContentLoaded', em_custom_mails, false );

document.addEventListener( 'DOMContentLoaded', () => {
	document.body.addEventListener( 'click', ( event ) => {
		if ( event.target.classList.contains( 'em-bookings-approve-offline' ) && ! confirm( EM.offline_confirm ) ) {
			event.stopPropagation();
			event.stopImmediatePropagation();
			event.preventDefault();
			return false;
		}
	} );

	document.body.addEventListener( 'click', ( event ) => {
		if ( event.target.classList.contains( 'em-transaction-delete' ) ) {
			const el = event.target;
			if ( ! confirm( EM.transaction_delete ) ) {
				return false;
			}
			const url = em_ajaxify( el.attr( 'href' ) );
			let td = el.parents( 'td' ).first();
			td.html( EM.txt_loading );
			td.load( url );
			return false;
		}
	} );
} );
