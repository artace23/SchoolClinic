<?php
	session_start();

	include("connection.php");
	include("function.php");

	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		$fname = $_POST['fname'];
        $lname = $_POST['lname'];
		$gender = $_POST['gender'];
		$bday = $_POST['bday'];
		$age = $_POST['age'];
		$bday_place = $_POST['bday_place'];
		$address = $_POST['address'];
		$year = $_POST['year'];
		if (strpos($fname, ' ') !== false) {
            $nameParts = explode(' ', $fname);
            $newfname = implode('', $nameParts);
            $email = strtolower($newfname . "." . $lname . "@hcdc.edu.ph");
		    $pass = strtolower($newfname.$lname."123");
        }else {
            $email = strtolower($fname . "." . $lname . "@hcdc.edu.ph");
		    $pass = strtolower($fname.$lname."123");
        }
        
        
        $school_id = $_POST["ID"];
        $date = date("Y-m-d");
	}

    $sqlUserID = "SELECT UserID FROM user ORDER BY UserID DESC LIMIT 1";
    $queryUserID = mysqli_query($con, $sqlUserID);
    $resultUserID = mysqli_fetch_assoc($queryUserID);

    $userID = $resultUserID['UserID']+1;

	if(isset($_POST['submit'])) {
		$sqlInsert = "INSERT INTO `user`(`UserID`, `Email`, `Password`, `Lastname`, `Firstname`, `Address`, `DateOfBirth`, `Age`, `Sex`, `RoleID`) VALUES ('$userID','$email','$pass','$lname','$fname','$address','$bday','$age','$gender','1')";
        $queryInsert = mysqli_query($con, $sqlInsert);

        if($queryInsert) {
            $sqlPatient = "INSERT INTO `patient`(`PatientID`, `RegisterDate`, `UserID`) VALUES ('$school_id','$date','$userID')";
            $queryPatient = mysqli_query($con, $sqlPatient);
            if($queryPatient) {
                echo "<script>alert('Successfully Enrolled!')</script>";
                header("Location: login.php");
            }
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="student/style.css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
	<title>Enroll</title>
</head>
<nav>
	<input id="nav-toggle" type="checkbox">
	<div class="logo"><img src="pics/hcdclogo.png">HCDC MEDICAL CLINIC</a></div>
</nav>
<body>
	<div class="modal fade" id="LogoutModal" tabindex="-1" aria-labelledby="LogoutModalLabel" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-centered">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	      </div>
	      <div class="modal-body">
	      	<div class="text-center">
	      		<h6>Do you wanna Logout?</h6>
	      		<a href="../logout.php" class="edit_btn">Yes</a>
	      	</div>
	      </div>
	      <div class="modal-footer">
	      </div>
	    </div>
	  </div>
	</div>
	<div class="container">
		<h2>Student Enrollment Form</h2>
		<div class="container-inside">
			<form method="post">
				<div class="row">
			  		<div class="col">
				      <input type="text" class="form-control" name="fname" placeholder="First Name" onkeypress="return ValidateAlpha(event)"required>
				    </div>
                    <div class="col">
				      <input type="text" class="form-control" name="lname" placeholder="Last Name" onkeypress="return ValidateAlpha(event)"required>
				    </div>
                    <div class="col">
				      <input type="text" class="form-control" name="ID" placeholder="Shool ID" onkeypress="return onlyNumberKey(event)"required>
				    </div>
				</div>
				<br>
				<div class="gender-radio">
					<div class="row">
						<div class="col">
					      <input class="form-check-input" type="radio" name="gender" id="exampleRadios1" value="Female">
						  <label class="form-check-label" for="exampleRadios2"> Female </label>
					  
						  <input class="form-check-input" type="radio" name="gender" id="exampleRadios2" value="Male">
						  <label class="form-check-label" for="exampleRadios2"> Male </label>
					 	</div>
					 	<div class="col">
					      <input type="text" class="form-control" name="age" onkeypress="return onlyNumberKey(event)" maxlength="2"placeholder="Age" required>
					    </div>
					    <div class="col">
					     	 <input type="date" class="form-control" name="bday" placeholder="Date of Birth" required>
					    </div>
					</div>
		  		</div>
			  <br>
			  <div class="row">
			    <div class="col">
			      <input type="text" class="form-control" name="address" placeholder="Address" required>
			    </div>
            </div>
				<br>
				<br>
				<div class="row">
				    <div class="col">
				      <input type="text" class="form-control" name="course"  placeholder="Course">
				    </div>
				    <div class="col">
				      <select class="form-select" id="year" name="year" required>
		      			<option>Year</option>
						<option>1</option>
						<option>2</option>
						<option>3</option>
						<option>4</option>
					</select>
				    </div>
				</div><br>
				<div class="btn2">
					<button type="submit" name="submit" class="btn btn-primary mb-2">Enroll</button>
				</div>
			</form>
		</div>
	</div>
	<script>
        function onlyNumberKey(evt) {
	        var ASCIICode = (evt.which) ? evt.which : evt.keyCode
	        if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
	            return false;
	        return true;
        }
        function ValidateAlpha(evt) {
	        var keyCode = (evt.which) ? evt.which : evt.keyCode
	        if ((keyCode < 65 || keyCode > 90) && (keyCode < 97 || keyCode > 123) && keyCode != 32)
	         
	        return false;
	            return true;
	    }
    </script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
</body>
</html>