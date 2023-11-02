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

    $sqlStaff = "SELECT * FROM staff";
    $queryStaff = mysqli_query($con, $sqlStaff);
    $staffs = mysqli_fetch_all($queryStaff, MYSQLI_ASSOC);


    if(isset($_POST["addStaff"])) {
        $staffID = $_POST["staffID"];
        $lname = $_POST["lname"];
        $fname = $_POST["fname"];
        $mname = $_POST["mname"];
        $sex = $_POST["sex"];
        $role = $_POST["role"];
        $address =  $_POST["address"];
        $schedule = $_POST["schedule"];

        $email = strtolower($fname . "." . $lname . "@hcdc.edu.ph");
        $password = strtolower($fname . $lname . "123");

        $currentDate = date("Y-m-d");

        $select1 = "SELECT UserID FROM user ORDER BY UserID DESC LIMIT 1";
        $query4 = mysqli_query($con, $select1);
        $result2 = mysqli_fetch_assoc($query4);

        $userID = $result2['UserID']+1;

        $sqlInsert = "INSERT INTO `user`(`UserID`, `Email`, `Password`, `Lastname`, `Firstname`, `Address`, `DateOfBirth`, `Age`, `Sex`, `RoleID`) VALUES ('$userID','$email','$password','$lname','$fname','$address','$currentDate','21','$sex','$role')";
        $queryInsert = mysqli_query($con, $sqlInsert);

        $select = "SELECT UserID FROM user ORDER BY UserID DESC LIMIT 1";
        $query3 = mysqli_query($con, $select);
        $result = mysqli_fetch_assoc($query3);

        $lastInsertID = $result["UserID"];
        
        if($queryInsert) {
            if($role == 2){
                $sqlInsert2 = "INSERT INTO `staff`(`StaffID`, `UserID`, `ScheduleID`) VALUES ('$staffID','$lastInsertID','$schedule')";
                $queryInsert2 = mysqli_query($con, $sqlInsert2);
                if($queryInsert2){
                    echo "<script>alert('Successfully Added!')</script>";
                }
            }
        }

    }

    if(isset($_POST["search"])) {

        $search = $_POST["search"];
        $sqlSearch = "SELECT * FROM staff WHERE StaffID = '$search'";
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
		<a href="staff.php" class="active">Staff/Physician</a>
		<a href="appointment.php">Appointment</a>
		<a href="treatment_plan.php">Treatment Plan</a>
		<a href="laboratory.php">Laboratory</a>
		<a href="medical_record.php">Medical Record</a>
		<a href="medicine_supply.php">Medicine Supply</a>
		<a href="../logout.php">Logout</a>
    </div>
	<div id="main">
		<div class="container">
			<h4>Staff</h4>
            <form method="post">
                <label>Search</label>
                <input type="text" name="search" class="form-control"><br>
            </form>
			<a href="" style="text-decoration: none" data-bs-toggle="modal" data-bs-target="#addTModal">Add Staff +</a>
            <br><br>
			<table class="table table-bordered border-secondary">
                <tr>
                    <th>Staff ID</th>
                    <th>Schedule</th>
                    <th>User ID</th>
                </tr>
                <?php 
                if(!isset($_POST['search'])) {
                    foreach($staffs as $staff) {
                        $schedule = $staff['ScheduleID'];
                        $sqlSchedule = "SELECT Day FROM schedule WHERE ScheduleID = '$schedule'";
                        $querySchedule = mysqli_query($con, $sqlSchedule);
                        $resultSchedule = mysqli_fetch_assoc($querySchedule);
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($staff['StaffID'])?></td>
                    <td><?php echo htmlspecialchars($resultSchedule['Day'])?></td>
                    <td><?php echo htmlspecialchars($staff['UserID'])?></td>
                </tr>
                <?php } } else { foreach($resultSearch as $searchs){
                        $schedule = $searchs['ScheduleID'];
                        $sqlSchedule = "SELECT Day FROM schedule WHERE ScheduleID = '$schedule'";
                        $querySchedule = mysqli_query($con, $sqlSchedule);
                        $resultSchedule = mysqli_fetch_assoc($querySchedule);
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($searchs['StaffID'])?></td>
                        <td><?php echo htmlspecialchars($resultSchedule['Day'])?></td>
                        <td><?php echo htmlspecialchars($searchs['UserID'])?></td>
                    </tr>
                <?php } }?>
            </table>
		</div>
	</div>

    <div class="modal fade" id="addTModal" tabindex="-1" aria-labelledby="addTModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Add Staff</h1>
            <button type="button" class="btn-close" data-bs-toggle="modal" data-bs-target="#teachersModal"></button>
            </div>
            <div class="modal-body">
            <form method="POST">
                <div class="mb-3">
                    <div>
                        <label>Staff ID</label>
                        <input type="text" name="staffID" class="form-control">
                    </div>
                    <div class="d-flex justify-content-between">
                        <div>
                            <label>Last Name</label>
                            <input type="text" name="lname" class="form-control">
                        </div>
                        <div>
                            <label>First Name</label>
                            <input type="text" name="fname" class="form-control">
                        </div>
                        <div>
                        <label>Middle Name</label>
                        <input type="text" name="mname" class="form-control">
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <div>
                            <label>Sex</label>
                            <input type="text" name="sex" class="form-control">
                        </div>
                        <div>
                        <label for="role">Role</label>
                        <select name="role" class="form-control">
                            <option value="2">Staff</option>
                            <option value="3">Physician</option>
                        </select>
                        </div>
                        <div>
                            <label>Contact</label>
                            <input type="text" name="contact" class="form-control">
                        </div>
                    </div>
                    
                    <label>Address</label>
                    <input type="text" name="address" class="form-control">
                    <label for="schedule">Schedule</label>
                    <select name="schedule" class="form-control">
                        <option value="0">Monday - Wednesday - Friday</option>
                        <option value="1">Tuesday - Thursday</option>
                    </select>
                </div>
                <div class="card-footer">
                    <button type="submit" name="addStaff" class="btn btn-primary float-end">
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