<?php
 	if(isset($userid)){
		echo '<meta http-equiv="refresh" content="0;url=showitem.php">';
	}
	else {
		if (isset($_POST['login'])) {
			if (!empty($_POST['email']) && !empty($_POST['psw'])) {
				require_once("function.php");
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
					echo '<meta http-equiv="refresh" content="0;url=index.php?error=1">';
				}
				mysqli_close($dbc);
			}
			else {
				echo '<meta http-equiv="refresh" content="0;url=index.php?error=2">';
			}
		}
	}	
?>

<html>
<head>
	<meta charset="utf-8">

	<title>萨堡MARKET</title>
	<script type="text/javascript" src="js/jquery-2.1.1.js"></script>
	<script type="text/javascript" src="js/js.js"></script>
	<script type="text/javascript" src="js/bootstrap.js"></script>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="css/over.css">
</head>
<body>
	<div id="bg" class="">
		<div  class="container center-block">
			<div class='row'>
				<div id="welcomediv" class="col-xs-5 col-xs-push-6 col-sm-6 col-sm-push-6 col-md-7 col-md-push-5 col-lg-7 col-lg-push-5" >
					<h1>Willkommen in Salzburg Markt</h1>

					>>>　<a id="visit" class="btn btn-sm btn-warning" href="showitem.php">随便看看</a>
				</div>
				<div class="col-xs-6 col-xs-pull-5 col-sm-5 col-sm-pull-5 col-md-3 col-md-pull-5 col-lg-3 col-lg-pull-5">
					<div id="logindiv" class="logdiv" >
						<form id="loginform" enctype="multipart/form-data" action="index.php" method="post">
							<div class="form-group">
								<label class="sr-only" for="email">email</label>
								<input type="text" id="email" class="form-control" name="email" placeholder="邮箱" required aria-required="true" autofocus>
							</div>
							<div class="form-group">
								<label class="sr-only" for="psw">密码</label>
								<input type="password" id="psw" class="form-control " name="psw" placeholder="密码" required aria-required="true">
							</div>

							<div class="form-group">
								<button type="submit" class="uploadinput btn btn-sm btn-info" id="login" name="login">登录</button>
							</div>

							<?php if(isset($_GET['error'])){echo '<p id="loginerror" class="rlabel">邮箱或密码输入错误</p>';}?>
						</form>

					</div>

					<div id="registerdiv" class="logdiv">
						<small class="logdivtitle">　还没注册？马上注册！</small>
						<hr>
						<form  id="registerform" action="register.php" method="post">
							<div class="form-group">
								<label class="sr-only" for="email">email</label>
								<input type="text" id="rname" class="form-control" name="name" placeholder="用户名" required aria-required="true" autofocus><p class="rlabel alert alert-danger hide-not-important" role="alert" id="valid"></p>
							</div>
							<div class="form-group">
								<label class="sr-only" for="email">email</label>
								<input type="text" id="remail" class="form-control" name="email" placeholder="邮箱" required aria-required="true" autofocus><p class="rlabel alert alert-danger hide-not-important" role="alert" id="emailvalid"></p>
							</div>
							<div class="form-group">
								<label class="sr-only" for="email">email</label>
								<input type="text" id="rnum" class="form-control" name="num" placeholder="手机" required aria-required="true" autofocus>
							</div>
							<div class="form-group">
								<label class="sr-only" for="email">email</label>
								<input type="password" id="rpsw1" class="form-control" name="psw1" placeholder="密码" required aria-required="true" autofocus>
							</div>
							<div class="form-group">
								<button type="submit" id="register" class="btn btn-sm btn-info" name="register" >注册</button>
							</div>
						</form>
					</div>

				</div>
			</div>
		</div>
	</div>
</body>
</html>