<?php
$user_name = '';
$user_profile_image = '';

if($_SESSION['type'] == 'Admin')
{
    $object->query = "
    SELECT * FROM admin_table 
    WHERE admin_id = '".$_SESSION['admin_id']."'
    ";

    $user_result = $object->get_result();

    foreach($user_result as $row)
    {
        $user_name = $row['admin_name'];
        $user_profile_image = '../img/undraw_profile.svg';
    }
}

if($_SESSION['type'] == 'Accountant')
{
    $object->query = "
    SELECT * FROM accountant_table 
    WHERE accountant_id = '".$_SESSION['admin_id']."'
    ";

    $user_result = $object->get_result();
    
    foreach($user_result as $row)
    {
        $user_name = $row['accountant_name'];
        $user_profile_image = $row['accountant_profile_image'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Dashboard - SB Admin</title>
        <link href="../css/styles.css" rel="stylesheet" />
        <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" type="text/css" href="../vendor/parsley/parsley.css"/>
        <link rel="stylesheet" type="text/css" href="../vendor/datatables/dataTables.bootstrap5.min.css"/>
    </head>

    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <!-- Navbar Brand-->
            <a class="navbar-brand ps-3">BRISHNA</a>
            <!-- Sidebar Toggle-->
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
            <!-- Navbar Search-->
            <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
                
            </form>
            <!-- Navbar-->
            <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img class="rounded-circle" src="<?php echo $user_profile_image; ?>" id="user_profile_image" style="width:30px"> <?php echo $user_name; ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <?php
                        if($_SESSION['type'] == 'Admin')
                        {
                        ?>
                        <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                        <?php
                        }
                        if($_SESSION['type'] == 'Accountant')
                        {
                        ?>
                        <li><a class="dropdown-item" href="accountant_profile.php">Profile</a></li>
                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                        <?php
                        }
                        ?>
                    </ul>
                </li>
            </ul>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">

                            <?php
                            if($_SESSION['type'] == 'Admin')
                            {
                            ?>
                            <a class="nav-link" href="dashboard.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Dashboard
                            </a>
                            <a class="nav-link" href="accountant.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-user-md"></i></div>
                                Accountant
                            </a>
                            <a class="nav-link" href="accountant_schedule.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-user-clock"></i></div>
                                Accountant Schedule
                            </a>
                            <a class="nav-link" href="customer.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-procedures"></i></div>
                                Customers
                            </a>
                            <?php
                            }
                            ?>

                            
                            <a class="nav-link" href="appointment.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-notes-medical"></i></div>
                                Appointment
                            </a>

                            <?php
                            if($_SESSION['type'] == 'Admin')
                            {
                            ?>
                            <a class="nav-link" href="profile.php">
                                <div class="sb-nav-link-icon"><i class="far fa-id-card"></i></div>
                                Profile
                            </a>
                            <?php
                            }
                            else
                            {
                            ?>
                            <a class="nav-link" href="Accountant_profile.php">
                                <div class="sb-nav-link-icon"><i class="far fa-id-card"></i></div>
                                Profile
                            </a>
                            <?php
                            }
                            ?>

                            <a class="nav-link" href="logout.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-sign-out-alt"></i></div>
                                Logout
                            </a>
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        <div class="small">Logged in as:</div>
                        <?php echo $user_name; ?>
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>