<?php
    ini_set('display_errors', 'off');
    require_once("includes/functions.php");
?>
<?php
    session_name("admin");
    session_start();
    $_SESSION = array();
    if (isset($_COOKIE[session_name("admin")])) {
        setcookie(session_name("admin"), '', time() - 42000, '/');
    }
    session_unset();
    session_destroy();
    redirect_to("index.php?logout=1");
?>