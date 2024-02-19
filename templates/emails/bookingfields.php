<table >
<?php

$form_fields = EM_Booking_Form::get_form($EM_Booking->event_id)->form_fields;
$form_values = $EM_Booking->meta['booking'] ? array_merge($EM_Booking->meta['registration'], $EM_Booking->meta['booking']) : $EM_Booking->meta['registration'];

foreach($form_fields as $name => $field) {
	
	$value = $form_values[$name];
	if($field['type'] == "html") {
		continue;
	}
	if($field['type'] == "email") {
		$value = "<a href='mailto:$value'>$value</a>";
	}
	if($field['type'] == "checkbox") {
		$value = $value ? __("Yes", "events-manager") : __("No", "events-manager");
	}
	if($field['type'] == "date") {
		$value = date_i18n(get_option('date_format'), strtotime($value));
	}
	echo "<tr>";
	if($name == "info") continue;
	echo "<td><b>" . ($field['label'] ?: $name) . "</b></td>";
	echo "<td>" . $value . "</td>";
	echo "</tr>";
}
?>
</table>