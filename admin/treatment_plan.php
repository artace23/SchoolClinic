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

	$sqlTreat = "SELECT * FROM treatment_plan";
	$query2 = mysqli_query($con, $sqlTreat);
	$treatments = mysqli_fetch_all($query2, MYSQLI_ASSOC);

	$sql2 = "SELECT TreatmentID FROM treatment_plan ORDER BY TreatmentID DESC LIMIT 1";
	$query3 = mysqli_query($con, $sql2);
	$result = mysqli_fetch_assoc($query3);

	if(isset($_POST["addTreat"])){
		$date = $_POST["date"];
		$weight = $_POST["weight"];
		$height = $_POST["height"];
		$patientID = $_POST['patientID'];
		$physicianID = $_POST['physicianID'];
		$BMI = $_POST['BMI'];
		$statusID = $_POST['status'];
		$treatment = $_POST['treatment'];
		$diagnosis = $_POST['diagnosis'];

		$sqlInsert = "INSERT INTO `treatment_plan`( `Date`, `Diagnosis`, `Treatment`, `PatientID`, `PhysicianID`, `StatusID`, `Height`, `Weight`, `BMI`) VALUES ('$date','$diagnosis','$treatment','$patientID','$physicianID','$statusID','$height','$weight','$BMI')";
		$queryInsert = mysqli_query($con, $sqlInsert);
		if($queryInsert){
			echo "<script>alert('Successfully Added!')</script>";
		}

	}

	if(isset($_POST["search"])){
		$search = $_POST["search"];
		$sqlSearch = "SELECT * FROM treatment_plan WHERE TreatmentID = '$search'";
		$querySearch = mysqli_query($con, $sqlSearch);
		$resultSearch = mysqli_fetch_all($querySearch, MYSQLI_ASSOC);
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
		<a href="treatment_plan.php" class="active">Treatment Plan</a>
		<a href="laboratory.php">Laboratory</a>
		<a href="medical_record.php">Medical Record</a>
		<a href="medicine_supply.php">Medicine Supply</a>
		<a href="../logout.php">Logout</a>
    </div>
	<div id="main">
		<div class="container">
			<h4>Treatment Plan</h4>
			<form method="post">
				<label>Search</label>
				<input type="text" name="search" class="form-control"><br>
			</form>
			<a href="" style="text-decoration: none" data-bs-toggle="modal" data-bs-target="#addTModal">Add Treatment Plan +</a>
            <br><br>
			<table class="table table-bordered border-secondary">
                <tr>
                    <th>ID</th>
                    <th>Date</th>
                    <th>Weight</th>
                    <th>Height</th>
                    <th>BMI</th>
                    <th>Treatment</th>
					<th>Diagnosis</th>
					<th>PatientID</th>
					<th>PhysicianID</th>
					<th>StatusID</th>
                </tr>
				<?php
				if(!isset($_POST['search'])) {
					foreach($treatments as $treat) {
				?>
					<tr>
						<td><?php echo htmlspecialchars($treat['TreatmentID'])?></td>
						<td><?php echo htmlspecialchars($treat['Date'])?></td>
						<td><?php echo htmlspecialchars($treat['Weight'])?></td>
						<td><?php echo htmlspecialchars($treat['Height'])?></td>
						<td><?php echo htmlspecialchars($treat['BMI'])?></td>
						<td><?php echo htmlspecialchars($treat['Treatment'])?></td>
						<td><?php echo htmlspecialchars($treat['Diagnosis'])?></td>
						<td><?php echo htmlspecialchars($treat['PatientID'])?></td>
						<td><?php echo htmlspecialchars($treat['PhysicianID'])?></td>
						<td><?php echo htmlspecialchars($treat['StatusID'])?></td>
					</tr>
				<?php } } else { foreach($resultSearch as $searchs) { ?>
					<tr>
						<td><?php echo htmlspecialchars($searchs['TreatmentID'])?></td>
						<td><?php echo htmlspecialchars($searchs['Date'])?></td>
						<td><?php echo htmlspecialchars($searchs['Weight'])?></td>
						<td><?php echo htmlspecialchars($searchs['Height'])?></td>
						<td><?php echo htmlspecialchars($searchs['BMI'])?></td>
						<td><?php echo htmlspecialchars($searchs['Treatment'])?></td>
						<td><?php echo htmlspecialchars($searchs['Diagnosis'])?></td>
						<td><?php echo htmlspecialchars($searchs['PatientID'])?></td>
						<td><?php echo htmlspecialchars($searchs['PhysicianID'])?></td>
						<td><?php echo htmlspecialchars($searchs['StatusID'])?></td>
					</tr>
				<?php } }?>
            </table>
			
		</div>
	</div>
	<div class="modal fade" id="addTModal" tabindex="-1" aria-labelledby="addTModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Add Treatment Plan</h1>
            <button type="button" class="btn-close" data-bs-toggle="modal" data-bs-target="#teachersModal"></button>
            </div>
            <div class="modal-body">
            <form method="POST">
                <div class="mb-3">
                    <div>
                        <label>Treatment ID</label>
                        <input type="text" name="treatmentID" class="form-control" disabled value ="<?php echo (isset($result['TreatmentID']) && $result['TreatmentID'] !== null) ? $result['TreatmentID']+1 : 0; ?>">
                    </div>
                    <div class="d-flex justify-content-between">
                        <div>
                            <label>Date</label>
                            <input type="date" name="date" class="form-control">
                        </div>
                        <div>
                            <label>Weight</label>
                            <input type="text" name="weight" class="form-control">
                        </div>
                        <div>
                        <label>Height</label>
                        <input type="text" name="height" class="form-control">
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <div>
                            <label>Patient ID</label>
                            <input type="text" name="patientID" class="form-control">
                        </div>
                        <div>
                        <label for="status">Status ID</label>
                        <select name="status" class="form-control">
                            <option value="0">Done</option>
                            <option value="1">Confirmed</option>
                            <option value="2">Declined</option>
                        </select>
                        </div>
						<div>
							<label>PhysicianID</label>
                            <input type="text" name="physicianID" class="form-control">
						</div>
                        <div>
                            <label>BMI</label>
                            <input type="text" name="BMI" class="form-control">
                        </div>
                    </div>
                    
                    <label>Treatment</label>
                    <input type="text" name="treatment" class="form-control">
					<label>Diagnosis</label>
                    <input type="text" name="diagnosis" class="form-control">
                </div>
                <div class="card-footer">
                    <button type="submit" name="addTreat" class="btn btn-primary float-end">
                        Confirm
                    </button>
                    <br><br><br>
                </div>
            </form>
            </div>
            <div class="modal-footer">
            </div>
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