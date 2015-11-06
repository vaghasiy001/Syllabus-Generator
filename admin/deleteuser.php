<?php
		require_once("includes/functions.php");
		include("includes/DataAccess.php");

if(isset($_GET["uid"]))
{
	$str="delete from users where uid=".$_GET["uid"];	
	ExecuteNonQuery($str);
	redirect_to("viewusers.php");
}
else
{
	redirect_to("index.php");
}
?>