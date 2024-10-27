<?php

//download.php

include('class/Appointment.php');

$object = new Appointment;

require 'vendor/autoload.php';

// Include Dompdf's namespace
use Dompdf\Dompdf;

if(isset($_GET["id"]))
{
	$html = '<table border="0" cellpadding="5" cellspacing="5" width="100%">';

	$object->query = "
	SELECT firm_name, firm_address, firm_contact_no, firm_logo 
	FROM admin_table
	";

	$firm_data = $object->get_result();

	foreach($firm_data as $firm_row)
	{
		$html .= '<tr><td align="center">';
		if($firm_row['firm_logo'] != '')
		{
			$html .= '<img src="'.substr($firm_row['firm_logo'], 3).'" /><br />';
		}
		$html .= '<h2 align="center">'.$firm_row['firm_name'].'</h2>
		<p align="center">'.$firm_row['firm_address'].'</p>
		<p align="center"><b>Contact No. - </b>'.$firm_row['firm_contact_no'].'</p></td></tr>
		';
	}

	$html .= "
	<tr><td><hr /></td></tr>
	<tr><td>
	";

	$object->query = "
	SELECT * FROM appointment_table 
	WHERE appointment_id = '".$_GET["id"]."'
	";

	$appointment_data = $object->get_result();

	foreach($appointment_data as $appointment_row)
	{

		$object->query = "
		SELECT * FROM customer_table 
		WHERE customer_id = '".$appointment_row["customer_id"]."'
		";

		$customer_data = $object->get_result();

		$object->query = "
		SELECT * FROM accountant_schedule_table 
		INNER JOIN accountant_table 
		ON accountant_table.accountant_id = accountant_schedule_table.accountant_id 
		WHERE accountant_schedule_table.accountant_schedule_id = '".$appointment_row["accountant_schedule_id"]."'
		";

		$accountant_schedule_data = $object->get_result();
		
		$html .= '
		<h4 align="center">Customer Details</h4>
		<table border="0" cellpadding="5" cellspacing="5" width="100%">';

		foreach($customer_data as $customer_row)
		{
			$html .= '<tr><th width="50%" align="right">Customer Name</th><td>'.$customer_row["customer_first_name"].' '.$customer_row["customer_last_name"].'</td></tr>
			<tr><th width="50%" align="right">Contact No.</th><td>'.$customer_row["customer_phone_no"].'</td></tr>
			<tr><th width="50%" align="right">Address</th><td>'.$customer_row["customer_address"].'</td></tr>';
		}

		$html .= '</table><br /><hr />
		<h4 align="center">Appointment Details</h4>
		<table border="0" cellpadding="5" cellspacing="5" width="100%">
			<tr>
				<th width="50%" align="right">Appointment No.</th>
				<td>'.$appointment_row["appointment_number"].'</td>
			</tr>
		';
		foreach($accountant_schedule_data as $accountant_schedule_row)
		{
			$html .= '
			<tr>
				<th width="50%" align="right">Accountant Name</th>
				<td>'.$accountant_schedule_row["accountant_name"].'</td>
			</tr>
			<tr>
				<th width="50%" align="right">Appointment Date</th>
				<td>'.$accountant_schedule_row["accountant_schedule_date"].'</td>
			</tr>
			<tr>
				<th width="50%" align="right">Appointment Day</th>
				<td>'.$accountant_schedule_row["accountant_schedule_day"].'</td>
			</tr>
				
			';
		}

		$html .= '
			<tr>
				<th width="50%" align="right">Appointment Time</th>
				<td>'.$appointment_row["appointment_time"].'</td>
			</tr>
			<tr>
				<th width="50%" align="right">Reason for Appointment</th>
				<td>'.$appointment_row["reason_for_appointment"].'</td>
			</tr>
			<tr>
				<th width="50%" align="right">Customer come into Firm</th>
				<td>'.$appointment_row["customer_come_into_firm"].'</td>
			</tr>
			<tr>
				<th width="50%" align="right">Accountant Comment</th>
				<td>'.$appointment_row["accountant_comment"].'</td>
			</tr>
		</table>
			';
	}

	$html .= '
			</td>
		</tr>
	</table>';

	// Create a new Dompdf instance
    $dompdf = new Dompdf();

	$dompdf->loadHtml($html, 'UTF-8');
	$dompdf->render();
	ob_end_clean();
	//$pdf->stream($_GET["id"] . '.pdf', array( 'Attachment'=>1 ));
	$dompdf->stream($_GET["id"] . '.pdf', array( 'Attachment'=>false ));
	exit(0);

}

?>