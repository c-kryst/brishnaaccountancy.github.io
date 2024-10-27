<?php

//accountant.php

include('../class/Appointment.php');

$object = new Appointment;

if(!$object->is_login())
{
    header("location:".$object->base_url."admin");
}

if($_SESSION['type'] != 'Admin')
{
    header("location:".$object->base_url."");
}

include('header.php');

?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Accountant Management</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
        <li class="breadcrumb-item active">Accountant</li>
    </ol>

    <span id="message"></span>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row">
                <div class="col">
                    <h6 class="m-0 font-weight-bold text-primary">Accountant List</h6>
                </div>
                <div class="col">
                    <button type="button" name="add_accountant" id="add_accountant" class="btn btn-success btn-circle btn-sm float-end"><i class="fas fa-plus"></i></button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="accountant_table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Email Address</th>
                            <th>Password</th>
                            <th>Accountant Name</th>
                            <th>Accountant Phone No.</th>
                            <th>Accountant Speciality</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<?php

include('footer.php');

?>

<div id="accountantModal" class="modal fade">
  	<div class="modal-dialog">
    	<form method="post" id="accountant_form">
      		<div class="modal-content">
        		<div class="modal-header">
          			<h4 class="modal-title" id="modal_title">Add Accountant</h4>
          			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        		</div>
        		<div class="modal-body">
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
        		<div class="modal-footer">
          			<input type="hidden" name="hidden_id" id="hidden_id" />
          			<input type="hidden" name="action" id="action" value="Add" />
          			<input type="submit" name="submit" id="submit_button" class="btn btn-success" value="Add" />
          			<button type="button" class="btn btn-default" data-bs-dismiss="modal" aria-label="Close">Close</button>
        		</div>
      		</div>
    	</form>
  	</div>
</div>

