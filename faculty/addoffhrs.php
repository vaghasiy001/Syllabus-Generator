<?php
ini_set('display_errors', 'off');


require_once("includes/functions.php");
require_once("includes/session.php");
include("includes/DataAccess.php");
?>
<? ob_start(); ?>
<?php
if (!logged_in()) {
    redirect_to("index.php");
}
?>
<?php
if (isset($_POST["btnadd"])) {
    $str = "";
    if (isset($_POST["cbx_m"])) {
        $str = $_POST["cbx_m"];
    }
    if (isset($_POST["cbx_t"])) {
        if ($str != "") {
            $str.=" " . $_POST["cbx_t"];
        } else {
            $str = $_POST["cbx_t"];
        }
    }
    if (isset($_POST["cbx_w"])) {
        if ($str != "") {
            $str.=" " . $_POST["cbx_w"];
        } else {
            $str = $_POST["cbx_w"];
        }
    }
    if (isset($_POST["cbx_th"])) {
        if ($str != "") {
            $str.=" " . $_POST["cbx_th"];
        } else {
            $str = $_POST["cbx_th"];
        }
    }
    if (isset($_POST["cbx_f"])) {
        if ($str != "") {
            $str.=" " . $_POST["cbx_f"];
        } else {
            $str = $_POST["cbx_f"];
        }
    }

    $st = $_POST["ddlsthrs"] . ":" . $_POST["ddlstmin"] . $_POST["ddlstampm"];
    $en = $_POST["ddlenhrs"] . ":" . $_POST["ddlenmin"] . $_POST["ddlenampm"];

    $sql = "insert into facultyhours(starttime,endtime,type,cday,uid,semid)values('" . $st . "','" . $en . "','office','$str'" . "," . $_SESSION["userid"] . "," . $_SESSION["ddlsem7"] . ")";
//echo $sql;
    ExecuteNonQuery($sql);
}
?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Add Office Hours</title>
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
                <div id="profilecontent">	
                    <form method="post" action="addoffhrs.php">
<?php
$data2 = ExecuteNonQuery('select semname,year FROM semester where active=1');
$snm = "";
$syr = "";
while ($info2 = mysqli_fetch_assoc($data2)) {
    $snm = $info2["semname"];
    $syr = $info2["year"];
}
?>
                <?php
                $time1 = '12:00PM';
                $time2 = '01:00AM';
                list($hours, $minutes) = explode(':', $time1);
                $startTimestamp = mktime($hours, $minutes);

                list($hours, $minutes) = explode(':', $time2);
                $endTimestamp = mktime($hours, $minutes);

                $seconds = $endTimestamp - $startTimestamp;
                $minutes = ($seconds / 60) % 60;
                $hours = floor($seconds / (60 * 60));
                ?>
                        <table>
                            <tr>
                                <td colspan="3"><?php echo "<i>Current Semester:</i>" . $snm . " " . $syr; ?></td>
                            </tr>
                            <tr>
                                <th colspan="3" align="center">Office Hours</th>
                            </tr>
                            <tr>
                                <td>Days</td>
                                <td>:</td>
                                <td>
                                    <input type="checkbox" value="M" name="cbx_m">M
                                    <input type="checkbox" value="T" name="cbx_t">T
                                    <input type="checkbox" value="W" name="cbx_w">W
                                    <input type="checkbox" value="Th" name="cbx_th">Th
                                    <input type="checkbox" value="F" name="cbx_f">F
                                </td>
                            </tr>
                            <tr>
                                <td>Hours</td>
                                <td>:</td>
                                <td>
                                    <table>
                                        <tr>
                                            <td>
                                                <b>Start Time </b></td>
                                            <td>
                                                <select name="ddlsthrs">
<?php
for ($i = 1; $i <= 12; $i++) {
    if (strlen($i) == "1") {
        $i = "0" . $i;
    }
    ?>
                                                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
<?php } ?>
                                                </select></td>
                                            <td>:</td><td> 

                                                <select name="ddlstmin">
<?php
for ($i = 0; $i <= 60; $i+=5) {
    if (strlen($i) == "1") {
        $i = "0" . $i;
    }
    ?>
                                                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
<?php } ?>

                                                </select>
                                            </td><td>
                                                <select name="ddlstampm">
                                                    <option>AM</option>
                                                    <option>PM</option>                    
                                                </select>
                                            </td>
                                        </tr><tr><td>
                                                <b>     End Time</b></td>
                                            <td>
                                                <select name="ddlenhrs">
