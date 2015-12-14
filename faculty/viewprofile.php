<?php
ini_set('display_errors', 'on');
require_once("includes/functions.php");
require_once("includes/session.php");
include("includes/DataAccess.php");
ob_start();
if (!logged_in()) {
    redirect_to("index.php");
}
?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>View Profile</title>
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
                <div id="facultycontent">	
                    <?php
                    $data = ExecuteNonQuery('select * FROM users where uid=' . $_SESSION["userid"]);
                    echo "<table>";
                    while ($info = mysqli_fetch_array($data)) {
                        ?>
                        <tr>
                            <td colspan="3" align="right"><a href="editfaculty.php?id=<?php echo $_SESSION["userid"]; ?>"><img src="images/pencil.png" alt="Edit" height="40px" width="40px" title="Edit"></a></td>
                        </tr>
                        <tr>
                            <td colspan="3"><img src="images/view-profile.jpg" width="100%" height="100px"></td>
                        <tr>	
                            <td>User Name</td>
                            <td>:</td>
                            <td><?php echo $info["username"]; ?> </td>
                        </tr>                      
                        <tr>
                            <td>Salutation</td>
                            <td>:</td>
                            <td><?php echo $info["salutation"]; ?> </td>
                        </tr>
                        <tr>
                            <td>First Name</td>
                            <td>:</td>
                            <td><?php echo $info["firstname"]; ?> </td>
                        </tr>                
                        <tr>
                            <td>Last Name</td>
                            <td>:</td>
                            <td><?php echo $info["lastname"]; ?> </td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td>:</td>
                            <td><?php echo $info["email"]; ?> </td>
                        </tr>
                        <tr>
                            <td>Office</td>
                            <td>:</td>
                            <td><?php echo $info["office"]; ?> </td>
                        </tr>
                        <tr>
                            <td>Office No</td>
                            <td>:</td>
                            <td><?php echo $info["officeno"]; ?> </td>
                        </tr>
                        <tr>
                            <td>Is Admin?</td>
                            <td>:</td>
                            <td><?php
                                if ($info["permission"] == 1) {
                                    echo "Yes";
                                } else {
                                    echo "No";
                                }
                                ?> </td>
                        </tr>
                        <tr>
                            <td>Is Active?</td>
                            <td>:</td>
                            <td><?php
                                if ($info["active"]) {
                                    echo "Yes";
                                } else {
                                    echo "No";
                                }
                                ?> </td>
                        </tr>
                    <?php } ?>
                    </table>
                </div>
            </div>
            <div id="footer">
                <?php include("footer.html"); ?>
            </div>
        </div>
    </body>
</html>