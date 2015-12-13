<?php
    ini_set('display_errors', 'off');
    require_once("includes/functions.php");
?>
<?php
    session_name("faculty");
    session_start();
    $_SESSION = array();
    if (isset($_COOKIE[session_name("faculty")])) {
        setcookie(session_name("faculty"), '', time() - 42000, '/');
    }
    session_unset();
    session_destroy();
    redirect_to("index.php?logout=1");
?>