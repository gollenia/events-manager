<?php
global $EM_Event, $post;

$start_date= $EM_Event->event_start_date ? $EM_Event->start()->getDate() : date('Y-m-d');
$end_date= $EM_Event->event_end_date ? $EM_Event->end()->getDate() : date('Y-m-d');
$unchanged= $EM_Event->event_end_date ? 0 : 1;

?>
<div class="event-form-when" id="em-form-when">
	
	<div class="components-panel__row">
		<label class="components-base-control__label"><?php _e ( 'From ', 'events-manager'); ?></label>
		
		<input class="em-date-input" id="event_start_date" type="date" name="event_start_date" value="<?php echo $start_date ?>" />
	</div>
	<div class="components-panel__row">
		<label class="components-base-control__label"><?php _e ( 'to', 'events-manager'); ?></label>
		
		<input class="em-date-input" data-unchanged="<?php echo $unchanged ?>" id="event_end_date" type="date" name="event_end_date" value="<?php echo $end_date ?>" />
	</div>

	
	<div class="components-panel__row">	
	<span class="em-event-text"><?php _e('Event starts at','events-manager'); ?></span>
	</div>
	<div class="components-panel__row">
	<input class="em-time-start"  type="time" name="event_start_time" value="<?php echo $EM_Event->start()->format("H:i"); ?>" />
	<label class="components-base-control__label"><?php _e('to','events-manager'); ?></label>
	<input class="em-time-end" type="time" name="event_end_time" value="<?php echo $EM_Event->end()->format("H:i"); ?>" />
	</div>
	<div class="components-panel__row">
	<?php _e('All day','events-manager'); ?> <input type="checkbox" class="em-time-all-day" name="event_all_day" value="1" <?php if(!empty($EM_Event->event_all_day)) echo 'checked="checked"'; ?> />
	</div>
	
	
	<span id='event-date-explanation'>
	<?php esc_html_e( 'This event spans every day between the beginning and end date, with start/end times applying to each day.', 'events-manager'); ?>
	</span>
</div>  
<?php if( false && get_option('dbem_recurrence_enabled') && $EM_Event->is_recurrence() ) : //in future, we could enable this and then offer a detach option alongside, which resets the recurrence id and removes the attachment to the recurrence set ?>
<input type="hidden" name="recurrence_id" value="<?php echo $EM_Event->recurrence_id; ?>" />
<?php endif; ?>

<script>

	jQuery(document).ready( function($){
		
		$('#event_start_date').on('change',function(){
			
			let end_date = $('#event_end_date');
			console.log(end_date.data('unchanged'));
			if(end_date.data('unchanged')){
				$('#event_end_date').val($(this).val());
			}
		});
	});
</script>