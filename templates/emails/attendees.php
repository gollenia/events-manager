<?php
$tickets = $EM_Booking->meta['attendees'];
$ticket_array = [];
$form_fields = EM_Attendees_Form::get_form($EM_Booking->event_id)->form_fields;

$data = $EM_Booking->get_attendees();

?>

<table>
	<tr>
		<?php 
			echo "<th>" . "Ticket" . "</th>";
			foreach($form_fields as $name => $field) {
				if($name == "info") continue;
				echo "<th>" . $field['label'] . "</th>";
			}
			echo "<th>" . "Price" . "</th>";
		?>
	</tr><?php
	foreach($data as $ticket) {

		$ticket_data = new EM_Ticket($ticket["ticket_id"]);
		echo "<tr>" ;
		echo "<td>" . $ticket_data->ticket_name . "</td>";
		
		
		foreach($form_fields as $name => $field) {
			if($name == "info") continue;
			echo "<td>" . $ticket['fields'][$name] . "</td>";
			
		}
		echo "<td>" . $ticket_data->ticket_price . "</td>";
		echo "</tr>";
	}
	?>
</table>