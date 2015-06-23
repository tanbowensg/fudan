<?php
	$page_title="复旦MARKET-个人信息";
	require_once("header.php");
	require_once("function.php");
	
	$dbc=mysqli_connect($DB_HOST,$DB_USER,$DB_PSW,$DB_NAME);

	$userid=$name =mysqli_real_escape_string($dbc, trim($_GET["userid"]));
	$query="SELECT * FROM user WHERE USER_ID='$userid'";

	$result=mysqli_query($dbc,$query);

	if ($data=mysqli_fetch_array($result)) {
		require_once("header.php");
		echo '<div class="container">';
			echo '<div class="panel panel-info">';
				echo '<div class="panel-heading">';
					echo '<h3 class="panel-title">个人信息</h3>';
				echo '</div>';
				echo '<ul class="list-group panel-body">';
					echo '<li class="list-group-item"><span id="username" >用户名：'.$data["USER_NAME"].'</span></li>';
					echo '<li class="list-group-item"><span id="emailname">邮箱：'.$data["USER_EMAIL"].'</span></li>';
					echo '<li class="list-group-item"><span id="usernum">手机：'.($data["USER_NUM"]?$data["USER_NUM"]:'该用户没有填写手机号').'</span></li>';
				echo '</div>';
			echo '</div>';
		echo '</div>';
	}
	mysqli_close($dbc);


?>
