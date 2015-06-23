<?php
	require_once("../function.php");

	if(!isset($_COOKIE['userid'])){
		echo 'sorry,please log in';
		exit();
	}
	
	$dbc=mysqli_connect($DB_HOST,$DB_USER,$DB_PSW,$DB_NAME);

	if (isset($_GET)) {
		$sold=mysqli_real_escape_string($dbc, trim($_GET['sold']));
		$id=mysqli_real_escape_string($dbc, trim($_GET['id']));
		$userid=mysqli_real_escape_string($dbc, trim($_COOKIE['userid']));

	}
	else{
		echo 'error';
		exit();
	}

	$query="UPDATE item SET ITEM_SOLD=".($sold?0:1)." WHERE ITEM_ID='$id' AND ITEM_OWNER='$userid'";

	$result=mysqli_query($dbc,$query);

	if($sold==1){
		echo "changetosold";

	}
	else if($sold==0){
		echo "changetonotsold";

	}

?>