<?php 
	require_once("function.php");
	if (isset($_COOKIE["userid"])){
		$dbc=mysqli_connect($DB_HOST,$DB_USER,$DB_PSW,$DB_NAME);
		$userid=$_COOKIE["userid"];
		$query="SELECT MAIL_READ FROM mail WHERE MAIL_REC='$userid'";
		$result=mysqli_query($dbc,$query);
		$no=0;
		while ($maildata=mysqli_fetch_array($result)) {
			if ($maildata["MAIL_READ"]==0){
				$no++;
			}
		}
		mysqli_close($dbc);
	}
?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,height=device-height,initial-scale=1,maximum-scale=1,user-scalabel=no">
	<title><?php echo $page_title; ?></title>
	<script type="text/javascript" src="js/jquery-2.1.1.js"></script>
	<script type="text/javascript" src="js/js.js"></script>
	<script type="text/javascript" src="js/bootstrap.js"></script>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="css/over.css">
</head>
<body>
<nav class='navbar navbar-default '>
	<div class="container">
		<div class="navbar-header">
		    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navli">
		        <span class="sr-only">Toggle navigation</span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		    </button>
			<a id="logo" class="navbar-brand" href="#"></a>
		</div>
		<div class="collapse navbar-collapse" id="navli">
			<ul class="nav navbar-nav">
				<li class=""><a href="showitem">首页<span class="sr-only">(current)</span></a></li>
				<!--<li><a href="showitem?keyword=书籍">书籍</a></li>
				<li class="navitem"><a href="showitem?keyword=电子">电子</a></li>
				<li class="navitem"><a href="showitem?keyword=衣服">衣服</a></li>
				<li class="navitem"><a href="showitem?keyword=杂物">杂物</a></li>-->
				<li ><a href="#" id="uploadbutton" data-toggle="modal" data-target="#uploaddialog">上传</a></li>
			</ul>
			<form class="navbar-form navbar-left" id="search" role="search"  method="get" action="showitem.php">
				<input class="form-control" id="searchtext" type="text" name="keyword" id="keyword" value=<?php echo '"'.$_GET['keyword'].'"';?>>
			<button type="submit" class="btn btn-default">搜索</button>

		</form>

	<?php if (isset($_COOKIE["username"])){ ?>
			<ul  class="nav navbar-nav navbar-right">
				<li role="presentation" class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><?php echo $_COOKIE["username"];?>  <span id="mailnotice" class="badge"><?php echo $no;?></span><span class="caret"></span></a>
					<ul class="dropdown-menu" role="menu">
						<li ><a  href=<?php echo '"userinfo?userid='.$_COOKIE["userid"].'"';?>>个人信息</a></li>
						<li ><a  href="checkmail">查收站内信<?php echo $no ;?></a></li>
						<li ><a  href="myitem">查看我的商品</a></li>
						<li ><a  href="logout">登出</a></li>
					</ul>
				</li>
			</ul>
<?php } else{ ?>
			<div id="logindiv" class="userdiv ">
				<ul  class="nav navbar-nav pull-right">
					<li id="loginli" class="navitem"><a href="index.php">登录</a></li>
				</ul>
			</div>
<?php } ?>
	</div>
</nav>

<div id="uploaddialog" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">商品上传</h4>
			</div>
			<div class="modal-body">
				<form id="uploadform" class="form-group" enctype="multipart/form-data" action="uploaditem.php" method="post">
					<div class="form-group">
						<label class="sr-only" for="title">标题</label>
						<input type="text" class="form-control uploadinput" id="title" name="title" placeholder="标题 必填项" required aria-required="true" autofocus>
					</div>
					<div class="form-group">
						<label class="sr-only" for="discription">简介</label>
						<textarea class="uploadinput form-control" id="discription" name="discription" placeholder="简介 必填项" required aria-required="true"></textarea>
					</div>
					<div class="form-group">
						<label class="sr-only" for="tags">标签</label>
						<input type="text" class="uploadinput form-control" id="tags" name="tags" onkeyup="searchtag(this.value)" placeholder="标签 多个标签用空格分开 必填项" required aria-required="true">
					<div id="tagtest" class="hide"></div>
					</div>
						<div class="form-group">
						<label class="sr-only" for="price">定价</label>
						<input type="text" class="uploadinput form-control" id="price" name="price" placeholder="定价 必填项" required aria-required="true">		
					</div>
					<div class="form-group">
						<label class="sr-only" for="pic">图片</label>
						<input type="file" class="uploadint" id="pic" name="pic[]" title="上传图片" accept="jpg" required aria-required="true" multiple><small class="uploadlimit">一次最多上传5个图片文件</small>
					</div>
				</form>

			</div>
			<div class="modal-footer">
				<button type="submit" class="uploadinput btn btn-default" id="upload" name="upload">上传</button>
			</div>
		</div>
	</div>
</div>

	<div id="sendmaildialog" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<span class="modal-title">发送站内信</span>

				</div>
				<div class="modal-body">
					<form id="mailform" class="form-group" enctype="multipart/form-data" action="ajax/" method="post">
						<div class="form-group">
							<label class="sr-only" for="title">收信人</label>
							<input type="text" class="uploadinput form-control" id="mailreceiver" name="mailreceiver" placeholder="收信人 必填项" required aria-required="true" autofocus>
						</div>
						<div class="form-group">
							<label class="sr-only" for="title">内容</label>
							<textarea class="uploadinput " id="mailcontent" name="mailcontent" placeholder="站内信内容 必填项" required aria-required="true"></textarea>
						</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-default" id="sendbutton" name="send">发送</button>
				</div>
			</div>
		</div>
	</div>
</html>