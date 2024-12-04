<?php

namespace Contexis\Events\Tickets;
/**
 * Deals with the ticket info for an event
 * @author marcus
 *
 */
class Tickets extends \EM_Object implements \Iterator, \Countable {
	
	/**
	 * Array of Ticket objects for a specific event
	 * @var array
	 */
	var $tickets = array();

	var $index = 0;
	/**
	 * @var int
	 */
	var $event_id;
	/**
	 * @var EM_Booking
	 */
	var $booking;
	var $spaces;
	
	
	/**
	 * Creates an Tickets instance
	 * @param mixed $event
	 */
	function __construct( $object = false ){
		global $wpdb;
		if( is_numeric($object) || (is_object($object) && in_array(get_class($object), array("EM_Event","EM_Booking"))) ){
			$this->event_id = (is_object($object)) ? $object->event_id:$object;
			$orderby_option = get_option('dbem_bookings_tickets_orderby');
			$order_by = get_option('dbem_bookings_tickets_ordering') ? array('ticket_order ASC') : array();
			$ticket_orderby_options = apply_filters('em_tickets_orderby_options', array(
				'ticket_price DESC, ticket_name ASC'=>__('Ticket Price (Descending)','events'),
				'ticket_price ASC, ticket_name ASC'=>__('Ticket Price (Ascending)','events'),
				'ticket_name ASC, ticket_price DESC'=>__('Ticket Name (Ascending)','events'),
				'ticket_name DESC, ticket_price DESC'=>__('Ticket Name (Descending)','events')
			));
			if( array_key_exists($orderby_option, $ticket_orderby_options) ){
				$order_by[] = $orderby_option;
			}else{
				$order_by[] = 'ticket_price DESC, ticket_name ASC';
			}
		    if( is_object($object) && get_class($object) == 'EM_Booking' ){
				$sql = "SELECT * FROM ". EM_TICKETS_TABLE ." WHERE ticket_id IN (SELECT ticket_id FROM ".EM_TICKETS_BOOKINGS_TABLE." WHERE booking_id='{$object->booking_id}') ORDER BY ".implode(',', $order_by);
		    }else{
		        $sql = "SELECT * FROM ". EM_TICKETS_TABLE ." WHERE event_id ='{$this->event_id}' ORDER BY ".implode(',', $order_by);
		    }
			$tickets = $wpdb->get_results($sql, ARRAY_A);
			foreach ($tickets as $ticket){
				$ticket = new Ticket($ticket);
				$ticket->event_id = $this->event_id;
				$this->tickets[$ticket->ticket_id] = $ticket;
                
			}
		}elseif( is_array($object) ){ //expecting an array of Ticket objects or ticket db array
			if( is_object(current($object)) && get_class(current($object)) == 'Ticket' ){
			    foreach($object as $ticket){
					$this->tickets[$ticket->ticket_id] = $ticket;
			    }
			}else{
				foreach($object as $ticket){
					$ticket = new Ticket($ticket);
					$ticket->event_id = $this->event_id;
					$this->tickets[$ticket->ticket_id] = $ticket;				
				}
			}
		}
		do_action('em_tickets', $this, $object);
	}
	
	/**
	 * @return EM_Event
	 */
	function get_event(){
		global $EM_Event;
		if( is_object($EM_Event) && $EM_Event->event_id == $this->event_id ){
			return $EM_Event;
		}else{
			return new \EM_Event($this->event_id);
		}
	}

	/**
	 * does this ticket exist?
	 * @return bool 
	 */
	function has_ticket($ticket_id){
		foreach( $this->tickets as $ticket){
			if($ticket->ticket_id == $ticket_id){
				return apply_filters('em_tickets_has_ticket',true, $ticket, $this);
			}
		}
		return apply_filters('em_tickets_has_ticket',false, false,$this);
	}
	
	/**
	 * Get the first Ticket object in this instance. Returns false if no tickets available.
	 * @return Ticket
	 */
	function get_first(){
		if( count($this->tickets) > 0 ){
			foreach($this->tickets as $ticket){
				return $ticket;
			}
		}else{
			return false;
		}
	}
	
	/**
	 * Delete tickets in this object
	 * @return boolean
	 */
	function delete(){
		global $wpdb;
		//get all the ticket ids
		$result = false;
		$ticket_ids = array();
		if( !empty($this->tickets) ){
			//get ticket ids if tickets are already preloaded into the object
			foreach( $this->tickets as $ticket ){
				$ticket_ids[] = $ticket->ticket_id;
			}
			//check that tickets don't have bookings
			if(count($ticket_ids) > 0){
				$bookings = $wpdb->get_var("SELECT COUNT(*) FROM ". EM_TICKETS_BOOKINGS_TABLE." WHERE ticket_id IN (".implode(',',$ticket_ids).")");
				if( $bookings > 0 ){
					$result = false;
					$this->add_error(__('You cannot delete tickets if there are any bookings associated with them. Please delete these bookings first.','events'));
				}else{
					$result = $wpdb->query("DELETE FROM ".EM_TICKETS_TABLE." WHERE ticket_id IN (".implode(',',$ticket_ids).")");
				}
			}
		}elseif( !empty($this->event_id) ){
			//if tickets aren't preloaded into object and this belongs to an event, delete via the event ID without loading any tickets
			$event_id = absint($this->event_id);
			$bookings = $wpdb->get_var("SELECT COUNT(*) FROM ". EM_TICKETS_BOOKINGS_TABLE." WHERE ticket_id IN (SELECT ticket_id FROM ".EM_TICKETS_TABLE." WHERE event_id='$event_id')");
			$ticket_ids = $wpdb->get_col("SELECT ticket_id FROM ". EM_TICKETS_TABLE." WHERE event_id='$event_id'");
			if( $bookings > 0 ){
				$result = false;
				$this->add_error(__('You cannot delete tickets if there are any bookings associated with them. Please delete these bookings first.','events'));
			}else{
				$result = $wpdb->query("DELETE FROM ".EM_TICKETS_TABLE." WHERE event_id='$event_id'");
			}
		}
		return apply_filters('em_tickets_delete', ($result !== false), $ticket_ids, $this);
	}
	
