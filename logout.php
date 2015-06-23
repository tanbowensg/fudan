<?php

		setcookie('userid','',time()-1000);
		setcookie('username','',time()-1000);
		require_once('header.php');
  		echo '<meta http-equiv="refresh" content="0;url=index.php">';
?>