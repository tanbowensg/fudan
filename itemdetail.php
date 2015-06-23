<?php
require_once("function.php");
$dbc=mysqli_connect($DB_HOST,$DB_USER,$DB_PSW,$DB_NAME);
	if (isset($_GET['id'])) {
		$id=mysqli_real_escape_string($dbc, trim($_GET['id']));
	}
	else{
		echo "出错了，请从正常网址访问";
	}
	$query="SELECT * FROM item WHERE ITEM_ID='$id'";

	$result=mysqli_query($dbc,$query);

	if($data=mysqli_fetch_array($result)){
//准备tag和图片
		$page_title='复旦MARKET-'.$data["ITEM_TITLE"];

			$owner=$data["ITEM_OWNER"];
			$query2="SELECT USER_NAME,USER_ID FROM user INNER JOIN item ON (user.USER_ID='$owner')"; 
			$result2=mysqli_query($dbc,$query2);
			$data2=mysqli_fetch_array($result2);
			$ownername=$data2['USER_NAME'];

				$tagid=$data["ITEM_TAG"];
				$idarray=split(' ',$tagid);
				foreach ($idarray as $key => $id) {
					$query3="SELECT TAG_NAME FROM tag WHERE TAG_ID='$id'"; 
					$result3=mysqli_query($dbc,$query3);
					$data3=mysqli_fetch_array($result3);
					$idarray["$key"]=$data3["TAG_NAME"];
				}

		$picdirarray=split(',,',$data["ITEM_PICDIR"]);
		array_pop($picdirarray);

		//-------生成页面 图片部分
		require_once("header.php");
		echo '<div id="itemdetail" class="container">';
			echo '<div class="row">';

				echo '<div id="picturediv" class="col-xs-12 col-sm-12 col-md-7 col-lg-7">';
					echo '<ul id="picturelist" class="col-xs-0 col-sm-0 col-md-3 col-lg-3">';
						foreach ($picdirarray as $value) {
							echo '<li class="lipicture thumbnail col-xs-12 col-sm-12 col-md-12 col-lg-12"><img  src="'.$value.'"></li>';
						}
					echo '</ul>';
					echo '<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">';
						echo '<img class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="smallpicture" src="'.$picdirarray[0].'">';
					echo '</div>';
				echo '</div>';

				//----------生成页面 信息部分
				echo '<div id="iteminformation" class="col-xs-12 col-sm-12 col-md-5 col-lg-5">';
					echo '<div class="itemtitlediv">';
						echo ($sold?'<span class="itemsold  label label-default label-xs pull-left">已售出</span>':'<span class="itemsoldno label label-default pull-left">待售中</span>');
						echo '<p class="itemtitle">'.$data["ITEM_TITLE"].'</p>';
						echo '<label class="itemprice-detail">￥'.$data["ITEM_PRICE"].'</label>';
					echo '</div>';
					echo '<div class="itemtagdiv tag-list">';
						echo '<i class="glyphicon glyphicon-tags"></i>';
						foreach ($idarray as $tagname) {
							echo '<a class="" href="showitem?keyword='.$tagname.'">  '.$tagname.'</a>';	
						}
					echo '</div>';
					echo '<div class="itemsellerdiv-detail">';
						if (isset($owner)){
							echo '<a href="userinfo.php?userid='.$owner.'">'.$ownername.'</a>';
							echo '<span class="itemmail" title="'.$ownername.'"><button class="contactbutton btn btn-warning">联系卖家</button>';
						}
					echo '</div>';
					echo '<hr>';
					echo '<div class="itemfulldisdiv">';
						echo '<h4>商品详细介绍</h4>';
						echo '<hr>';
						echo '<p class="itemfulldis ">'.$data["ITEM_DISCRIPTION"].'</p>';
					echo '</div>';
				echo '</div>';
			echo '</div>';
		echo '</div>';
		require_once("footer.php");
	}

?>

	
	
	
