<?php

//login.php

include('header.php');

?>

<div class="container">
	<div class="row justify-content-md-center">
		<div class="col col-md-6">
			<span id="message"></span>
			<div class="card">
				<div class="card-header">Register</div>
				<div class="card-body">
					<form method="post" id="customer_register_form">
						<div class="mb-3">
							<label>Customer Email Address<span class="text-danger">*</span></label>
							<input type="text" name="customer_email_address" id="customer_email_address" class="form-control" required autofocus data-parsley-type="email" data-parsley-trigger="keyup" />
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
							<input type="hidden" name="action" value="customer_register" />
							<input type="submit" name="customer_register_button" id="customer_register_button" class="btn btn-primary" value="Register" />
						</div>

						<div class="mb-3 text-center">
							<p><a href="login.php">Login</a></p>
						</div>
					</form>
				</div>
			</div>
			<br />
			<br />
		</div>
	</div>
</div>

<?php

include('footer.php');

?>

<script>

$(document).ready(function(){

	$('#customer_register_form').parsley();

	$('#customer_register_form').on('submit', function(event){

		event.preventDefault();

		if($('#customer_register_form').parsley().isValid())
		{
			$.ajax({
				url:"action.php",
				method:"POST",
				data:$(this).serialize(),
				dataType:'json',
				beforeSend:function(){
					$('#customer_register_button').attr('disabled', 'disabled');
				},
				success:function(data)
				{
					$('#customer_register_button').attr('disabled', false);
					$('#customer_register_form')[0].reset();
					if(data.error !== '')
					{
						$('#message').html(data.error);
					}
					if(data.success != '')
					{
						$('#message').html(data.success);
					}
				}
			});
		}

	});

});

</script>