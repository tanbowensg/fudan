
<?php
	require_once("function.php");

	$dbc=mysqli_connect($DB_HOST,$DB_USER,$DB_PSW,$DB_NAME);

	$query="SELECT ITEM_TAG FROM item WHERE ITEM_SHOW=1";

	$result=mysqli_query($dbc,$query);
	$taglist = array();
	while ($data=mysqli_fetch_array($result)) {
		$temptag=split(' ',$data["ITEM_TAG"]);
		foreach ($temptag as $key => $value) {
			$taglist["$value"]++;
		}
	}

	arsort($taglist);

	$taglist=array_chunk($taglist,20,true);

	$sum=array_sum($taglist[0]);

	echo '<div class="tag-cloud">';
	echo '<h4>标签云</h4>';
	echo '<hr>';
	echo '<div class="tag-cloud-tagdiv">';
	foreach ($taglist[0] as $key => $value) {
		$dbc3=mysqli_connect($DB_HOST,$DB_USER,$DB_PSW,$DB_NAME);

		$tempquery="SELECT TAG_NAME FROM tag WHERE TAG_ID='$key'";

		$tempresult=mysqli_query($dbc3,$tempquery);

		$tagname=mysqli_fetch_array($tempresult);

		$key=$tagname["TAG_NAME"];

		$size=$value/$sum*60+10;//某种算法

		//echo '<a class="tagclouditem" href="showitem?keyword='.$key.'" style="font-size:'.$size.'px">'.$key.'</a>';根据热度设置字体大小
		echo '<a class="tagclouditem" href="showitem?keyword='.$key.'">'.$key.'</a>';
	}
	echo '</div>';
	echo '</div>';
	
	
?>