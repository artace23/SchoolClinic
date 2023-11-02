<?php

	$dbhost = "localhost";
	$dbuser = "root";
	$dbpass = "";
	$dbname = "hcdc-clinic";

	if(!$con = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname))
	{
		die("failed to connect!");
	}