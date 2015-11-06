<?php
ini_set('display_errors','off');

 require_once("includes/functions.php"); ?>
<?php
		// Four steps to closing a session
		// (i.e. logging out)

		// 1. Find the session
		$_SESSION["username"]="";
		$_SESSION["userid"]="";
		$_SESSION["usrpermission"]="";
	
		session_start();
		
		// 2. Unset all the session variables
		$_SESSION = array();
		
		// 3. Destroy the session cookie
		if(isset($_COOKIE[session_name()])) {
			setcookie(session_name(), '', time()-42000, '/');
		}
		// 4. Destroy the session
		session_unset();
		session_destroy();
		
		redirect_to("index.php?logout=1");
?>