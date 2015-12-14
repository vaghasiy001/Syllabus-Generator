<?php
ini_set('display_errors', 'off');
require_once("includes/functions.php");
require_once("includes/session.php");
require_once("includes/connect.php");
include("includes/DataAccess.php");
?>
<? ob_start(); ?>
<?php
if (!logged_in()) {
    redirect_to("index.php");
}
?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>View Faculties</title>
        <link href="css/menu.css" rel="stylesheet" type="text/css">
        <script src="js/jquery-latest.min.js"></script>
        <script type='text/javascript' src='js/menu_jquery.js'></script>
        <link href="css/style.css" rel="stylesheet" type="text/css">
    </head>
    <body>
        <div id="containermain">
            <div id="header">
                <div id="banner">
                <?php include('header.html'); ?>
                </div>
                <?php
                if ($_SESSION["username"] != null) {
                    include('menu.php');
                }
                ?>
            </div>
            <div id="wrapper">

                <div id="viewfaculties">
                    <form method="post" action="viewfaculties.php" name="form1">
                        <div style="width:100%" align="center">Group:
                            <select onChange="document.form1.submit();" name="selfgrp">
                                <option value="">All</option>
                                <option value="S" <?php if ($_POST["selfgrp"] == "S") echo "selected"; ?> >Staff  Members</option>
                                <option value="F" <?php if ($_POST["selfgrp"] == "F") echo "selected"; ?>> Faculty Members</option>
                                <option value="A" <?php if ($_POST["selfgrp"] == "A") echo "selected"; ?>>Adjunct Faculty Members</option>
                            </select>
                        </div>
                        <table border="1">
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Office</th>
                                <th>Office No</th>
                                <th>Office Hours</th>
                            </tr>
                            <?php
                            if (isset($_POST["selfgrp"]) && $_POST["selfgrp"] != "") {
                                if ($_POST["selfgrp"] == "S")
                                    $data = ExecuteNonQuery('select * FROM users where sm="S" order by lastname');
                                else if ($_POST["selfgrp"] == "F")
                                    $data = ExecuteNonQuery('select * FROM users where sm="F" order by lastname');
                                else if ($_POST["selfgrp"] == "A")
                                    $data = ExecuteNonQuery('select * FROM users where sm="A" order by lastname');
                                else
                                    $data = ExecuteNonQuery('select * FROM users order by lastname');
                            }
                            else {
                                $data = ExecuteNonQuery('select * FROM users order by lastname');
                            }
                            while ($info = mysqli_fetch_assoc($data)) {
                                ?>
                                <tr>
                                    <td><?php echo $info["salutation"] . " " . $info["firstname"] . " " . $info["lastname"]; ?> </td>
                                    <td><?php echo $info["email"]; ?> </td>
                                    <td><?php echo $info["office"]; ?> </td>
                                    <td><?php echo $info["officeno"]; ?> </td>
                                    <td>
                                        <?php
                                        $semid = GetSingleField("select semid from semester where active=1", "semid");
                                        $data1 = ExecuteNonQuery("select cday,starttime,endtime from facultyhours where uid=" . $info["uid"] . " and semid=" . $semid . " and type='office'");
                                        while ($info1 = mysqli_fetch_assoc($data1)) {
                                            echo $info1["cday"] . " " . $info1["starttime"] . ":" . $info1["endtime"] . "<br>";
                                        }
                                        ?>
                                    </td>
                                </tr>
                            <?php } ?>
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