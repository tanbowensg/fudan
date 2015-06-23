<?php 
	$page_title="复旦MARKET-站内信";
	require_once("header.php");
	require_once("function.php");
	$userid=$_COOKIE['userid'];

	if(!isset($userid)){
		echo 'sorry,please log in';
		require_once("footer.php");
		exit();
	}	
	$page_title="复旦MARKET-站内信";
	$dbc=mysqli_connect($DB_HOST,$DB_USER,$DB_PSW,$DB_NAME);

	$query="SELECT * FROM mail WHERE (MAIL_REC='$userid' OR MAIL_SEND='$userid') AND MAIL_SHOW=1 ORDER BY MAIL_DATE DESC";
	$result=mysqli_query($dbc,$query);	

	echo '<div class="container">';	
		echo '<div class="panel panel-info">';
			echo '<div class="panel-heading">';
				echo '<h4 class="panel-title">我的站内信<button class="contactbutton btn btn-xs btn-success pull-right" data-toggle="modal" data-target="#sendmaildialog">发站内信</button></h4>';
				//echo '<button class="button btn btn-info pull-right" id="sendmail">发站内信</button>';
			echo '</div>';
			echo '<ul class="list-group panel-body">';
				if (mysqli_num_rows($result) > 0) {
					echo '<div id="mailarea">';
					while($data=mysqli_fetch_array($result)) {
						if($userid==$data["MAIL_REC"]){
							$senderid=$data["MAIL_SEND"];
							$query2="SELECT USER_NAME FROM user WHERE USER_ID='$senderid'";

							$result2=mysqli_query($dbc,$query2);
							$data2=mysqli_fetch_array($result2);

							echo '<li class="maildiv list-group-item" id="mailblock'.$data["MAIL_ID"].'">';
							echo 'From: <a href="userinfo.php?userid='.$senderid.'">'.$data2["USER_NAME"].'</a>：';
							echo '<p class="mailcontent">　　'.$data["MAIL_CONT"].'</p>';
							echo '<span class="itemdate text-muted small">'.$data["MAIL_DATE"].'</span>';
							echo '<div class="buttondiv">';
								echo '<button class="deletemail mailspan btn btn-xs btn-danger" id="'.$data["MAIL_ID"].'">删除</button>';
								echo '<button class="contactbutton mailspan btn btn-xs btn-info" data-toggle="modal" data-target="#sendmaildialog" title="'.$data2["USER_NAME"].'">回复</button>';
							echo '</div>';
							echo '</li>';

							$mailid=$data["MAIL_ID"];
							$query3="UPDATE mail SET MAIL_READ = 1 WHERE MAIL_ID='$mailid'";
							mysqli_query($dbc,$query3);
						}
						else if($userid==$data["MAIL_SEND"]){
							$receiverid=$data["MAIL_REC"];
							$query2="SELECT USER_NAME FROM user WHERE USER_ID='$receiverid'";

							$result2=mysqli_query($dbc,$query2);
							$data2=mysqli_fetch_array($result2);

							echo '<li class="maildiv list-group-item" id="mailblock'.$data["MAIL_ID"].'">';
							echo 'To: <a href="userinfo.php?userid='.$receiverid.'">'.$data2["USER_NAME"].'</a>：</br>';
							echo '<p class="mailcontent">　　'.$data["MAIL_CONT"].'</p>';
							echo '<span class="itemdate text-muted small">'.$data["MAIL_DATE"].'</span>';
							echo '</li>';
						}
					}
					echo '</div>';
				}
				else{
					echo '</br><h4>暂时还没有人给你发站内信</h4>';
				}
	echo '</div>';
	mysqli_close($dbc);
	require_once("footer.php");

?>	




