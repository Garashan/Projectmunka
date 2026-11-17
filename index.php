<?php
ob_start();
session_start();
require_once('include/adabazis_csatlakozas.php');
require_once('include/auth.php');

// CSRF-token létrehozása a kijelentkezéshez
if (isLoggedIn() && !isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<html>
<head>
	<link href="icon.ico" rel="shortcut icon" />
	<link rel=stylesheet href="/css/style.css" type="text/css">
</head>
<body>
<div class="menu">
	<a class="menuelem" href="/termekek">Termékek</a>
	<?php
	if(isLoggedIn()) {
	echo '<a class="menuelem" href="/fiok">Felhasználói fiók</a>';
	}
	else
	{
	echo '<a class="menuelem" href="/fiok">Bejelentkezés</a>';
	}
	?>
	<a class="menuelem" href="/rolunk">Rólunk</a>
	<?PHP
	if(isLoggedIn()) {
	echo '<form action="/logout.php" method="POST" class="logout_form">';
	echo '<input type="hidden" name="csrf_token" value="' .
		 htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') . '">';
	echo '<button type="submit" class="menuelem">Kijelentkezés</button>';
	echo '</form>';
	}
	?>
</div>
<div id="content">
	<?PHP
	include("contentvalaszto.php");
	$page=menu() . ".php";
	include($page);

	?>
</div>
</body>
</html>