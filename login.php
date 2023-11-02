<?php
session_start();

	include("connection.php");
	include("function.php");

	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		$user_name = $_POST['user_name'];
		$password = $_POST['user_pass'];

		if (!empty($user_name) && !empty($password)) {
				$query = "select * from user where Email = '$user_name' limit 1";
				$result = mysqli_query($con, $query);

				if($result){
				if($result && mysqli_num_rows($result) > 0){
					$user_data = mysqli_fetch_assoc($result);

					if($user_data['Password'] == $password){
						$_SESSION['UserID'] = $user_data['UserID'];
						if($user_data['RoleID'] == 1){
							header("Location: index.php");
							die();
						}else if($user_data["RoleID"] == 2){
							header("Location: admin_index.php");
							die();
						}
					}
					}
				}
				echo '<script> alert("Invalid credentials!") </script>';
		}
	}
  
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="login/style.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
	<title>Login</title>
</head>
<body>
	<div class="box">
		<form method="post" autocomplete="off">
			<div class="wrapper">
				<img src="pics/hcdc.png">
			</div>
			<div class="inputBox">
				<input type="text" name="user_name" required="required" placeholder="Enter Username">
				<span class="input-title">Username</span>
				<i></i>
			</div>
			<div class="inputBox">
				<input type="password" name="user_pass" required="required" id="myInput" placeholder="Enter Password">
				<span class="input-title">Password</span>
				<i></i>
			</div>
			<div class="links">
				<button type="submit"> Login </button>
			</div>
			<div class="links">
				<a href="register.php">Create Account</a>
			</div>
		</form>
	</div>
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.min.js" integrity="sha384-IDwe1+LCz02ROU9k972gdyvl+AESN10+x7tBKgc9I5HFtuNz0wWnPclzo6p9vxnk" crossorigin="anonymous"></script>
</body>
</html>