	/**
	 * Retrieve multiple ticket info via POST
	 * @return boolean
	 */
	function get_post(){
		//Build Event Array
		do_action('em_tickets_get_post_pre', $this);
		$current_tickets = $this->tickets; //save previous tickets so things like ticket_meta doesn't get overwritten
		$this->tickets = array(); //clean current tickets out
		
		if( !empty($_POST['em_tickets']) && is_array($_POST['em_tickets']) ){
			//get all ticket data and create objects
			
			$order = 1;
			foreach($_POST['em_tickets'] as $row => $ticket_data){
			    if( $row > 0 ){
			    	if( !empty($ticket_data['ticket_id']) && !empty($current_tickets[$ticket_data['ticket_id']]) ){
			    		$ticket = $current_tickets[$ticket_data['ticket_id']];
			    	}else{
			    		$ticket = new Ticket();
			    	}
					$ticket_data['event_id'] = $this->event_id;
					$ticket->get_post($ticket_data);
					$ticket->ticket_order = $order;
					if( $ticket->ticket_id ){
						$this->tickets[$ticket->ticket_id] = $ticket;
					}else{
						$this->tickets[] = $ticket;
					}
				    $order++;
			    }
			}
		}else{
			//we create a blank standard ticket
			$ticket = new Ticket(array(
				'event_id' => $this->event_id,
				'ticket_name' => __('Standard','events')
			));
			$this->tickets[] = $ticket;
		}
		return apply_filters('em_tickets_get_post', count($this->errors) == 0, $this);
	}
	
	/**
	 * Go through the tickets in this object and validate them 
	 */
	function validate(){
		$this->errors = array();
		foreach($this->tickets as $ticket){
			if( !$ticket->validate() ){
				$this->add_error($ticket->get_errors());
			} 
		}
		return apply_filters('em_tickets_validate', count($this->errors) == 0, $this);
	}
	
	/**
	 * Save tickets into DB 
	 */
	function save(){
		$result = true;
		foreach( $this->tickets as $ticket ){
			/* @var $ticket Ticket */
			$ticket->event_id = $this->event_id; //pass on saved event_data
			if( !$ticket->save() ){
				$result = false;
				$this->add_error($ticket->get_errors());
			}
		}
		return apply_filters('em_tickets_save', $result, $this);
	}
	
	/**
	 * Goes through each ticket and populates it with the bookings made
	 */
	function get_ticket_bookings(){
		foreach( $this->tickets as $ticket ){
			$ticket->get_bookings();
		}
	}

	
	
	/**
	 * Get the total number of spaces this event has. This will show the lower value of event global spaces limit or total ticket spaces. Setting $force_refresh to true will recheck spaces, even if previously done so.
	 * @param boolean $force_refresh
	 * @return int
	 */
	function get_spaces( $force_refresh=false ){
		$spaces = 0;
		if($force_refresh || $this->spaces == 0){
			foreach( $this->tickets as $ticket ){
				/* @var $ticket Ticket */
				$spaces += $ticket->get_spaces();
			}
			$this->spaces = $spaces;
		}
		return apply_filters('em_booking_get_spaces',$this->spaces,$this);
	}
	
	/**
	 * Returns the columns used in ticket public pricing tables/forms
	 * @param unknown_type $EM_Event
	 */
	function get_ticket_columns($EM_Event = false){
		if( !$EM_Event ) $EM_Event = $this->get_event();
		$columns = array( 'type' => __('Ticket Type','events'), 'price' => __('Price','events'), 'spaces' => __('Spaces','events'));
		if( $EM_Event->is_free() ) unset($columns['price']); //add event price
		return apply_filters('em_booking_form_tickets_cols', $columns, $EM_Event );
	}
	
	//Iterator Implementation
    public function rewind() : void {
        reset($this->tickets);
    }
	/**
	 * @return Ticket
	 */
    public function current() : Ticket {
        $var = current($this->tickets);
        return $var;
    }  

	#[\ReturnTypeWillChange]
    public function key(){
        $var = key($this->tickets);
        return $var;
    }
	/**
	 * @return Ticket
	 */
    public function next() : void {
        next($this->tickets);
    }  
    public function valid() : bool {
        $key = key($this->tickets);
        $var = ($key !== NULL && $key !== FALSE);
        return $var;
    }
    //Countable Implementation
    public function count() : int {
    	return count($this->tickets);
    }
}
