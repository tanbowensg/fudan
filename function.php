<?php
$DB_HOST="localhost";
$DB_NAME="fudan";
$DB_USER="root";
$DB_PSW="joygame1";
$uploadpath="images/";




//从post中提取变量
function trimextract(&$_POST){
	$dbc=mysqli_connect($DB_HOST,$DB_USER,$DB_PSW,$DB_NAME);
	foreach($_POST as $value){
		if($value){
			$value=mysqli_real_escape_string($dbc, trim($value));
		}
	}
	extract($_POST);
	mysqli_close($dbc);
}
//所有信息验证一次就行了，只显示整体的对错，如果出错就返回前段验证。前端已经验证过一般不会出错。
	function validate_register(&$name,&$email,&$psw1){//这里用了引用传递参数

		$valid=true;

		if (!preg_match('/^[a-zA-Z0-9][a-zA-Z0-9\._\-&!?=#]*@/',$email)) {//验证email
			$valid=false;
			return $valid;
		}
/*验证注册密码的
		if (!$psw1==$psw2) {
			$valid=false;
			return $valid;
		}
*/
		return true;
	}
//注册用的
	function register(&$name,&$email,&$psw1,&$num){

		$dbc=mysqli_connect($DB_HOST,$DB_USER,$DB_PSW,$DB_NAME);

		$insert_query = "INSERT INTO user (USER_NAME,USER_EMAIL,USER_PSW,USER_NUM)".
		 "VALUES ('$name','$email',SHA('$psw1'),'$num')";

		if($result=mysqli_query($dbc,$insert_query)){
			echo "注册成功";
		}
		else {   
		require_once("header.php"); 
			echo "注册失败";
   		}
		mysqli_close($dbc);
	}
//发站内信
	function sendmail($dbc,$send,$sendto,$mail){
		$query="INSERT INTO mail (MAIL_SEND,MAIL_REC,MAIL_CONT,MAIL_DATE)".
			"VALUES ('$send','$sendto','$mail',NOW())";
		$result=mysqli_query($dbc,$query);
		return $result;
	}

//对汉字使用的substr
function msubstr($string, $length, $encoding  = 'utf-8') {
    $string = trim($string);

    if($length && strlen($string) > $length) {
        //截断字符
        $wordscut = '';
        if(strtolower($encoding) == 'utf-8') {
            //utf8编码
            $n = 0;
            $tn = 0;
            $noc = 0;
            while ($n < strlen($string)) {
                $t = ord($string[$n]);
                if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
                    $tn = 1;
                    $n++;
                    $noc++;
                } elseif(194 <= $t && $t <= 223) {
                    $tn = 2;
                    $n += 2;
                    $noc += 2;
                } elseif(224 <= $t && $t < 239) {
                    $tn = 3;
                    $n += 3;
                    $noc += 2;
                } elseif(240 <= $t && $t <= 247) {
                    $tn = 4;
                    $n += 4;
                    $noc += 2;
                } elseif(248 <= $t && $t <= 251) {
                    $tn = 5;
                    $n += 5;
                    $noc += 2;
                } elseif($t == 252 || $t == 253) {
                    $tn = 6;
                    $n += 6;
                    $noc += 2;
                } else {
                    $n++;
                }
                if ($noc >= $length) {
                    break;
                }
            }
            if ($noc > $length) {
                $n -= $tn;
            }
            $wordscut = substr($string, 0, $n);
        } else {
            for($i = 0; $i < $length - 1; $i++) {
                if(ord($string[$i]) > 127) {
                    $wordscut .= $string[$i].$string[$i + 1];
                    $i++;
                } else {
                    $wordscut .= $string[$i];
                }
            }
        }
        $string = $wordscut;
    }
    return trim($string);
}

