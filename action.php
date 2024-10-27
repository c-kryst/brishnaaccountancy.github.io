<?php

//action.php

include('class/Appointment.php');

$object = new Appointment;

if(isset($_POST["action"]))
{
	if($_POST["action"] == 'check_login')
	{
		if(isset($_SESSION['customer_id']))
		{
			echo 'dashboard.php';
		}
		else
		{
			echo 'login.php';
		}
	}

	if($_POST['action'] == 'customer_register')
	{
		$error = '';

		$success = '';

		$data = array(
			':customer_email_address'	=>	$_POST["customer_email_address"]
		);

		$object->query = "
		SELECT * FROM customer_table 
		WHERE customer_email_address = :customer_email_address
		";

		$object->execute($data);

		if($object->row_count() > 0)
		{
			$error = '<div class="alert alert-danger">Email Address Already Exists</div>';
		}
		else
		{
			$customer_verification_code = md5(uniqid());
			$data = array(
				':customer_email_address'		=>	$object->clean_input($_POST["customer_email_address"]),
				':customer_password'			=>	$_POST["customer_password"],
				':customer_first_name'			=>	$object->clean_input($_POST["customer_first_name"]),
				':customer_last_name'			=>	$object->clean_input($_POST["customer_last_name"]),
				':customer_date_of_birth'		=>	$object->clean_input($_POST["customer_date_of_birth"]),
				':customer_gender'				=>	$object->clean_input($_POST["customer_gender"]),
				':customer_address'				=>	$object->clean_input($_POST["customer_address"]),
				':customer_phone_no'			=>	$object->clean_input($_POST["customer_phone_no"]),
				':customer_maritial_status'		=>	$object->clean_input($_POST["customer_maritial_status"]),
				':customer_added_on'			=>	$object->now,
				':customer_verification_code'	=>	$customer_verification_code,
				':email_verify'					=>	'Yes'
			);

			$object->query = "
			INSERT INTO customer_table 
			(customer_email_address, customer_password, customer_first_name, customer_last_name, customer_date_of_birth, customer_gender, customer_address, customer_phone_no, customer_maritial_status, customer_added_on, customer_verification_code, email_verify) 
			VALUES (:customer_email_address, :customer_password, :customer_first_name, :customer_last_name, :customer_date_of_birth, :customer_gender, :customer_address, :customer_phone_no, :customer_maritial_status, :customer_added_on, :customer_verification_code, :email_verify)
			";

			$object->execute($data);

			/*require 'class/class.phpmailer.php';
			$mail = new PHPMailer;
			$mail->IsSMTP();
			$mail->Host = 'smtpout.secureserver.net';
			$mail->Port = '80';
			$mail->SMTPAuth = true;
			$mail->Username = 'xxxxx';
			$mail->Password = 'xxxxx';
			$mail->SMTPSecure = '';
			$mail->From = 'tutorial@webslesson.info';
			$mail->FromName = 'Webslesson';
			$mail->AddAddress($_POST["customer_email_address"]);
			$mail->WordWrap = 50;
			$mail->IsHTML(true);
			$mail->Subject = 'Verification code for Verify Your Email Address';

			$message_body = '
			<p>For verify your email address, Please click on this <a href="'.$object->base_url.'verify.php?code='.$customer_verification_code.'"><b>link</b></a>.</p>
			<p>Sincerely,</p>
			<p>Webslesson.info</p>
			';
			$mail->Body = $message_body;

			if($mail->Send())
			{
				$success = '<div class="alert alert-success">Please Check Your Email for email Verification</div>';
			}
			else
			{
				$error = '<div class="alert alert-danger">' . $mail->ErrorInfo . '</div>';
			}*/
            $success = '<div class="alert alert-success">Registration Completed</div>';
		}

		$output = array(
			'error'		=>	$error,
			'success'	=>	$success
		);
		echo json_encode($output);
	}

	if($_POST['action'] == 'customer_login')
	{
		$error = '';

		$data = array(
			':customer_email_address'	=>	$_POST["customer_email_address"]
		);

		$object->query = "
		SELECT * FROM customer_table 
		WHERE customer_email_address = :customer_email_address
		";

		$object->execute($data);

		if($object->row_count() > 0)
		{

			$result = $object->statement_result();

			foreach($result as $row)
			{
				if($row["email_verify"] == 'Yes')
				{
					if($row["customer_password"] == $_POST["customer_password"])
					{
						$_SESSION['customer_id'] = $row['customer_id'];
						$_SESSION['customer_name'] = $row['customer_first_name'] . ' ' . $row['customer_last_name'];
					}
					else
					{
						$error = '<div class="alert alert-danger">Wrong Password</div>';
					}
				}
				else
				{
					$error = '<div class="alert alert-danger">Please first verify your email address</div>';
				}
			}
		}
		else
		{
			$error = '<div class="alert alert-danger">Wrong Email Address</div>';
		}

		$output = array(
			'error'		=>	$error
		);

		echo json_encode($output);

	}

	if($_POST['action'] == 'fetch_schedule')
	{
		$output = array();

		$order_column = array('accountant_table.accountant_name', 'accountant_table.accountant_degree', 'accountant_table.accountant_expert_in', 'accountant_schedule_table.accountant_schedule_date', 'accountant_schedule_table.accountant_schedule_day', 'accountant_schedule_table.accountant_schedule_start_time');
		
		$main_query = "
		SELECT * FROM accountant_schedule_table 
		INNER JOIN accountant_table 
		ON accountant_table.accountant_id = accountant_schedule_table.accountant_id 
		";

		$search_query = '
		WHERE accountant_schedule_table.accountant_schedule_date >= "'.date('Y-m-d').'" 
		AND STR_TO_DATE(CONCAT(accountant_schedule_table.accountant_schedule_date, " ", accountant_schedule_table.accountant_schedule_end_time), "%Y-%m-%d %H:%i:%s") > "'.$object->now.'"   
		AND accountant_schedule_table.accountant_schedule_status = "Active" 
		AND accountant_table.accountant_status = "Active" 
		';

		if(isset($_POST["search"]["value"]))
		{
			$search_query .= 'AND ( accountant_table.accountant_name LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR accountant_table.accountant_degree LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR accountant_table.accountant_expert_in LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR accountant_schedule_table.accountant_schedule_date LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR accountant_schedule_table.accountant_schedule_day LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR accountant_schedule_table.accountant_schedule_start_time LIKE "%'.$_POST["search"]["value"].'%") ';
		}
		
		if(isset($_POST["order"]))
		{
			$order_query = 'ORDER BY '.$order_column[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' ';
		}
		else
		{
			$order_query = 'ORDER BY accountant_schedule_table.accountant_schedule_date ASC ';
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

			$sub_array[] = $row["accountant_name"];

			$sub_array[] = $row["accountant_degree"];

			$sub_array[] = $row["accountant_expert_in"];

			$sub_array[] = $row["accountant_schedule_date"];

			$sub_array[] = $row["accountant_schedule_day"];

			$sub_array[] = $row["accountant_schedule_start_time"];

			$sub_array[] = '
			<div align="center">
			<button type="button" name="get_appointment" class="btn btn-primary btn-sm get_appointment" data-accountant_id="'.$row["accountant_id"].'" data-accountant_schedule_id="'.$row["accountant_schedule_id"].'">Get Appointment</button>
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

	if($_POST['action'] == 'edit_profile')
	{
		$data = array(
			':customer_password'			=>	$_POST["customer_password"],
			':customer_first_name'		=>	$_POST["customer_first_name"],
			':customer_last_name'		=>	$_POST["customer_last_name"],
			':customer_date_of_birth'	=>	$_POST["customer_date_of_birth"],
			':customer_gender'			=>	$_POST["customer_gender"],
			':customer_address'			=>	$_POST["customer_address"],
			':customer_phone_no'			=>	$_POST["customer_phone_no"],
			':customer_maritial_status'	=>	$_POST["customer_maritial_status"]
		);

		$object->query = "
		UPDATE customer_table  
		SET customer_password = :customer_password, 
		customer_first_name = :customer_first_name, 
		customer_last_name = :customer_last_name, 
		customer_date_of_birth = :customer_date_of_birth, 
		customer_gender = :customer_gender, 
		customer_address = :customer_address, 
		customer_phone_no = :customer_phone_no, 
		customer_maritial_status = :customer_maritial_status 
		WHERE customer_id = '".$_SESSION['customer_id']."'
		";

		$object->execute($data);

		$_SESSION['success_message'] = '<div class="alert alert-success">Profile Data Updated</div>';

		echo 'done';
	}

	if($_POST['action'] == 'make_appointment')
	{
		$object->query = "
		SELECT * FROM customer_table 
		WHERE customer_id = '".$_SESSION["customer_id"]."'
		";

		$customer_data = $object->get_result();

		$object->query = "
		SELECT * FROM accountant_schedule_table 
		INNER JOIN accountant_table 
		ON accountant_table.accountant_id = accountant_schedule_table.accountant_id 
		WHERE accountant_schedule_table.accountant_schedule_id = '".$_POST["accountant_schedule_id"]."'
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
			<tr>
				<th width="40%" class="text-right">Available Time</th>
				<td>'.$accountant_schedule_row["accountant_schedule_start_time"].' - '.$accountant_schedule_row["accountant_schedule_end_time"].'</td>
			</tr>
			';
		}

		$html .= '
		</table>';
		echo $html;
	}

	if($_POST['action'] == 'book_appointment')
	{
		$error = '';
		$data = array(
			':customer_id'			=>	$_SESSION['customer_id'],
			':accountant_schedule_id'	=>	$_POST['hidden_accountant_schedule_id']
		);

		$object->query = "
		SELECT * FROM appointment_table 
		WHERE customer_id = :customer_id 
		AND accountant_schedule_id = :accountant_schedule_id
		";

		$object->execute($data);

		if($object->row_count() > 0)
		{
			$error = '<div class="alert alert-danger">You have already applied for appointment for this day, try for other day.</div>';
		}
		else
		{
			$object->query = "
			SELECT * FROM accountant_schedule_table 
			WHERE accountant_schedule_id = '".$_POST['hidden_accountant_schedule_id']."'
			";

			$schedule_data = $object->get_result();

			$object->query = "
			SELECT COUNT(appointment_id) AS total FROM appointment_table 
			WHERE accountant_schedule_id = '".$_POST['hidden_accountant_schedule_id']."' 
			";

			$appointment_data = $object->get_result();

			$total_accountant_available_minute = 0;
			$average_consulting_time = 0;
			$total_appointment = 0;

			foreach($schedule_data as $schedule_row)
			{
				$end_time = strtotime($schedule_row["accountant_schedule_end_time"] . ':00');

				$start_time = strtotime($schedule_row["accountant_schedule_start_time"] . ':00');

				$total_accountant_available_minute = ($end_time - $start_time) / 60;

				$average_consulting_time = $schedule_row["average_consulting_time"];
			}

			foreach($appointment_data as $appointment_row)
			{
				$total_appointment = $appointment_row["total"];
			}

			$total_appointment_minute_use = $total_appointment * $average_consulting_time;

			$appointment_time = date("H:i", strtotime('+'.$total_appointment_minute_use.' minutes', $start_time));

			$status = '';

			$appointment_number = $object->Generate_appointment_no();

			if(strtotime($end_time) > strtotime($appointment_time . ':00'))
			{
				$status = 'Booked';
			}
			else
			{
				$status = 'Waiting';
			}
			
			$data = array(
				':accountant_id'					=>	$_POST['hidden_accountant_id'],
				':customer_id'					=>	$_SESSION['customer_id'],
				':accountant_schedule_id'			=>	$_POST['hidden_accountant_schedule_id'],
				':appointment_number'			=>	$appointment_number,
				':reason_for_appointment'		=>	$_POST['reason_for_appointment'],
				':appointment_time'				=>	$appointment_time,
				':status'						=>	'Booked',
				':customer_come_into_firm'	=>	'No',
				':accountant_comment'				=>	''
			);

			$object->query = "
			INSERT INTO appointment_table 
			(accountant_id, customer_id, accountant_schedule_id, appointment_number, reason_for_appointment, appointment_time, status, customer_come_into_firm, accountant_comment) 
			VALUES (:accountant_id, :customer_id, :accountant_schedule_id, :appointment_number, :reason_for_appointment, :appointment_time, :status, :customer_come_into_firm, :accountant_comment)
			";

			$object->execute($data);

			$_SESSION['appointment_message'] = '<div class="alert alert-success">Your Appointment has been <b>'.$status.'</b> with Appointment No. <b>'.$appointment_number.'</b></div>';
		}
		echo json_encode(['error' => $error]);
		
	}

	if($_POST['action'] == 'fetch_appointment')
	{
		$output = array();

		$order_column = array('appointment_table.appointment_number','accountant_table.accountant_name', 'accountant_schedule_table.accountant_schedule_date', 'appointment_table.appointment_time', 'accountant_schedule_table.accountant_schedule_day', 'appointment_table.status');
		
		$main_query = "
		SELECT * FROM appointment_table  
		INNER JOIN accountant_table 
		ON accountant_table.accountant_id = appointment_table.accountant_id 
		INNER JOIN accountant_schedule_table 
		ON accountant_schedule_table.accountant_schedule_id = appointment_table.accountant_schedule_id 
		
		";

		$search_query = '
		WHERE appointment_table.customer_id = "'.$_SESSION["customer_id"].'" 
		';

		if(isset($_POST["search"]["value"]))
		{
			$search_query .= 'AND ( appointment_table.appointment_number LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR accountant_table.accountant_name LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR accountant_schedule_table.accountant_schedule_date LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR appointment_table.appointment_time LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR accountant_schedule_table.accountant_schedule_day LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR appointment_table.status LIKE "%'.$_POST["search"]["value"].'%") ';
		}
		
		if(isset($_POST["order"]))
		{
			$order_query = 'ORDER BY '.$order_column[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' ';
		}
		else
		{
			$order_query = 'ORDER BY appointment_table.appointment_id ASC ';
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

			$sub_array[] = $row["accountant_name"];

			$sub_array[] = $row["accountant_schedule_date"];			

			$sub_array[] = $row["appointment_time"];

			$sub_array[] = $row["accountant_schedule_day"];

			$status = '';

			$delete_btn = '';

			if($row["status"] == 'Booked')
			{
				$status = '<span class="badge bg-warning">' . $row["status"] . '</span>';
				$delete_btn = '<button type="button" name="cancel_appointment" class="btn btn-danger btn-sm cancel_appointment" data-id="'.$row["appointment_id"].'"><i class="fas fa-times"></i></button>';
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

			$sub_array[] = '<a href="download.php?id='.$row["appointment_id"].'" class="btn btn-danger btn-sm" target="_blank"><i class="fas fa-file-pdf"></i> PDF</a>';

			$sub_array[] = $delete_btn;

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

	if($_POST['action'] == 'cancel_appointment')
	{
		$data = array(
			':status'			=>	'Cancel',
			':appointment_id'	=>	$_POST['appointment_id']
		);
		$object->query = "
		UPDATE appointment_table 
		SET status = :status 
		WHERE appointment_id = :appointment_id
		";
		$object->execute($data);
		echo '<div class="alert alert-success">Your Appointment has been Cancel</div>';
	}
}



?>