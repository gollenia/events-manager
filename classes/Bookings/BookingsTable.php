<?php
//Builds a table of bookings, still work in progress...
// May be replaced by JS App in future
class EM_Bookings_Table {
	/**
	 * associative array of columns that'll be shown in order from left to right
	 * 
	 * * key - column name in the databse, what will be used when searching
	 * * value - label for use in column headers 
	 * @var array
	 */
	public $cols = ['user_name','event_name','booking_spaces','booking_status','booking_price','donation','booking_date'];
	/**
	 * Asoociative array of available column keys and corresponding headers, which will be used to display this table of bookings
	 * @var array
	 */
	public $cols_template = [];
	public $sortable_cols = ['booking_date'];
	/**
	 * Object we're viewing bookings in relation to.
	 * @var object
	 */
	public $cols_view;
	/**
	 * Index key used for looking up status information we're filtering in the booking table 
	 * @var string
	 */
	public $string = 'needs-attention';
	/**
	 * Associative array of status information.
	 * 
	 * * key - status index value
	 * * value - associative array containing keys
	 * ** label - the label for use in filter forms
	 * ** search - array or integer status numbers to search 
	 * 
	 * @var array
	 */
	public $statuses = array();
	/**
	 * Maximum number of rows to show
	 * @var int
	 */
	public $limit = 20;
	public $order = 'ASC';
	public $orderby = 'booking_name';
	public $page = 1;
	public $offset = 0;
	public $scope = 'future';
	public bool $show_tickets = false;
	public $bookings_count = 0;
	public EM_Bookings $bookings;
	public $status = '';
	public $cols_tickets_template = array();
	public $person;
	public $ticket;
	public $event;
	
	
	function __construct($show_tickets = false){
		$this->statuses = array(
			'all' => array('label'=>__('All','events'), 'search'=>false),
			'pending' => array('label'=>__('Pending','events'), 'search'=>0),
			'confirmed' => array('label'=>__('Confirmed','events'), 'search'=>1), 
			'cancelled' => array('label'=>__('Cancelled','events'), 'search'=>3),
			'rejected' => array('label'=>__('Rejected','events'), 'search'=>2),
			'needs-attention' => array('label'=>__('Needs Attention','events'), 'search'=>array(0)),
			'incomplete' => array('label'=>__('Incomplete Bookings','events'), 'search'=>array(0))
		);
		if( !get_option('dbem_bookings_approval') ){
			unset($this->statuses['pending']);
			unset($this->statuses['incomplete']);
			$this->statuses['confirmed']['search'] = array(0,1);
		}
		//Set basic vars
		$this->order = ( !empty($_REQUEST ['order']) && $_REQUEST ['order'] == 'DESC' ) ? 'DESC':'ASC';
		$this->orderby = ( !empty($_REQUEST ['orderby']) ) ? sanitize_sql_orderby($_REQUEST['orderby']):'booking_name';
		$this->limit = ( !empty($_REQUEST['limit']) && is_numeric($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;//Default limit
		$this->page = ( !empty($_REQUEST['pno']) && is_numeric($_REQUEST['pno']) ) ? $_REQUEST['pno']:1;
		$this->offset = ( $this->page > 1 ) ? ($this->page-1)*$this->limit : 0;
		$this->scope = ( !empty($_REQUEST['scope']) && array_key_exists($_REQUEST ['scope'], \EM_Object::get_scopes()) ) ? sanitize_text_field($_REQUEST['scope']):'future';
		$this->status = ( !empty($_REQUEST['status']) && array_key_exists($_REQUEST['status'], $this->statuses) ) ? sanitize_text_field($_REQUEST['status']):'needs-attention';
		//build template of possible columns
		$this->cols_template = apply_filters('em_bookings_table_cols_template', array(
			'user_login' => __('Username', 'events'),
			'user_name'=>__('Name','events'),
			'first_name'=>__('First Name','events'),
			'last_name'=>__('Last Name','events'),
			'event_name'=>__('Event','events'),
			'event_date'=>__('Event Date(s)','events'),
			'event_time'=>__('Event Time(s)','events'),
			'user_email'=>__('E-mail','events'),
			'dbem_phone'=>__('Phone Number','events'),
			'booking_spaces'=>__('Spaces','events'),
			'booking_status'=>__('Status','events'),
			'booking_date'=>__('Booking Date','events'),
			'booking_price'=>__('Total','events'),
			'booking_id'=>__('Booking ID','events'),
			'booking_comment'=>__('Booking Comment','events')
		), $this);
		$this->cols_tickets_template = apply_filters('em_bookings_table_cols_tickets_template', array(
			'ticket_name'=>__('Ticket Name','events'),
			'ticket_description'=>__('Ticket Description','events'),
			'ticket_price'=>__('Ticket Price','events'),
			'ticket_total'=>__('Ticket Total','events'),
			'ticket_id'=>__('Ticket ID','events')
		), $this);
		//add tickets to template if we're showing rows by booking-ticket
		if( $show_tickets ){
			$this->show_tickets = true;
			$this->cols = array('user_name','event_name','ticket_name','ticket_price','booking_spaces','booking_status');
			$this->cols_template = array_merge( $this->cols_template, $this->cols_tickets_template);
		}
		
		//calculate columns if post requests		
		if( !empty($_REQUEST['cols']) ){
		    if( is_array($_REQUEST['cols']) ){
			    $this->cols = array();
		    	foreach( $_REQUEST['cols'] as $k => $col ){
		    		$this->cols[$k] = sanitize_text_field($col);
			    }
    		}else{
    			foreach( explode(',',$_REQUEST['cols']) as $k => $col ){
    				if( array_key_exists($col, $this->cols_template) ){
					    $this->cols[$k] = $col;
				    }
			    }
    		}
		}
		//load column view settings
		if( $this->get_person() !== false ){
			$this->cols_view = $this->get_person();
		}elseif( $this->get_ticket() !== false ){
			$this->cols_view = $this->get_ticket();
		}elseif( $this->get_event() !== false ){
			$this->cols_view = $this->get_event();
		}
		//save columns depending on context and user preferences
		if( empty($_REQUEST['cols']) ){
			if(!empty($this->cols_view) && is_object($this->cols_view)){
				//check if user has settings for object type
				$settings = get_user_meta(get_current_user_id(), 'em_bookings_view-'.get_class($this->cols_view), true );
			}else{
				$settings = get_user_meta(get_current_user_id(), 'em_bookings_view', true );
			}
			if( !empty($settings) ){
				$this->cols = $settings;
			}
		}elseif( !empty($_REQUEST['cols']) && empty($_REQUEST['no_save']) ){ //save view settings for next time
		    if( !empty($this->cols_view) && is_object($this->cols_view) ){
				update_user_meta(get_current_user_id(), 'em_bookings_view-'.get_class($this->cols_view), $this->cols );
			}else{
				update_user_meta(get_current_user_id(), 'em_bookings_view', $this->cols );
			}
		}
		//clean any columns from saved views that no longer exist
		foreach($this->cols as $col_key => $col_name){
			if( !array_key_exists($col_name, $this->cols_template)){
				unset($this->cols[$col_key]);
			}
		}
		do_action('em_bookings_table', $this);
	}


	
	/**
	 * @return EM_Person|false
	 */
	function get_person(){
		global $EM_Person;
		if( !empty($this->person) && is_object($this->person) ){
			return $this->person;
		}elseif( !empty($_REQUEST['person_id']) && !empty($EM_Person) && is_object($EM_Person) ){
			return $EM_Person;
		}
		return false;
	}
	/**
	 * @return Ticket|false
	 */
	function get_ticket(){
		if(!isset($_REQUEST['ticket_id'])) return false;
		$ticket_id = is_numeric($_REQUEST['ticket_id']) ? $_REQUEST['ticket_id'] : 0;
		$ticket = new \Contexis\Events\Tickets\Ticket($ticket_id);
		
		if( !empty($this->ticket) && is_object($this->ticket) ){
			return $this->ticket;
		}elseif( !empty($ticket) && is_object($ticket) ){
			return $ticket;
		}
		return false;
	}
	/**
	 * @return $EM_Event|false
	 */
	function get_event() : EM_Event|false {
		global $EM_Event;
		if( !empty($this->event) && is_object($this->event) ){
			return $this->event;
		}elseif( !empty($EM_Event) && is_object($EM_Event) ){
			return $EM_Event;
		}
		return false;
	}
	
	/**
	 * Gets the bookings for this object instance according to its settings
	 * @param boolean $force_refresh
	 * @return EM_Bookings
	 */
	function get_bookings($force_refresh = true){
		if(!empty($this->bookings) && !$force_refresh) return $this->bookings;
		$EM_Event = $this->get_event();
		$args = [
			'limit' => $this->limit,
			'offset' => $this->offset,
			'order' => $this->order,
			'orderby' => $this->orderby,
			'status' => $this->statuses[$this->status]['search'],
			'scope' => $EM_Event ? false : $this->scope,
		];
		if( $EM_Event !== false ){
			$args['event'] = $EM_Event->event_id;
		}
		$args['owner'] = !current_user_can('manage_others_bookings') ? get_current_user_id() : false;
		$this->bookings_count = EM_Bookings::count($args);
		$this->bookings = EM_Bookings::get($args);
		return $this->bookings;
	}
	
	function get_count(){
		return $this->bookings_count;
	}
	
	function output(){
		do_action('em_bookings_table_header',$this); //won't be overwritten by JS	
		$this->output_overlays();
		$this->output_table();
		do_action('em_bookings_table_footer',$this); //won't be overwritten by JS	
	}
	
	function output_overlays(){
		$ticket = $this->get_ticket();
		$EM_Event = $this->get_event();
		$EM_Person = $this->get_person();
		?>
		<div id="em-bookings-table-settings" class="em-bookings-table-overlay" style="display:none;" title="<?php esc_attr_e('Bookings Table Settings','events'); ?>">
			<form id="em-bookings-table-settings-form" class="em-bookings-table-form" action="" method="post">
				<p><?php _e('Modify what information is displayed in this booking table.','events') ?></p>
				<div id="em-bookings-table-settings-form-cols">
					<p>
						<strong><?php _e('Columns to show','events')?></strong><br />
						<?php _e('Drag items to or from the left column to add or remove them.','events'); ?>
					</p>
					<ul id="em-bookings-cols-active" class="em-bookings-cols-sortable">
						<?php foreach( $this->cols as $col_key ): ?>
							<li class="ui-state-highlight">
								<input id="em-bookings-col-<?php echo esc_attr($col_key); ?>" type="hidden" name="<?php echo esc_attr($col_key); ?>" value="1" class="em-bookings-col-item" />
								<?php echo esc_html($this->cols_template[$col_key]); ?>
							</li>
						<?php endforeach; ?>
					</ul>			
					<ul id="em-bookings-cols-inactive" class="em-bookings-cols-sortable">
						<?php foreach( $this->cols_template as $col_key => $col_data ): ?>
							<?php if( !in_array($col_key, $this->cols) ): ?>
								<li class="ui-state-default">
									<input id="em-bookings-col-<?php echo esc_attr($col_key); ?>" type="hidden" name="<?php echo esc_attr($col_key); ?>" value="0" class="em-bookings-col-item"  />
									<?php echo esc_html($col_data); ?>
								</li>
							<?php endif; ?>
						<?php endforeach; ?>
					</ul>
				</div>
			</form>
		</div>
		<div id="em-bookings-table-export" class="em-bookings-table-overlay" style="display:none;" title="<?php esc_attr_e('Export Bookings','events'); ?>">
			<form id="em-bookings-table-export-form" class="em-bookings-table-form" action="" method="post">
				<p><?php esc_html_e('Select the options below and export all the bookings you have currently filtered (all pages) into a CSV spreadsheet format.','events') ?></p>
				
				<p>
				<input type="checkbox" name="show_tickets" value="1" />
				<label><?php esc_html_e('Split bookings by ticket type','events')?> </label>
				
				<?php do_action('em_bookings_table_export_options'); ?>
				<div id="em-bookings-table-settings-form-cols">
					<p><strong><?php esc_html_e('Columns to export','events')?></strong></p>
					<ul id="em-bookings-export-cols-active" class="em-bookings-cols-sortable">
						<?php foreach( $this->cols as $col_key ): ?>
							<li class="ui-state-highlight">
								<input id="em-bookings-col-<?php echo esc_attr($col_key); ?>" type="hidden" name="cols[<?php echo esc_attr($col_key); ?>]" value="1" class="em-bookings-col-item" />
								<?php echo esc_html($this->cols_template[$col_key]); ?>
							</li>
						<?php endforeach; ?>
					</ul>			
					<ul id="em-bookings-export-cols-inactive" class="em-bookings-cols-sortable">
						<?php foreach( $this->cols_template as $col_key => $col_data ): ?>
							<?php if( !in_array($col_key, $this->cols) ): ?>
								<li class="ui-state-default">
									<input id="em-bookings-col-<?php echo esc_attr($col_key); ?>" type="hidden" name="cols[<?php echo esc_attr($col_key); ?>]" value="0" class="em-bookings-col-item"  />
									<?php echo esc_html($col_data); ?>
								</li>
							<?php endif; ?>
						<?php endforeach; ?>
						<?php if( !$this->show_tickets ): ?>
						<?php foreach( $this->cols_tickets_template as $col_key => $col_data ): ?>
							<?php if( !in_array($col_key, $this->cols) ): ?>
								<li class="ui-state-default <?php if(array_key_exists($col_key, $this->cols_tickets_template)) echo 'em-bookings-col-item-ticket'; ?>">
									<input id="em-bookings-col-<?php echo esc_attr($col_key); ?>" type="hidden" name="cols[<?php echo esc_attr($col_key); ?>]" value="0" class="em-bookings-col-item"  />
									<?php echo esc_html($col_data); ?>
								</li>
							<?php endif; ?>
						<?php endforeach; ?>
						<?php endif; ?>
					</ul>
				</div>
				<?php if( $EM_Event !== false ): ?>
				<input type="hidden" name="event_id" value='<?php echo esc_attr($EM_Event->event_id); ?>' />
				<?php endif; ?>
				<?php if( $ticket !== false ): ?>
				<input type="hidden" name="ticket_id" value='<?php echo esc_attr($ticket->ticket_id); ?>' />
				<?php endif; ?>
				<?php if( $EM_Person !== false ): ?>
				<input type="hidden" name="person_id" value='<?php echo esc_attr($EM_Person->ID); ?>' />
				<?php endif; ?>
				<input type="hidden" name="scope" value='<?php echo esc_attr($this->scope); ?>' />
				<input type="hidden" name="status" value='<?php echo esc_attr($this->status); ?>' />
				<input type="hidden" name="no_save" value='1' />
				<input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce('export_bookings_csv'); ?>" />
				<input type="hidden" name="action" value="export_bookings_csv" />
			</form>
		</div>
		<br class="clear" />
		<?php
	}
	
	function output_table(){
		$ticket = $this->get_ticket();
		$EM_Event = $this->get_event();
		$EM_Person = $this->get_person();
		$this->get_bookings(true); //get bookings and refresh
		?>
		<div class='em-bookings-table em_obj' id="em-bookings-table">
			<form class='bookings-filter' method='get' action='<?php echo esc_url(site_url()); ?>/wp-admin/edit.php'>
				<?php if( $EM_Event !== false ): ?>
				<input type="hidden" name="event_id" value='<?php echo esc_attr($EM_Event->event_id); ?>' />
				<?php endif; ?>
				
				<input type="hidden" name="is_public" value="<?php echo ( !empty($_REQUEST['is_public']) || !is_admin() ) ? 1:0; ?>" />
				<input type="hidden" name="pno" value='<?php echo esc_attr($this->page); ?>' />
				<input type="hidden" name="order" value='<?php echo esc_attr($this->order); ?>' />
				<input type="hidden" name="orderby" value='<?php echo esc_attr($this->orderby); ?>' />
				<input type="hidden" name="post_type" value="event" />
				<input type="hidden" name="page" value="events-bookings" />
				<input type="hidden" name="cols" value="<?php echo esc_attr(implode(',', $this->cols)); ?>" />
				
				<div class='tablenav'>
					<div class="alignleft actions">
						<a href="#" class="em-bookings-table-export button-secondary" id="em-bookings-table-export-trigger" rel="#em-bookings-table-export" title="<?php _e('Export these bookings.','events'); ?>"><i class="material-symbols-outlined">export_notes</i></a>
						<a href="#" class="em-bookings-table-settings button-secondary" id="em-bookings-table-settings-trigger" rel="#em-bookings-table-settings"><i class="material-symbols-outlined">table</i></a>
						<?php if( $EM_Event === false ): ?>
						<select name="scope">
							<?php
							foreach ( EM_Object::get_scopes() as $key => $value ) {
								$selected = "";
								if ($key == $this->scope)
									$selected = "selected='selected'";
								echo "<option value='".esc_attr($key)."' $selected>".esc_html($value)."</option>  ";
							}
							?>
						</select>
						<?php endif; ?>
						<select name="limit">
							<option value="<?php echo esc_attr($this->limit) ?>"><?php echo esc_html(sprintf(__('%s Rows','events'),$this->limit)); ?></option>
							<option value="5">5</option>
							<option value="10">10</option>
							<option value="25">25</option>
							<option value="50">50</option>
							<option value="100">100</option>
						</select>
						<select name="status">
							<?php
							foreach ( $this->statuses as $key => $value ) {
								$selected = "";
								if ($key == $this->status)
									$selected = "selected='selected'";
								echo "<option value='".esc_attr($key)."' $selected>".esc_html($value['label'])."</option>  ";
							}
							?>
						</select>
						
						<input name="pno" type="hidden" value="1" />
						<button id="post-query-submit" class="button-secondary" type="submit" value="" ><?php esc_attr_e( 'Filter' )?>
						
					</div>
					<?php 
					if ( $this->bookings_count >= $this->limit ) {
						$bookings_nav = Contexis\Events\Admin\Pagination::paginate( $this->bookings_count, $this->limit, $this->page, array(),'#%#%','#');
						echo $bookings_nav;
					}
					?>
				</div>
				<div class="clear"></div>
				<div class='table-wrap'>
				<table id='dbem-bookings-table' class='widefat post bookingstable'>
					<thead>
						<tr>
							<?php /*						
							<th class='manage-column column-cb check-column' scope='col'>
								<input class='select-all' type="checkbox" value='1' />
							</th>
							*/ ?>
							<th class='manage-column' scope='col'><?php echo implode("</th><th class='manage-column' scope='col'>", $this->get_headers()); ?></th>
						</tr>
					</thead>
					<?php if( $this->bookings_count > 0 ): ?>
					<tbody>
						<?php 
						
						$event_count = (!empty($event_count)) ? $event_count:0;
						foreach ($this->bookings->bookings as $EM_Booking) {
							?>
							<tr>
								<?php  /*
								<th scope="row" class="check-column" style="padding:7px 0px 7px;"><input type='checkbox' value='<?php echo $EM_Booking->booking_id ?>' name='bookings[]'/></th>
								*/ 
								/* @var $EM_Booking EM_Booking */
								/* @var $ticket_Booking ticket_booking */
								if( $this->show_tickets ){
									foreach($EM_Booking->get_tickets_bookings()->tickets_bookings as $ticket_booking){
										$row = $this->get_row($ticket_booking);
										foreach( $row as $row_cell ){
										?><td><?php echo $row_cell; ?></td><?php
										}
									}
								} else {
									$row = $this->get_row($EM_Booking);
									foreach( $row as $row_cell ){
									?><td class="<?php echo $row_cell['class']; ?>"><?php echo $row_cell['content']; ?></td><?php
									}
								}
								?>
							</tr>
							<?php
						}
						?>
					</tbody>
					<?php else: ?>
						<tbody>
							<tr><td scope="row" colspan="<?php echo count($this->cols); ?>"><?php esc_html_e('No bookings.', 'events'); ?></td></tr>
						</tbody>
					<?php endif; ?>
				</table>
				</div>
				<?php if( !empty($bookings_nav) && $this->bookings_count >= $this->limit ) : ?>
				<div class='tablenav'>
					<?php echo $bookings_nav; ?>
					<div class="clear"></div>
				</div>
				<?php endif; ?>
			</form>
		</div>
		<?php
	}
	
	function get_headers($csv = false){
		$headers = array();
		foreach($this->cols as $col){
			if( $col == 'actions' ){
				if( !$csv ) $headers[$col] = '&nbsp;';
			}elseif(array_key_exists($col, $this->cols_template)){
				/* for later - col ordering!
				if($this->orderby == $col){
					if($this->order == 'ASC'){
						$headers[] = '<a class="em-bookings-orderby" href="#'.$col.'">'.$this->cols_template[$col].' (^)</a>';
					}else{
						$headers[] = '<a class="em-bookings-orderby" href="#'.$col.'">'.$this->cols_template[$col].' (d)</a>';
					}
				}else{
					$headers[] = '<a class="em-bookings-orderby" href="#'.$col.'">'.$this->cols_template[$col].'</a>';
				}
				*/
				$v = $this->cols_template[$col];
				if( $csv ){
					$v = self::sanitize_spreadsheet_cell($v);
				}
				$headers[$col] = $csv ? '<b>' . $v . '</b>' : $v;
			}
		}
		return apply_filters('em_bookings_table_get_headers', $headers, $csv, $this);
	}
	
	function get_table(){
		
	}
	
	/**
	 * @param Object $object
	 * @return array()
	 */
	function get_row( $object, $format = 'html' ){
		/* @var $ticket Ticket */
		/* @var $ticket_booking ticket_booking */
		/* @var $EM_Booking EM_Booking */
		get_class($object);
		if( get_class($object) == 'Contexis\Events\Tickets\TicketBooking' ){
			$ticket_booking = $object;
			$EM_Booking = $ticket_booking->get_booking();
		}else{
			$EM_Booking = $object;
		}
		$cols = array();
		foreach($this->cols as $col){
			if( $col == 'actions' && $format == 'csv' ) continue; 
			$cols[] = ['content' => $this->get_cell($EM_Booking, $col, $format), 'class' => 'em-bookings-col-'.$col];
		}
		return $cols;
	}

	function get_cell($EM_Booking, $column, $format = 'html'){
		
		$price_array = $EM_Booking->get_price_summary_array();
		switch ($column) {
			case 'user_email':
				return $EM_Booking->get_person()->user_email;
				break;
			case 'user_name':
				if( $format == 'csv' ) return $EM_Booking->get_person()->get_name();
				$url = $EM_Booking->get_event()->get_bookings_url();
				$url = add_query_arg(['booking_id'=>$EM_Booking->booking_id, 'em_ajax'=>null, 'em_obj'=>null], $url);
				
				$ret = "<strong><a class='row-title' href='$url'>" . $EM_Booking->get_person()->get_name() . '</a></strong>';
				$ret .= "<div class='row-actions'>" . implode(' | ', $this->get_booking_actions($EM_Booking)) . "</div>";
				return $ret;
				break;
			case 'first_name':
				return $EM_Booking->get_person()->first_name;
				break;
			case 'last_name':
				return $EM_Booking->get_person()->last_name;
				break;
			case 'event_name':
				return $format == 'csv' ? $EM_Booking->get_event()->event_name : '<a href="'.$EM_Booking->get_event()->get_bookings_url().'">'. esc_html($EM_Booking->get_event()->event_name) .'</a>';
				break;
			case 'event_date':
				return $EM_Booking->get_event()->output('#_EVENTDATES');
				break;
			case 'event_time':
				return $EM_Booking->get_event()->output('#_EVENTTIMES');
				break;
			case 'booking_price':
				return \Contexis\Events\Intl\Price::format( $price_array['total'] );
				break;
			case 'donation':
				return \Contexis\Events\Intl\Price::format( $price_array['donation'] );
				break;
			case 'booking_status':
				if( $format == 'csv' ) return $EM_Booking->get_status();
				$status = array_search($EM_Booking->booking_status, array_column($this->statuses, 'search'));
				return '<span class="em-label em-label-'.$status.'"><i class="material-symbols-outlined">'.$this->get_status_icon($status).'</i>'.ucwords($EM_Booking->get_status()).'</span>';
				break;
			case 'booking_date':
				return \Contexis\Events\Intl\Date::get_date($EM_Booking->date()->getTimestamp()) . " " . \Contexis\Events\Intl\Date::get_time($EM_Booking->date()->getTimestamp());
				break;
			case 'booking_id':
				return $EM_Booking->booking_id;
				break;
			case 'actions':
				return '';
				break;
			case 'booking_spaces':
				return $EM_Booking->get_spaces();
				break;
			case 'booking_comment':
				return $EM_Booking->booking_comment;
				break;
			case 'ticket_name':
				return $EM_Booking->get_tickets_bookings()->tickets_bookings[0]->get_ticket()->ticket_name;
				break;
			case 'ticket_description':
				return $EM_Booking->get_tickets_bookings()->tickets_bookings[0]->get_ticket()->ticket_description;
				break;
			case 'ticket_price':
				return \Contexis\Events\Intl\Price::format( $EM_Booking->get_tickets_bookings()->tickets_bookings[0]->get_ticket()->get_price() );
				break;
			case 'ticket_total':
				return $EM_Booking->get_tickets_bookings()->tickets_bookings[0]->get_price(false);
				break;
			
			case 'ticket_id':
				return $EM_Booking->get_tickets_bookings()->tickets_bookings[0]->get_ticket()->ticket_id;
				break;
			case 'dbem_phone':
				return $EM_Booking->get_person()->phone;
				break;
			case 'coupons':
				return implode(', ', $EM_Booking->get_coupons());
				if( !EM_Coupons::booking_has_coupons($EM_Booking) ) {
					return '';
					break;
				}
				$coupon_codes = array();
				$coupons = EM_Coupons::booking_get_coupons($EM_Booking);
				foreach( $coupons as $EM_Coupon ){
					$coupon_codes[] = $EM_Coupon->coupon_code;
				}
				$coupon_codes = implode(' ', $coupon_codes);
				
				return $coupon_codes;
				break;
			default:
				return apply_filters('em_bookings_table_get_cell', '', $EM_Booking, $column, $format);
				break;
		}
	}

	function get_status_icon ($status) {
		$icons = [
			'pending',
			'check_circle',
			'check_circle',
			'block',
			'pan_tool',
			'overview',
			'overview',
			'credit_card_clock',
			'overview',
		];
		return $icons[$status];
	}
	
	function get_row_csv($EM_Booking){
	    $row = $this->get_row($EM_Booking, 'csv');
	    foreach($row as $k=>$v){
			var_dump($v['content']);
	    	$row[$k] = html_entity_decode($v['content']);
	    } //remove things like &amp; which may have been saved to the DB directly
	    return $row;
	}
	
	public static function sanitize_spreadsheet_cell( $cell ){
		return preg_replace('/^([;=@\+\-])/', "'$1", $cell);
	}
	
	/**
	 * @param EM_Booking $EM_Booking
	 * @return mixed
	 */
	function get_booking_actions($EM_Booking){
		$booking_actions = array();

		switch($EM_Booking->booking_status){
			case EM_Booking::PENDING: 
				if( !get_option('dbem_bookings_approval') ) break;
				$actions = ['approve', 'reject'];
				break;
				
			case EM_Booking::APPROVED:
				$actions = ['unapprove', 'cancel'];
				break;
			default:
				$actions = ['approve'];
				break;	
		}

		$actions = apply_filters('em_bookings_table_booking_actions_'.$EM_Booking->booking_status, $actions, $EM_Booking);
		$actions[] = 'delete';
		$booking_actions = $this->generate_action_links($actions, $EM_Booking);
		
		return apply_filters('em_bookings_table_cols_col_action', $booking_actions, $EM_Booking);
	}

	private function generate_action_links(array $actions, $EM_Booking) : array {
		$links = [];

		foreach($actions as $action) {
			$class = $action== 'delete' ? 'trash' : '';
			$links[] =  "<span class='$class'><a class='em-bookings-action' data-action='$action' data-booking-id='$EM_Booking->booking_id'>" . __(ucfirst($action), 'events') . "</a></span>";
		}
		
		return $links;
	}
}
?>