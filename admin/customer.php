<?php

//customer.php

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
    <h1 class="mt-4">Customer Management</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
        <li class="breadcrumb-item active">Customer List</li>
    </ol>

    <span id="message"></span>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Customer List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="customer_table" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email Address</th>
                            <th>Contact No.</th>
                            <th>Email Verification Status</th>
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

<div id="viewModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal_title">View Customer Details</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">&times;</button>
            </div>
            <div class="modal-body" id="customer_details">
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-bs-dismiss="modal" aria-label="Close">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){

	var dataTable = $('#customer_table').DataTable({
		"processing" : true,
		"serverSide" : true,
		"order" : [],
		"ajax" : {
			url:"customer_action.php",
			type:"POST",
			data:{action:'fetch'}
		},
		"columnDefs":[
			{
				"targets":[5],
				"orderable":false,
			},
		],
	});

    $(document).on('click', '.view_button', function(){

        var customer_id = $(this).data('id');

        $.ajax({

            url:"customer_action.php",

            method:"POST",

            data:{customer_id:customer_id, action:'fetch_single'},

            dataType:'JSON',

            success:function(data)
            {
                var html = '<div class="table-responsive">';
                html += '<table class="table">';

                html += '<tr><th width="40%" class="text-right">Email Address</th><td width="60%">'+data.customer_email_address+'</td></tr>';

                html += '<tr><th width="40%" class="text-right">Password</th><td width="60%">'+data.customer_password+'</td></tr>';

                html += '<tr><th width="40%" class="text-right">Customer Name</th><td width="60%">'+data.customer_first_name+' '+data.customer_last_name+'</td></tr>';

                html += '<tr><th width="40%" class="text-right">Contact No.</th><td width="60%">'+data.customer_phone_no+'</td></tr>';

                html += '<tr><th width="40%" class="text-right">Address</th><td width="60%">'+data.customer_address+'</td></tr>';

                html += '<tr><th width="40%" class="text-right">Date of Birth</th><td width="60%">'+data.customer_date_of_birth+'</td></tr>';
                html += '<tr><th width="40%" class="text-right">Gender</th><td width="60%">'+data.customer_gender+'</td></tr>';

                html += '<tr><th width="40%" class="text-right">Maritial Status</th><td width="60%">'+data.customer_maritial_status+'</td></tr>';

                html += '<tr><th width="40%" class="text-right">Email Verification Status</th><td width="60%">'+data.email_verify+'</td></tr>';

                html += '</table></div>';

                $('#viewModal').modal('show');

                $('#customer_details').html(html);

            }

        })
    });
});
</script>