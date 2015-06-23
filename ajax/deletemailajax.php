<?php
	require_once("../function.php");
	if (isset($_GET["todelete"])){//不知道为什么不能用post
		if(!isset($_COOKIE['userid'])){
			echo 'not log in';
		}

		else{
			$dbc=mysqli_connect($DB_HOST,$DB_USER,$DB_PSW,$DB_NAME);

			foreach($_GET as $value){
				if($value){
					$value=mysqli_real_escape_string($dbc, trim($value));
				}
			}
			extract($_GET);

			$query="SELECT MAIL_REC FROM mail WHERE MAIL_ID='$todelete'";


			$result=mysqli_query($dbc,$query);

			$data=mysqli_fetch_array($result);


			if($data['MAIL_REC']!==$_COOKIE['userid']){
				echo 'cant delete other';
			}
			else{
				$deletequery="UPDATE mail SET MAIL_SHOW=0 WHERE MAIL_ID='$todelete'";
				if($deleteresult=mysqli_query($dbc,$deletequery)){
					echo "success";
				}
				else{
					echo "fail";
				}
			}	
		}	
		mysqli_close($dbc);
	}
	
?>
