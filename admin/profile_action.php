<?php

include('../class/Appointment.php');

$object = new Appointment;

if($_POST["action"] == 'accountant_profile')
{
	sleep(2);

	$error = '';

	$success = '';

	$accountant_profile_image = '';

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
		'error'					=>	$error,
		'success'				=>	$success,
		'accountant_email_address'	=>	$_POST["accountant_email_address"],
		'accountant_password'		=>	$_POST["accountant_password"],
		'accountant_name'			=>	$_POST["accountant_name"],
		'accountant_profile_image'	=>	$accountant_profile_image,
		'accountant_phone_no'		=>	$_POST["accountant_phone_no"],
		'accountant_address'		=>	$_POST["accountant_address"],
		'accountant_date_of_birth'	=>	$_POST["accountant_date_of_birth"],
		'accountant_degree'			=>	$_POST["accountant_degree"],
		'accountant_expert_in'		=>	$_POST["accountant_expert_in"],
	);

	echo json_encode($output);
}

if($_POST["action"] == 'admin_profile')
{
	sleep(2);

	$error = '';

	$success = '';

	$firm_logo = $_POST['hidden_firm_logo'];

	if($_FILES['firm_logo']['name'] != '')
	{
		$allowed_file_format = array("jpg", "png");

	    $file_extension = pathinfo($_FILES["firm_logo"]["name"], PATHINFO_EXTENSION);

	    if(!in_array($file_extension, $allowed_file_format))
		{
		    $error = "<div class='alert alert-danger'>Upload valiid file. jpg, png</div>";
		}
		else if (($_FILES["firm_logo"]["size"] > 2000000))
		{
		   $error = "<div class='alert alert-danger'>File size exceeds 2MB</div>";
	    }
		else
		{
		    $new_name = rand() . '.' . $file_extension;

			$destination = '../images/' . $new_name;

			move_uploaded_file($_FILES['firm_logo']['tmp_name'], $destination);

			$firm_logo = $destination;
		}
	}

	if($error == '')
	{
		$data = array(
			':admin_email_address'			=>	$object->clean_input($_POST["admin_email_address"]),
			':admin_password'				=>	$_POST["admin_password"],
			':admin_name'					=>	$object->clean_input($_POST["admin_name"]),
			':firm_name'				=>	$object->clean_input($_POST["firm_name"]),
			':firm_address'				=>	$object->clean_input($_POST["firm_address"]),
			':firm_contact_no'			=>	$object->clean_input($_POST["firm_contact_no"]),
			':firm_logo'				=>	$firm_logo
		);

		$object->query = "
		UPDATE admin_table  
		SET admin_email_address = :admin_email_address, 
		admin_password = :admin_password, 
		admin_name = :admin_name, 
		firm_name = :firm_name, 
		firm_address = :firm_address, 
		firm_contact_no = :firm_contact_no, 
		firm_logo = :firm_logo 
		WHERE admin_id = '".$_SESSION["admin_id"]."'
		";
		$object->execute($data);

		$success = '<div class="alert alert-success">Admin Data Updated</div>';

		$output = array(
			'error'					=>	$error,
			'success'				=>	$success,
			'admin_email_address'	=>	$_POST["admin_email_address"],
			'admin_password'		=>	$_POST["admin_password"],
			'admin_name'			=>	$_POST["admin_name"], 
			'firm_name'			=>	$_POST["firm_name"],
			'firm_address'		=>	$_POST["firm_address"],
			'firm_contact_no'	=>	$_POST["firm_contact_no"],
			'firm_logo'			=>	$firm_logo
		);

		echo json_encode($output);
	}
	else
	{
		$output = array(
			'error'					=>	$error,
			'success'				=>	$success
		);
		echo json_encode($output);
	}
}

?>