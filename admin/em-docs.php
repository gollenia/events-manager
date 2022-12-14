<?php

function em_docs_init($force_init = false){
	global $pagenow;
	if( ($pagenow == 'edit.php' && !empty($_GET['page']) && $_GET['page']=='events-manager-help' && class_exists('EM_Event')) || $force_init){
		add_action('wp_head', 'emd_head');
		//Generate the docs
		global $EM_Documentation;
		$EM_Documentation = array(
			'arguments' => array(
				'events' => array(
					'blog' => array( 'desc' => sprintf('Limit search to %s created in a specific blog id (MultiSite only)','events')),
					'bookings' => array( 'desc'=> 'Include only events with bookings enabled. Use \'user\' to show events a logged in user has booked.'.'1 = yes, 0 = no'),
					'category' => array( 'desc'=> str_replace('%s','categories', 'Supply a single id, slug or comma-separated ids or slugs (e.g. "1,%s-slug,3") to limit the search to events in any of these %s. You can also use negative numbers and slugs to exclude specific %s (e.g. -1,-exclude-%s,-3). If you mix inclusions and exclusions, all events with included %s AND without excluded %s will be shown. You can also use &amp; to separate ids and slugs, in which case events must contain (or not contain) both %s to be shown.'), 'default'=>0),
					'event' => array( 'desc'=> sprintf('Supply a single id or comma-separated ids (e.g. "1,2,3") to limit the search to %s with the %s.','events', 'event_id(s)'), 'default'=>0),
					'group' => array( 'desc' => 'Limit search to events belonging to a specific group id (BuddyPress only). Using \'my\' will show events belonging to groups the logged in user is a member of.'),
					'near' => array('desc'=>'Accepts a comma-separated coordinates (e.g. 1,1) value, which searches for events or locations located near this coordinate.'),
					'near_unit' => array('desc'=>'The distance unit used when a near attribute is provided.', 'default'=>'mi', 'args'=>'mi or km'),
					'near_distance' => array('desc'=>'The radius distance when searching with the near attribute. near_unit will determine whether this is in miles or kilometers.'),
				    'owner' => array('desc'=> 'Limits returned results to a specific owner, identified by their user id (e.g. list events or locations owned by user)', 'default'=>0),
					'post_id' => array( 'desc' => sprintf('Supply a single id or comma-separated ids (e.g. "1,2,3") to limit the search to %s with the %s.','events', 'post_id(s)')),
					'private' => array( 'desc' => sprintf('Display private %s within your list?','events'), 'args' => '1 = yes, 0 = no', 'default' => 'If user can view private events, 1, otherwise 0.'),
					'private_only' => array( 'desc' =>sprintf('Display only private %s ?','events'), 'args' => '1 = yes, 0 = no', 'default' => '0'),
					'recurrences' => array( 'desc'=> 'Show only recurrences if set to 1 or non-recurrences if set to 0, shows all events if not used.'),
					'recurrence' => array( 'desc'=> 'If set to the event id of the recurring event, this will show only events this event recurrences.', 'default'=>0),
					'recurring' => array( 'desc'=> 'If set to 1, will only show recurring event templates. Only useful if you know what you\'re doing, use recurrence or recurrences if you want to filter event recurrences.', 'default'=>0),
					'scope' => array( 'desc'=> 'Choose the time frame of events to show. Additionally you can supply dates (in format of YYYY-MM-DD), either single for events on a specific date or two dates separated by a comma (e.g. 2010-12-25,2010-12-31) for events ocurring between these dates.', 'default'=>'future', 'args'=>array("future", "past", "today", "tomorrow", "month", "next-month", "1-months", "2-months", "3-months", "6-months", "12-months","all")),
					'search' => array( 'desc'=> 'Do a search for this string within event name, details and location address.' ),
					'status' => array( 'desc' => sprintf('Limit search to %s with a spefic status (1 is active, 0 is pending approval)','events'), 'default'=>1),
					'tag' => array( 'desc'=> str_replace('%s', 'tags', 'Supply a single id, slug or comma-separated ids or slugs (e.g. "1,%s-slug,3") to limit the search to events in any of these %s. You can also use negative numbers and slugs to exclude specific %s (e.g. -1,-exclude-%s,-3). If you mix inclusions and exclusions, all events with included %s AND without excluded %s will be shown. You can also use &amp; to separate ids and slugs, in which case events must contain (or not contain) both %s to be shown.'), 'default'=>0),
					'year' => array( 'desc'=> 'If set to a year (e.g. 2010) only events that start or end during this year/month will be returned. Does not work as intended if used with scope.', 'default'=>''),
					'has_location' => array( 'desc'=> 'When set to true, only events WITH an assigned location will be shown, has priority over no_location.', 'default'=>''),
					'no_location' => array( 'desc'=> 'When set to true, only events WITHOUT an assigned location will be shown.', 'default'=>''),
					'groupby' => array( 'desc'=> 'Show one event for each unique value of the provide field name, along with events contaning no defined value for that field. See our <a href="http://wp-events-plugin.com/documentation/event-search-attributes/event-location-grouping-ordering/">grouping documentation</a> for more information.', 'default'=>0, 'args'=>'Database field name from the wp_em_events or wp_em_locations tables, e.g. <code>event_name</code> or <code>location_name</code>', 'since'=>'5.8'),
					'groupby_orderby' => array( 'desc'=> 'When groupby is defined, the fields defined here will determine the sorting of events in each group. See our <a href="http://wp-events-plugin.com/documentation/event-search-attributes/event-location-grouping-ordering/">grouping documentation</a> for more information.', 'default'=>'event_start_date,event_start_time', 'args'=>'Database fields (comma-seperated) in the wp_em_events and wp_em_locations tables, e.g. <code>event_start_date,event_start_time</code> or <code>location_name</code>', 'since'=>'5.8'),
					'groupby_order' => array( 'desc'=> 'Indicates the alphabeitcal/numerical/date order of the sorting in groupby_orderby. Choose between ASC (ascending) and DESC (descending), case insensitive.', 'default'=>'ASC', 'since'=>'5.8'),
				),
				'locations' => array(
					'blog' => array( 'desc' => sprintf('Limit search to %s created in a specific blog id (MultiSite only)','locations')),				    
					'country' => array( 'desc'=> sprintf('Search for %s in this %s (no partial matches, case sensitive).','locations','Country'), 'default' => 'none', 'args'=>'Use two-character country codes as defined in <a href="http://countrycode.org/">countrycode.org</a>, can be comma-separated e.g. US,GB,ES'),
					'eventful' => array( 'desc'=> 'If set to 1 will only show locations that have at least one event occurring during the scope.', 'default' => 0),
					'eventless' => array( 'desc'=> 'If set to 1 will only show locations that have no events occurring during the scope.', 'default' => 0),
					'location' => array( 'desc'=> sprintf('Supply a single id or comma-separated ids (e.g. "1,2,3") to limit the search to %s with the %s.','locations', 'location_id(s)'), 'default'=>0),
					'near' => array('desc'=>'Accepts a comma-separated coordinates (e.g. 1,1) value, which searches for events or locations located near this coordinate.'),
					'near_unit' => array('desc'=>'The distance unit used when a near attribute is provided.', 'default'=>'mi', 'args'=>'mi or km'),
					'near_distance' => array('desc'=>'The radius distance when searching with the near attribute. near_unit will determine whether this is in miles or kilometers.'), 
				    'owner' => array('desc'=> 'Limits returned results to a specific owner, identified by their user id (e.g. list events or locations owned by user)', 'default'=>0),
					'post_id' => array( 'desc' => sprintf('Supply a single id or comma-separated ids (e.g. "1,2,3") to limit the search to %s with the %s.','locations', 'post_id(s)')),
					'postcode' => array( 'desc'=> sprintf('Search for %s in this %s (no partial matches, case sensitive).','locations','Postcode'), 'default' => 'none'),
				    'private' => array( 'desc' => sprintf('Display private %s within your list?','locations'), 'args' => '1 = yes, 0 = no', 'default' => 'If user can view private locations, 1, otherwise 0.'),
					'private_only' => array( 'desc' =>sprintf('Display only private %s ?','locations'), 'args' => '1 = yes, 0 = no', 'default' => '0'),
					'region' => array( 'desc'=> sprintf('Search for %s in this %s (no partial matches, case sensitive).','locations','Region'), 'default' => 'none'),
					'scope' => array( 'default' => 'all'),
					'state' => array( 'desc'=> sprintf('Search for %s in this %s (no partial matches, case sensitive).','locations','State'), 'default' => 'none'),
					'status' => array( 'desc' => sprintf('Limit search to %s with a spefic status (1 is active, 0 is pending approval)','locations'), 'default'=>1),
					'town' => array( 'desc'=> sprintf('Search for %s in this %s (no partial matches, case sensitive).','locations','Town'), 'default' => 'none'),
					'groupby' => array( 'desc'=> 'Show one location for each unique value of the provide field name, along with locations contaning no defined value for that field. See our <a href="http://wp-events-plugin.com/documentation/event-search-attributes/event-location-grouping-ordering/">grouping documentation</a> for more information.', 'default'=>0, 'args'=>'Database field name from the wp_em_events or wp_em_locations tables, e.g. <code>event_name</code> or <code>location_name</code>', 'since'=>'5.8'),
					'groupby_orderby' => array( 'desc'=> 'When groupby is defined, the fields defined here will determine the sorting of events in each group. See our <a href="http://wp-events-plugin.com/documentation/event-search-attributes/event-location-grouping-ordering/">grouping documentation</a> for more information.', 'default'=>'event_start_date,event_start_time', 'args'=>'Database fields (comma-seperated) in the wp_em_events and wp_em_locations tables, e.g. <code>location_country,location_town</code> or <code>location_name</code>', 'since'=>'5.8'),
					'groupby_order' => array( 'desc'=> 'Indicates the alphabeitcal/numerical/date order of the sorting in groupby_orderby. Choose between ASC (ascending) and DESC (descending), case insensitive.', 'default'=>'ASC', 'since'=>'5.8'),
				),
				'categories' => array(
					'' => array( 'desc' => 'See the <a href="http://codex.wordpress.org/Function_Reference/get_terms">WordPress get_terms() Codex</a> for a list of possible search attributes/arguments.'),
				),
				'tags' => array(
					'' => array( 'desc' => 'See the <a href="http://codex.wordpress.org/Function_Reference/get_terms">WordPress get_terms() Codex</a> for a list of possible search attributes/arguments.'),
				),
				'calendar' => array(
					'full' => array( 'desc'=> 'If set to 1 it will display a full calendar that shows event names.', 'default' => 0),
					'long_events' => array( 'desc'=> 'If set to 1, will show events that last longer than a day.', 'default' => 0),
				),
				//The object is commonly shared by all, so entries above overwrite entries here
				'general' => array(
					'array' => array( 'desc'=> 'If you supply this as an argument, the returned data will be in an array, not an object (only useful wen using PHP, not shortcodes)', 'default'=>0),
					'format_header' => array( 'desc'=> sprintf('If you are displaying lists (e.g. listing events), you can supply the %s html and placeholders here.','header'), 'default'=> 'If the <em>format</em> attribute is not provided, the relevant default format will be taken from the settings page.'),
					'format' => array( 'desc'=> 'If you are displaying some information with the shortcode or function (e.g. listing events), you can supply the html and placeholders here. When providing a custom format value, format_header and format_footer will be blank if not also provided.', 'default'=> 'The relevant default format will be taken from the settings page.'),
					'format_footer' => array( 'desc'=> sprintf('If you are displaying lists (e.g. listing events), you can supply the %s html and placeholders here.','footer'), 'default'=> 'If the <em>format</em> attribute is not provided, the relevant default format will be taken from the settings page.'), 
					'limit' => array( 'desc'=> 'Limits the amount of values returned to this number.', 'default'=>'0 (no limit)'),
					'offset' => array( 'desc'=> 'For example, if you have ten results, if you set this to 5, only the last 5 results will be returned. A limit higher than 0 is required for offsets to work.', 'default'=>0), 
					'order' => array( 'desc'=> 'Indicates the alphabeitcal/numerical order of the lists. Choose between ASC (ascending) and DESC (descending).', 'default'=>'ASC'),
					'orderby' => array( 'desc'=> 'Choose what fields to order your results by. You can supply a single field or multiple comma-separated fields (e.g. "event_start_date,event_name").', 'default'=>0, 'args'=>'Database table fields, e.g. <code>event_name</code> or <code>location_name</code>'),
					'pagination' => array('desc'=> 'When using a function or shortcode that outputs items (e.g. [events_list] for events, [locations_list] for locations), if the number of items supercede the limit of items to show, setting this to 1 will show page links under the list.', 'default'=>0),
					'page' => array('desc' => 'Which page shall be shown. Requires pagination to be set to 1 and will override the offset value if also provided.'),
					'page_queryvar' => array('desc'=>'The default parameter name used to figure out the page number to show is pno, provide a unique name if you want to display two lists on the same page with independent pagination.'),
				)
			),
			'placeholders' => array(
				'events' => array(
					'Event Details' => array(
						'placeholders' => array(
							'#_EVENTID' => array( 'desc' => 'Shows the event ID number in the wp_em_events table.' ),
							'#_EVENTPOSTID' => array( 'desc' => 'Shows the event corresponding Post ID in the wp_posts table.' ),
							'#_EVENTNAME' => array( 'desc' => 'Displays the name of the event.' ),
							'#_EVENTEXCERPT' => array( 'desc' => 'If an excerpt has been added to the event, it will be used. If you added a <a href="http://en.support.wordpress.com/splitting-content/more-tag/">more tag</a> to your event description, only the content before this tag will show.' ),
							'#_EVENTEXCERPT{words,...}' => array( 'desc' => 'If an excerpt has not been added to the event you can use this format <code>#_EVENTEXCERPT{10,...}</code>, where 10 is the number of words to show and ... is what is used at the cut-off point.' ),
							'#_EVENTIMAGE' => array( 'desc' => 'Shows the event image, if available.' ),
							'#_EVENTIMAGE{x,y}' => array( 'desc' => 'Shows the event image thumbnail, x and y are width and height respectively, both being numbers e.g. <code>#_EVENTIMAGE{100,100}</code>. If 0 is used for either width or height, the corresponding dimension will be proportionally sized' ),
							'#_EVENTCATEGORIES' => array( 'desc' => 'Shows a list of category links this event belongs to.' ),
							'#_EVENTTAGS' => array( 'desc' => 'Shows a list of tag links this event belongs to.' ),
						)
					),
					'Links/URLs' => array(
						'placeholders' => array(
							'#_EVENTURL' => array( 'desc' => 'Simply prints the event URL. You can use this placeholder to build your own customised links.' ),
							'#_EVENTLINK' => array( 'desc' => 'Displays the event name with a link to the event page.' ),
						)
					),
					'Bookings' => array(
						'desc' => 'These placeholders will only show if bookings are enabled for the given event and in the events manager settings page. Spaces placeholders will default to 0',
						'placeholders' => array(
							'#_BOOKINGFIELDS' => array( 'desc' => 'Show all booking fields in a table.' ),
							'#_BOOKINGFIELD' => array( 'desc' => 'Single booking field' ),
							'#_AVAILABLESPACES' => array( 'desc' => 'Shows available spaces for the event.' ),
							'#_BOOKEDSPACES' => array( 'desc' => 'Shows the amount of currently booked spaces for the event.' ),
							'#_PENDINGSPACES' => array( 'desc' => 'Shows the amount of pending spaces for the event.' ),
							'#_SPACES' => array( 'desc' => 'Shows the total spaces for the event.' ),
							'#_BOOKINGSURL' => array( 'desc' => 'Shows the url to the admin, front-end or buddypress (if activated) bookings management page for this event. Only shown if user is logged in and able to manage bookings.' ),
							'#_BOOKINGSLINK' => array( 'desc' => 'Shows a link to the admin, front-end or buddypress (if activated) bookings management page for this event. Only shown if user is logged in and able to manage bookings.' ),
						)
					),
					'Contact Details' => array(
						'desc' => 'The values here are taken from the chosen contact for the specific event, or the default contact in the settings page.',
						'placeholders' => array(
							'#_CONTACTNAME' => array( 'desc' => 'Name of the contact person for this event (as shown in the dropdown when adding an event).' ),
							'#_CONTACTUSERNAME' => array( 'desc' => 'Contact person\'s username.' ),
							'#_CONTACTEMAIL' => array( 'desc' => 'E-mail of the contact person for this event.' ),
							'#_CONTACTAVATAR' => array( 'desc' => 'Contact person\'s avatar.' )
						)
					),
				),
				'locations' => array(
					'Location Details' => array(
						'desc' => '',
						'placeholders' => array(
							'#_LOCATIONID' => array( 'desc' => 'Shows the event ID number in the wp_em_locations table.' ),
							'#_LOCATIONNAME' => array( 'desc' => 'Displays the location name.' ),
							'#_LOCATIONADDRESS' => array( 'desc' => 'Displays the address.' ),
							'#_LOCATIONTOWN' => array( 'desc' => 'Displays the town.' ),
							'#_LOCATIONSTATE' => array( 'desc' => 'Displays the state/county.' ),
							'#_LOCATIONPOSTCODE' => array( 'desc' => 'Displays the postcode.' ),
							'#_LOCATIONREGION' => array( 'desc' => 'Displays the region.' ),
							'#_LOCATIONCOUNTRY' => array( 'desc' => 'Displays the country.' ),
							'#_LOCATIONIMAGE' => array( 'desc' => 'Shows the location image.' ),
						)
					),
					'Links' => array(
						'placeholders' => array(
							'#_LOCATIONURL' => array( 'desc' => 'Simply prints the location URL. You can use this placeholder to build your own customised links.' ),
							'#_LOCATIONLINK' => array( 'desc' => 'Displays the location name with a link to the location page.' ),
							'#_EDITLOCATIONLINK' => array( 'desc' => 'Inserts a link to the admin  or buddypress (if activated) edit location page, only if a user is logged in and is allowed to edit the location.' ),
							'#_EDITLOCATIONURL' => array( 'desc' => 'Inserts a url to the admin or buddypress (if activated) edit location page, only if a user is logged in and is allowed to edit the location.' )
						)
					)
				),
				'bookings' => array(
					'Individual Booking Information' => array(
						'desc' => 'When a specific booking is displayed (on screen and on email), you can use these placeholders to show specific information about the booking. Event and Location placeholders are also available in these cases.',
						'placeholders' => array(
							'#_BOOKINGID' => array( 'desc' => 'The unique ID of this booking, useful if you are making your own customizations to this plugin.' ),
							'#_BOOKINGNAME' => array( 'desc' => 'Name of person who made the booking.' ),
							'#_BOOKINGEMAIL' => array( 'desc' => 'Email of person who made the booking.' ),
							'#_BOOKINGTICKETNAME' => array( 'desc' => 'Name of the ticket booked. Useful in single ticket mode, if multiple tickets are booked a random ticket is used.' ),
							'#_BOOKINGTICKETDESCRIPTION' => array( 'desc' => 'Description of the ticket booked. Useful in single ticket mode, if multiple tickets are booked a random ticket is used.' ),
							'#_BOOKINGFIELD{field_slug}' => array( 'desc' => 'Show the value of a single booking field' ),
							'#_BOOKINGATTENDEES' => array('desc' => sprintf('(<a href="%s">pro only</a>) Generates a list of attendee information displaying the filled in form data for each attendee (requires individual attendee forms enabled for the event). This list is split by ticket type, then by individual attendee.','http://wp-events-plugin.com/features/')), //coupons too!
							'#_BOOKINGADMINURL' => array( 'desc' => 'URL for admins to view and manage the booking. This should only be used on admin-specific email templates.' ),
							'#_BOOKINGADMINLINK' => array( 'desc' => 'HTML link for admins to view and manage the booking. This should only be used on admin-specific email templates.' ),
						)
					),
					'Pricing Information' => array(
						'desc' => '',
						'placeholders' => array(
							'#_PRICE' => array( 'desc' => 'Displays booking total price (tax inclusion depends on your booking settings).' ),
							
							
						)
					),
					'Ticket Information' => array(
						'desc' => '',
						'placeholders' => array(
							'#_BOOKINGTICKETS' => array( 'desc' => 'Shows a breakdown of tickets and pricing, defined in the <code>emails/bookingtickets.php</code> template. (See <a href="http://wp-events-plugin.com/documentation/using-template-files/">Using Template Files</a> for more information)' ),
							'#_BOOKINGTICKETDESCRIPTION' => array( 'desc' => 'Shows the description of the first ticket booked (useful in single ticket mode/events).' ),
							'#_BOOKINGTICKETPRICE' => array( 'desc' => 'Shows the price of the first ticket booked, tax inclusion depending on your booking settings (useful in single ticket mode/events).' ),
							'#_BOOKINGTICKETTAX' => array( 'desc' => 'Shows the tax of the first ticket booked (useful in single ticket mode/events).' ),
							'#_BOOKINGTICKETPRICEWITHTAX' => array( 'desc' => 'Shows the price including tax of the first ticket booked (useful in single ticket mode/events).' ),
							'#_BOOKINGTICKETPRICEWITHOUTTAX' => array( 'desc' => 'Shows the price excluding tax of the first ticket booked (useful in single ticket mode/events).' ),
						)
					),

					'Gateway-Specific Information' => array(
						'desc' => 'Information pertaining to speicifc gateways. '. sprintf('Requires <a href="%s">Events Manager Pro</a>','http://wp-events-plugin.com/features/'),
						'placeholders' => array(
							'#_BOOKINGTXNID' => array( 'desc' => '<em>Online Payments Only</em> - Prints the transaction ID of this booking if available.' ),
							'#_IBAN' => array( 'desc' => __('Shows the IBAN number for Offline Payments.', 'em-pro') ),
							'#_BENEFICIARY' => array( 'desc' => __('Displays the beneficiary name.', 'em-pro') ),
							'#_REFERENCE' => array( 'desc' => __('Payment reference (auto generated)', 'em-pro') ),
							'#_BANK' => array( 'desc' => __('Name of the Bank', 'em-pro') ),
							'#_PAYMENTDEADLINE' => array( 'desc' => __('Show the date until the payment has to be done', 'em-pro') ),
						)
					),
					'Coupon Information' => array(
						'desc' => 'When a booking has been made with a coupon, you can display coupon information using these placeholders. If no coupon is used, nothing will be shown. '.sprintf('Requires <a href="%s">Events Manager Pro</a>','http://wp-events-plugin.com/features/'),
						'placeholders' => array(
							'#_BOOKINGCOUPON' => array('desc' => 'Displays the coupon code followed by the amount/percentage discounted.'),
							'#_BOOKINGCOUPONCODE' => array('desc' => 'Displays the coupon code used.'),
							'#_BOOKINGCOUPONNAME' => array('desc' => 'Displays the name given to this coupon.'),
							'#_BOOKINGCOUPONDISCOUNT' => array('desc' => 'Displays amount/percentage discounted (e.g. 25% Off).'),
							'#_BOOKINGCOUPONDESCRIPTION' => array('desc' => 'Displays the coupon description.'),
						)
					),
				),
			),
			//TODO add capabilites explanations
			'capabilities' => array()
		);
		$EM_Documentation = apply_filters('em_documentation', $EM_Documentation);
	}
}
add_action('init', 'em_docs_init');

function em_docs_placeholders($atts){
	ob_start();
	?>
	<div class="em-docs">
		<?php
		global $EM_Documentation;
		$type = $atts['type'];
		$data = $EM_Documentation['placeholders'][$type];
		foreach($data as $sectionTitle => $details) : ?>
			<div>
				<h3><?php echo $sectionTitle; ?></h3>
				<?php if( !empty($details['desc']) ): ?>
				<p><?php echo $details['desc']; ?></p>
				<?php endif; ?>
				<dl>
					<?php foreach($details['placeholders'] as $placeholder => $desc ): ?>
					<dt><b><?php echo $placeholder; ?></b></dt>
					<dd><?php echo $desc['desc']; ?></dd>
					<?php endforeach; ?>
				</dl>
			</div>
		<?php endforeach; ?>
	</div>
	<?php
	return ob_get_clean();
}
?>