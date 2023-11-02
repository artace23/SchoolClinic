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

	$sqlMed = "SELECT * FROM medical_record";
	$query2 = mysqli_query($con, $sqlMed);
	$medical = mysqli_fetch_all($query2, MYSQLI_ASSOC);

	if(isset($_POST["search"])){
        $search = $_POST["search"];
        $sqlSearch = "SELECT * FROM medical_record WHERE RecordID = '$search'";
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
		<a href="appointment.php">Appointment</a>
		<a href="treatment_plan.php">Treatment Plan</a>
		<a href="laboratory.php">Laboratory</a>
		<a href="medical_record.php" class="active">Medical Record</a>
		<a href="medicine_supply.php">Medicine Supply</a>
		<a href="../logout.php">Logout</a>
    </div>
	<div id="main">
		<div class="container">
			<h4>Medical Record</h4>
			<form method="post">
				<label>Search</label>
				<input type="text" name="search" class="form-control"><br>
			</form>
            <br><br>
			<table class="table table-bordered border-secondary">
                <tr>
                    <th>Record ID</th>
                    <th>Patient ID</th>
                    <th>Date</th>
                    <th>Physical Examination</th>
                    <th>Treatment ID</th>
                    <th>Staff ID</th>
                </tr>
				<?php
				if(!isset($_POST['search'])) {
					foreach($medical as $med) {
				?>
					<tr>
						<td><?php echo htmlspecialchars($med['RecordID'])?></td>
						<td><?php echo htmlspecialchars($med['PatientID'])?></td>
						<td><?php echo htmlspecialchars($med['Date'])?></td>
						<td><?php echo htmlspecialchars($med['PhysicalExamination'])?></td>
						<td><?php echo htmlspecialchars($med['TreatmentID'])?></td>
						<td><?php echo htmlspecialchars($med['StaffID'])?></td>
					</tr>
				<?php } } else { foreach($resultSearch as $searchs) {?>
					<tr>
						<td><?php echo htmlspecialchars($searchs['RecordID'])?></td>
						<td><?php echo htmlspecialchars($searchs['PatientID'])?></td>
						<td><?php echo htmlspecialchars($searchs['Date'])?></td>
						<td><?php echo htmlspecialchars($searchs['PhysicalExamination'])?></td>
						<td><?php echo htmlspecialchars($searchs['TreatmentID'])?></td>
						<td><?php echo htmlspecialchars($searchs['StaffID'])?></td>
					</tr>
				<?php }}?> 
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