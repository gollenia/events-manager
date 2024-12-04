<?php
if(!class_exists('EM_Gateways_Transactions')) {
class EM_Gateways_Transactions{
	var $limit = 20;
	var $total_transactions = 0;

	var $order;
	var $orderby;
	var $page;
	var $gateway;
	
	function __construct(){
		$this->order = ( !empty($_REQUEST ['order']) ) ? $_REQUEST ['order']:'ASC';
		$this->orderby = ( !empty($_REQUEST ['order']) ) ? $_REQUEST ['order']:'booking_name';
		$this->limit = ( !empty($_REQUEST['limit']) ) ? $_REQUEST['limit'] : 20;//Default limit
		$this->page = ( !empty($_REQUEST['pno']) ) ? $_REQUEST['pno']:1;
		$this->gateway = !empty($_REQUEST['gateway']) ? $_REQUEST['gateway']:false;
		//Add options and tables to EM admin pages
		if( current_user_can('manage_others_bookings') ){
			add_action('em_bookings_dashboard', array(&$this, 'output'),10,1);
			add_action('em_bookings_ticket_footer', array(&$this, 'output'),10,1);
			add_action('em_bookings_single_footer', array(&$this, 'output'),10,1);
			add_action('em_bookings_person_footer', array(&$this, 'output'),10,1);
			add_action('em_bookings_event_footer', array(&$this, 'output'),10,1);
		}
		//Booking Total Payments Hook
		add_filter('em_booking_get_total_paid', 'EM_Gateways_Transactions::get_total_paid', 10, 2);
		//Clean up of transactions when booking is deleted
		add_action('em_bookings_deleted', array(&$this, 'em_bookings_deleted'), 10, 2);
		//Booking Tables UI
		add_filter('em_bookings_table_rows_col', array(&$this,'em_bookings_table_rows_col'),10,5);
		add_filter('em_bookings_table_cols_template', array(&$this, 'em_bookings_table_cols_template'),10,2);
		add_action('wp_ajax_em_transactions_table', array(&$this, 'ajax'),10,1);
	}
	
	/**
	 * @param unknown $result
	 * @param array $booking_ids
	 * @return unknown
	 */
	public static function em_bookings_deleted($result, $booking_ids){
		if( $result && count($booking_ids) > 0 ){
			//TODO decouple transaction logic from gateways
			global $wpdb;
			foreach($booking_ids as $k => $v){ $booking_ids[$k] = absint($v); if( empty($booking_ids[$k]) ) unset($booking_ids[$k]); }
			$wpdb->query('DELETE FROM '.EM_TRANSACTIONS_TABLE." WHERE booking_id IN (".implode(',', $booking_ids).")");
		}
		return $result;
	}
	
	/**
	 * Returns the total paid for a specific booking. Hooks into em_booking_get_total_paid.
	 * @param EM_Booking $EM_Booking
	 * @return string|float
	 */
	public static function get_total_paid( $total, $EM_Booking ){
		global $wpdb;
		$total = $wpdb->get_var('SELECT SUM(transaction_total_amount) FROM '.EM_TRANSACTIONS_TABLE." WHERE booking_id={$EM_Booking->booking_id}");
		return $total;
	}
	
	function ajax(){
		if( wp_verify_nonce($_REQUEST['_wpnonce'],'em_transactions_table') ){
			//Get the context
			global $EM_Event, $EM_Booking, $EM_Person;
			$ticket = new \Contexis\Events\Tickets\Ticket();
			em_load_event();
			$context = false;
			if( !empty($_REQUEST['booking_id']) && is_object($EM_Booking) && $EM_Booking->can_manage('manage_bookings','manage_others_bookings') ){
				$context = $EM_Booking;
			}elseif( !empty($_REQUEST['event_id']) && is_object($EM_Event) && $EM_Event->can_manage('manage_bookings','manage_others_bookings') ){
				$context = $EM_Event;
			}elseif( !empty($_REQUEST['person_id']) && is_object($EM_Person) && current_user_can('manage_bookings') ){
				$context = $EM_Person;
			}elseif( !empty($_REQUEST['ticket_id']) && is_object($ticket) && $ticket->can_manage('manage_bookings','manage_others_bookings') ){
				$context = $ticket;
			}			
			echo $this->mytransactions($context);
			exit;
		}
	}
	
	function output( $context = false ) {
		global $page, $action, $wp_query;
		?>
		<div class="wrap">
		<h2><?php _e('Transactions','events'); ?></h2>
		<?php $this->mytransactions($context); ?>
		<script type="text/javascript">
			jQuery(document).ready( function($){
				//Pagination link clicks
				$(document).on('click', '#em-transactions-table .tablenav-pages a', function(){
					var el = $(this);
					var form = el.parents('#em-transactions-table form.transactions-filter');
					//get page no from url, change page, submit form
					var match = el.attr('href').match(/#[0-9]+/);
					if( match != null && match.length > 0){
						var pno = match[0].replace('#','');
						form.find('input[name=pno]').val(pno);
					}else{
						form.find('input[name=pno]').val(1);
					}
					form.trigger('submit');
					return false;
				});
				//Widgets and filter submissions
				$(document).on('submit', '#em-transactions-table form.transactions-filter', function(e){
					var el = $(this);			
					el.parents('#em-transactions-table').find('.table-wrap').first().append('<div id="em-loading" />');
					$.get( EM.ajaxurl, el.serializeArray(), function(data){
						el.parents('#em-transactions-table').first().replaceWith(data);
						$('#em-transactions-table form.transactions-filter input[name="pno"]').val(1); //reset pno in JS
					});
					return false;
				});
			});
		</script>
		</div>
		<?php
	}

	function mytransactions($context=false) {
		global $EM_Person;
		$transactions = $this->get_transactions($context);
		$total = $this->total_transactions;

		$columns = array();

		$columns['event'] = __('Event','events');
		$columns['user'] = __('User','events');
		$columns['date'] = __('Date','events');
		$columns['amount'] = __('Amount','events');
		$columns['transid'] = __('Transaction id','events');
		$columns['gateway'] = __('Gateway','events');
		$columns['status'] = __('Status','events');
		$columns['note'] = __('Notes','events');
		$columns['actions'] = '';

		$trans_navigation = paginate_links( array(
			'base' => add_query_arg( 'paged', '%#%' ),
			'format' => '',
			'total' => ceil($total / 20),
			'current' => $this->page
		));
		?>
		<div id="em-transactions-table" class="em_obj">
		<form id="em-transactions-table-form" class="transactions-filter" action="" method="post">
			<?php if( is_object($context) && get_class($context)=="EM_Event" ): ?>
			<input type="hidden" name="event_id" value='<?php echo $context->event_id ?>' />
			<?php elseif( is_object($context) && get_class($context)=="EM_Person" ): ?>
			<input type="hidden" name="person_id" value='<?php echo $context->ID ?>' />
			<?php elseif( is_object($context) && (get_class($context)=="EM_Booking" || get_class($context)=="EM_Multiple_Booking") ): ?>
			<input type="hidden" name="booking_id" value='<?php echo $context->booking_id ?>' />
			<?php elseif( is_object($context) && get_class($context)=="Ticket" ): ?>
			<input type="hidden" name="ticket_id" value='<?php echo $context->ticket_id ?>' />
			<?php endif; ?>
			<input type="hidden" name="pno" value='<?php echo $this->page ?>' />
			<input type="hidden" name="order" value='<?php echo $this->order ?>' />
			<input type="hidden" name="orderby" value='<?php echo $this->orderby ?>' />
			<input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce('em_transactions_table'); ?>" />
			<input type="hidden" name="action" value="em_transactions_table" />
			
			<div class="tablenav">
				<div class="alignleft actions">
					<select name="limit">
						<option value="<?php echo $this->limit ?>"><?php echo sprintf(esc_html('%s Rows','events'),$this->limit); ?></option>
						<option value="5">5</option>
						<option value="10">10</option>
						<option value="25">25</option>
						<option value="50">50</option>
						<option value="100">100</option>
					</select>
					<select name="gateway">
						<option value="">All</option>
						<?php
						global $EM_Gateways;
						foreach ( $EM_Gateways as $EM_Gateway ) {
							?><option value='<?php echo $EM_Gateway->gateway ?>' <?php if($EM_Gateway->gateway == $this->gateway) echo "selected='selected'"; ?>><?php echo $EM_Gateway->title ?></option><?php
						}
						?>
					</select>
					<button id="post-query-submit" class="button-secondary" type="" value="" ><?php esc_attr_e( 'Filter' )?>
					<?php if( is_object($context) && get_class($context)=="EM_Event" ): ?>
					<?php esc_html_e('Displaying Event','events'); ?> : <?php echo $context->event_name; ?>
					<?php elseif( is_object($context) && get_class($context)=="EM_Person" ): ?>
					<?php esc_html_e('Displaying User','events'); echo ' : '.$context->get_name(); ?>
					<?php endif; ?>
				</div>
				<?php 
				if ( $this->total_transactions >= $this->limit ) {
					echo Contexis\Events\Admin\Pagination::paginate( $this->total_transactions, $this->limit, $this->page, array(),'#%#%','#');
				}
				?>
			</div>

			<div class="table-wrap">
			<table cellspacing="0" class="widefat">
				<thead>
				<tr>
				<?php
					foreach($columns as $key => $col) {
						?>
						<th style="" class="manage-column column-<?php echo $key; ?>" id="<?php echo $key; ?>" scope="col"><?php echo $col; ?></th>
						<?php
					}
				?>
				</tr>
				</thead>

				<tfoot>
				<tr>
					<?php
						reset($columns);
						foreach($columns as $key => $col) {
							?>
							<th style="" class="manage-column column-<?php echo $key; ?>" id="<?php echo $key; ?>" scope="col"><?php echo $col; ?></th>
							<?php
						}
					?>
				</tr>
				</tfoot>

				<tbody>
					<?php
						echo $this->print_transactions($transactions);
					?>

				</tbody>
			</table>
			</div>
		</form>
		</div>
		<?php
	}
	
	function print_transactions($transactions, $columns=7){
		ob_start();
		if($transactions) {
			foreach($transactions as $key => $transaction) {
				?>
				<tr valign="middle" class="alternate">
					<td>
						<?php
							$EM_Booking = EM_Booking::find($transaction->booking_id);
							if( get_class($EM_Booking) == 'EM_Multiple_Booking' ){
								$link = add_query_arg(['booking_id'=>$EM_Booking->booking_id, 'em_ajax'=>null, 'em_obj'=>null], $EM_Booking->get_admin_url());
								echo '<a href="'.$link.'">'.$EM_Booking->get_event()->event_name.'</a>';
							}else{
								echo '<a href="'.$EM_Booking->get_event()->get_bookings_url().'">'.$EM_Booking->get_event()->event_name.'</a>';
							}
						?>
					</td>
					<td>
						<?php
							echo '<a href="'.$EM_Booking->get_person()->get_bookings_url().'">'. $EM_Booking->person->get_name() .'</a>';
						?>
					</td>
					<td class="column-date">
						<?php
						echo $transaction->transaction_timestamp;
						?>
					</td>
					<td class="column-amount">
						<?php
							$amount = $transaction->transaction_total_amount;
							echo \Contexis\Events\Intl\Price::format($amount);
						?>
					</td>
					<td class="column-gateway-trans-id">
						<?php
							if(!empty($transaction->transaction_gateway_id)) {
								$transaction_gateway_id = apply_filters('em_gateways_transactions_table_gateway_id', $transaction->transaction_gateway_id, $transaction, $EM_Booking);
								//use the below filter to override specific gateways, the above for modifying the field for all gateways
								echo apply_filters('em_gateways_transactions_table_gateway_id_'.$transaction->transaction_gateway, $transaction_gateway_id, $transaction, $EM_Booking);
							} else {
								echo __('None yet','events');
							}
						?>
					</td>
					<td class="column-gateway">
						<?php
							if(!empty($transaction->transaction_gateway)) {
								echo $transaction->transaction_gateway;
							} else {
								echo __('None yet','events');
							}
						?>
					</td>
					<td class="column-trans-status">
						<?php
							if(!empty($transaction->transaction_status)) {
								echo "<span class='em-label " . str_replace(" ", "-", strtolower($transaction->transaction_status)) . "'>"; 
								echo '<i class="material-symbols-outlined">check_circle</i>';
								_e($transaction->transaction_status, 'events');
								echo "</span>";
							} else {
								echo __('None yet','events');
							}
						?>
					</td>
					<td class="column-trans-note-id">
						<?php
							if(!empty($transaction->transaction_note)) {
								echo esc_html($transaction->transaction_note);
							} else {
								echo __('None','events');
							}
						?>
					</td>
					<td class="column-trans-note-id">
						<?php if( $EM_Booking->can_manage() ): ?>
						<span class="trash"><a class="em-transaction-delete" href="<?php echo add_query_arg(['action'=>'transaction_delete', 'txn_id'=>$transaction->transaction_id, '_wpnonce'=>wp_create_nonce('transaction_delete_'.$transaction->transaction_id.'_'.get_current_user_id())], $_SERVER['REQUEST_URI']); ?>"><?php esc_html_e('Delete','events'); ?></a></span>
						<?php endif; ?>
					</td>
			    </tr>
				<?php
			}
		} else {
			?>
			<tr valign="middle" class="alternate" >
				<td colspan="<?php echo $columns; ?>" scope="row"><?php _e('No Transactions','events'); ?></td>
		    </tr>
			<?php
		}
		return ob_get_clean();
	}
	
	/**
	 * @param mixed $context
	 * @return stdClass|false
	 */
	function get_transactions($context=false) {
		global $wpdb;
		$join = '';
		$conditions = array();
		$table = EM_BOOKINGS_TABLE;
		//we can determine what to search for, based on if certain variables are set.
		if( is_object($context) && (get_class($context)=="EM_Booking" || get_class($context)=="EM_Multiple_Booking" ) && $context->can_manage('manage_bookings','manage_others_bookings') ){
			$booking_condition = "tx.booking_id = ".$context->booking_id;
			
			$conditions[] = $booking_condition;
		}elseif( is_object($context) && get_class($context)=="EM_Event" && $context->can_manage('manage_bookings','manage_others_bookings') ){
			$join = " JOIN $table ON $table.booking_id=tx.booking_id";
			$booking_condition = "event_id = ".$context->event_id;
			
			$conditions[] = $booking_condition;		
		}elseif( is_object($context) && get_class($context)=="EM_Person" ){
			$join = " JOIN $table ON $table.booking_id=tx.booking_id";
			$conditions[] = "person_id = ".$context->ID;			
		}elseif( is_object($context) && get_class($context)=="EM_Ticket" && $context->can_manage('manage_bookings','manage_others_bookings') ){
			$booking_ids = array();
			$ticket = $context;
			foreach( EM_Bookings::get( array('ticket_id' => $ticket->ticket_id, 'array' => 'booking_id') ) as $booking ){
				$booking_ids[] = $booking['booking_id'];
			}
			if( count($booking_ids) > 0 ){
				$conditions[] = "tx.booking_id IN (".implode(',', $booking_ids).")";
			}else{
				return new stdClass();
			}			
		}
		
		//filter by gateway
		if( !empty($this->gateway) ){
			$conditions[] = $wpdb->prepare('transaction_gateway = %s',$this->gateway);
		}
		//build conditions string
		$condition = (!empty($conditions)) ? "WHERE ".implode(' AND ', $conditions):'';
		$offset = ( $this->page > 1 ) ? ($this->page-1)*$this->limit : 0;		
		$sql = $wpdb->prepare( "SELECT SQL_CALC_FOUND_ROWS * FROM ".EM_TRANSACTIONS_TABLE." tx $join $condition ORDER BY transaction_id DESC  LIMIT %d, %d", $offset, $this->limit );
		$return = $wpdb->get_results( $sql );
		$this->total_transactions = $wpdb->get_var( "SELECT FOUND_ROWS();" );
		return $return;
	}	

	
	/*
	 * ----------------------------------------------------------
	 * Booking Table and CSV Export
	 * ----------------------------------------------------------
	 */
	
	function em_bookings_table_rows_col($value, $col, $EM_Booking, $EM_Bookings_Table, $format){
		global $EM_Event;
		if( $col == 'gateway_txn_id' ){
			//check if this isn't a multiple booking, otherwise look for info from main booking
			
			//get latest transaction with an ID
			$old_limit = $this->limit;
			$old_orderby = $this->orderby;
			$old_page = $this->page;
			$this->limit = $this->page = 1;
			$this->orderby = 'booking_date';
			$transactions = $this->get_transactions($EM_Booking);
			if(count($transactions) > 0){
				$value = $transactions[0]->transaction_gateway_id;
			}
			$this->limit = $old_limit;
			$this->orderby = $old_orderby;
			$this->page = $old_page;
		}elseif( $col == 'payment_total' ){
			$value = $EM_Booking->get_total_paid(true);
		}
		return $value;
	}
	
	function em_bookings_table_cols_template($template, $EM_Bookings_Table){
		$template['gateway_txn_id'] = __('Transaction ID','events');
		$template['payment_total'] = __('Total Paid','events');
		return $template;
	}
}
}

/**
 * Checks for any deletions requested 
 */
function emp_transactions_init(){
	global $EM_Gateways_Transactions;
	$EM_Gateways_Transactions = new EM_Gateways_Transactions();
	
	if( !empty($_REQUEST['action']) && $_REQUEST['action'] == 'transaction_delete' && wp_verify_nonce($_REQUEST['_wpnonce'], 'transaction_delete_'.$_REQUEST['txn_id'].'_'.get_current_user_id()) ){
		//get booking from transaction, ensure user can manage it before deleting
		global $wpdb;
		$booking_id = $wpdb->get_var('SELECT booking_id FROM '.EM_TRANSACTIONS_TABLE." WHERE transaction_id='".$_REQUEST['txn_id']."'");
		if( !empty($booking_id) ){
			$EM_Booking = EM_Booking::find($booking_id);
			if( (!empty($EM_Booking->booking_id) && $EM_Booking->can_manage()) || is_super_admin() ){
				//all good, delete it
				$wpdb->query('DELETE FROM '.EM_TRANSACTIONS_TABLE." WHERE transaction_id='".$_REQUEST['txn_id']."'");
				_e('Transaction deleted','events');
				exit();
			}
		}
		_e('Transaction could not be deleted', 'events');
		exit();
	}
}
add_action('init','emp_transactions_init');