<?php
   if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
	function logged_in() {
		return isset($_SESSION['username']);
		return isset($_SESSION['userid']);
		return isset($_SESSION['usrpermission']);
				// set timeout period in seconds
			$inactive = 600;
			// check to see if $_SESSION['timeout'] is set
			if(isset($_SESSION['timeout']) ) {
			$session_life = time() - $_SESSION['timeout'];
			if($session_life > $inactive)
			{ session_destroy(); header("Location: logoutpage.php"); }
			}
			$_SESSION['timeout'] = time();
				
		}
	
	function confirm_logged_in() {
		if (!logged_in()) {
			redirect_to("login.php");
		}
	}
?>
