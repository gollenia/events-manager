<?php

use Contexis\Events\Intl\Date;
use Contexis\Events\Tickets\Tickets;
use Contexis\Events\Tickets\TicketsBookings;

/**
 * Contains all information and relevant functions surrounding a single booking made with Events Manager
 * @property int|false $booking_status
 * @property string $language
 * @property EM_Person $person
 */
class EM_Booking extends EM_Object{

	const PENDING = 0;
	const APPROVED = 1;
	const REJECTED = 2;
	const CANCELLED = 3;
	const AWAITING_ONLINE_PAYMENT = 4;
	const AWAITING_PAYMENT = 5;
	
	public $booking_id;
	public $event_id = 0;
	public $person_id;
	public ?float $booking_price = null;
	public float $booking_donation = 0;
	public int $booking_spaces;
	public string $booking_comment = "";
	public $booking_status = false;
	public array $booking_meta = []; 

	public array $fields = array(
		'booking_id' => array('name'=>'id','type'=>'%d'),
		'event_id' => array('name'=>'event_id','type'=>'%d'),
		'person_id' => array('name'=>'person_id','type'=>'%d'),
		'booking_price' => array('name'=>'price','type'=>'%f'),
		'booking_spaces' => array('name'=>'spaces','type'=>'%d'),
		'booking_comment' => array('name'=>'comment','type'=>'%s'),
		'booking_status' => array('name'=>'status','type'=>'%d'),
		'booking_donation' => array('name'=>'donation','type'=>'%f'),
		'booking_meta' => array('name'=>'meta','type'=>'%s')
	);

	var $notes;

	protected string $booking_date;
	protected EM_DateTime $date;
	protected $person;

	public array $required_fields = [ 'booking_id', 'event_id', 'person_id', 'booking_spaces' ];
	public string $feedback_message = "";
	public array $errors = [];
	
	public int $mails_sent = 0;
	
	/**
	 * Contains an array of custom fields for a booking. This is loaded from em_meta, where the booking_custom name contains arrays of data.
	 * @var array
	 */
	//var $custom = array();
	/**
	 * If saved in this instance, you can see what previous approval status was.
	 * @TODO make this either a boolean or an integer, not both.
	 * @var int
	 */
	var $previous_status = false;

	public array $status_array = [];

	public $tickets;
	public $event;
	public $tickets_bookings;
	public bool $manage_override;
	

	function __construct( $booking_data = false ) 
	{
		$this->set_status_array();
		global $wpdb;
		if($booking_data !== false) {
			$booking = array();
			if( is_array($booking_data) ){
				$booking = $booking_data;
			}elseif( is_numeric($booking_data) ){
				$sql = $wpdb->prepare("SELECT * FROM ". EM_BOOKINGS_TABLE ." WHERE booking_id =%d", $booking_data);
				$booking = $wpdb->get_row($sql, ARRAY_A);
			}
			$booking['booking_meta'] = (!empty($booking['booking_meta'])) ? maybe_unserialize($booking['booking_meta']):array();
			$this->from_array($booking);
			$this->previous_status = $this->booking_status;
			$this->booking_date = !empty($booking['booking_date']) ? $booking['booking_date']:false;
		}
		do_action('em_booking', $this, $booking_data);
	}

	private function set_status_array() : void 
	{
		$this->status_array = array(
			self::PENDING => __('Pending','events'),
			self::APPROVED => __('Approved','events'),
			self::REJECTED => __('Rejected','events'),
			self::CANCELLED => __('Cancelled','events'),
			self::AWAITING_ONLINE_PAYMENT => __('Awaiting Online Payment','events'),
			self::AWAITING_PAYMENT => __('Awaiting Payment','events')
		);
	}

	
	function __get( string $var ) : mixed
	{
	    if( $var == 'timestamp' ){
	    	if( $this->date() === false ) return 0;
	    	return $this->date()->getTimestampWithOffset();
	    }elseif( $var == 'language' ){
	    	if( !empty($this->booking_meta['lang']) ){
	    		return $this->booking_meta['lang'];
		    }
	    }elseif( $var == 'booking_status' ){
			return ($this->booking_status == 0 && !get_option('dbem_bookings_approval') ) ? 1:$this->booking_status;
	    }elseif( $var == 'person' ){
	    	return $this->get_person();
	    }
	    return null;
	}
	
	public function __set( string $property, mixed $value ) : void 
	{
		if( $property == 'timestamp' ){
			if( $this->date() !== false ) $this->date()->setTimestamp($value);
		}elseif( $property == 'language' ){
			$this->booking_meta['lang'] = $value;
		}else{
			$this->$property = $value;
		}
	}
	
	public function __isset( $property ) : bool 
	{
		if( $property == 'timestamp' ) return $this->date()->getTimestamp() > 0;
		if( $property == 'language' ) return !empty($this->booking_meta['lang']);
		return  isset($this->$property);
	}
	
	public function __sleep() : array 
	{
		$array = array('booking_id','event_id','person_id','booking_price','booking_spaces','booking_comment','booking_status','booking_donation','booking_meta','notes','booking_date','person','feedback_message','errors','mails_sent','custom','previous_status','status_array','manage_override','tickets_bookings');
		if( !empty($this->bookings) ) $array[] = 'bookings'; // EM Pro backwards compatibility
		return apply_filters('em_booking_sleep', $array, $this);
	}
	
	public function __wakeup() : void 
	{
		foreach($this->get_tickets_bookings()->tickets_bookings as $ticket_booking){
			$ticket_booking->booking = $this;
		}
	}

