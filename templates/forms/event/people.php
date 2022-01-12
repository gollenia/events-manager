<?php
global $EM_Event;
$speaker_id = intval($EM_Event->speaker_id);
$args = array(  
    'post_type' => 'event-speaker',
    'post_status' => 'publish',
    'orderby' => 'title', 
    'order' => 'ASC', 
);
$query = new WP_Query( $args );
$speakers = $query->posts;


?>


<div class="event-form-when" id="em-form-when">
	<div class="components-panel__row">
		<label class="components-base-control__label"><?php _e ( 'Audience', 'events-manager'); ?></label>
		<input class="em-audience-input" type="text" name="event_audience" value="<?php echo $EM_Event->event_attributes['event_audience'] ?? ''; ?>" />
	</div>
	<div class="components-panel__row">
		<label class="components-base-control__label"><?php _e ( 'Speaker', 'events-manager'); ?></label>
		
		<select class="em-speaker-input" name="speaker_id">
        <option value="0" <?php echo ($EM_Event->speaker_id == 0 ? "selected" : "") ?> ><?php _e("Select Speaker", "events-manager") ?></option>
        <?php foreach ($speakers as $speaker) { ?>
            <option value="<?php echo $speaker->ID ?>" <?php if(intval($EM_Event->speaker_id) == $speaker->ID) { echo "selected"; } ?>><?php echo $speaker->post_title ?></option>
        <?php } ?>
        </select>
	</div>

</div>  