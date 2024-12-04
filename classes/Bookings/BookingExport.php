<?php

class BookingExport
{
	
	public static function init() 
	{
		$instance = new self();
		add_action('wp_ajax_em_export_bookings_xls', array($instance, 'export_bookings_xls') );
	}
	
	public function export_bookings_xls($request)
	{
		
			if( !empty($_REQUEST['event_id']) ){
				$EM_Event = EM_Event::find( absint($_REQUEST['event_id']) );
			}
			//sort out cols
			if( !empty($_REQUEST['cols']) && is_array($_REQUEST['cols']) ){
				$cols = array();
				foreach($_REQUEST['cols'] as $col => $active){
					if( $active ){ $cols[] = $col; }
				}
				$_REQUEST['cols'] = $cols;
			}
			$_REQUEST['limit'] = 0;
			
			//generate bookings export according to search request
			$show_tickets = !empty($_REQUEST['show_tickets']);
			$EM_Bookings_Table = new EM_Bookings_Table($show_tickets);
		
			
			
			$EM_Bookings_Table->limit = 350; //if you're having server memory issues, try messing with this number
			$EM_Bookings = $EM_Bookings_Table->get_bookings();
			var_dump($EM_Bookings);
			$excel_sheet = [$EM_Bookings_Table->get_headers(true)];
			
			while( !empty($EM_Bookings->bookings) ){
				foreach( $EM_Bookings->bookings as $EM_Booking ) { /* @var EM_Booking $EM_Booking */
					//Display all values
					if( $show_tickets ){
						foreach($EM_Booking->get_tickets_bookings()->tickets_bookings as $ticket_booking){ 
							$row = $EM_Bookings_Table->get_row_csv($ticket_booking);
							array_push($excel_sheet, $row);
						}
					}else{
						$row = $EM_Bookings_Table->get_row_csv($EM_Booking);
						array_push($excel_sheet, $row);
					}
				}
				//reiterate loop
				$EM_Bookings_Table->offset += $EM_Bookings_Table->limit;
				$EM_Bookings = $EM_Bookings_Table->get_bookings();
			}
			//$xlsx = Shuchkin\SimpleXLSXGen::fromArray( $excel_sheet );
			//$xlsx->downloadAs($EM_Event->event_slug . '-bookings.xlsx');
			
			exit();
		
	}
}

BookingExport::init();