	public function get_attendees() : array 
	{
		$result = [];
		foreach($this->booking_meta['attendees'] as $ticket_id => $attendees){
			foreach($attendees as $attendee) {
				array_push($result, ["ticket_id" => $ticket_id, "fields" => $attendee]);
			}
		}

		return $result;
	}

	/*
	public function get_attendee_data(array $attendee_data) : array 
	{
		$form = EM_Attendees_Form::get_form($this->event_id)->form_fields;
		$attendee = [];
		foreach($attendee_data as $key => $value) {
			$attendee[$key] = [
				"value" => $value,
				"label" => $form[$key]['label']
			];
		}
		return $attendee;
	} */

	function get_booking_date() : string
	{
		$booking_date_int = strtotime($this->booking_date);
		$booking_time = date("H:i", $booking_date_int);
		return \Contexis\Events\Intl\Date::get_date($booking_date_int) . " " . __('at', 'events') . ' ' . $booking_time;
	}
	
	
	
	/**
	 * Saves the booking into the database, whether a new or existing booking
	 * @param bool $mail whether or not to email the user and contact people
	 * @return boolean
	 */
	function save(bool $mail = true) : bool 
	{
		global $wpdb;
		$table = EM_BOOKINGS_TABLE;
		do_action('em_booking_save_pre', $this);
		
		if (!$this->can_manage()) {
			$this->feedback_message = __('Forbidden!', 'events');
			$this->errors[] = sprintf(__('You cannot manage this %s.', 'events'), __('Booking', 'events'));
			return apply_filters('em_booking_save', false, $this, false);
		}

		// Update prices, spaces, person_id
		$this->get_spaces(true);
		$this->booking_price = $this->get_price();
		$this->person_id = $this->person_id ?: $this->get_person()->ID;
		
		// Prepare data for saving
		$data = $this->to_array();
		$data['booking_meta'] = serialize($data['booking_meta']);
		$data_types = $this->get_types($data);
		
		// Save or update booking
		if ($this->booking_id) {
			$result = $wpdb->update($table, $data, ['booking_id' => $this->booking_id], $data_types) !== false;
			$this->feedback_message = __('Changes saved', 'events');
			if(!$result) $this->feedback_message = __('There was a problem UPDATING the booking.', 'events');
		} else {
			$data['booking_date'] = $this->booking_date = gmdate('Y-m-d H:i:s');
			$data_types[] = '%s';
			$result = $wpdb->insert($table, $data, $data_types);
			$this->booking_id = $wpdb->insert_id;
			$this->feedback_message = __('Your booking has been recorded', 'events');
			if(!$result) $this->feedback_message = __('There was a problem SAVING the booking.', 'events');
		}

		if ($result) {
			$tickets_bookings_result = $this->get_tickets_bookings()->save();
			if (!$tickets_bookings_result) {
				if (!$this->booking_id) $this->delete();
				$this->errors[] = __('There was a problem saving the booking.', 'events');
				$this->errors[] = $this->get_tickets_bookings()->get_errors();
			}
		}

		// Apply filters and possibly send email
		//$this->compat_keys();
		$return = apply_filters('em_booking_save', count($this->errors) === 0, $this, (bool)$this->booking_id);
		
		if (count($this->errors) === 0 && $mail) {
			$this->email();
		}
		
		return $return;
	}


	/**
	 * Find a booking by ID, or return a new booking object if no ID is passed.
	 * 
	 * @param mixed $id
	 * @return EM_Booking
	 */
	public static function find(mixed $id = false) : EM_Booking 
	{
		global $EM_Booking;
	
		if (is_object($EM_Booking) && get_class($EM_Booking) == 'EM_Booking' && (
			(is_object($id) && $EM_Booking->booking_id == $id->booking_id) ||
			(is_numeric($id) && $EM_Booking->booking_id == $id) ||
			(is_array($id) && !empty($id['booking_id']) && $EM_Booking->booking_id == $id['booking_id'])
		)) {
			return $EM_Booking;
		}
	
		return (is_object($id) && get_class($id) == 'EM_Booking') ? $id : new EM_Booking($id);
	}
	
	/**
	 * Load a record into this object by passing an associative array of table criteria to search for.
	 * Returns boolean depending on whether a record is found or not. 
	 * @param $search
	 * @return boolean
	 */
	function get($search) : bool 
	{
		global $wpdb;
		$conds = array(); 
		foreach($search as $key => $value) {
			if( array_key_exists($key, $this->fields) ){
				$value = esc_sql($value);
				$conds[] = "`$key`='$value'";
			} 
		}
		$sql = "SELECT * FROM ". EM_BOOKINGS_TABLE ." WHERE " . implode(' AND ', $conds) ;
		$result = $wpdb->get_row($sql, ARRAY_A);
		if($result){
			$this->from_array($result);
			$this->person = new EM_Person($this->person_id);
			return true;
		}

		return false;
	}

