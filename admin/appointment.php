<?php

//appointment.php

include('../class/Appointment.php');

$object = new Appointment;

if(!isset($_SESSION['admin_id']))
{
    header('location:'.$object->base_url.'');
}

include('header.php');

?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Appointment Management</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
        <li class="breadcrumb-item active">Appointment List</li>
    </ol>

    <span id="message"></span>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row">
                <div class="col-sm-6">
                    <h6 class="m-0 font-weight-bold text-primary">Appointment List</h6>
                </div>
                <div class="col-sm-6">
                    <div class="row">
                        <div class="col-md-9">
                            <div class="row input-daterange">
                                <div class="col-md-6">
                                    <input type="date" name="start_date" id="start_date" class="form-control form-control-sm" />
                                </div>
                                <div class="col-md-6">
                                    <input type="date" name="end_date" id="end_date" class="form-control form-control-sm" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button type="button" name="search" id="search" value="Search" class="btn btn-info btn-sm"><i class="fas fa-search"></i></button>
                            &nbsp;<button type="button" name="refresh" id="refresh" class="btn btn-secondary btn-sm"><i class="fas fa-sync-alt"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="appointment_table">
                    <thead>
                        <tr>
                            <th>Appointment No.</th>
                            <th>Customer Name</th>
                            <?php
                            if($_SESSION['type'] == 'Admin')
                            {
                            ?>
                            <th>Accountant Name</th>
                            <?php
                            }
                            ?>
                            <th>Appointment Date</th>
                            <th>Appointment Time</th>
                            <th>Appointment Day</th>
                            <th>Appointment Status</th>
                            <th>View</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
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
        <form method="post" id="edit_appointment_form">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal_title">View Appointment Details</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="appointment_details"></div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="hidden_appointment_id" id="hidden_appointment_id" />
                    <input type="hidden" name="action" value="change_appointment_status" />
                    <input type="submit" name="save_appointment" id="save_appointment" class="btn btn-primary" value="Save" />
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal" aria-label="Close">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function(){

    fetch_data('no');

    function fetch_data(is_date_search, start_date='', end_date='')
    {
        var dataTable = $('#appointment_table').DataTable({
            "processing" : true,
            "serverSide" : true,
            "order" : [],
            "ajax" : {
                url:"appointment_action.php",
                type:"POST",
                data:{
                    is_date_search:is_date_search, start_date:start_date, end_date:end_date, action:'fetch'
                }
            },
            "columnDefs":[
                {
                    <?php
                    if($_SESSION['type'] == 'Admin')
                    {
                    ?>
                    "targets":[7],
                    <?php
                    }
                    else
                    {
                    ?>
                    "targets":[6],
                    <?php
                    }
                    ?>
                    "orderable":false,
                },
            ],
        });
    }

    $(document).on('click', '.view_button', function(){

        var appointment_id = $(this).data('id');

        $.ajax({

            url:"appointment_action.php",

            method:"POST",

            data:{appointment_id:appointment_id, action:'fetch_single'},

            success:function(data)
            {
                $('#viewModal').modal('show');

                $('#appointment_details').html(data);

                $('#hidden_appointment_id').val(appointment_id);

            }

        })
    });

    $('#search').click(function(){
        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();
        if(start_date != '' && end_date !='')
        {
            $('#appointment_table').DataTable().destroy();
            fetch_data('yes', start_date, end_date);
        }
        else
        {
            alert("Both Date is Required");
        }
    });

    $('#refresh').click(function(){
        $('#appointment_table').DataTable().destroy();
        fetch_data('no');
    });

    $('#edit_appointment_form').parsley();

    $('#edit_appointment_form').on('submit', function(event){
        event.preventDefault();
        if($('#edit_appointment_form').parsley().isValid())
        {       
            $.ajax({
                url:"appointment_action.php",
                method:"POST",
                data: $(this).serialize(),
                beforeSend:function()
                {
                    $('#save_appointment').attr('disabled', 'disabled');
                    $('#save_appointment').val('wait...');
                },
                success:function(data)
                {
                    $('#save_appointment').attr('disabled', false);
                    $('#save_appointment').val('Save');
                    $('#viewModal').modal('hide');
                    $('#message').html(data);
                    $('#appointment_table').DataTable().destroy();
                    fetch_data('no');
                    setTimeout(function(){
                        $('#message').html('');
                    }, 5000);
                }
            })
        }
    });

});
</script>