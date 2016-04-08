<?php
	session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<title>Main Menu</title>
	<link rel="stylesheet" type="text/css" href="menu.css"/>
</head>
<body>
	<div style="margin:0; text-align:center">
		<header>
			<p><img src="pitt.gif" alt=""></p>
		</header>
		<input onclick="location.href='pickup.php'" type="button" class="btn" value="Pick Up">
		<input onclick="location.href='dropoff.php'" type="button" class="btn" value="Drop Off">
		<input onclick="location.href='questions.php'" type="button" class="btn" value="Questions">
	</div>
	
	<footer>
		<p>Copyright &copy; 2016</p>
	</footer>
</body>
</html>
