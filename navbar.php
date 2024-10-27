<nav class="navbar navbar-expand-lg navbar-dark bg-primary"> 
    <div class="container-fluid">
        <a class="navbar-brand" href="#"><?php echo $_SESSION['customer_name']; ?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
	      			<a class="nav-link" href="profile.php">Profile</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="dashboard.php">Book Appointment</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="appointment.php">My Appointment</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="logout.php">Logout</a>
				</li>
            </ul>
        </div>
    </div>
</nav>