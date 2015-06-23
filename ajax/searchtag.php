<?php

	require_once("../function.php");
	
	if (!empty($_GET['keytag'] )) {

		$dbc=mysqli_connect($DB_HOST,$DB_USER,$DB_PSW,$DB_NAME);
		$keytag=mysqli_real_escape_string($dbc, trim($_GET['keytag']));
		$query="SELECT TAG_NAME from tag WHERE TAG_NAME LIKE '$keytag%' LIMIT 5";

		$result=mysqli_query($dbc,$query);

		while ($data=mysqli_fetch_array($result)) {
				echo '<span class="tagtochoose">'.$data['TAG_NAME'].'</span>';
			}

		mysqli_close($dbc);

	}

?>