<?php
	session_start();

	include("../connection.php");
	include("../function.php");

	$user_data = check_login($con);

	$current_user_id = $_SESSION['UserID'];

	$sql = "SELECT * FROM user WHERE UserID = '$current_user_id'";
	$query = mysqli_query($con, $sql);
	$profiles = mysqli_fetch_assoc($query);

	
    $sql1 = "SELECT StaffID FROM staff WHERE UserID = '$current_user_id'";
	$query1 = mysqli_query($con, $sql1);
	$profiles1 = mysqli_fetch_assoc($query1);

    $sqlAppoint = "SELECT * FROM appointment";
    $queryAppoint = mysqli_query($con, $sqlAppoint);
    $appointments = mysqli_fetch_all($queryAppoint, MYSQLI_ASSOC);

    if(isset($_POST["search"])){
        $search = $_POST["search"];
        $sqlSearch = "SELECT * FROM appointment WHERE PatientID = '$search'";
        $querySearch = mysqli_query($con, $sqlSearch);
        $searchResult = mysqli_fetch_all($querySearch, MYSQLI_ASSOC);
    }
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="../student/style.css">
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
	<div class="logo"><img src="../pics/hcdclogo.png">HCDC MEDICAL CLINIC<i class="fa fa-bars mx-3" onclick="toggleNav()"></i></div>
    
</nav>
<body>
	<div id="mySidenav" class="sidenav">
		<div class="d-flex justify-content-center">
			<img src="../pics/hcdclogo.png" style="width: 50%;">
			<div class="text-center mt-5 mx-2 text-white">
			<h6>
				<?php echo htmlspecialchars($profiles1['StaffID'])?><br>	
				<?php echo htmlspecialchars($profiles['Firstname'])." ".htmlspecialchars($profiles['Lastname'])?><br>
			</h6>
			</div>
		</div>
		<a href="../admin_index.php">Dashboard</a>
		<a href="patient.php">Patient</a>
		<a href="staff.php">Staff/Physician</a>
		<a href="appointment.php" class="active">Appointment</a>
		<a href="treatment_plan.php">Treatment Plan</a>
		<a href="laboratory.php">Laboratory</a>
		<a href="medical_record.php">Medical Record</a>
		<a href="medicine_supply.php">Medicine Supply</a>
		<a href="../logout.php">Logout</a>
    </div>
	<div id="main">
		<div class="container">
			<h4>Appointment</h4>
            <form method="post">
                <label for="search">Search</label>
                <input type="text" name="search" class="form-control">
            </form>
            <br><br>

			<table class="table table-bordered border-secondary">
                <tr>
                    <th>ID</th>
                    <th>Patient ID</th>
                    <th>Date/Time</th>
                    <th>Reason</th>
                    <th>Physician ID</th>
                    <th>Status ID</th>
                </tr>
                
                <?php 
                if(!isset($_POST['search'])){
                    foreach($appointments as $appointment) {
                        $status = $appointment['StatusID'];
                        $sqlStatus = "SELECT StatusName FROM status WHERE StatusID = '$status'";
                        $queryStatus = mysqli_query($con, $sqlStatus);
                        $status = mysqli_fetch_assoc($queryStatus);
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($appointment['AppointmentID'])?></td>
                    <td><?php echo htmlspecialchars($appointment['PatientID'])?></td>
                    <td><?php echo htmlspecialchars($appointment['Date'])?></td>
                    <td><?php echo htmlspecialchars($appointment['Reason'])?></td>
                    <td><?php echo htmlspecialchars($appointment['PhysicianID'])?></td>
                    <td><?php echo htmlspecialchars($status['StatusName'])?></td>
                </tr>
                <?php } } else { ?>
                <?php
                    foreach($searchResult as $searchs){ 
                    $status = $searchs['StatusID'];
                    $sqlStatus = "SELECT StatusName FROM status WHERE StatusID = '$status'";
                    $queryStatus = mysqli_query($con, $sqlStatus);
                    $status = mysqli_fetch_assoc($queryStatus);
                    ?>
                    </tr>
                        <td><?php echo htmlspecialchars($searchs['AppointmentID'])?></td>
                        <td><?php echo htmlspecialchars($searchs['PatientID'])?></td>
                        <td><?php echo htmlspecialchars($searchs['Date'])?></td>
                        <td><?php echo htmlspecialchars($searchs['Reason'])?></td>
                        <td><?php echo htmlspecialchars($searchs['PhysicianID'])?></td>
                        <td><?php echo htmlspecialchars($status['StatusName'])?></td>
                    <tr>
                <?php } ?>
            <?php }?>
            </table>
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