<?php

include('../class/Appointment.php');

$object = new Appointment;

if(!$object->is_login())
{
    header("location:".$object->base_url."");
}

if($_SESSION['type'] != 'Accountant')
{
    header("location:".$object->base_url."");
}

$object->query = "
    SELECT * FROM accountant_table
    WHERE accountant_id = '".$_SESSION["admin_id"]."'
    ";

$result = $object->get_result();

include('header.php');

?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Profile</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
        <li class="breadcrumb-item active">Profile</li>
    </ol>

    <form method="post" id="profile_form" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-10">
                <span id="message"></span>
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <div class="row">
                            <div class="col">
                                <h6 class="m-0 font-weight-bold text-primary">Profile</h6>
                            </div>
                            <div class="col">
                                <input type="hidden" name="action" value="accountant_profile" />
                                <input type="hidden" name="hidden_id" id="hidden_id" />
                                <button type="submit" name="edit_button" id="edit_button" class="btn btn-primary btn-sm float-end"><i class="fas fa-edit"></i> Edit</button>
                                        &nbsp;&nbsp;
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <span id="form_message"></span>
                        <div class="mb-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Accountant Email Address <span class="text-danger">*</span></label>
                                    <input type="text" name="accountant_email_address" id="accountant_email_address" class="form-control" required data-parsley-type="email" data-parsley-trigger="keyup" />
                                </div>
                                <div class="col-md-6">
                                    <label>Accountant Password <span class="text-danger">*</span></label>
                                    <input type="password" name="accountant_password" id="accountant_password" class="form-control" required  data-parsley-trigger="keyup" />
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Accountant Name <span class="text-danger">*</span></label>
                                    <input type="text" name="accountant_name" id="accountant_name" class="form-control" required data-parsley-trigger="keyup" />
                                </div>
                                <div class="col-md-6">
                                    <label>Accountant Phone No. <span class="text-danger">*</span></label>
                                    <input type="text" name="accountant_phone_no" id="accountant_phone_no" class="form-control" required  data-parsley-trigger="keyup" />
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Accountant Address </label>
                                    <input type="text" name="accountant_address" id="accountant_address" class="form-control" />
                                </div>
                                <div class="col-md-6">
                                    <label>Accountant Date of Birth </label>
                                    <input type="date" name="accountant_date_of_birth" id="accountant_date_of_birth" class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Accountant Degree <span class="text-danger">*</span></label>
                                    <input type="text" name="accountant_degree" id="accountant_degree" class="form-control" required data-parsley-trigger="keyup" />
                                </div>
                                <div class="col-md-6">
                                    <label>Accountant Speciality <span class="text-danger">*</span></label>
                                    <input type="text" name="accountant_expert_in" id="accountant_expert_in" class="form-control" required  data-parsley-trigger="keyup" />
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label>Accountant Image <span class="text-danger">*</span></label>
                            <br />
                            <input type="file" name="accountant_profile_image" id="accountant_profile_image" />
                            <div id="uploaded_image"></div>
                            <input type="hidden" name="hidden_accountant_profile_image" id="hidden_accountant_profile_image" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

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
    $('#hidden_id').val("<?php echo $row['accountant_id']; ?>");
    $('#accountant_email_address').val("<?php echo $row['accountant_email_address']; ?>");
    $('#accountant_password').val("<?php echo $row['accountant_password']; ?>");
    $('#accountant_name').val("<?php echo $row['accountant_name']; ?>");
    $('#accountant_phone_no').val("<?php echo $row['accountant_phone_no']; ?>");
    $('#accountant_address').val("<?php echo $row['accountant_address']; ?>");
    $('#accountant_date_of_birth').val("<?php echo $row['accountant_date_of_birth']; ?>");
    $('#accountant_degree').val("<?php echo $row['accountant_degree']; ?>");
    $('#accountant_expert_in').val("<?php echo $row['accountant_expert_in']; ?>");
    
    $('#uploaded_image').html('<img src="<?php echo $row["accountant_profile_image"]; ?>" class="img-thumbnail" width="100" /><input type="hidden" name="hidden_accountant_profile_image" value="<?php echo $row["accountant_profile_image"]; ?>" />');

    $('#hidden_accountant_profile_image').val("<?php echo $row['accountant_profile_image']; ?>");
    <?php
    }
    ?>

    $('#accountant_profile_image').change(function(){
        var extension = $('#accountant_profile_image').val().split('.').pop().toLowerCase();
        if(extension != '')
        {
            if(jQuery.inArray(extension, ['png','jpg']) == -1)
            {
                alert("Invalid Image File");
                $('#accountant_profile_image').val('');
                return false;
            }
        }
    });

    $('#profile_form').parsley();

	$('#profile_form').on('submit', function(event){
		event.preventDefault();
		if($('#profile_form').parsley().isValid())
		{		
			$.ajax({
				url:"profile_action.php",
				method:"POST",
				data:new FormData(this),
                dataType:'json',
                contentType:false,
                processData:false,
				beforeSend:function()
				{
					$('#edit_button').attr('disabled', 'disabled');
					$('#edit_button').html('wait...');
				},
				success:function(data)
				{
					$('#edit_button').attr('disabled', false);
                    $('#edit_button').html('<i class="fas fa-edit"></i> Edit');

                    $('#accountant_email_address').val(data.accountant_email_address);
                    $('#accountant_password').val(data.accountant_password);
                    $('#accountant_name').val(data.accountant_name);
                    $('#accountant_phone_no').val(data.accountant_phone_no);
                    $('#accountant_address').text(data.accountant_address);
                    $('#accountant_date_of_birth').text(data.accountant_date_of_birth);
                    $('#accountant_degree').text(data.accountant_degree);
                    $('#accountant_expert_in').text(data.accountant_expert_in);
                    if(data.accountant_profile_image != '')
                    {
                        $('#uploaded_image').html('<img src="'+data.accountant_profile_image+'" class="img-thumbnail" width="100" />');

                        $('#user_profile_image').attr('src', data.accountant_profile_image);
                    }

                    $('#hidden_accountant_profile_image').val(data.accountant_profile_image);
						
                    $('#message').html(data.success);

					setTimeout(function(){

				        $('#message').html('');

				    }, 5000);
				}
			})
		}
	});

});
</script>