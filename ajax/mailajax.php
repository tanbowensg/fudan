<?php 
	require_once("../function.php");

	if (isset($_GET["receiver"])){//不知道为什么不能用post
		if(!isset($_COOKIE['userid'])){
			echo 'not log in';
		}
		else {
			$dbc=mysqli_connect($DB_HOST,$DB_USER,$DB_PSW,$DB_NAME);

			foreach($_GET as $value){
				if($value){
					$value=mysqli_real_escape_string($dbc, trim($value));
				}
			}
			extract($_GET);

			 $receiver = $_GET['receiver'];

				$query="SELECT USER_ID FROM user WHERE USER_NAME='$receiver'";

				$result=mysqli_query($dbc,$query);

				if (!$data=mysqli_fetch_array($result)){
					echo "receiver not exist";
					exit();
				}

				else {
					$send=$_COOKIE['userid'];

					if($send!==''&&$content!==''){
						sendmail($dbc,$send,$data['USER_ID'],$content);
						echo "mail success";
					}
				}
		mysqli_close($dbc);
		}
	}
			
?>