<?php
for ($i = 1; $i <= 12; $i++) {
    if (strlen($i) == "1") {
        $i = "0" . $i;
    }
    ?>
                                                        <option  value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                                    <?php } ?>

                                                </select>
                                            </td>
                                            <td>
                                                :  </td><td>

                                                <select name="ddlenmin">
<?php
for ($i = 0; $i <= 60; $i+=5) {
    if (strlen($i) == "1") {
        $i = "0" . $i;
    }
    ?>
                                                        <option  value="<?php echo $i; ?>"><?php echo $i; ?></option>
<?php } ?>

                                                </select>
                                            </td>
                                            <td>
                                                <select name="ddlenampm">
                                                    <option>AM</option>
                                                    <option>PM</option>                    
                                                </select>
                                            </td></tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" align="center"><input type="submit" value="Add" name="btnadd"></td>
                            </tr>
                        </table>
                    </form>
<?php
$sql = "select * from facultyhours where uid=" . $_SESSION["userid"] . " and semid=" . $_SESSION["ddlsem7"] . " and type='office'";
$cnt = CountRecords($sql);
//	echo $cnt;
?>
                    <form method="post" action="addoffhrs.php">
                        <table border="1">
                                                    <?php
                                                    if ($cnt != 0) {
                                                        ?>
                                <tr>
                                    <th>Day</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                </tr>
<?php } ?>
<?php
if ($cnt == 0) {
    ?>
                                <tr>
                                    <td colspan="6" align="center"><b><i>No office hours for this semester</i></b></td>
                                </tr>
    <?php
} else {
    $sql = "select * from facultyhours where uid=" . $_SESSION["userid"] . " and semid=" . $_SESSION["ddlsem7"] . " and type='office'";
    $data = ExecuteNonQuery($sql);
    $hrtemp = 0;
    $mintemp = 0;
    $cday = array();
    while ($info = mysqli_fetch_assoc($data)) {
        $cday = explode(" ", $info["cday"]);
        for ($i = 0; $i < count($cday); $i++) {

            $time1 = date("H:i", strtotime($info["starttime"]));
            $time2 = date("H:i", strtotime($info["endtime"]));
//							echo $time1." ".$time2;
            $t = get_time_difference($time1, $time2);
            //	echo date("h:i A", strtotime($t));
            //echo (timeDiff($time1,$time2)/60)/60;
            /* list($hours, $minutes) = explode(':', $time1);
              $startTimestamp = mktime($hours, $minutes);

              list($hours, $minutes) = explode(':', $time2);
              $endTimestamp = mktime($hours, $minutes);

              $seconds = $endTimestamp - $startTimestamp;
              $minutes = ($seconds / 60) % 60;
              $hours = floor($seconds / (60 * 60));

              $hrtemp=$hrtemp+$hours;
              $mintemp=$mintemp+$minutes; */
        }

        //		echo "Time passed: <b>$hrtemp</b> hours and <b>$mintemp</b> minutes<br>";					
        ?>

                                    <tr>
                                        <td><?php echo $info["cday"]; ?> </td>
                                        <td><?php echo $info["starttime"]; ?> </td>
                                        <td><?php echo $info["endtime"]; ?> </td>
                                        <td>
                                            <a href="editoffhrs.php?id=<?php echo $info["fhid"]; ?>"><img src="images/edit.png"></a>
                                        <td align="center">
                                            <a href="deloffhrs.php?id=<?php echo $info["fhid"]; ?>"><img src="images/close_icon.png" onClick="return confirm('Are you sure you want to delete?');">

                                            </a>
                                        </td>
                                    </tr>
                                    <?php
                                }

                                if ($mintemp >= 60) {
                                    while ($mintemp >= 60) {
                                        $hrtemp = $hrtemp + 1;
                                        $mintemp = $mintemp - 60;
                                    }
                                }
                                echo "Total Office Hours: <b>$hrtemp</b> hours and <b>$mintemp</b> minutes";
                            }
                            ?>
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
<?php

function get_time_difference($time1, $time2) {
    $time1 = strtotime("1/1/1980 $time1");
    $time2 = strtotime("1/1/1980 $time2");

    if ($time2 < $time1) {
        $time2 = $time2 + 86400;
    }

    return ($time2 - $time1) / 3600;
}
?>