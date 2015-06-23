<?php
	require_once("function.php");
	if(!isset($_COOKIE['userid'])) {
		if (isset($_POST['login'])) {
			if (!empty($_POST['email']) && !empty($_POST['psw'])) {

				$dbc=mysqli_connect($DB_HOST,$DB_USER,$DB_PSW,$DB_NAME);

				foreach($_POST as $value){
					if($value){
						$value=mysqli_real_escape_string($dbc, trim($value));
					}
				}
				extract($_POST);
				
				$select_query = "SELECT USER_ID,USER_NAME FROM user WHERE USER_EMAIL='$email' AND USER_PSW=SHA('$psw')";
				$result=mysqli_query($dbc,$select_query);

				$userinfo=mysqli_fetch_array($result);

				if (!empty($userinfo)) {
					setcookie('userid' , $userinfo['USER_ID'],time()+(60*60*12));
					setcookie('username' , $userinfo['USER_NAME'],time()+(60*60*12));
					echo '<meta http-equiv="refresh" content="0;url=showitem.php">';
				}
				else {
					echo '<meta http-equiv="refresh" content="0;url=index.php">';
					echo "密码或用户名输入错误";
				}
			}
			else {
				echo '<meta http-equiv="refresh" content="0;url=index.php">';
				echo "请输入用户名和密码";
			}
		mysqli_close($dbc);
		}
	}?>

	<?php/*
	require_once('header.php');
	if(!isset($_COOKIE['userid']) && !isset($_POST['login'])) {
	?><div id="login">
		<form enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
			<input type="text" id="email" name="email" placeholder="邮箱"></br>
			<input type="text" id="psw" name="psw" placeholder="密码"></br>
			<input type="submit" id="login" name="login" value="登录">
		</form>
	</div>
	<?php } 
	require_once('footer.php');
	*/?>
