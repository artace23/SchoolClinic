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

	$sqlLab = "SELECT * FROM laboratory";
	$query2 = mysqli_query($con, $sqlLab);
	$laboratory = mysqli_fetch_all($query2, MYSQLI_ASSOC);

    $sqlLab1 = "SELECT LaboratoryID FROM laboratory ORDER BY LaboratoryID DESC LIMIT 1";
	$query3 = mysqli_query($con, $sqlLab1);
	$result = mysqli_fetch_assoc($query3);

    if(isset($_POST["search"])){
		$search = $_POST["search"];
		$sqlSearch = "SELECT * FROM laboratory WHERE LaboratoryID = '$search'";
		$querySearch = mysqli_query($con, $sqlSearch);
		$resultSearch = mysqli_fetch_all($querySearch, MYSQLI_ASSOC);
	}

    if(isset($_POST["addLab"])){
        $laboratoryID = $_POST["laboratoryID"];
        $patientID = $_POST["patientID"];
        $treatmentID = $_POST["treatmentID"];
        $staffID = $_POST["staffID"];
        $date = $_POST["date"];
        $services = $_POST["exampleRadios"];

        $verifyStaff = "SELECT StaffID FROM staff WHERE StaffID = '$staffID'";
        $query4 = mysqli_query($con, $verifyStaff);
        $result4 = mysqli_fetch_assoc($query4);

        $verifyTreat = "SELECT TreatmentID FROM treatment_plan WHERE TreatmentID = '$treatmentID'";
        $query5 = mysqli_query($con, $verifyTreat);
        $result5 = mysqli_fetch_assoc($query5);

        if ($result4 && isset($result4["StaffID"])) {
            $staff = $result4["StaffID"];

            if($result5 && isset($result5['TreatmentID'])) {
                $treatment = $result5["TreatmentID"];
    
                $sqlInsert = "INSERT INTO `laboratory`(`LaboratoryID`, `Date`, `ServiceOffered`, `StaffID`, `TreatmentID`, `patientid`) VALUES ('$laboratoryID','$date','$services','$staff','$treatment','$patientID')";
                $queryInsert = mysqli_query($con, $sqlInsert);
                if($queryInsert) {
                    echo "<script>alert('Successfully Added!)</script>";
                } 
            }
            
        }
    
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
		<a href="laboratory.php" class="active">Laboratory</a>
		<a href="medical_record.php">Medical Record</a>
		<a href="medicine_supply.php">Medicine Supply</a>
		<a href="../logout.php">Logout</a>
    </div>
	<div id="main">
		<div class="container">
			<h4>Laboratory</h4>
            <form method="post">
                <label>Search</label>
                <input type="text" name="search" class="form-control"><br>
            </form>
			<a href="" style="text-decoration: none"  data-bs-toggle="modal" data-bs-target="#addTModal">Add Laboratory +</a>
            <br><br>
			<table class="table table-bordered border-secondary">
                <tr>
                    <th>Laboratory ID</th>
                    <th>Date</th>
                    <th>Sevice</th>
                    <th>Staff ID</th>
                    <th>Patient ID</th>
                    <th>Treatment ID</th>
                </tr>
				<?php
                if(!isset($_POST['search'])) {
					foreach($laboratory as $lab) {
				?>
					<tr>
						<td><?php echo htmlspecialchars($lab['LaboratoryID'])?></td>
						<td><?php echo htmlspecialchars($lab['Date'])?></td>
						<td><?php echo htmlspecialchars($lab['ServiceOffered'])?></td>
						<td><?php echo htmlspecialchars($lab['StaffID'])?></td>
						<td><?php echo htmlspecialchars($lab['TreatmentID'])?></td>
						<td><?php echo htmlspecialchars($lab['patientid'])?></td>
					</tr>
				<?php } } else { foreach($resultSearch as $searchs){?>
                    <tr>
						<td><?php echo htmlspecialchars($searchs['LaboratoryID'])?></td>
						<td><?php echo htmlspecialchars($searchs['Date'])?></td>
						<td><?php echo htmlspecialchars($searchs['ServiceOffered'])?></td>
						<td><?php echo htmlspecialchars($searchs['StaffID'])?></td>
						<td><?php echo htmlspecialchars($searchs['TreatmentID'])?></td>
						<td><?php echo htmlspecialchars($searchs['patientid'])?></td>
					</tr>
                <?php }} ?>
            </table>
			
		</div>
	</div>

    <div class="modal fade" id="addTModal" tabindex="-1" aria-labelledby="addTModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Add Laboratory</h1>
            <button type="button" class="btn-close" data-bs-toggle="modal" data-bs-target="#teachersModal"></button>
            </div>
            <div class="modal-body">
            <form method="POST">
                <div class="mb-3 row">
                    <div class="col">
                        <label>Laboratory ID</label>
                        <input type="text" name="laboratoryID" class="form-control" readonly value ="<?php echo (isset($result['LaboratoryID']) && $result['LaboratoryID'] !== null) ? $result['LaboratoryID']+1 : 0; ?>">
                        <div>
                            <label>Patient ID</label>
                            <input type="text" name="patientID" class="form-control">
                        </div>
                        <div>
                            <label>Treatment ID</label>
                            <input type="text" name="treatmentID" class="form-control">
                        </div>
                        <div>
                            <label>Staff ID</label>
                            <input type="text" name="staffID" class="form-control">
                        </div>
                        <label>Date</label>
                        <input type="date" name="date" class="form-control">
                    </div>
                    <div class="col">
                        <h6>SERVICES</h6>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios1" value="CBC">
                            <label class="form-check-label" for="exampleRadios1">
                                CBC
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios2" value="HEMATOCRIT">
                            <label class="form-check-label" for="exampleRadios2">
                                HEMATOCRIT
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios1" value="PLATELET COUNT">
                            <label class="form-check-label" for="exampleRadios1">
                                PLATELET COUNT
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios2" value="RBC">
                            <label class="form-check-label" for="exampleRadios2">
                                RBC
                            </label>
                        </div><div class="form-check">
                            <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios1" value="WBC">
                            <label class="form-check-label" for="exampleRadios1">
                                WBC
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios2" value="SPUTUM EXAM">
                            <label class="form-check-label" for="exampleRadios2">
                                SPUTUM EXAM
                            </label>
                        </div><div class="form-check">
                            <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios1" value="URINALYSIS">
                            <label class="form-check-label" for="exampleRadios1">
                                URINALYSIS
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios2" value="FECALYSIS">
                            <label class="form-check-label" for="exampleRadios2">
                                FECALYSIS
                            </label>
                        </div><div class="form-check">
                            <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios1" value="CHEST X-RAY">
                            <label class="form-check-label" for="exampleRadios1">
                                CHEST X-RAY
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios2" value="2D ECHO">
                            <label class="form-check-label" for="exampleRadios2">
                                2D ECHO
                            </label>
                        </div><div class="form-check">
                            <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios1" value="LIPID PROFILE">
                            <label class="form-check-label" for="exampleRadios1">
                                LIPID PROFILE
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios2" value="SGPT">
                            <label class="form-check-label" for="exampleRadios2">
                                SGPT
                            </label>
                        </div><div class="form-check">
                            <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios1" value="SGOT">
                            <label class="form-check-label" for="exampleRadios1">
                                SGOT
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios2" value="SERUM URIC ACID">
                            <label class="form-check-label" for="exampleRadios2">
                                SERUM URIC ACID
                            </label>
                        </div><div class="form-check">
                            <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios1" value="SERUM CREATINE">
                            <label class="form-check-label" for="exampleRadios1">
                                SERUM CREATINE
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios2" value="HBsAg (Qualitative)">
                            <label class="form-check-label" for="exampleRadios2">
                                HBsAg (Qualitative)
                            </label>
                        </div><div class="form-check">
                            <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios1" value="Anti-HBs (Quantitative)">
                            <label class="form-check-label" for="exampleRadios1">
                                Anti-HBs (Quantitative)
                            </label>
                        </div>
                    </div>
                </div>
                    
                </div>
                <div class="card-footer">
                    <button type="submit" name="addLab" class="btn btn-primary float-end">
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