<?php 
/* 
 * Used for both multiple and single tickets. $col_count will always be 1 in single ticket mode, and be a unique number for each ticket starting from 1 
 * This form should have $ticket and $col_count available globally. 
 */
global $col_count;

$ticket = new \Contexis\Events\Tickets\Ticket();

$col_count = absint($col_count); //now we know it's a number
?>
<div class="em-ticket-form">
	<input type="hidden" name="em_tickets[<?php echo $col_count; ?>][ticket_id]" class="ticket_id" value="<?php echo esc_attr($ticket->ticket_id) ?>" />
	<div class="em-ticket-form-main">
		<div class="ticket-name">
			<label title="<?php esc_attr_e('Enter a ticket name.','events-manager'); ?>"><?php esc_html_e('Name','events-manager') ?></label>
			<input type="text" name="em_tickets[<?php echo $col_count; ?>][ticket_name]" value="<?php echo esc_attr($ticket->ticket_name) ?>" class="ticket_name" />
		</div>
		<div class="ticket-description">
			<label><?php esc_html_e('Description','events-manager') ?></label>
			<textarea name="em_tickets[<?php echo $col_count; ?>][ticket_description]" class="ticket_description"><?php echo esc_html(wp_unslash($ticket->ticket_description)) ?></textarea>
		</div>
		<div class="ticket-price"><label><?php esc_html_e('Price','events-manager') ?></label><input type="text" name="em_tickets[<?php echo $col_count; ?>][ticket_price]" class="ticket_price" value="<?php echo esc_attr($ticket->get_price_precise(true)) ?>" /></div>
		
		<div class="ticket-spaces">
			<label title="<?php esc_attr_e('Enter a maximum number of spaces (required).','events-manager'); ?>"><?php esc_html_e('Spaces','events-manager') ?></label>
			<input type="text" name="em_tickets[<?php echo $col_count; ?>][ticket_spaces]" value="<?php echo esc_attr($ticket->ticket_spaces) ?>" class="ticket_spaces" />
		</div>
	</div>
	<div class="em-ticket-form-advanced">
		<div class="ticket-spaces ticket-spaces-min">
			<label title="<?php esc_attr_e('Leave either blank for no upper/lower limit.','events-manager'); ?>"><?php echo esc_html_x('At least','spaces per booking','events-manager');?></label>
			<input type="text" name="em_tickets[<?php echo $col_count; ?>][ticket_min]" value="<?php echo esc_attr($ticket->ticket_min) ?>" class="ticket_min" />
			<?php esc_html_e('spaces per booking', 'events-manager')?>
		</div>
		<div class="ticket-spaces ticket-spaces-max">
			<label title="<?php esc_attr_e('Leave either blank for no upper/lower limit.','events-manager'); ?>"><?php echo esc_html_x('At most','spaces per booking', 'events-manager'); ?></label>
			<input type="text" name="em_tickets[<?php echo $col_count; ?>][ticket_max]" value="<?php echo esc_attr($ticket->ticket_max) ?>" class="ticket_max" />
			<?php esc_html_e('spaces per booking', 'events-manager')?>
		</div>
		<div class="ticket-dates em-time-range em-date-range">
			<div class="ticket-dates-from">
				<label title="<?php esc_attr_e('Add a start or end date (or both) to impose time constraints on ticket availability. Leave either blank for no upper/lower limit.','events-manager'); ?>">
					<?php esc_html_e('Available from','events-manager') ?>
				</label>
				<div class="ticket-dates-from-normal">
					<input type="date" class="em-date-input-loc em-date-start" name="em_tickets[<?php echo $col_count; ?>][ticket_start]" class="em-date-input ticket_start" value="<?php echo ( !empty($ticket->ticket_start) ) ? $ticket->start()->format("Y-m-d"):''; ?>" />
				</div>
				<div class="ticket-dates-from-recurring">
					<input type="text" name="em_tickets[<?php echo $col_count; ?>][ticket_start_recurring_days]" size="3" value="<?php if( !empty($ticket->ticket_meta['recurrences']['start_days']) && is_numeric($ticket->ticket_meta['recurrences']['start_days'])) echo absint($ticket->ticket_meta['recurrences']['start_days']); ?>" />
					<?php esc_html_e('day(s)','events-manager'); ?>
					<select name="em_tickets[<?php echo $col_count; ?>][ticket_start_recurring_when]" class="ticket-dates-from-recurring-when">
						<option value="before" <?php if( isset($ticket->ticket_meta['recurrences']['start_days']) && $ticket->ticket_meta['recurrences']['start_days'] <= 0) echo 'selected="selected"'; ?>><?php echo esc_html(sprintf(_x('%s the event starts','before or after','events-manager'),__('Before','events-manager'))); ?></option>
						<option value="after" <?php if( !empty($ticket->ticket_meta['recurrences']['start_days']) && $ticket->ticket_meta['recurrences']['start_days'] > 0) echo 'selected="selected"'; ?>><?php echo esc_html(sprintf(_x('%s the event starts','before or after','events-manager'),__('After','events-manager'))); ?></option>
					</select>
				</div>
				<?php echo esc_html_x('at', 'time','events-manager'); ?>
				<input class="em-time-input em-time-start" type="time" size="8" maxlength="8" name="em_tickets[<?php echo $col_count; ?>][ticket_start_time]" value="<?php echo ( !empty($ticket->ticket_start) ) ? $ticket->start()->format( "H:i" ):''; ?>" />
			</div>
			<div class="ticket-dates-to">
				<label title="<?php esc_attr_e('Add a start or end date (or both) to impose time constraints on ticket availability. Leave either blank for no upper/lower limit.','events-manager'); ?>">
					<?php esc_html_e('Available until','events-manager') ?>
				</label>
				<div class="ticket-dates-to-normal">
					<input type="date" name="em_tickets[<?php echo $col_count; ?>][ticket_end]" name="ticket_end_pub" class="em-date-input-loc em-date-end" value="<?php echo ( !empty($ticket->ticket_end) ) ? $ticket->end()->format("Y-m-d"):''; ?>"/>
				</div>
				<div class="ticket-dates-to-recurring">
					<input type="text" name="em_tickets[<?php echo $col_count; ?>][ticket_end_recurring_days]" size="3" value="<?php if( isset($ticket->ticket_meta['recurrences']['end_days']) && $ticket->ticket_meta['recurrences']['end_days'] !== false ) echo absint($ticket->ticket_meta['recurrences']['end_days']); ?>" />
					<?php esc_html_e('day(s)','events-manager'); ?>
					<select name="em_tickets[<?php echo $col_count; ?>][ticket_end_recurring_when]" class="ticket-dates-to-recurring-when">
						<option value="before" <?php if( isset($ticket->ticket_meta['recurrences']['end_days']) && $ticket->ticket_meta['recurrences']['end_days'] <= 0) echo 'selected="selected"'; ?>><?php echo esc_html(sprintf(_x('%s the event starts','before or after','events-manager'),__('Before','events-manager'))); ?></option>
						<option value="after" <?php if( !empty($ticket->ticket_meta['recurrences']['end_days']) && $ticket->ticket_meta['recurrences']['end_days'] > 0) echo 'selected="selected"'; ?>><?php echo esc_html(sprintf(_x('%s the event starts','before or after','events-manager'),__('After','events-manager'))); ?></option>
					</select>
				</div>
				<?php echo esc_html_x('at', 'time','events-manager'); ?>
				<input class="em-time-input em-time-end ticket-times-to-normal" type="time" size="8" maxlength="8" name="em_tickets[<?php echo $col_count; ?>][ticket_end_time]" value="<?php echo ( !empty($ticket->ticket_end) ) ? $ticket->end()->format( "H:i" ):''; ?>" />
			</div>
		</div>
		<?php if( count($ticket->get_event()->get_tickets()->tickets) > 1 ): ?>
		<div class="ticket-required">
			<label title="<?php esc_attr_e('If checked every booking must select one or the minimum number of this ticket.','events-manager'); ?>"><?php esc_html_e('Required?','events-manager') ?></label>
			<input type="checkbox" value="1" name="em_tickets[<?php echo $col_count; ?>][ticket_required]" <?php if($ticket->ticket_required) echo 'checked="checked"'; ?> class="ticket_required" />
		</div>
		<?php endif; ?>
		<div class="ticket-type">
			<label><?php esc_html_e('Available for','events-manager') ?></label>
			<select name="em_tickets[<?php echo $col_count; ?>][ticket_type]" class="ticket_type">
				<option value=""><?php _e('Everyone','events-manager'); ?></option>
				<option value="members" <?php if($ticket->ticket_members) echo 'selected="selected"'; ?>><?php esc_html_e('Logged In Users','events-manager'); ?></option>
				<option value="guests" <?php if($ticket->ticket_guests) echo 'selected="selected"'; ?>><?php esc_html_e('Guest Users','events-manager'); ?></option>
			</select>
		</div>
		<div class="ticket-roles" <?php if( !$ticket->ticket_members ): ?>style="display:none;"<?php endif; ?>>
			<label><?php _e('Restrict to','events-manager'); ?></label>
			<div>
				<?php 
				$WP_Roles = new WP_Roles();
				foreach($WP_Roles->roles as $role => $role_data){ /* @var $WP_Role WP_Role */
					?>
					<input type="checkbox" name="em_tickets[<?php echo $col_count; ?>][ticket_members_roles][]" value="<?php echo esc_attr($role); ?>" <?php if( in_array($role, $ticket->ticket_members_roles) ) echo 'checked="checked"' ?> class="ticket_members_roles" /> <?php echo esc_html($role_data['name']); ?><br />
					<?php
				}
				?>
			</div>
		</div>
		<?php do_action('em_ticket_edit_form_fields', $col_count, $ticket); //do not delete, add your extra fields this way, remember to save them too! ?>
	</div>
	<div class="ticket-options">
		
	</div>
</div>	