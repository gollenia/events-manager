<?php 
/**
 * Display function for the support page. here we can give links to forums and special upgrade instructions e.g. migration features 
 */
function em_admin_help_page(){
	global $wpdb;
	?>
	<div class="wrap">
		<h1><?php _e('Getting Help for Events Manager','events-manager'); ?></h1>
		<div class="em-docs">
			<h2>Where To Get Help</h2>
			<p>
				This page is only a small portion of the event documentation which is here for quick reference. If you're just starting out, we recommend you visit the following places for further support:
			</p>
			<ol>
				<li>Developers may want to look at the <a href="https://github.com/gollenia/events">GitHub Project page</a> getting started guide.</li>
				<li>The original Events Manager can be found at <a href="https://wp-events-plugin.com/">https://wp-events-plugin.com</a>.</li>
			</ol>
			
			<h2><?php _e('Placeholders for customizing event pages','events-manager'); ?></h2>
			<p><?php echo sprintf( __("In the <a href='%s'>settings page</a>, you'll find various textboxes where you can edit how event information looks, such as for event and location lists. Using the placeholders below, you can choose what information should be displayed.",'events-manager'), EM_ADMIN_URL .'&amp;events-manager-options'); ?></p>
			<a name="event-placeholders"></a>
			<h3 style="margin-top:20px;"><?php _e('Event Related Placeholders','events-manager'); ?></h3>
			<?php echo em_docs_placeholders( array('type'=>'events') ); ?>
			<a name="location-placeholders"></a>
			<h3><?php _e('Location Related Placeholders','events-manager'); ?></h3>
			<?php echo em_docs_placeholders( array('type'=>'locations') ); ?>
			<a name="booking-placeholders"></a>
			<h3><?php _e('Booking Related Placeholders','events-manager'); ?></h3>
			<?php echo em_docs_placeholders( array('type'=>'bookings') ); ?>
		</div>
		
	</div>
	<?php
}