	function get_request(\WP_REST_Request $request) : bool 
	{
		if(!$this->event_id) {
			$this->event_id = isset($request['event_id']) ? absint($request['event_id']) : 0;
		}

		$registration = $request['registration'];

		foreach([ 'first_name', 'last_name', 'user_email' ] as $key) {
			$this->booking_meta['registration'][$key] = $registration[$key];
			unset($registration[$key]);
		}

		$this->booking_meta['booking'] = $registration;
		$this->booking_meta['attendees'] = $request['attendees'];
		$this->booking_meta['gateway'] = $request['gateway'];
		if( !empty($request['coupon']) ) {
			$this->booking_meta['coupon_code'] = $request['coupon'];
		}

		if( isset($request['donation']) && floatval($request['donation'] > 0) ){
			file_put_contents('/var/www/vhosts/kids-team.internal/log/debug.log', print_r(floatval($request['donation']), TRUE));
			$this->booking_donation = floatval($request['donation']);
		}
		
		$this->tickets_bookings = new \Contexis\Events\Tickets\TicketsBookings($this->booking_id);

		foreach( $request['attendees'] as $ticket_id => $tickets){
			if(!is_array($tickets)) continue;
			$ticket_id = absint($ticket_id);
			if(!$this->get_event()->get_bookings()->ticket_exists($ticket_id)) continue;
			$args = array('ticket_id'=>$ticket_id, 'ticket_booking_spaces'=> count($tickets), 'booking_id'=>$this->booking_id);

			$ticket_booking = new \Contexis\Events\Tickets\TicketBooking($args);
			$ticket_booking->booking = $this;
			$this->tickets_bookings->add( $ticket_booking );
		}

		if( !empty($request['data_privacy_consent']) ){
			$this->booking_meta['consent'] = true;
		}

		$this->booking_spaces = count($request['attendees']);
		$this->retrieve_status($request);
		$this->get_person();
		return true;
	}

	function retrieve_status($request) 
	{
		if( $request->has_param('status') )	return $request->get_param('status');
		if( !$request->has_param('gateway') ) return self::PENDING;
		global $EM_Gateways;
		$EM_Gateways[$request['gateway']]->booking_add($this);
	}
	
	function validate( bool $override_availability = false ) : bool
	{
		//step 1, basic info
		$basic = ( 
			(empty($this->event_id) || is_numeric($this->event_id)) && 
			(empty($this->person_id) || is_numeric($this->person_id)) &&
			is_numeric($this->booking_spaces) && $this->booking_spaces > 0
		);
		//give some errors in step 1
		if( $this->booking_spaces == 0 ){
			$this->errors[] = __('You must request at least one space to book an event.','events');
		}
		//step 2, tickets bookings info
		if( $this->get_tickets_bookings()->count() > 0 ){
			$ticket_validation = array();
			foreach($this->get_tickets_bookings()->tickets_bookings as $ticket_booking){ 
				if ( !$ticket_booking->validate() ){
					$ticket_validation[] = false;
					$this->errors[] = array_merge($this->errors, $ticket_booking->get_errors());
				}
			}
			$result = $basic && !in_array(false,$ticket_validation);
		}else{
			$result = false;
		}
		if( !$override_availability ){
			// are bookings even available due to event and ticket cut-offs/restrictions? This is checked earlier in booking processes, but is relevant in checkout/cart situations where a previously-made booking is validated just before checkout
			if( $this->get_event()->rsvp_end()->getTimestamp() < time() ){
				$result = false;
				$this->errors[] = __( 'Bookings have closed (e.g. event has started).', 'events');
			}else{
				foreach( $this->get_tickets_bookings() as $ticket_booking ){
					if( !$ticket_booking->get_ticket()->is_available() ){
						$result = false;
						$message = __('The ticket %s is no longer available.', 'events');
						$this->errors = get_option('dbem_booking_feedback_ticket_unavailable', sprintf($message, "'".$ticket_booking->get_ticket()->name."'"));
					}
				}
			}
			//is there enough space overall?
			if( $this->get_event()->get_bookings()->get_available_spaces() < $this->get_spaces() ){
				$result = false;
				$this->errors[] = get_option('dbem_booking_feedback_full');
			}
		}
		//can we book this amount of spaces at once?
		if( $this->get_event()->event_rsvp_spaces > 0 && $this->get_spaces() > $this->get_event()->event_rsvp_spaces ){
			$result = false;
			$this->errors[] = __('You cannot book more spaces than are available.','events');
		}
		return apply_filters('em_booking_validate',$result,$this);
	}

	function get_payment_info() 
	{
		return EM_Gateways::get_gateway($this->booking_meta['gateway'])->get_payment_info($this);
	}
	
	/**
	 * Get the total number of spaces booked in THIS booking. Setting $force_refresh to true will recheck spaces, even if previously done so.
	 * @param boolean $force_refresh
	 * @return int
	 */
	function get_spaces( bool $force_refresh=false ) : int
	{
		if($this->booking_spaces == 0 || $force_refresh == true ){
			$this->booking_spaces = $this->get_tickets_bookings()->get_spaces($force_refresh);
		}
		return apply_filters('em_booking_get_spaces',$this->booking_spaces,$this);
	}
	
	/* Price Calculations */
	
	/**
	 * Gets the total price for this whole booking, including any discounts and any other additional items. In other words, what the person has to pay or has supposedly paid.
	 * This price shouldn't change once established, unless there's any alteration to the booking itself that'd affect the price, such as a change in ticket numbers, discount, etc.
	 * @param boolean $format
	 * @return double|string
	 */
	function get_price() : float 
	{
		//if( $this->booking_price !== null ) return $this->booking_price;
		$price = $this->get_price_base();
		$price -= $this->get_price_adjustments_amount('discounts');
		$price += $this->get_price_adjustments_amount('donation');
		$this->booking_price = $price;
		return round($this->booking_price,2);
	}
	
	/**
	 * Total of tickets without discounts or any other modification. No filter given here for that very reason!
	 * @param boolean $format
	 * @return double|string
	 */
	function get_price_base( )
	{
	    return $this->get_tickets_bookings()->get_price();
	}

