<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="">
		<title>Accountant Appointment Management System in PHP</title>

	    <!-- Custom styles for this page -->
	    <link href="vendor/bootstrap/bootstrap.min.css" rel="stylesheet">

	    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

	    <link rel="stylesheet" type="text/css" href="vendor/parsley/parsley.css"/>

	    <!-- Custom styles for this page -->
    	<link href="vendor/datatables/dataTables.bootstrap5.min.css" rel="stylesheet">
	    <style>
	    	.border-top { border-top: 1px solid #e5e5e5; }
			.border-bottom { border-bottom: 1px solid #e5e5e5; }

			.box-shadow { box-shadow: 0 .25rem .75rem rgba(0, 0, 0, .05); }
	    </style>
	</head>
	<body>
		<div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom box-shadow">
			<div class="col">
		    	<h5 class="my-0 mr-md-auto font-weight-normal">Brishna</h5>
		    </div>
		    <?php
		    if(!isset($_SESSION['customer_id']))
		    {
		    ?>
		    <div class="col float-end" style="text-align:right"><a href="login.php">Login</a></div>
		   	<?php
		   	}
		   	?>
		    
	    </div>

	    <div class="text-center">
	      	<h2 class="display-4 mt-4 mb-4"><b>Welcome to Brishna Login</b></h2>
	    </div>
	    <div class="container-fluid">