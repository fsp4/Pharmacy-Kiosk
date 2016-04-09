<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, height=device-height">

	<title>Thank You!</title>
	<link rel="stylesheet" type="text/css" href="menu.css"/>
</head>

<body>
	<header>
		<p><img src="pitt_logo.png"></p>
	</header>
	<button onclick="location.href='main_menu.html'" class="btnTypeTwo" style="width:150px; font-size:150%;">HOME</button>
	<br></br>
	<div id="thanks">
		<?php
			$num = $_SESSION["num"];
			echo "<b>Your queue number is $num </b> <br></br>";
			echo "Once your queue number has been reached, please report to the counter and present your script to the pharmacist if dropping off";
		?>
	</div>
</body>
</html>