	/**
	 * Returns an array of discounts to be applied to a booking. Here is an example of an array item that is expected:
	 * array('name' => 'Name of Discount', 'type'=>'% or #', 'amount'=> 0.00, 'desc' => 'Comments about discount', 'data' => 'any info for hooks to use' );
	 * About the array keys:
	 * type - # means a fixed amount of discount, % means a percentage off the base price
	 * amount - if type is a percentage, it is written as a number from 0-100, e.g. 10 = 10%
	 * data - any data to be stored that can be used by actions/filters
	 * @param string $type The type of adjustment you would like to retrieve. This would normally be 'discounts' or 'donation'.
	 * @return array
	 */
	function get_price_adjustments( string $type ){
		$adjustments = array();

		if( $type == 'donation') {
			$adjustments[] = array('name' => __('Donation', 'events'), 'type' => '#', 'amount' => $this->booking_donation, 'desc' => __('Donation', 'events'));
		}
		
		return apply_filters('em_booking_get_price_adjustments', $adjustments, $type, $this);
	}
	
	/**
	 * Returns a numerical amount to adjust the price by, in the context of a certain type.
	 * This will be a positive number whether or not this is to be added or subtracted from the price.
	 * @param string $type The type of adjustment to get, which would normally be 'discounts' or 'surcharges'
	 * @param float $price Price relative to be adjusted.
	 * @return float
	 */
	function get_price_adjustments_amount( string $type ){
		$adjustments = $this->get_price_adjustments_summary($type);
		
		$adjustment_amount = 0;
		foreach($adjustments as $adjustment){
			$adjustment_amount += $adjustment['amount_adjusted'];
		}
		return $adjustment_amount;
	}
	
	/**
	 * Provides an array summary of adjustments to make to the price, in the context of a certain type.
	 * @param string $type The type of adjustment to get, which would normally be 'discounts' or 'surcharges'
	 * @param float $price Price to calculate relative to adjustments. If not supplied or if $pre_or_post is 'both', price is automatically obtained from booking instance. 
	 * @return array
	 */
	function get_price_adjustments_summary( string $type ) : array{
		
		$adjustments = $this->get_price_adjustments($type);
		
		$price = $this->get_price_base();
		

		$adjustment_summary = [];

		foreach($adjustments as $adjustment){
			if(empty($adjustment['amount']) || empty($adjustment['type'])) continue;
			$description = !empty($adjustment['desc']) ? $adjustment['desc'] : '';
			$adjustment_summary_item = array('name' => $adjustment['name'], 'desc' => $description, 'adjustment'=>'0', 'amount_adjusted'=>0);
			$adjustment_summary_item['amount_adjusted'] = $adjustment['type'] == '%' ? round($price * ($adjustment['amount']/100),2) : round($adjustment['amount'],2);
			$adjustment_summary_item['adjustment'] = $adjustment['type'] == '%' ? number_format($adjustment['amount'],2).'%' : $this->format_price($adjustment['amount']);
			$adjustment_summary_item['amount'] = $this->format_price($adjustment_summary_item['amount_adjusted']);	
			$adjustment_summary[] = $adjustment_summary_item;
		}
		
		return $adjustment_summary;
	}

	/**
	 * When generating totals at the bottom of a booking, this creates a useful array for displaying the summary in a meaningful way. 
	 */
	function get_price_summary_array(){
	    $summary = array();
	    $summary['total_base'] = $this->get_price_base();
	    $summary['discounts'] = $this->get_price_adjustments_amount('discounts');
	    $summary['donation'] = $this->get_price_adjustments_amount('donation');
	    $summary['total'] =  $this->get_price();
	    return $summary;
	}
	
	/**
	 * Returns the amount paid for this booking. By default, a booking is considered either paid in full or not at all depending on whether the booking is confirmed or not.
	 * @param boolean $format If set to true a currency-formatted string value is returned
	 * @return string|float
	 */
	function get_total_paid( $format = false ){
		$status = ($this->booking_status == 0 && !get_option('dbem_bookings_approval') ) ? 1:$this->booking_status;
		$total = $status ? $this->get_price() : 0;
		$total = apply_filters('em_booking_get_total_paid', $total, $this);
		if( $format ){
			return $this->format_price($total);
		}
		return $total;
	}
	
	
	/* Get Objects linked to booking */
	
	/**
	 * Gets the event this booking belongs to and saves a reference in the event property
	 * @return EM_Event
	 */
	function get_event(){
		global $EM_Event;
		if( is_object($this->event) && get_class($this->event)=='EM_Event' && ($this->event->event_id == $this->event_id )) {
			return $this->event;
		}elseif( is_object($EM_Event) && $EM_Event->event_id == $this->event_id ){
			$this->event = $EM_Event;
		}else{
			$this->event = EM_Event::find($this->event_id, 'event_id');
		}
		return apply_filters('em_booking_get_event', $this->event, $this);
	}
	
	/**
	 * Gets the ticket object this booking belongs to, saves a reference in ticket property
	 * @return Tickets
	 */
	function get_tickets(){
		if( is_object($this->tickets) && get_class($this->tickets)=='Tickets' ){
			return apply_filters('em_booking_get_tickets', $this->tickets, $this);
		}else{
			$this->tickets = new \Contexis\Events\Tickets\Tickets($this);
		}
		return apply_filters('em_booking_get_tickets', $this->tickets, $this);
	}
	
	/**
	 * Gets the ticket object this booking belongs to, saves a reference in ticket property
	 * @return TicketsBookings TicketsBookings
	 */
	function get_tickets_bookings(){
		if( !is_object($this->tickets_bookings) ){
			$this->tickets_bookings = new \Contexis\Events\Tickets\TicketsBookings($this);
		} 
		return apply_filters('em_booking_get_tickets_bookings', $this->tickets_bookings, $this);
	}
	
