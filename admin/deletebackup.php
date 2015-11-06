<?php
ini_set('display_errors','off');
include("includes/DataAccess.php");  
require_once("includes/functions.php");
$id=$_GET["id"];
$cnt=ExecuteNonQuery("delete from dbbackup where id=".$id);

if($cnt>0)
	redirect_to("viewbackups.php?flag=1&msg=Backup deleted successfully...");
?>