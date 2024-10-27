<?php

//accountant_schedule.php

include('../class/Appointment.php');

$object = new Appointment;

if(!$object->is_login())
{
    header("location:".$object->base_url."admin");
}

include('header.php');

?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Accountant Schedule Management</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
        <li class="breadcrumb-item active">Accountant Schedule List</li>
    </ol>

    <span id="message"></span>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
			<div class="row">
				<div class="col">
					<h6 class="m-0 font-weight-bold text-primary">Accountant Schedule List</h6>
				</div>
				<div class="col" align="right">
					<button type="button" name="add_exam" id="add_accountant_schedule" class="btn btn-success btn-circle btn-sm"><i class="fas fa-plus"></i></button>
				</div>
			</div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="accountant_schedule_table" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <?php
                            if($_SESSION['type'] == 'Admin')
                            {
                            ?>
                            <th>Accountant Name</th>
                            <?php
                            }
                            ?>
                            <th>Schedule Date</th>
                            <th>Schedule Day</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Consulting Time</th>
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

<div id="accountant_scheduleModal" class="modal fade">
  	<div class="modal-dialog">
    	<form method="post" id="accountant_schedule_form">
      		<div class="modal-content">
        		<div class="modal-header">
          			<h4 class="modal-title" id="modal_title">Add Accountant Schedule</h4>
          			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        		</div>
        		<div class="modal-body">
        			<span id="form_message"></span>
                    <?php
                    if($_SESSION['type'] == 'Admin')
                    {
                    ?>
                    <div class="mb-3">
                        <label>Select Accountant</label>
                        <select name="accountant_id" id="accountant_id" class="form-select" required>
                            <option value="">Select Accountant</option>
                            <?php
                            $object->query = "
                            SELECT * FROM accountant_table 
                            WHERE accountant_status = 'Active' 
                            ORDER BY accountant_name ASC
                            ";

                            $result = $object->get_result();

                            foreach($result as $row)
                            {
                                echo '
                                <option value="'.$row["accountant_id"].'">'.$row["accountant_name"].'</option>
                                ';
                            }
                            ?>
                        </select>
                    </div>
                    <?php
                    }
                    ?>
                    <div class="mb-3">
                        <label>Schedule Date</label>
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar"></i></span>
                            <input type="date" name="accountant_schedule_date" id="accountant_schedule_date" class="form-control" required />
                        </div>
                    </div>
		          	<div class="mb-3">
		          		<label>Start Time</label>
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-clock"></i></span>
		          		    <input type="time" name="accountant_schedule_start_time" id="accountant_schedule_start_time" class="form-control" required onkeydown="return false" onpaste="return false;" ondrop="return false;" autocomplete="off" />
                        </div>
		          	</div>
                    <div class="mb-3">
                        <label>End Time</label>
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-clock"></i></span>
                            <input type="time" name="accountant_schedule_end_time" id="accountant_schedule_end_time" class="form-control" required onkeydown="return false" onpaste="return false;" ondrop="return false;" autocomplete="off" />
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Average Consulting Time</label>
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-clock"></i></span>
                            <select name="average_consulting_time" id="average_consulting_time" class="form-select" required>
                                <option value="">Select Consulting Duration</option>
                                <?php
                                $count = 0;
                                for($i = 1; $i <= 15; $i++)
                                {
                                    $count += 5;
                                    echo '<option value="'.$count.'">'.$count.' Minute</option>';
                                }
                                ?>
                            </select>
                        </div>
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

<script>
$(document).ready(function(){

	var dataTable = $('#accountant_schedule_table').DataTable({
		"processing" : true,
		"serverSide" : true,
		"order" : [],
		"ajax" : {
			url:"accountant_schedule_action.php",
			type:"POST",
			data:{action:'fetch'}
		},
		"columnDefs":[
			{
                <?php
                if($_SESSION['type'] == 'Admin')
                {
                ?>
                "targets":[6, 7],
                <?php
                }
                else
                {
                ?>
                "targets":[5, 6],
                <?php
                }
                ?>
				
				"orderable":false,
			},
		],
	});

    $("#accountant_schedule_start_time").on("change.datetimepicker", function (e) {
        console.log('test');
        $('#accountant_schedule_end_time').datetimepicker('minDate', e.date);
    });

    $("#accountant_schedule_end_time").on("change.datetimepicker", function (e) {
        $('#accountant_schedule_start_time').datetimepicker('maxDate', e.date);
    });

	$('#add_accountant_schedule').click(function(){
		
		$('#accountant_schedule_form')[0].reset();

		$('#accountant_schedule_form').parsley().reset();

    	$('#modal_title').text('Add Accountant Schedule Data');

    	$('#action').val('Add');

    	$('#submit_button').val('Add');

    	$('#accountant_scheduleModal').modal('show');

    	$('#form_message').html('');

	});

	$('#accountant_schedule_form').parsley();

	$('#accountant_schedule_form').on('submit', function(event){
		event.preventDefault();
		if($('#accountant_schedule_form').parsley().isValid())
		{		
			$.ajax({
				url:"accountant_schedule_action.php",
				method:"POST",
				data:$(this).serialize(),
				dataType:'json',
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
						$('#accountant_scheduleModal').modal('hide');
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

		var accountant_schedule_id = $(this).data('id');

		$('#accountant_schedule_form').parsley().reset();

		$('#form_message').html('');

		$.ajax({

	      	url:"accountant_schedule_action.php",

	      	method:"POST",

	      	data:{accountant_schedule_id:accountant_schedule_id, action:'fetch_single'},

	      	dataType:'JSON',

	      	success:function(data)
	      	{
                <?php
                if($_SESSION['type'] == 'Admin')
                {
                ?>
                $('#accountant_id').val(data.accountant_id);
                <?php
                }
                ?>
	        	$('#accountant_schedule_date').val(data.accountant_schedule_date);

                $('#accountant_schedule_start_time').val(data.accountant_schedule_start_time);

                $('#accountant_schedule_end_time').val(data.accountant_schedule_end_time);

				$('#average_consulting_time').val(data.average_consulting_time);

	        	$('#modal_title').text('Edit Accountant Schedule Data');

	        	$('#action').val('Edit');

	        	$('#submit_button').val('Edit');

	        	$('#accountant_scheduleModal').modal('show');

	        	$('#hidden_id').val(accountant_schedule_id);

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

        		url:"accountant_schedule_action.php",

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

	$(document).on('click', '.delete_button', function(){

    	var id = $(this).data('id');

    	if(confirm("Are you sure you want to remove it?"))
    	{

      		$.ajax({

        		url:"accountant_schedule_action.php",

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