	/**
	 * @return EM_Person
	 */
	function get_person()
	{
		
		global $EM_Person;
		
		if( is_object($this->person) && get_class($this->person)=='EM_Person' && ($this->person->ID == $this->person_id || empty($this->person_id) ) ) {
		
		} elseif( is_object($EM_Person) && ($EM_Person->ID === $this->person_id || $this->booking_id == '') ){
			$this->person = $EM_Person;
			$this->person_id = $this->person->ID;
		} elseif( is_numeric($this->person_id) ){
			$this->person = new EM_Person($this->person_id);
		} else{
			$this->person = new EM_Person(0);
			$this->person_id = $this->person->ID;
		}

		//override any registration data into the person objet
		if( !empty($this->booking_meta['registration']) ){
			foreach($this->booking_meta['registration'] as $key => $value){
				$this->person->$key = $value;
			}
		}
		
		$this->person->user_email = ( !empty($this->booking_meta['registration']['user_email']) ) ? $this->booking_meta['registration']['user_email']:$this->person->user_email;
		//if a full name is given, overwrite the first/last name values IF they are also not defined
		if( !empty($this->booking_meta['registration']['user_name']) ){
			if( !empty($this->booking_meta['registration']['first_name']) ){
				//first name is defined, so we remove it from full name in case we need the rest for surname
				$last_name = trim(str_replace($this->booking_meta['registration']['first_name'], '', $this->booking_meta['registration']['user_name']));
				//if last name isn't defined, provide the rest of the name minus the first name we just removed
				if( empty($this->booking_meta['registration']['last_name']) ){
					$this->booking_meta['registration']['last_name'] = $last_name;
				}
			}else{
				//no first name defined, check for last name and act accordingly
				if( !empty($this->booking_meta['registration']['last_name']) ){
					//we do opposite of above, remove last name from full name and use the rest as first name
					$first_name = trim(str_replace($this->booking_meta['registration']['last_name'], '', $this->booking_meta['registration']['user_name']));
					$this->booking_meta['registration']['first_name'] = $first_name;
				}else{
					//no defined first or last name, so we use the name and take first string for first name, second part for surname
					$name_string = explode(' ',$this->booking_meta['registration']['user_name']);
					$this->booking_meta['registration']['first_name'] = array_shift($name_string);
					$this->booking_meta['registration']['last_name'] = implode(' ', $name_string);
				}
			}
		}
		$this->person->user_firstname = ( !empty($this->booking_meta['registration']['first_name']) ) ? $this->booking_meta['registration']['first_name']:__('Guest User','events');
		$this->person->first_name = $this->person->user_firstname;
		$this->person->user_lastname = ( !empty($this->booking_meta['registration']['last_name']) ) ? $this->booking_meta['registration']['last_name']:'';
		$this->person->last_name = $this->person->user_lastname;
		//build display name
		$full_name = trim($this->person->user_firstname  . " " . $this->person->user_lastname);
		
		$this->person->display_name = ( empty($full_name) ) ? __('Guest User','events') : $full_name;
		$this->person->loaded_no_user = $this->booking_id;
		
		return apply_filters('em_booking_get_person', $this->person, $this);
	}

	
	function get_status() : string 
	{
		$status = ($this->booking_status == 0 && !get_option('dbem_bookings_approval') ) ? 1:$this->booking_status;
		return apply_filters('em_booking_get_status', $this->status_array[$status], $this);
	}
	
	function delete() : bool 
	{
		global $wpdb;
		$result = false;
		if( $this->can_manage('manage_bookings','manage_others_bookings') ){
			$sql = $wpdb->prepare("DELETE FROM ". EM_BOOKINGS_TABLE . " WHERE booking_id=%d", $this->booking_id);
			$result = $wpdb->query( $sql );
			if( $result !== false ){
				//delete the tickets too
				$this->get_tickets_bookings()->delete();
				$this->previous_status = $this->booking_status;
				$this->booking_status = false;
				$this->feedback_message = sprintf(__('%s deleted', 'events'), __('Booking','events'));
				do_action('em_booking_deleted', $this);
			}else{
				$this->errors[] = sprintf(__('%s could not be deleted', 'events'), __('Booking','events'));
			}
		}
		do_action('em_bookings_deleted', $result, array($this->booking_id), $this);
		return apply_filters('em_booking_delete',( $result !== false ), $this);
	}
	
	function cancel($email = true) : bool 
	{
		return $this->set_status(3, $email);
	}
	
	function approve($email = true, $ignore_spaces = false) : bool {
		return $this->set_status(1, $email, $ignore_spaces);
	}	

	function reject($email = true) : bool 
	{
		return $this->set_status(2, $email);
	}	
	
	function unapprove($email = true) : bool 
	{
		return $this->set_status(0, $email);
	}
	
