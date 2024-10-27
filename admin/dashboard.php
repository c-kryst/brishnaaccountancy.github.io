<?php 

include('../class/Appointment.php');

$object = new Appointment;

if(!$object->is_login())
{
	header("location:".$object->base_url."");
}

if($_SESSION['type'] != 'Admin')
{
    header("location:".$object->base_url."");
}

include('header.php');

?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Dashboard</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Dashboard</li>
    </ol>

    <div class="row row-cols-5">
        <div class="col mb-4">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <div class="row g-0 align-items-center">
                        <div class="col me-2">
                            <div class="text-xs fw-bold mb-1">Today Total Appointment</div>
                            <div class="h4 mb-0 fw-bold text-gray-800">
                                <?php echo $object->get_total_today_appointment(); ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col mb-4">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">
                    <div class="row g-0 align-items-center">
                        <div class="col me-2">
                            <div class="text-xs fw-bold mb-1">Yesterday Total Appointment</div>
                            <div class="h4 mb-0 fw-bold text-gray-800">
                                <?php echo $object->get_total_yesterday_appointment(); ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col mb-4">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <div class="row g-0 align-items-center">
                        <div class="col me-2">
                            <div class="text-xs fw-bold mb-1">Last 7 Days Total Appointment</div>
                            <div class="h4 mb-0 fw-bold text-gray-800">
                                <?php echo $object->get_total_seven_day_appointment(); ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col mb-4">
            <div class="card bg-info text-white mb-4">
                <div class="card-body">
                    <div class="row g-0 align-items-center">
                        <div class="col me-2">
                            <div class="text-xs fw-bold mb-1">Total Appointment till date</div>
                            <div class="h4 mb-0 fw-bold text-gray-800">
                                <?php echo $object->get_total_appointment(); ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col mb-4">
            <div class="card bg-secondary text-white mb-4">
                <div class="card-body">
                    <div class="row g-0 align-items-center">
                        <div class="col me-2">
                            <div class="text-xs fw-bold mb-1">Total Registered Customer</div>
                            <div class="h4 mb-0 fw-bold text-gray-800">
                                <?php echo $object->get_total_customer(); ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php

include('footer.php');

?>