<?php
	$page_title="复旦MARKET-首页" ;

	require_once("function.php");
	require_once("header.php");
	
	$dbc=mysqli_connect($DB_HOST,$DB_USER,$DB_PSW,$DB_NAME);
	//------
	if (isset($_GET['curpage'])) {
		$curpage=mysqli_real_escape_string($dbc, trim($_GET['curpage']));
	}
	else{
		$curpage=0;
	}
	$skip=$curpage*2;
	//------
	if (isset($_GET['option'])) {
		$option=mysqli_real_escape_string($dbc, trim($_GET['option']));
	}
	//--------


	$orderquery="ORDER BY ITEM_DATE DESC ";
	$limitquery="LIMIT $skip,6";
	if (isset($_GET['keyword'])&&$_GET['keyword']!=='') {
		$keyword=mysqli_real_escape_string($dbc, trim($_GET['keyword']));
		$tagid=tagnametoid($keyword);
		$keywordquery="AND (ITEM_TAG LIKE '%$tagid%' OR ITEM_TITLE LIKE '%$keyword%' OR ITEM_DISCRIPTION LIKE '%$keyword%') ";
		if (isset($_GET['option'])&&$_GET['option']!=='') {
			$option=mysqli_real_escape_string($dbc, trim($_GET['option']));
			$optionquery="AND ITEM_SOLD=".($option=='sell'?0:1).' ';
			$query="SELECT * from item WHERE ITEM_SHOW=1 ".$keywordquery.$optionquery.$orderquery.$limitquery;
		}
		else {
			$query="SELECT * from item WHERE ITEM_SHOW=1 ".$keywordquery.$orderquery.$limitquery;	
		}
	}
	else {
		if (isset($_GET['option'])&&$_GET['option']!=='') {
			$option=mysqli_real_escape_string($dbc, trim($_GET['option']));
			$optionquery="AND ITEM_SOLD=".($option=='sell'?0:1).' ';
			$query="SELECT * from item WHERE ITEM_SHOW=1 ".$optionquery.$orderquery.$limitquery;
		}
		else{
			$query="SELECT * from item WHERE ITEM_SHOW=1 ".$orderquery.$limitquery;
		}
	}

/*
	if (isset($_GET['keyword'])) {

		$keyword=mysqli_real_escape_string($dbc, trim($_GET['keyword']));
		$tagid=tagnametoid($keyword);
		$query="SELECT * from item WHERE ITEM_TAG LIKE '%$tagid%' OR ITEM_TITLE LIKE '%$keyword%' AND ITEM_SHOW=1 ORDER BY ITEM_DATE DESC LIMIT $skip,3";
	}
	else {
		$query="SELECT * from item WHERE ITEM_SHOW=1 ORDER BY ITEM_DATE DESC LIMIT $skip,3";		
	}
*/	


	
	$result=mysqli_query($dbc,$query);

	echo '<div id="main-div" class="container">';
		echo '<div class="container row">';
			echo '<div class="itemarea col-xs-12 col-sm-12 col-md-9 col-lg-9">';
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

					showitem($data["ITEM_ID"],$data["ITEM_TITLE"],$idarray,$data["ITEM_PRICE"],$data["ITEM_DISCRIPTION"],$data["ITEM_PICDIR"],$data["ITEM_DATE"],$data2["USER_ID"],$data2["USER_NAME"],$data["ITEM_SOLD"],0);
				}
				
				echo '<button class="col-xs-12 col-sm-12 col-md-12 col-lg-12 btn btn-default moreitem" name="moreitem">更多...</button>';
				echo '<input class="temp hide" id="tempcurpage" value="'.$curpage.'">';
				echo '<input class="temp hide" id="tempkeyword" value="'.$keyword.'">';
				echo '<input class="temp hide" id="tempoption" value="'.($option?",'".$option."'":'').'">';
			}
			else {
				echo '<span class="col-xs-12 col-sm-12 col-md-12 col-lg-12 btn btn-info moreitem" name="moreitem">木有更多商品了...</span>';
			}
			
			echo '</div>';
			echo '<ul class="optiondiv cos-xs-3 col-sm-0 col-md-3 col-lg-3 list-group">';
				//echo '<li><h3>设置显示<h3></li>';
				echo '<li><a class="displayoptions list-group-item" href="showitem?'.($keyword?'keyword='.$keyword:'').'">显示全部商品</a></li>';
				echo '<li><a class="displayoptions list-group-item" href="showitem?'.($keyword?'keyword='.$keyword:'').'&option=sell">只显示待售中商品</a></li>';
				echo '<li><a class="displayoptions list-group-item" href="showitem?'.($keyword?'keyword='.$keyword:'').'&option=sold">只显示已售出商品</a></li>';
				require_once('tagcount.php');
			echo '</div>';
		echo '</div>';
		require_once("footer.php");
	echo '</div>';
	mysqli_close($dbc);
?>