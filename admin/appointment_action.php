<?php

//appointment_action.php

include('../class/Appointment.php');

$object = new Appointment;

if(isset($_POST["action"]))
{
	if($_POST["action"] == 'fetch')
	{
		$output = array();

		if($_SESSION['type'] == 'Admin')
		{
			$order_column = array('appointment_table.appointment_number', 'customer_table.customer_first_name', 'accountant_table.accountant_name', 'accountant_schedule_table.accountant_schedule_date', 'appointment_table.appointment_time', 'accountant_schedule_table.accountant_schedule_day', 'appointment_table.status');
			$main_query = "
			SELECT * FROM appointment_table  
			INNER JOIN accountant_table 
			ON accountant_table.accountant_id = appointment_table.accountant_id 
			INNER JOIN accountant_schedule_table 
			ON accountant_schedule_table.accountant_schedule_id = appointment_table.accountant_schedule_id 
			INNER JOIN customer_table 
			ON customer_table.customer_id = appointment_table.customer_id 
			";

			$search_query = '';

			if($_POST["is_date_search"] == "yes")
			{
			 	$search_query .= 'WHERE accountant_schedule_table.accountant_schedule_date BETWEEN "'.$_POST["start_date"].'" AND "'.$_POST["end_date"].'" AND (';
			}
			else
			{
				$search_query .= 'WHERE ';
			}

			if(isset($_POST["search"]["value"]))
			{
				$search_query .= 'appointment_table.appointment_number LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR customer_table.customer_first_name LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR customer_table.customer_last_name LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR accountant_table.accountant_name LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR accountant_schedule_table.accountant_schedule_date LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR appointment_table.appointment_time LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR accountant_schedule_table.accountant_schedule_day LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR appointment_table.status LIKE "%'.$_POST["search"]["value"].'%" ';
			}
			if($_POST["is_date_search"] == "yes")
			{
				$search_query .= ') ';
			}
			else
			{
				$search_query .= '';
			}
		}
		else
		{
			$order_column = array('appointment_table.appointment_number', 'customer_table.customer_first_name', 'accountant_schedule_table.accountant_schedule_date', 'appointment_table.appointment_time', 'accountant_schedule_table.accountant_schedule_day', 'appointment_table.status');

			$main_query = "
			SELECT * FROM appointment_table 
			INNER JOIN accountant_schedule_table 
			ON accountant_schedule_table.accountant_schedule_id = appointment_table.accountant_schedule_id 
			INNER JOIN customer_table 
			ON customer_table.customer_id = appointment_table.customer_id 
			";

			$search_query = '
			WHERE appointment_table.accountant_id = "'.$_SESSION["admin_id"].'" 
			';

			if($_POST["is_date_search"] == "yes")
			{
			 	$search_query .= 'AND accountant_schedule_table.accountant_schedule_date BETWEEN "'.$_POST["start_date"].'" AND "'.$_POST["end_date"].'" ';
			}
			else
			{
				$search_query .= '';
			}

			if(isset($_POST["search"]["value"]))
			{
				$search_query .= 'AND (appointment_table.appointment_number LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR customer_table.customer_first_name LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR customer_table.customer_last_name LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR accountant_schedule_table.accountant_schedule_date LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR appointment_table.appointment_time LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR accountant_schedule_table.accountant_schedule_day LIKE "%'.$_POST["search"]["value"].'%" ';
				$search_query .= 'OR appointment_table.status LIKE "%'.$_POST["search"]["value"].'%") ';
			}
		}

		if(isset($_POST["order"]))
		{
			$order_query = 'ORDER BY '.$order_column[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' ';
		}
		else
		{
			$order_query = 'ORDER BY appointment_table.appointment_id DESC ';
		}

		$limit_query = '';

		if($_POST["length"] != -1)
		{
			$limit_query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
		}

		$object->query = $main_query . $search_query . $order_query;

		$object->execute();

		$filtered_rows = $object->row_count();

		$object->query .= $limit_query;

		$result = $object->get_result();

		$object->query = $main_query . $search_query;

		$object->execute();

		$total_rows = $object->row_count();

		$data = array();

		foreach($result as $row)
		{
			$sub_array = array();

			$sub_array[] = $row["appointment_number"];

			$sub_array[] = $row["customer_first_name"] . ' ' . $row["customer_last_name"];

			if($_SESSION['type'] == 'Admin')
			{
				$sub_array[] = $row["accountant_name"];
			}
			$sub_array[] = $row["accountant_schedule_date"];

			$sub_array[] = $row["appointment_time"];

			$sub_array[] = $row["accountant_schedule_day"];

			$status = '';

			if($row["status"] == 'Booked')
			{
				$status = '<span class="badge bg-warning text-dark">' . $row["status"] . '</span>';
			}

			if($row["status"] == 'In Process')
			{
				$status = '<span class="badge bg-primary">' . $row["status"] . '</span>';
			}

			if($row["status"] == 'Completed')
			{
				$status = '<span class="badge bg-success">' . $row["status"] . '</span>';
			}

			if($row["status"] == 'Cancel')
			{
				$status = '<span class="badge bg-danger">' . $row["status"] . '</span>';
			}

			$sub_array[] = $status;

			$sub_array[] = '
			<div align="center">
			<button type="button" name="view_button" class="btn btn-info btn-circle btn-sm view_button" data-id="'.$row["appointment_id"].'"><i class="fas fa-eye"></i></button>
			</div>
			';
			$data[] = $sub_array;
		}

		$output = array(
			"draw"    			=> 	intval($_POST["draw"]),
			"recordsTotal"  	=>  $total_rows,
			"recordsFiltered" 	=> 	$filtered_rows,
			"data"    			=> 	$data
		);
			
		echo json_encode($output);

	}

	if($_POST["action"] == 'fetch_single')
	{
		$object->query = "
		SELECT * FROM appointment_table 
		WHERE appointment_id = '".$_POST["appointment_id"]."'
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

			$html = '
			<h4 class="text-center">Customer Details</h4>
			<table class="table">
			';

			foreach($customer_data as $customer_row)
			{
				$html .= '
				<tr>
					<th width="40%" class="text-right">Customer Name</th>
					<td>'.$customer_row["customer_first_name"].' '.$customer_row["customer_last_name"].'</td>
				</tr>
				<tr>
					<th width="40%" class="text-right">Contact No.</th>
					<td>'.$customer_row["customer_phone_no"].'</td>
				</tr>
				<tr>
					<th width="40%" class="text-right">Address</th>
					<td>'.$customer_row["customer_address"].'</td>
				</tr>
				';
			}

			$html .= '
			</table>
			<hr />
			<h4 class="text-center">Appointment Details</h4>
			<table class="table">
				<tr>
					<th width="40%" class="text-right">Appointment No.</th>
					<td>'.$appointment_row["appointment_number"].'</td>
				</tr>
			';
			foreach($accountant_schedule_data as $accountant_schedule_row)
			{
				$html .= '
				<tr>
					<th width="40%" class="text-right">Accountant Name</th>
					<td>'.$accountant_schedule_row["accountant_name"].'</td>
				</tr>
				<tr>
					<th width="40%" class="text-right">Appointment Date</th>
					<td>'.$accountant_schedule_row["accountant_schedule_date"].'</td>
				</tr>
				<tr>
					<th width="40%" class="text-right">Appointment Day</th>
					<td>'.$accountant_schedule_row["accountant_schedule_day"].'</td>
				</tr>
				
				';
			}

			$html .= '
				<tr>
					<th width="40%" class="text-right">Appointment Time</th>
					<td>'.$appointment_row["appointment_time"].'</td>
				</tr>
				<tr>
					<th width="40%" class="text-right">Reason for Appointment</th>
					<td>'.$appointment_row["reason_for_appointment"].'</td>
				</tr>
			';

			if($appointment_row["status"] != 'Cancel')
			{
				if($_SESSION['type'] == 'Admin')
				{
					if($appointment_row['customer_come_into_firm'] == 'Yes')
					{
						if($appointment_row["status"] == 'Completed')
						{
							$html .= '
								<tr>
									<th width="40%" class="text-right">Customer come into Hostpital</th>
									<td>Yes</td>
								</tr>
								<tr>
									<th width="40%" class="text-right">Accountant Comment</th>
									<td>'.$appointment_row["accountant_comment"].'</td>
								</tr>
							';
						}
						else
						{
							$html .= '
								<tr>
									<th width="40%" class="text-right">Customer come into Hostpital</th>
									<td>
										<select name="customer_come_into_firm" id="customer_come_into_firm" class="form-control" required>
											<option value="">Select</option>
											<option value="Yes" selected>Yes</option>
										</select>
									</td>
								</tr
							';
						}
					}
					else
					{
						$html .= '
							<tr>
								<th width="40%" class="text-right">Customer come into Firm</th>
								<td>
									<select name="customer_come_into_firm" id="customer_come_into_firm" class="form-control" required>
										<option value="">Select</option>
										<option value="Yes">Yes</option>
									</select>
								</td>
							</tr
						';
					}
				}

				if($_SESSION['type'] == 'Accountant')
				{
					if($appointment_row["customer_come_into_firm"] == 'Yes')
					{
						if($appointment_row["status"] == 'Completed')
						{
							$html .= '
								<tr>
									<th width="40%" class="text-right">Accountant Comment</th>
									<td>
										<textarea name="accountant_comment" id="accountant_comment" class="form-control" rows="8" required>'.$appointment_row["accountant_comment"].'</textarea>
									</td>
								</tr
							';
						}
						else
						{
							$html .= '
								<tr>
									<th width="40%" class="text-right">Accountant Comment</th>
									<td>
										<textarea name="accountant_comment" id="accountant_comment" class="form-control" rows="8" required></textarea>
									</td>
								</tr
							';
						}
					}
				}
			
			}

			$html .= '
			</table>
			';
		}

		echo $html;
	}

	if($_POST['action'] == 'change_appointment_status')
	{
		if($_SESSION['type'] == 'Admin')
		{
			$data = array(
				':status'							=>	'In Process',
				':customer_come_into_firm'		=>	'Yes',
				':appointment_id'					=>	$_POST['hidden_appointment_id']
			);

			$object->query = "
			UPDATE appointment_table 
			SET status = :status, 
			customer_come_into_firm = :customer_come_into_firm 
			WHERE appointment_id = :appointment_id
			";

			$object->execute($data);

			echo '<div class="alert alert-success">Appointment Status change to In Process</div>';
		}

		if($_SESSION['type'] == 'Accountant')
		{
			if(isset($_POST['accountant_comment']))
			{
				$data = array(
					':status'							=>	'Completed',
					':accountant_comment'					=>	$_POST['accountant_comment'],
					':appointment_id'					=>	$_POST['hidden_appointment_id']
				);

				$object->query = "
				UPDATE appointment_table 
				SET status = :status, 
				accountant_comment = :accountant_comment 
				WHERE appointment_id = :appointment_id
				";

				$object->execute($data);

				echo '<div class="alert alert-success">Appointment Completed</div>';
			}
		}
	}
	

	if($_POST["action"] == 'delete')
	{
		$object->query = "
		DELETE FROM accountant_schedule_table 
		WHERE accountant_schedule_id = '".$_POST["id"]."'
		";

		$object->execute();

		echo '<div class="alert alert-success">Accountant Schedule has been Deleted</div>';
	}
}

?>