<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Thank You!</title>
	<link rel="stylesheet" type="text/css" href="menu.css"/>
</head>

<body>
	<header>
		<p><img src="pitt_logo.png" alt=""></p>
	</header>
	<button onclick="location.href='main_menu.php'" class="btnTypeTwo" style="width:150px; font-size:150%;">HOME</button>
	<br></br>
	<?php
		$num = $_SESSION["num"];
		echo "$num";
	?>
</body>
</html>
