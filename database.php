<?php
$servername = "sql308.epizy.com";
	$username = "epiz_25395564";
	$password = "BixwNrur3UuMZ5";
	$dbname = "epiz_25395564_chat";
$conn = mysqli_connect($servername, $username, $password, $dbname);
	/* check connection */
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}
?>