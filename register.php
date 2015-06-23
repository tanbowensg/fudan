<?php
	require_once("function.php");
	if (isset($_POST)) {

		$dbc=mysqli_connect($DB_HOST,$DB_USER,$DB_PSW,$DB_NAME);
//trimextract($dbc,$_POST);
		foreach($_POST as $value){
		if($value){
			$value=mysqli_real_escape_string($dbc, trim($value));
			}
		}//这里有问题！！！这个函数在服务器上不能用。
		extract($_POST);

//------------------------------------------我实在是不知道下面怎么办，明明可以重用namevalidate 但就是不行，会出错。只有这样才不会出错。
		mysqli_select_db($dbc,'fudan');
		$query="SELECT * FROM user WHERE USER_NAME='$name'";

		$result=mysqli_query($dbc,$query);

		if($data=mysqli_fetch_array($result)){
			$valid="yes";
		}

		if(!$data=mysqli_fetch_array($result)){
			$valid='no';
		}
		mysqli_close($dbc);
//-------------------------------------------------


		if ($valid=='no'&&validate_register($name,$email,$psw1)){
			$dbc=mysqli_connect($DB_HOST,$DB_USER,$DB_PSW,$DB_NAME);

			$insert_query = "INSERT INTO user (USER_NAME,USER_EMAIL,USER_PSW,USER_NUM)".
			 "VALUES ('$name','$email',SHA('$psw1'),'$num')";

			if($result=mysqli_query($dbc,$insert_query)){
				$idquery="SELECT USER_ID,USER_NAME from user WHERE USER_EMAIL='$email'";
				$id=mysqli_fetch_array(mysqli_query($dbc,$idquery));
				setcookie('userid' , $id['USER_ID'],time()+(60*60*12));
				setcookie('username' , $id['USER_NAME'],time()+(60*60*12));//这里以后可以弄个函数
				sendmail($dbc,5,$id['USER_ID'],'恭喜您已经成功注册！');
				//echo "注册成功,现在自动登录返回首页";
				echo '<meta http-equiv="refresh" content="0;url=showitem.php">';//注册后自动登录
				mysqli_close($dbc);
			}
			else {
				require_once("header.php");    
				echo "注册失败";
		   	}

		}
		else {
			require_once("header.php");
			echo "注册失败，可能是你的信息输入不规范或者两次输入的密码不一样。具体的检测机制我还没做好。";
		}
	}

/*	else{这个是最初的注册界面
		require_once("header.php");?>
		<div id="register">
		<form enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
		<input type="text" id="name" name="name" placeholder="用户名"onkeyup="nameValidate(this.value)"><label id="valid"></label></br>
		<input type="text" id="email" name="email" placeholder="邮箱"></br>
		<input type="text" id="num" name="num" placeholder="手机"></br>
		<input type="text" id="psw1" name="psw1" placeholder="密码"></br>
		<input type="text" id="psw2" name="psw2" placeholder="确认密码"></br>
		<input type="submit" id="submit" name="submit"  >
		</form>
		</div id="register">
		<?php
	}
	require_once("footer.php");
*/	
?>
