<?php

	require_once("../function.php");
	
	$dbc=mysqli_connect($DB_HOST,$DB_USER,$DB_PSW,$DB_NAME);
	
	if (isset($_GET['curpage'])) {
		$curpage=mysqli_real_escape_string($dbc, trim($_GET['curpage']));
	}
	else{
		$curpage=0;
	}

	$skip=$curpage*3;


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

	$orderquery="ORDER BY ITEM_DATE DESC ";
	$limitquery="LIMIT $skip,6";//这里不知道为什么，实际显示的数量总是比设定的少一个。
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




	$result=mysqli_query($dbc,$query);

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
		echo 'end';
	}

	mysqli_close($dbc);

?>
