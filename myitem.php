<?php
$page_title="复旦MARKET-我的商品";
	require_once("function.php");

	require_once("header.php");

	if(!isset($_COOKIE['userid'])){
		echo 'sorry,please log in';
		require_once("footer.php");
		exit();
	}
	$id=$_COOKIE['userid'];
	$name=$_COOKIE['username'];

	$dbc=mysqli_connect($DB_HOST,$DB_USER,$DB_PSW,$DB_NAME);
	$query="SELECT * from item WHERE ITEM_OWNER='$id' AND ITEM_SHOW=1 ORDER BY ITEM_DATE DESC";

	$result=mysqli_query($dbc,$query);
	echo '<div class="container">';	
		echo '<div class="panel panel-info">';
			echo '<div class="panel-heading">';
				echo '<h4 class="panel-title">我的商品</h4>';
			echo '</div>';
			echo '<div class="list-group panel-body">';
				echo '<div class="row">';
					echo '<div class="itemarea col-md-12">';
							if (mysqli_num_rows($result) > 0) {
								while($data=mysqli_fetch_array($result)) {
									$owner=$data["ITEM_OWNER"];
									$query2="SELECT USER_NAME,USER_ID FROM user INNER JOIN item ON (user.USER_ID='$owner')"; 
									$result2=mysqli_query($dbc,$query2);
									$data2=mysqli_fetch_array($result2);
									//这里开始是处理标签

										global $dbc;//终于找到了解决方法。dbc在这里不是全局的，所以要把它变成全局变量才能用。
										$tagid=$data["ITEM_TAG"];
										$idarray=split(' ',$tagid);
										foreach ($idarray as $key => $id) {
											$query3="SELECT TAG_NAME FROM tag WHERE TAG_ID='$id'"; 
											$result3=mysqli_query($dbc,$query3);
											$data3=mysqli_fetch_array($result3);
											$idarray["$key"]=$data3["TAG_NAME"];
										}
									//这里结束
									showmyitem($data["ITEM_ID"],$data["ITEM_TITLE"],$idarray,$data["ITEM_PRICE"],$data["ITEM_DISCRIPTION"],$data["ITEM_PICDIR"],$data["ITEM_DATE"],$data2["USER_ID"],$data2["USER_NAME"],$data["ITEM_SOLD"],1);
								}
							//	echo '<span class="moreitem" name="moreitem">更多...</span>';
							}
							else {
								echo '</br><h3>不过你好像还没有上传物品，赶快点击上方导航栏的“上传”按钮，发布你的第一个商品吧！</h3></br>';
							}
					echo '</div>';
				echo '</div>';
			echo '</div>';
		echo '</div>';
	echo '</div>';
	mysqli_close($dbc);
	require_once("footer.php");
?>