	/**
	 * Change the status of the booking. This will save to the Database too. 
	 * @param int $status
	 * @return boolean
	 */
	function set_status(int $status, bool $email = true, $ignore_spaces = false) : bool 
	{
		global $wpdb;
		$action_string = strtolower($this->status_array[$status]); 
		//if we're approving we can't approve a booking if spaces are full, so check before it's approved.
		if(!$ignore_spaces && $status == 1){
			if( !$this->is_reserved() && $this->get_event()->get_bookings()->get_available_spaces() < $this->get_spaces() && !get_option('dbem_bookings_approval_overbooking') ){
				$this->feedback_message = sprintf(__('Not approved, spaces full.','events'), $action_string);
				$this->errors[] = $this->feedback_message;
				return apply_filters('em_booking_set_status', false, $this);
			}
		}
		$this->previous_status = $this->booking_status;
		$this->booking_status = $status;
		$result = $wpdb->query($wpdb->prepare('UPDATE '.EM_BOOKINGS_TABLE.' SET booking_status=%d WHERE booking_id=%d', array($status, $this->booking_id)));
		if($result !== false){
			$this->feedback_message = sprintf(__('Booking %s.','events'), $action_string);
			$result = apply_filters('em_booking_set_status', $result, $this); // run the filter before emails go out, in case others need to hook in first
			if( $result && $email && $this->previous_status != $this->booking_status ){ //email if status has changed
				if( $this->email() ){
				    if( $this->mails_sent > 0 ){
				        $this->feedback_message .= " ".__('Email Sent.','events');
				    }
				}else{
					//extra errors may be logged by email() in EM_Object
					$this->feedback_message .= ' <span style="color:red">'.__('ERROR : Email Not Sent.','events').'</span>';
					$this->errors[] = __('ERROR : Email Not Sent.','events');
				}
			}
		}else{
			//errors should be logged by save()
			$this->feedback_message = sprintf(__('Booking could not be %s.','events'), $action_string);
			$this->errors[] = sprintf(__('Booking could not be %s.','events'), $action_string);
			$result =  apply_filters('em_booking_set_status', false, $this);
		}
		return $result;
	}
	
	/**
	 * Returns true if booking is reserving a space at this event, whether confirmed or not 
	 */
	function is_reserved(){
	    $result = false;
	    if( $this->booking_status == self::PENDING && get_option('dbem_bookings_approval_reserved') ){
	        $result = true;
	    }elseif( $this->booking_status == self::PENDING && !get_option('dbem_bookings_approval') ){
	        $result = true;
	    }elseif( $this->booking_status == self::APPROVED ){
	        $result = true;
	    }
	    return apply_filters('em_booking_is_reserved', $result, $this);
	}
	
	/**
	 * Returns true if booking is associated with a non-registered user, i.e. booked as a guest 'no user mode'.
	 * @return mixed
	 */
	function is_no_user(){
		return apply_filters('em_booking_is_no_user', $this->get_person()->ID === 0, $this);
	}
	
	/**
	 * Returns true if booking is either pending or reserved but not confirmed (which is assumed pending) 
	 */
	function is_pending() : bool
	{
		$result = ($this->is_reserved() || $this->booking_status == 0) && $this->booking_status != 1;
	    return apply_filters('em_booking_is_pending', $result, $this);
	}
	
	

	function get_admin_url() : string
	{
		return is_admin() ? EM_ADMIN_URL. "&page=events-bookings&event_id=".$this->event_id."&booking_id=".$this->booking_id : "";
	}
	
	function output($format, $target="html") : string {
		do_action('em_booking_output_pre', $this, $format, $target);
	 	preg_match_all("/(#@?_?[A-Za-z0-9]+)({([^}]+)})?/", $format, $placeholders);
		$output_string = $format;
		$replaces = array();
		foreach($placeholders[1] as $key => $result) {
			$replace = '';
			$full_result = $placeholders[0][$key];
			$placeholder_atts = array($result);
			if( !empty($placeholders[3][$key]) ) $placeholder_atts[] = $placeholders[3][$key];
			switch( $result ){
				case '#_BOOKINGFORMCUSTOM':
					if(!$placeholder_atts[1]) break; 
					$replace = $this->meta['booking'][$placeholder_atts[1]];
					break;
				case '#_BOOKINGFIELDS': 
					ob_start();
					em_locate_template('emails/bookingfields.php', true, array('EM_Booking'=>$this));
					$replace = ob_get_clean();
					break;
				case '#_BOOKINGFIELD':
					if(!$placeholder_atts[1]) break;
					if(key_exists($placeholder_atts[1], $this->meta['booking'])) {
						$replace = $this->booking_meta['booking'][$placeholder_atts[1]];
						break;
					}
					if(key_exists($placeholder_atts[1], $this->meta['registration'])) {
						$replace = $this->booking_meta['registration'][$placeholder_atts[1]];
					}
					break;
				case '#_BOOKINGID':
					$replace = $this->booking_id;
					break;
				case '#_BOOKINGNAME':
					$replace = $this->get_person()->get_name();
					break;
				case '#_BOOKINGEMAIL':
					$replace = $this->booking_meta['registration']['user_email'];
					break;
				case '#_BOOKINGSPACES':
					$replace = $this->get_spaces();
					break;
				case '#_BOOKINGDATE':
					$replace = ( $this->date() !== false ) ? \Contexis\Events\Intl\Date::get_date($this->date()->getTimestamp()) :'n/a';
					break;
				case '#_BOOKINGTIME':
					$replace = ( $this->date() !== false ) ?  \Contexis\Events\Intl\Date::get_time($this->date()->getTimestamp()) :'n/a';
					break;
				case '#_BOOKINGCOMMENT':
					$replace = $this->booking_comment;
				case '#_BOOKINGPRICE':
					$replace = $this->format_price($this->get_price());
					break;
				case '#_BOOKINGTICKETS':
					ob_start();
					em_locate_template('emails/bookingtickets.php', true, array('EM_Booking'=>$this));
					$replace = ob_get_clean();
					break;
				case '#_BOOKINGSUMMARY':
					ob_start();
					em_locate_template('emails/bookingsummary.php', true, array('EM_Booking'=>$this));
					$replace = ob_get_clean();
					break;
				case '#_BOOKINGADMINURL':
				case '#_BOOKINGADMINLINK':
					$bookings_link = esc_url( add_query_arg('booking_id', $this->booking_id, $this->get_event()->get_bookings_url()) );
					if($result == '#_BOOKINGADMINLINK'){
						$replace = '<a href="'.$bookings_link.'">'.esc_html__('Edit Booking', 'events'). '</a>';
					}else{
						$replace = $bookings_link;
					}
					break;
				case '#_IBAN':
					$replace = get_option("em_offline_iban", true);
					break;
				case '#_BENEFICIARY':
					$replace = get_option("em_offline_beneficiary", true);
					break;
				case '#_REFERENCE':
					$replace = $this->booking_id . "-" . $this->event->post_name . "-" . $this->booking_meta['registration']['last_name'];
					break;
				case '#_PRICE': 
					$replace = \Contexis\Events\Intl\Price::format($this->booking_price);
					break;
				case '#_BANK':
					$replace = get_option("em_offline_bank", true);
					break;
				case '#_PAYMENTDEADLINE':
					$date = new DateTime();
					$interval = new DateInterval('P' . get_option("em_offline_deadline", 10) . 'D');
					$date->add($interval);
					$replace = \Contexis\Events\Intl\Date::get_date($date->getTimestamp());
					break;
				case '#_COUPON':
					$replace = $this->get_price_adjustments_summary('discounts', 'pre');
					break;
				case '#_BOOKINGATTENDEES':
					ob_start();
					em_locate_template('emails/attendees.php', true, array('EM_Booking'=>$this));
					$replace = ob_get_clean();
					break;
				default:
					$replace = $full_result;
					break;
			}
			$replaces[$full_result] = apply_filters('em_booking_output_placeholder', $replace, $this, $full_result, $target, $placeholder_atts);
		}
		//sort out replacements so that during replacements shorter placeholders don't overwrite longer varieties.
		krsort($replaces);
		foreach($replaces as $full_result => $replacement){
			$output_string = str_replace($full_result, $replacement , $output_string );
		}
		//run event output too, since this is never run from within events and will not infinitely loop
		$EM_Event = apply_filters('em_booking_output_event', $this->get_event(), $this); //allows us to override the booking event info if it belongs to a parent or translation
		$output_string = $EM_Event->output($output_string, $target);
		return apply_filters('em_booking_output', $output_string, $this, $format, $target);	
	}
	