//显示东西
	function showitem($id,$title,$tag,$price,$discription,$picdir,$date,$owner,$ownername,$sold,$myitem){
			//处理图片的地址
				$picdirtmp=split(',,',$picdir);
				$picdir=$picdirtmp[0];
			//------
		echo '<section class="col-xs-12 col-xs-12- col-md-6 col-sm-6 col-lg-6" id="itemblock'.$id.'">';
			echo '<div class="itemsection thumbnail col-xs-12 col-sm-12 col-xs-12 col-md-12 ">';
				echo '<div class="iteminfo">';

					echo '<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 padding0 itemimgdiv"><img class="itemimg" src="'.$picdir.'"></div>';

					echo '<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8 iteminfodiv">';

						echo '<div class="itemtitlediv">';
							if($myitem==1){//用户界面下的售出按钮可以按，浏览界面不可以
							$soldlabel = ($sold?'<span id="itemsold'.$id.'" class="soldbutton changesold itemsold label label-default pull-left">已售出</span>':
								'<span id="itemsold'.$id.'" class="soldbutton changesold itemsoldno label label-info pull-left">待售中</span>');
							}
							else{
							$soldlabel= ($sold?'<span id="itemsold'.$id.'" class="soldbutton itemsold label label-default pull-left">已售出</span>':
								'<span id="itemsold'.$id.'" class="soldbutton itemsoldno label label-info pull-left">待售中</span>');	
							}//售出情况结束
							echo $soldlabel;
							echo '<a class="itemtitle" href="itemdetail?id='.$id.'"><h5>'.msubstr($title,28).'...</h5></a>';
						echo '</div>';

						echo '<span class="itemprice">￥'.$price.'</span>';

						echo '<small class="itemdiscription text-justify">'.msubstr($discription,100).'...</small>';

						echo '<div class="itemsellerdiv">';
							if (isset($owner)){
								echo '<span class="contactbutton btn btn-xs btn-warning" data-toggle="modal" data-target="#sendmaildialog" title="'.$ownername.'">联系卖家</span>';
								echo '<a class="col-xs-0" href="userinfo.php?userid='.$owner.'"> '.$ownername.' </a>';
							}
						//	echo '<span class="itemdate small">   '.$date.'</span></br>';日期暂时不要了
							
					echo '</div>';

				echo '</div>';//上半部分div结束
				echo '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 tag-list">';
					echo '<hr>';
					echo '<i class="glyphicon glyphicon-tags"></i>';
					foreach ($tag as $tagname) {
						echo '<a class="" href="showitem?keyword='.$tagname.'">  '.$tagname.'</a>';	
					}
				echo '</div>';
			echo '</div>';
		echo '</section>';
	}

	function showmyitem($id,$title,$tag,$price,$discription,$picdir,$date,$owner,$ownername,$sold,$myitem){
			//处理图片的地址
				$picdirtmp=split(',,',$picdir);
				$picdir=$picdirtmp[0];
			//------
		echo '<section class="col-md-6" id="itemblock'.$id.'">';
			echo '<div class="itemsection thumbnail col-md-12">';
				echo '<div class="iteminfo">';

					echo '<div class="col-md-4 padding0 itemimgdiv"><img class="itemimg" src="'.$picdir.'"></div>';

					echo '<div class="col-md-8 iteminfodiv">';

						echo '<div class="itemtitlediv">';
							if($myitem==1){//用户界面下的售出按钮可以按，浏览界面不可以
							$soldlabel = ($sold?'<span id="itemsold'.$id.'" class="soldbutton changesold itemsold btn btn-default btn-xs pull-left">已售出</span>':
								'<span id="itemsold'.$id.'" class="soldbutton changesold itemsoldno btn btn-info btn-xs pull-left">待售中</span>');
							}
							else{
							$soldlabel= ($sold?'<span id="itemsold'.$id.'" class="soldbutton itemsold btn btn-default btn-xs pull-left">已售出</span>':
								'<span id="itemsold'.$id.'" class="soldbutton itemsoldno label label-info pull-left">待售中</span>');	
							}//售出情况结束
							echo $soldlabel;
							echo '<a class="itemtitle" href="itemdetail?id='.$id.'"><h5>'.$title.'</h5></a>';
						echo '</div>';

						echo '<span class="itemprice">￥'.$price.'</span>';

						echo '<small class="itemdiscription text-justify">'.msubstr($discription,100).'...</small>';

						echo '<div class="itemsellerdiv">';
							if (isset($owner)){
								echo '<span class="itemdelete btn btn-xs btn-danger" id="'.$id.'">删除</span>';
								echo '<a href="userinfo.php?userid='.$owner.'"> '.$ownername.' </a>';
							}

						//	echo '<span class="itemdate small">   '.$date.'</span></br>';日期暂时不要了
						echo '</div>';
							if($myitem==1){
								echo '<span id="deleteitem'.$id.'" class="itemdelete" ></span>';		
							}
					echo '</div>';

				echo '</div>';//上半部分div结束
				echo '<div class="col-md-12 tag-list">';
					echo '<hr>';
					echo '<i class="glyphicon glyphicon-tags"></i>';
					foreach ($tag as $tagname) {
						echo '<a class="" href="showitem?keyword='.$tagname.'">  '.$tagname.'</a>';	
					}
				echo '</div>';
			echo '</div>';
		echo '</section>';
	}

//把文字tag变成数字----------------------
	function tagnametoid($tags){
		global $dbc;
		$tagarray=split(' ',$tags);
		foreach ($tagarray as $key => $tagname) {
			$query3="SELECT TAG_ID FROM tag WHERE TAG_NAME='$tagname'"; 
			$result3=mysqli_query($dbc,$query3);
			if($data3=mysqli_fetch_array($result3)){
				$tagarray["$key"]=$data3["TAG_ID"];
			}
			else{//添加没有的tag--------------------------------
				$query4="INSERT INTO tag (TAG_NAME) VALUES ('$tagname')"; 
				$result4=mysqli_query($dbc,$query4);
				$query5="SELECT TAG_ID FROM tag WHERE TAG_NAME='$tagname'"; 
				$data5=mysqli_fetch_array(mysqli_query($dbc,$query3));
				$tagarray["$key"]=$data5["TAG_ID"];
			}
		}
		$tagidstring=join(' ',$tagarray);
		return($tagidstring);
	}

?>