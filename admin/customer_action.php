<?php

//customer_action.php

include('../class/Appointment.php');

$object = new Appointment;

if(isset($_POST["action"]))
{
	if($_POST["action"] == 'fetch')
	{
		$order_column = array('customer_first_name', 'customer_last_name', 'customer_email_address', 'customer_phone_no', 'email_verify');

		$output = array();

		$main_query = "
		SELECT * FROM customer_table ";

		$search_query = '';

		if(isset($_POST["search"]["value"]))
		{
			$search_query .= 'WHERE customer_first_name LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR customer_last_name LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR customer_email_address LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR customer_phone_no LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR email_verify LIKE "%'.$_POST["search"]["value"].'%" ';
		}

		if(isset($_POST["order"]))
		{
			$order_query = 'ORDER BY '.$order_column[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' ';
		}
		else
		{
			$order_query = 'ORDER BY customer_id DESC ';
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

		$object->query = $main_query;

		$object->execute();

		$total_rows = $object->row_count();

		$data = array();

		foreach($result as $row)
		{
			$sub_array = array();
			$sub_array[] = $row["customer_first_name"];
			$sub_array[] = $row["customer_last_name"];
			$sub_array[] = $row["customer_email_address"];
			$sub_array[] = $row["customer_phone_no"];
			$status = '';
			if($row["email_verify"] == 'Yes')
			{
				$status = '<span class="badge bg-success">Yes</span>';
			}
			else
			{
				$status = '<span class="badge bg-danger">No</span>';
			}
			$sub_array[] = $status;
			$sub_array[] = '
			<div align="center">
			<button type="button" name="view_button" class="btn btn-info btn-circle btn-sm view_button" data-id="'.$row["customer_id"].'"><i class="fas fa-eye"></i></button>
			<button type="button" name="edit_button" class="btn btn-warning btn-circle btn-sm edit_button" data-id="'.$row["customer_id"].'"><i class="fas fa-edit"></i></button>
			<button type="button" name="delete_button" class="btn btn-danger btn-circle btn-sm delete_button" data-id="'.$row["customer_id"].'"><i class="fas fa-times"></i></button>
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

	/*if($_POST["action"] == 'Add')
	{
		$error = '';

		$success = '';

		$data = array(
			':accountant_email_address'	=>	$_POST["accountant_email_address"]
		);

		$object->query = "
		SELECT * FROM accountant_table 
		WHERE accountant_email_address = :accountant_email_address
		";

		$object->execute($data);

		if($object->row_count() > 0)
		{
			$error = '<div class="alert alert-danger">Email Address Already Exists</div>';
		}
		else
		{
			$accountant_profile_image = '';
			if($_FILES['accountant_profile_image']['name'] != '')
			{
				$allowed_file_format = array("jpg", "png");

	    		$file_extension = pathinfo($_FILES["accountant_profile_image"]["name"], PATHINFO_EXTENSION);

	    		if(!in_array($file_extension, $allowed_file_format))
			    {
			        $error = "<div class='alert alert-danger'>Upload valiid file. jpg, png</div>";
			    }
			    else if (($_FILES["accountant_profile_image"]["size"] > 2000000))
			    {
			       $error = "<div class='alert alert-danger'>File size exceeds 2MB</div>";
			    }
			    else
			    {
			    	$new_name = rand() . '.' . $file_extension;

					$destination = '../images/' . $new_name;

					move_uploaded_file($_FILES['accountant_profile_image']['tmp_name'], $destination);

					$accountant_profile_image = $destination;
			    }
			}
			else
			{
				$character = $_POST["accountant_name"][0];
				$path = "../images/". time() . ".png";
				$image = imagecreate(200, 200);
				$red = rand(0, 255);
				$green = rand(0, 255);
				$blue = rand(0, 255);
			    imagecolorallocate($image, 230, 230, 230);  
			    $textcolor = imagecolorallocate($image, $red, $green, $blue);
			    imagettftext($image, 100, 0, 55, 150, $textcolor, '../font/arial.ttf', $character);
			    imagepng($image, $path);
			    imagedestroy($image);
			    $accountant_profile_image = $path;
			}

			if($error == '')
			{
				$data = array(
					':accountant_email_address'			=>	$object->clean_input($_POST["accountant_email_address"]),
					':accountant_password'				=>	$_POST["accountant_password"],
					':accountant_name'					=>	$object->clean_input($_POST["accountant_name"]),
					':accountant_profile_image'			=>	$accountant_profile_image,
					':accountant_phone_no'				=>	$object->clean_input($_POST["accountant_phone_no"]),
					':accountant_address'				=>	$object->clean_input($_POST["accountant_address"]),
					':accountant_date_of_birth'			=>	$object->clean_input($_POST["accountant_date_of_birth"]),
					':accountant_degree'				=>	$object->clean_input($_POST["accountant_degree"]),
					':accountant_expert_in'				=>	$object->clean_input($_POST["accountant_expert_in"]),
					':accountant_status'				=>	'Active',
					':accountant_added_on'				=>	$object->now
				);

				$object->query = "
				INSERT INTO accountant_table 
				(accountant_email_address, accountant_password, accountant_name, accountant_profile_image, accountant_phone_no, accountant_address, accountant_date_of_birth, accountant_degree, accountant_expert_in, accountant_status, accountant_added_on) 
				VALUES (:accountant_email_address, :accountant_password, :accountant_name, :accountant_profile_image, :accountant_phone_no, :accountant_address, :accountant_date_of_birth, :accountant_degree, :accountant_expert_in, :accountant_status, :accountant_added_on)
				";

				$object->execute($data);

				$success = '<div class="alert alert-success">Accountant Added</div>';
			}
		}

		$output = array(
			'error'		=>	$error,
			'success'	=>	$success
		);

		echo json_encode($output);

	}*/

	if($_POST["action"] == 'fetch_single')
	{
		$object->query = "
		SELECT * FROM customer_table 
		WHERE customer_id = '".$_POST["customer_id"]."'
		";

		$result = $object->get_result();

		$data = array();

		foreach($result as $row)
		{
			$data['customer_email_address'] = $row['customer_email_address'];
			$data['customer_password'] = $row['customer_password'];
			$data['customer_first_name'] = $row['customer_first_name'];
			$data['customer_last_name'] = $row['customer_last_name'];
			$data['customer_date_of_birth'] = $row['customer_date_of_birth'];
			$data['customer_gender'] = $row['customer_gender'];
			$data['customer_address'] = $row['customer_address'];
			$data['customer_phone_no'] = $row['customer_phone_no'];
			$data['customer_maritial_status'] = $row['customer_maritial_status'];
			if($row['email_verify'] == 'Yes')
			{
				$data['email_verify'] = '<span class="badge bg-success">Yes</span>';
			}
			else
			{
				$data['email_verify'] = '<span class="badge bg-danger">No</span>';
			}
		}

		echo json_encode($data);
	}

	/*if($_POST["action"] == 'Edit')
	{
		$error = '';

		$success = '';

		$data = array(
			':accountant_email_address'	=>	$_POST["accountant_email_address"],
			':accountant_id'			=>	$_POST['hidden_id']
		);

		$object->query = "
		SELECT * FROM accountant_table 
		WHERE accountant_email_address = :accountant_email_address 
		AND accountant_id != :accountant_id
		";

		$object->execute($data);

		if($object->row_count() > 0)
		{
			$error = '<div class="alert alert-danger">Email Address Already Exists</div>';
		}
		else
		{
			$accountant_profile_image = $_POST["hidden_accountant_profile_image"];

			if($_FILES['accountant_profile_image']['name'] != '')
			{
				$allowed_file_format = array("jpg", "png");

	    		$file_extension = pathinfo($_FILES["accountant_profile_image"]["name"], PATHINFO_EXTENSION);

	    		if(!in_array($file_extension, $allowed_file_format))
			    {
			        $error = "<div class='alert alert-danger'>Upload valiid file. jpg, png</div>";
			    }
			    else if (($_FILES["accountant_profile_image"]["size"] > 2000000))
			    {
			       $error = "<div class='alert alert-danger'>File size exceeds 2MB</div>";
			    }
			    else
			    {
			    	$new_name = rand() . '.' . $file_extension;

					$destination = '../images/' . $new_name;

					move_uploaded_file($_FILES['accountant_profile_image']['tmp_name'], $destination);

					$accountant_profile_image = $destination;
			    }
			}

			if($error == '')
			{
				$data = array(
					':accountant_email_address'			=>	$object->clean_input($_POST["accountant_email_address"]),
					':accountant_password'				=>	$_POST["accountant_password"],
					':accountant_name'					=>	$object->clean_input($_POST["accountant_name"]),
					':accountant_profile_image'			=>	$accountant_profile_image,
					':accountant_phone_no'				=>	$object->clean_input($_POST["accountant_phone_no"]),
					':accountant_address'				=>	$object->clean_input($_POST["accountant_address"]),
					':accountant_date_of_birth'			=>	$object->clean_input($_POST["accountant_date_of_birth"]),
					':accountant_degree'				=>	$object->clean_input($_POST["accountant_degree"]),
					':accountant_expert_in'				=>	$object->clean_input($_POST["accountant_expert_in"])
				);

				$object->query = "
				UPDATE accountant_table  
				SET accountant_email_address = :accountant_email_address, 
				accountant_password = :accountant_password, 
				accountant_name = :accountant_name, 
				accountant_profile_image = :accountant_profile_image, 
				accountant_phone_no = :accountant_phone_no, 
				accountant_address = :accountant_address, 
				accountant_date_of_birth = :accountant_date_of_birth, 
				accountant_degree = :accountant_degree,  
				accountant_expert_in = :accountant_expert_in 
				WHERE accountant_id = '".$_POST['hidden_id']."'
				";

				$object->execute($data);

				$success = '<div class="alert alert-success">Accountant Data Updated</div>';
			}			
		}

		$output = array(
			'error'		=>	$error,
			'success'	=>	$success
		);

		echo json_encode($output);

	}

	if($_POST["action"] == 'change_status')
	{
		$data = array(
			':accountant_status'		=>	$_POST['next_status']
		);

		$object->query = "
		UPDATE accountant_table 
		SET accountant_status = :accountant_status 
		WHERE accountant_id = '".$_POST["id"]."'
		";

		$object->execute($data);

		echo '<div class="alert alert-success">Class Status change to '.$_POST['next_status'].'</div>';
	}

	if($_POST["action"] == 'delete')
	{
		$object->query = "
		DELETE FROM accountant_table 
		WHERE accountant_id = '".$_POST["id"]."'
		";

		$object->execute();

		echo '<div class="alert alert-success">Accountant Data Deleted</div>';
	}*/
}

?>