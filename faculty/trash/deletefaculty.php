<?php
ini_set('display_errors','off');
include("includes/DataAccess.php");  
require_once("includes/functions.php");
echo "hello";
$id=$_GET["id"];
$cnt=ExecuteNonQuery("delete from users where userid=".$id);
$cntrec=CountRecords("select * from subject where userid=".$id);
if($cntrec>0)
	$cnt=ExecuteNonQuery("delete from subject where userid=".$id);
$cntrec=CountRecords("select * from outcomes_result where userid=".$id);
if($cntrec>0)
	$cnt=ExecuteNonQuery("delete from outcomes_result where userid=".$id);
if($cnt>0)
	redirect_to("viewfaculties.php?flag=1&msg=Record deleted successfully");	
?>