<?php
////////
	session_start();

	include("connection.php");
	include("function.php");

	$user_data = check_login($con);

	$current_user_id = $_SESSION['UserID'];

	$sql = "SELECT * FROM user WHERE UserID = '$current_user_id'";
	$query = mysqli_query($con, $sql);
	$profiles = mysqli_fetch_all($query, MYSQLI_ASSOC);

	$sql1 = "SELECT PatientID FROM patient WHERE UserID = '$current_user_id'";
	$query1 = mysqli_query($con, $sql1);
	$profiles1 = mysqli_fetch_assoc($query1);

	$sql2 = "SELECT * FROM user WHERE UserID = '$current_user_id'";
	$query2 = mysqli_query($con, $sql2);
	$profiles2 = mysqli_fetch_assoc($query2);
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="student/style.css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<style>
        .sidenav {
        height: 100%;
        width: 0; 
        position: fixed; 
        z-index: 1;
        top: 5; 
        left: 0;
        background-color: #111;
        overflow-x: hidden;
        padding-top: 60px;
        transition: 0.5s;
        }

        .sidenav a {
        padding: 8px 8px 8px 32px;
        text-decoration: none;
        font-size: 25px;
        color: #818181;
        display: block;
        transition: 0.3s;
        }

		.sidenav a.active {
		position: relative;
		width: 90%;
		border-bottom: 2px solid #f1f1f1;
		bottom: 0;
		left: 0;
		}

        .sidenav a:hover {
        color: #f1f1f1;
        }

        .sidenav .closebtn {
        position: relative;
        top: 5;
        right: 25px;
        font-size: 24px;
        margin-left: 50px;
        }

        #main {
        transition: margin-left .5s;
        padding: 20px;
        }

        @media screen and (max-height: 450px) {
        .sidenav {padding-top: 15px;}
        .sidenav a {font-size: 18px;}
        }
    </style>
	<title>My Website</title>
</head>
<nav>
	<div class="logo"><img src="pics/hcdclogo.png">HCDC MEDICAL CLINIC<i class="fa fa-bars mx-3" onclick="toggleNav()"></i></div>
    
</nav>
<body>
	<div id="mySidenav" class="sidenav">
	<div class="d-flex justify-content-center">
		<img src="pics/hcdclogo.png" style="width: 50%;">
		<div class="text-center mt-5 mx-2 text-white">
			<h6>
				<?php echo htmlspecialchars($profiles1['PatientID'])?><br>	
				<?php echo htmlspecialchars($profiles2['Firstname'])." ".htmlspecialchars($profiles2['Lastname'])?><br>
			</h6>
		</div>
	</div>
    <a href="index.php" class="active">Profile</a>
    <a href="student/appointment.php">Appointment</a>
    <a href="student/medical_records.php">Medical Records</a>
    <a href="student/laboratory_results.php">Laboratory Results</a>
	<br><br><br><br><br><br><br><br><br>
	<a href="logout.php">Logout</a>
    </div>
	<div id="main">
		<div class="container">
			<h4>Profile</h4>
			<div class="profile">
				<?php foreach ($profiles as $profile) { ?>
				<div class="row">
					<div class="column">
						<h1>3</h1>
						<h6>Current Level</h6>
					</div>
					<div class="column">
						<h1>BSIT</h1>
						<h6>Balance</h6>
					</div>
					<div class="column">
						<h1>Enrolled</h1>
						<h6>Status</h6>
					</div>
					<div class="column-1">
						<div class="profile-col">
							<h6>ID: <?php echo htmlspecialchars($profiles1['PatientID']) ?></h6>
							<h6>Name: <?php echo htmlspecialchars($profile['Firstname'])." ".htmlspecialchars($profile['Lastname'])?></h6>
							<h6>Program: BSIT</h6>
						</div>
						<div class="profile-col">
							<h1>SY: 2022 - 2023</h1>
						</div>
					</div>
					<div class="column">
						<h1>Email: </h1>
						<h6><?php echo htmlspecialchars($profile['Email'])?></h6>
					</div>
					</div>
				</div>
				<?php } ?>
			</div>
		</div>
	</div>
	<script>
		var navIsOpen = false;

		function toggleNav() {
		var nav = document.getElementById("mySidenav");
		
		var main = document.getElementById("main");
		if (navIsOpen) {
			nav.style.width = "0";
			main.style.marginLeft = "0";
			navIsOpen = false;
		} else {
			nav.style.width = "250px"; 
			main.style.marginLeft = "250px";
			navIsOpen = true;
		}
		}
    </script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
</body>
</html>
