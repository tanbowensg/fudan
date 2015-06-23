<?php

	require_once("function.php");

	if(!isset($_COOKIE['userid'])){
		echo 'sorry,please log in';
		require_once("footer.php");
		exit();
	}	


	if (isset($_POST["title"])){

		$dbc=mysqli_connect($DB_HOST,$DB_USER,$DB_PSW,$DB_NAME);

		foreach($_POST as $value){
			if($value){
				$value=mysqli_real_escape_string($dbc, trim($value));
			}
		}
		echo $_POST['title'];
		extract($_POST);
		echo $discription;
		$owner=$_COOKIE["userid"];
//--------------上传图片
		$fulltarget='';
		if (count($_FILES['pic']['name'])>5){
			echo 'morethan5';
			exit();
		}
		for($i=0; $i<count($_FILES['pic']['name']); $i++) {
			$picname=mysqli_real_escape_string($dbc, trim($picname=$_FILES['pic']['name'][$i]));
			$pictmp=$_FILES['pic']['tmp_name'][$i];
			if (!empty($pictmp)){
				$target=$uploadpath.rand().$picname;
				move_uploaded_file($_FILES['pic']['tmp_name'][$i],$target);
				$fulltarget=$fulltarget.$target.',,';
			}
		}
//----------------------------------
		$tagidstring=tagnametoid($tags);//引用function里的函数

		if($title!==''&&$price!==''&&$discription!==''&&$tagidstring!==''){

			$insert_query = "INSERT INTO item (ITEM_TITLE,ITEM_PRICE,ITEM_DATE,ITEM_DISCRIPTION,ITEM_PIC,ITEM_PICDIR,ITEM_TAG,ITEM_OWNER)".
			 "VALUES ('$title','$price',NOW(),'$discription','$pic','$fulltarget','$tagidstring','$owner')";
			$result=mysqli_query($dbc,$insert_query);

			echo '<meta http-equiv="refresh" content="0;url=showitem.php">';
		}

		mysqli_close($dbc);
	}

	?>
