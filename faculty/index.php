<?php
ini_set('display_errors', 'off');
require_once("includes/functions.php");
require_once("includes/session.php");
include_once("includes/DataAccess.php");
include_once("includes/form_functions.php");
?>
<?php
if (logged_in()) {
    redirect_to("welcome.php");
}

if (isset($_POST["submit"])) {
    $errors = array();
    $required_fields = array('username', 'password');
    $errors = array_merge($errors, check_required_fields($required_fields, $_POST));


    if (empty($errors)) {

        $unm = $_POST["username"];
        $pwd = $_POST["password"];
        $cnt = CountRecords("select * from users where username='" . $unm . "' and password='" . $pwd . "'");
        $usrid = GetSingleField("select uid from users where username='" . $unm . "' and password='" . $pwd . "'", "uid");
        $permission = GetSingleField("select permission from users where username='" . $unm . "' and password='" . $pwd . "'", "permission");
        $activefld = GetSingleField("select active from users where username='" . $unm . "' and password='" . $pwd . "'", "active");
        if ($cnt > 0) {
            if ($permission == "0") {
                if ($activefld == "1") {
                    if (!is_dir($unm)) {
                        mkdir($unm, 0700);
                    }
                    $_SESSION["username"] = $unm;
                    $_SESSION["userid"] = $usrid;
                    $_SESSION["usrpermission"] = $permission;

                    redirect_to('welcome.php');
                } else {
                    $message = "You are not active user..Please contact administrator";
                }
            } else {
                $message = "You are an administrator...Please goto <a href='../admin/index.php'>admin module</a>";
            }
        } else {
            $message = "Username or password is incorrect...";
        }
    } else {
        if (count($errors) == 1) {
            $message = "There was 1 error in the form.";
        } else {
            $message = "There were " . count($errors) . " errors in the form.";
        }
    }
    ob_end_clean();
}
?>

<!doctype html>
<html>
    <head>
        <link href="css/menu.css" rel="stylesheet" type="text/css">
        <script src="js/jquery-latest.min.js"></script>
        <script type='text/javascript' src='js/menu_jquery.js'></script>
        <link href="css/style.css" rel="stylesheet" type="text/css">
        <meta charset="utf-8">
        <title>Login Page</title>
    </head>

    <body>
        <div id="containermain">
            <div id="header">
                <div id="banner">
<?php include('header.html'); ?>
                </div>
            </div>
            <div id="loginwrapper">

                <div id="logincontent">
                    <form action="index.php" method="post">
                        <table id="logintable">
                            <tr>
                                <td colspan="3" align="center" style="color:#F00;">

<?php
if (!empty($message)) {
    echo $message;
} else {
    echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
}
?>
<?php
if (!empty($errors)) {
    display_errors1($errors);
}
?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <img src="images/images.jpg" width="100%" height="90px"/>
                                </td>
                            </tr>
                            <tr>
                                <td>User Name</td>
                                <td>:</td>
                                <td><input type="text" name="username"></td>
                            </tr>
                            <tr>
                                <td>Password</td>
                                <td>:</td>
                                <td><input type="password" name="password"></td>
                            </tr>
                            <tr>
                                <td colspan="3" align="center"><input type="submit" value="Submit" name="submit"></td>
                            </tr>
                        </table>
                    </form>

                </div>
            </div>
            <div id="footer">
<?php include("footer.html"); ?>
            </div>
        </div>
    </body>
</html>
<? ob_flush(); ?>