	/**
	 * @param boolean $email_admin
	 * @param boolean $force_resend
	 * @param boolean $email_attendee
	 * @return boolean
	 */
	function email( bool $email_admin = true, bool $force_resend = false, bool $email_attendee = true ) : bool
	{
		$result = true;
		$this->mails_sent = 0;
		
		
		//Make sure event matches booking, and that booking used to be approved.
		if( $this->booking_status !== $this->previous_status || $force_resend ){
			// before we format dates or any other language-specific placeholders, make sure we're translating the site language, not the user profile language in the admin area (e.g. if an admin is sending a booking confirmation email), assuming this isn't a ML-enabled site.
			
			do_action('em_booking_email_before_send', $this);
			//get event info and refresh all bookings
			$EM_Event = $this->get_event(); //We NEED event details here.
			$EM_Event->get_bookings(true); //refresh all bookings
			//messages can be overridden just before being sent
			$msg = $this->email_messages();

			//Send user (booker) emails
			if( !empty($msg['user']['subject']) && $email_attendee ){
				$msg['user']['subject'] = $this->output($msg['user']['subject'], 'raw');
				$msg['user']['body'] = $this->output($msg['user']['body'], 'email');
				$attachments = array();
				if( !empty($msg['user']['attachments']) && is_array($msg['user']['attachments']) ){
					$attachments = $msg['user']['attachments'];
				}
				//Send to the person booking
				if( !$this->email_send( $msg['user']['subject'], $msg['user']['body'], $this->get_person()->user_email, $attachments) ){
					$result = false;
				}else{
					$this->mails_sent++;
				}
			}
			
			//Send admin/contact emails if this isn't the event owner or an events admin
			if( $email_admin && !empty($msg['admin']['subject']) ){ //emails won't be sent if admin is logged in unless they book themselves
				//get admin emails that need to be notified, hook here to add extra admin emails
				$admin_emails = str_replace(' ','',get_option('dbem_bookings_notify_admin'));
				$admin_emails = apply_filters('em_booking_admin_emails', explode(',', $admin_emails), $this); //supply emails as array
				if( get_option('dbem_bookings_contact_email') == 1 && !empty($EM_Event->get_contact()->user_email) ){
				    //add event owner contact email to list of admin emails
				    $admin_emails[] = $EM_Event->get_contact()->user_email;
				}
				foreach($admin_emails as $key => $email){ if( !is_email($email) ) unset($admin_emails[$key]); } //remove bad emails
				//proceed to email admins if need be
				if( !empty($admin_emails) ){
					//Only gets sent if this is a pending booking, unless approvals are disabled.
					$msg['admin']['subject'] = $this->output($msg['admin']['subject'],'raw');
					$msg['admin']['body'] = $this->output($msg['admin']['body'], 'email');
					$attachments = array();
					if( !empty($msg['admin']['attachments']) && is_array($msg['admin']['attachments']) ){
						$attachments = $msg['admin']['attachments'];
					}
					//email admins
						if( !$this->email_send( $msg['admin']['subject'], $msg['admin']['body'], $admin_emails, $attachments) && current_user_can('manage_options') ){
							$this->errors[] = __('Confirmation email could not be sent to admin. Registrant should have gotten their email (only admin see this warning).','events');
							$result = false;
						}else{
							$this->mails_sent++;
						}
				}
			}
			do_action('em_booking_email_after_send', $this);
		}
		return apply_filters('em_booking_email', $result, $this, $email_admin, $force_resend, $email_attendee);
		//TODO need error checking for booking mail send
	}	
	
