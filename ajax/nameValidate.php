<?php 

function nameValidate ($name){
	require_once("../function.php");
	$dbc=mysqli_connect($DB_HOST,$DB_USER,$DB_PSW,$DB_NAME);
	$name =mysqli_real_escape_string($dbc, trim($_GET['name']));
	$query="SELECT * FROM user WHERE USER_NAME='$name'";

	$result=mysqli_query($dbc,$query);

	if($data=mysqli_fetch_array($result)){
		echo "yes";
	}

	else {
		echo "no";
	}
	mysqli_close($dbc);
}
nameValidate($name);
?>