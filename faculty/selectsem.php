<?php
ob_start();
ini_set('display_errors', 'off');
require_once("includes/functions.php");
require_once("includes/session.php");
include("includes/DataAccess.php");
?>
<?php ?>
<?php
if (!logged_in()) {
    redirect_to("index.php");
}
?>
<?php
if (isset($_POST["ddlsem"])) {
    if ($_POST["ddlsem"] != "") {
        $_SESSION["ddlsem3"] = $_POST["ddlsem"];
        redirect_to("modifycoursedetails.php");
    }
}
?>
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Select Semester</title>
        <link href="css/menu.css" rel="stylesheet" type="text/css">
        <script src="js/jquery-latest.min.js"></script>
        <script src="ckeditor/ckeditor.js"></script>

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
                <?php if ($_GET["flag"] == 1) { ?>
                    <div id="divsuccess">
                        <img src="images/success.png" width="25px" height="25px"><?php echo $_GET["msg"]; ?> 
                    </div>
                <?php } ?>
                <?php if ($_GET["flag"] == 2) { ?>
                    <div id="diverror">
                        <img src="images/cross.png" width="25px" height="25px"><?php echo $_GET["msg"]; ?> 
                    </div>
                <?php } ?>
                <div id="viewsubjects">
                    <form method="post" action="selectsem.php">			
                        <table border="1">
                            <?php
                            $data1 = ExecuteNonQuery('select semid,semname,active,year from semester order by sortorder');
                            ?>
                            <tr>
                                <td colspan="3" align="center">
                                    Semester:
                                    <select name="ddlsem">
                                        <option value="">Select</option>
                                        <?php
                                        while ($info1 = mysqli_fetch_assoc($data1)) {
                                            ?>
                                            <option value="<?php echo $info1["semid"]; ?>" <?php if ($info1["active"] == "1") echo "selected"; ?>><?php echo $info1["semname"] . " " . $info1["year"]; ?></option>		
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </td>

                            </tr>
                            <tr>
                                <td colspan="3" align="center"><input type="submit" value="View Courses"/></td>
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