	function email_messages() : array
	{
		$msg = array( 'user'=> array('subject'=>'', 'body'=>''), 'admin'=> array('subject'=>'', 'body'=>'')); //blank msg template			
		//admin messages won't change whether pending or already approved
	    switch( $this->booking_status ){
	    	case 0:
	    	case 5: //TODO remove offline status from here and move to pro
	    		$msg['user']['subject'] = get_option('dbem_bookings_email_pending_subject');
	    		$msg['user']['body'] = get_option('dbem_bookings_email_pending_body');
	    		//admins should get something (if set to)
	    		$msg['admin']['subject'] = get_option('dbem_bookings_contact_email_pending_subject');
	    		$msg['admin']['body'] = get_option('dbem_bookings_contact_email_pending_body');
	    		break;
	    	case 1:
	    		$msg['user']['subject'] = get_option('dbem_bookings_email_confirmed_subject');
	    		$msg['user']['body'] = get_option('dbem_bookings_email_confirmed_body');
	    		//admins should get something (if set to)
	    		$msg['admin']['subject'] = get_option('dbem_bookings_contact_email_confirmed_subject');
	    		$msg['admin']['body'] = get_option('dbem_bookings_contact_email_confirmed_body');
	    		break;
	    	case 2:
	    		$msg['user']['subject'] = get_option('dbem_bookings_email_rejected_subject');
	    		$msg['user']['body'] = get_option('dbem_bookings_email_rejected_body');
	    		//admins should get something (if set to)
	    		$msg['admin']['subject'] = get_option('dbem_bookings_contact_email_rejected_subject');
	    		$msg['admin']['body'] = get_option('dbem_bookings_contact_email_rejected_body');
	    		break;
	    	case 3:
	    		$msg['user']['subject'] = get_option('dbem_bookings_email_cancelled_subject');
	    		$msg['user']['body'] = get_option('dbem_bookings_email_cancelled_body');
	    		//admins should get something (if set to)
	    		$msg['admin']['subject'] = get_option('dbem_bookings_contact_email_cancelled_subject');
	    		$msg['admin']['body'] = get_option('dbem_bookings_contact_email_cancelled_body');
	    		break;
	    }
	    return apply_filters('em_booking_email_messages', $msg, $this);
	}
	
	/**
	 * Returns an EM_DateTime representation of when booking was made in UTC timezone. If no valid date defined, false will be returned
	 * @param boolean $utc_timezone
	 * @return EM_DateTime
	 * @throws Exception
	 */
	public function date( bool $utc_timezone = false ) : EM_DateTime 
	{
		if( empty($this->date) || !$this->date->valid ){
			if( !empty($this->booking_date ) ){
			    $this->date = new EM_DateTime($this->booking_date, 'UTC');
			}else{
				//we retrn a date regardless but it's not based on a 'valid' booking date
				$this->date = new EM_DateTime();
				$this->date->valid = false;
			}
		}
		//Set to UTC timezone if requested, local blog time by default
		if( $utc_timezone ){
			$timezone = 'UTC';
		}else{
			//we could set this to false but this way we might avoid creating a new timezone if it's already in this one
			$timezone = get_option( 'timezone_string' );
			if( !$timezone ) $timezone = get_option('gmt_offset');
		}
		$this->date->setTimezone($timezone);
		return $this->date;
	}
	
	/**
	 * Can the user manage this event? 
	 */
	function can_manage( $owner_capability = false, $admin_capability = false, $user_to_check = false ){
		return $this->get_event()->can_manage('manage_bookings','manage_others_bookings') || empty($this->booking_id) || !empty($this->manage_override);
	}

	static function booking_enabled() : array
	{
		$enabled = [
			'is_enabled' => true,
			'message' => ''
			
		];

		$active_gateways = EM_Gateways::active_gateways();

		if( count($active_gateways) == 0 ){
			$enabled['is_enabled'] = false;
			$enabled['message'] = __('No payment gateways are enabled. Please enable at least one payment gateway.', 'events');
			return $enabled;
		}

		if( array_key_exists('offline', $active_gateways) && (!get_option('em_offline_iban', false) || !get_option('em_offline_beneficiary', false) || !get_option('em_offline_bank', false)) ) {
			$enabled['is_enabled'] = false;
			$missing_fields = array();
			if( !get_option('em_offline_iban', false) ) $missing_fields[] = __('IBAN', 'events');
			if( !get_option('em_offline_beneficiary', false) ) $missing_fields[] = __('Beneficiary', 'events');
			if( !get_option('em_offline_bank', false) ) $missing_fields[] = __('Bank', 'events');
			$enabled['message'] = __('Offline Payment is not configured correctly. The following fields are missing:', 'events') . ' ' . implode(', ', $missing_fields) . __('. Please check your gateway settings.', 'events');
			return $enabled;
		}

		if( array_key_exists('mollie', $active_gateways) && !get_option('em_mollie_api_key', false) ) {
			$enabled['is_enabled'] = false;
			$enabled['message'] = __('Mollie API Key is not set. Please check your gateway settings.', 'events');
			return $enabled;
		}

		return $enabled;
	}

	/**
	 * Returns this object in the form of an array
	 * @return array
	 */
	function to_array($person = false) : array 
	{
		$booking = array();
		//Core Data
		$booking = parent::to_array();
		//Person Data
		if($person && is_object($this->person)){
			$person = $this->person->to_array();
			$booking = array_merge($booking, $person);
		}
		return $booking;
	}

	


}