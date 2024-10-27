<?php

//profile.php

include('class/Appointment.php');

$object = new Appointment;

$object->query = "
SELECT * FROM customer_table 
WHERE customer_id = '".$_SESSION["customer_id"]."'
";

$result = $object->get_result();

include('header.php');

?>

<div class="container-fluid">
	<?php include('navbar.php'); ?>

	<div class="row justify-content-md-center">
		<div class="col col-md-6">
			<br />
			<?php
			if(isset($_GET['action']) && $_GET['action'] == 'edit')
			{
			?>
			<div class="card">
				<div class="card-header">
					<div class="row">
						<div class="col-md-6">
							Edit Profile Details
						</div>
						<div class="col-md-6 text-right">
							<a href="profile.php" class="btn btn-secondary btn-sm float-end">View</a>
						</div>
					</div>
				</div>
				<div class="card-body">
					<form method="post" id="edit_profile_form">
						<div class="mb-3">
							<label>Customer Email Address<span class="text-danger">*</span></label>
							<input type="text" name="customer_email_address" id="customer_email_address" class="form-control" required autofocus data-parsley-type="email" data-parsley-trigger="keyup" readonly />
						</div>
						<div class="mb-3">
							<label>Customer Password<span class="text-danger">*</span></label>
							<input type="password" name="customer_password" id="customer_password" class="form-control" required  data-parsley-trigger="keyup" />
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="mb-3">
									<label>Customer First Name<span class="text-danger">*</span></label>
									<input type="text" name="customer_first_name" id="customer_first_name" class="form-control" required  data-parsley-trigger="keyup" />
								</div>
							</div>
							<div class="col-md-6">
								<div class="mb-3">
									<label>Customer Last Name<span class="text-danger">*</span></label>
									<input type="text" name="customer_last_name" id="customer_last_name" class="form-control" required  data-parsley-trigger="keyup" />
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="mb-3">
									<label>Customer Date of Birth<span class="text-danger">*</span></label>
									<input type="date" name="customer_date_of_birth" id="customer_date_of_birth" class="form-control" required  data-parsley-trigger="keyup" />
								</div>
							</div>
							<div class="col-md-6">
								<div class="mb-3">
									<label>Customer Gender<span class="text-danger">*</span></label>
									<select name="customer_gender" id="customer_gender" class="form-select">
										<option value="Male">Male</option>
										<option value="Female">Female</option>
										<option value="Other">Other</option>
									</select>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="mb-3">
									<label>Customer Contact No.<span class="text-danger">*</span></label>
									<input type="text" name="customer_phone_no" id="customer_phone_no" class="form-control" required  data-parsley-trigger="keyup" />
								</div>
							</div>
							<div class="col-md-6">
								<div class="mb-3">
									<label>Customer Maritial Status<span class="text-danger">*</span></label>
									<select name="customer_maritial_status" id="customer_maritial_status" class="form-select">
										<option value="Single">Single</option>
										<option value="Married">Married</option>
										<option value="Seperated">Seperated</option>
										<option value="Divorced">Divorced</option>
										<option value="Widowed">Widowed</option>
									</select>
								</div>
							</div>
						</div>
						<div class="mb-3">
							<label>Customer Complete Address<span class="text-danger">*</span></label>
							<textarea name="customer_address" id="customer_address" class="form-control" required data-parsley-trigger="keyup"></textarea>
						</div>
						<div class="mb-3 text-center">
							<input type="hidden" name="action" value="edit_profile" />
							<input type="submit" name="edit_profile_button" id="edit_profile_button" class="btn btn-primary" value="Edit" />
						</div>
					</form>
				</div>
			</div>

			<br />
			<br />
			

			<?php
			}
			else
			{

				if(isset($_SESSION['success_message']))
				{
					echo $_SESSION['success_message'];
					unset($_SESSION['success_message']);
				}
			?>

			<div class="card">
				<div class="card-header">
					<div class="row">
						<div class="col-md-6">
							Profile Details
						</div>
						<div class="col-md-6">
							<a href="profile.php?action=edit" class="btn btn-secondary btn-sm float-end">Edit</a>
						</div>
					</div>
				</div>
				<div class="card-body">
					<table class="table table-striped">
						<?php
						foreach($result as $row)
						{
						?>
						<tr>
							<th class="text-right" width="40%">Customer Name</th>
							<td><?php echo $row["customer_first_name"] . ' ' . $row["customer_last_name"]; ?></td>
						</tr>
						<tr>
							<th class="text-right" width="40%">Email Address</th>
							<td><?php echo $row["customer_email_address"]; ?></td>
						</tr>
						<tr>
							<th class="text-right" width="40%">Password</th>
							<td><?php echo $row["customer_password"]; ?></td>
						</tr>
						<tr>
							<th class="text-right" width="40%">Address</th>
							<td><?php echo $row["customer_address"]; ?></td>
						</tr>
						<tr>
							<th class="text-right" width="40%">Contact No.</th>
							<td><?php echo $row["customer_phone_no"]; ?></td>
						</tr>
						<tr>
							<th class="text-right" width="40%">Date of Birth</th>
							<td><?php echo $row["customer_date_of_birth"]; ?></td>
						</tr>
						<tr>
							<th class="text-right" width="40%">Gender</th>
							<td><?php echo $row["customer_gender"]; ?></td>
						</tr>
						
						<tr>
							<th class="text-right" width="40%">Maritial Status</th>
							<td><?php echo $row["customer_maritial_status"]; ?></td>
						</tr>
						<?php
						}
						?>	
					</table>					
				</div>
			</div>
			<br />
			<br />
			<?php
			}
			?>
		</div>
	</div>
</div>

<?php

include('footer.php');


?>

<script>

$(document).ready(function(){

<?php
	foreach($result as $row)
	{

?>
$('#customer_email_address').val("<?php echo $row['customer_email_address']; ?>");
$('#customer_password').val("<?php echo $row['customer_password']; ?>");
$('#customer_first_name').val("<?php echo $row['customer_first_name']; ?>");
$('#customer_last_name').val("<?php echo $row['customer_last_name']; ?>");
$('#customer_date_of_birth').val("<?php echo $row['customer_date_of_birth']; ?>");
$('#customer_gender').val("<?php echo $row['customer_gender']; ?>");
$('#customer_phone_no').val("<?php echo $row['customer_phone_no']; ?>");
$('#customer_maritial_status').val("<?php echo $row['customer_maritial_status']; ?>");
$('#customer_address').val("<?php echo $row['customer_address']; ?>");

<?php

	}

?>

	$('#edit_profile_form').parsley();

	$('#edit_profile_form').on('submit', function(event){

		event.preventDefault();

		if($('#edit_profile_form').parsley().isValid())
		{
			$.ajax({
				url:"action.php",
				method:"POST",
				data:$(this).serialize(),
				beforeSend:function()
				{
					$('#edit_profile_button').attr('disabled', 'disabled');
					$('#edit_profile_button').val('wait...');
				},
				success:function(data)
				{
					window.location.href = "profile.php";
				}
			})
		}

	});

});

</script>