<?PHP
session_start();
	if ($_SESSION['logged_in'] == 0) {
		header("Location: ?q=unauthorised");
	}
?>