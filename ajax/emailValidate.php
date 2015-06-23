<?php 

function emailValidate ($email){
	require_once("../function.php");
	$dbc=mysqli_connect($DB_HOST,$DB_USER,$DB_PSW,$DB_NAME);
	$email =mysqli_real_escape_string($dbc, trim($_GET['email']));
	$query="SELECT * FROM user WHERE USER_NAME='$email'";

	$result=mysqli_query($dbc,$query);

	if($data=mysqli_fetch_array($result)){
		echo "yes";
	}

	else {
		echo "no";
	}
	mysqli_close($dbc);
}
nameValidate($email);
?>