<div id="viewModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal_title">View Accountant Details</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">&times;</button>
            </div>
            <div class="modal-body" id="accountant_details">
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-bs-dismiss="modal" aria-label="Close">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){

	var dataTable = $('#accountant_table').DataTable({
		"processing" : true,
		"serverSide" : true,
		"order" : [],
		"ajax" : {
			url:"accountant_action.php",
			type:"POST",
			data:{action:'fetch'}
		},
		"columnDefs":[
			{
				"targets":[0, 1, 2, 4, 5, 6, 7],
				"orderable":false,
			},
		],
	});

	$('#add_accountant').click(function(){
		
		$('#accountant_form')[0].reset();

		$('#accountant_form').parsley().reset();

    	$('#modal_title').text('Add accountant');

    	$('#action').val('Add');

    	$('#submit_button').val('Add');

    	$('#accountantModal').modal('show');

    	$('#form_message').html('');

	});

	$('#accountant_form').parsley();

	$('#accountant_form').on('submit', function(event){
		event.preventDefault();
		if($('#accountant_form').parsley().isValid())
		{		
			$.ajax({
				url:"accountant_action.php",
				method:"POST",
				data: new FormData(this),
				dataType:'json',
                contentType: false,
                cache: false,
                processData:false,
				beforeSend:function()
				{
					$('#submit_button').attr('disabled', 'disabled');
					$('#submit_button').val('wait...');
				},
				success:function(data)
				{
					$('#submit_button').attr('disabled', false);
					if(data.error != '')
					{
						$('#form_message').html(data.error);
						$('#submit_button').val('Add');
					}
					else
					{
						$('#accountantModal').modal('hide');
						$('#message').html(data.success);
						dataTable.ajax.reload();

						setTimeout(function(){

				            $('#message').html('');

				        }, 5000);
					}
				}
			})
		}
	});

	$(document).on('click', '.edit_button', function(){

		var accountant_id = $(this).data('id');

		$('#accountant_form').parsley().reset();

		$('#form_message').html('');

		$.ajax({

	      	url:"accountant_action.php",

	      	method:"POST",

	      	data:{accountant_id:accountant_id, action:'fetch_single'},

	      	dataType:'JSON',

	      	success:function(data)
	      	{

	        	$('#accountant_email_address').val(data.accountant_email_address);

                $('#accountant_email_address').val(data.accountant_email_address);
                $('#accountant_password').val(data.accountant_password);
                $('#accountant_name').val(data.accountant_name);
                $('#uploaded_image').html('<img src="'+data.accountant_profile_image+'" class="img-fluid img-thumbnail" width="150" />')
                $('#hidden_accountant_profile_image').val(data.accountant_profile_image);
                $('#accountant_phone_no').val(data.accountant_phone_no);
                $('#accountant_address').val(data.accountant_address);
                $('#accountant_date_of_birth').val(data.accountant_date_of_birth);
                $('#accountant_degree').val(data.accountant_degree);
                $('#accountant_expert_in').val(data.accountant_expert_in);

	        	$('#modal_title').text('Edit Accountant');

	        	$('#action').val('Edit');

	        	$('#submit_button').val('Edit');

	        	$('#accountantModal').modal('show');

	        	$('#hidden_id').val(accountant_id);

	      	}

	    })

	});

	$(document).on('click', '.status_button', function(){
		var id = $(this).data('id');
    	var status = $(this).data('status');
		var next_status = 'Active';
		if(status == 'Active')
		{
			next_status = 'Inactive';
		}
		if(confirm("Are you sure you want to "+next_status+" it?"))
    	{

      		$.ajax({

        		url:"accountant_action.php",

        		method:"POST",

        		data:{id:id, action:'change_status', status:status, next_status:next_status},

        		success:function(data)
        		{

          			$('#message').html(data);

          			dataTable.ajax.reload();

          			setTimeout(function(){

            			$('#message').html('');

          			}, 5000);

        		}

      		})

    	}
	});

    $(document).on('click', '.view_button', function(){
        var accountant_id = $(this).data('id');

        $.ajax({

            url:"accountant_action.php",

            method:"POST",

            data:{accountant_id:accountant_id, action:'fetch_single'},

            dataType:'JSON',

            success:function(data)
            {
                var html = '<div class="table-responsive">';
                html += '<table class="table">';

                html += '<tr><td colspan="2" class="text-center"><img src="'+data.accountant_profile_image+'" class="img-fluid img-thumbnail" width="150" /></td></tr>';

                html += '<tr><th width="40%" class="text-right">Accountant Email Address</th><td width="60%">'+data.accountant_email_address+'</td></tr>';

                html += '<tr><th width="40%" class="text-right">Accountant Password</th><td width="60%">'+data.accountant_password+'</td></tr>';

                html += '<tr><th width="40%" class="text-right">Accountant Name</th><td width="60%">'+data.accountant_name+'</td></tr>';

                html += '<tr><th width="40%" class="text-right">Accountant Phone No.</th><td width="60%">'+data.accountant_phone_no+'</td></tr>';

                html += '<tr><th width="40%" class="text-right">Accountant Address</th><td width="60%">'+data.accountant_address+'</td></tr>';

                html += '<tr><th width="40%" class="text-right">Accountant Date of Birth</th><td width="60%">'+data.accountant_date_of_birth+'</td></tr>';
                html += '<tr><th width="40%" class="text-right">Accountant Qualification</th><td width="60%">'+data.accountant_degree+'</td></tr>';

                html += '<tr><th width="40%" class="text-right">Accountant Speciality</th><td width="60%">'+data.accountant_expert_in+'</td></tr>';

                html += '</table></div>';

                $('#viewModal').modal('show');

                $('#accountant_details').html(html);

            }

        })
    });

	$(document).on('click', '.delete_button', function(){

    	var id = $(this).data('id');

    	if(confirm("Are you sure you want to remove it?"))
    	{

      		$.ajax({

        		url:"accountant_action.php",

        		method:"POST",

        		data:{id:id, action:'delete'},

        		success:function(data)
        		{

          			$('#message').html(data);

          			dataTable.ajax.reload();

          			setTimeout(function(){

            			$('#message').html('');

          			}, 5000);

        		}

      		})

    	}

  	});



});
</script>