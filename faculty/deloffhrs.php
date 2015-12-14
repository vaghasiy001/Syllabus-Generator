<?php

require_once("includes/functions.php");
include("includes/DataAccess.php");

if (isset($_GET["id"])) {
    $str = "delete from facultyhours where fhid=" . $_GET["id"];
    ExecuteNonQuery($str);
    redirect_to("addoffhrs.php");
} else {
    redirect_to("index.php");
}
?>