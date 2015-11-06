<?php
ini_set('display_errors','off');


		require_once("includes/functions.php");
		require_once("includes/session.php");
		include("includes/DataAccess.php");
?>
<? ob_start(); ?>
<?php	if (!logged_in()) {
		redirect_to("index.php");
	}
?>
<?php	if (!isset($_GET["id"])) {
		redirect_to("index.php");
	}
?>

<?php
if($_POST["action"]=="Submit Form")
{
$str="";
if(isset($_POST["cbx_m"]))
{
	$str=$_POST["cbx_m"];
}
if(isset($_POST["cbx_t"]))
{
	if($str!="")
	{
			$str.=" ".$_POST["cbx_t"];
	}
	else
	{
			$str=$_POST["cbx_t"];
	}
}
if(isset($_POST["cbx_w"]))
{
	if($str!="")
	{
			$str.=" ".$_POST["cbx_w"];
	}
	else
	{
			$str=$_POST["cbx_w"];
	}
}
if(isset($_POST["cbx_th"]))
{
	if($str!="")
	{
			$str.=" ".$_POST["cbx_th"];
	}
	else
	{
			$str=$_POST["cbx_th"];
	}
}
if(isset($_POST["cbx_f"]))
{
	if($str!="")
	{
			$str.=" ".$_POST["cbx_f"];
	}
	else
	{
			$str=$_POST["cbx_f"];
	}
}

$st=$_POST["ddlsthrs"].":".$_POST["ddlstmin"].$_POST["ddlstampm"];
$en=$_POST["ddlenhrs"].":".$_POST["ddlenmin"].$_POST["ddlenampm"];

$sql="update facultyhours set starttime='".$st."',endtime='".$en."',type='office',cday='$str'".",uid=".$_SESSION["userid"].",semid=".$_SESSION["ddlsem7"]." where fhid=".$_GET["id"];
//echo $sql;
ExecuteNonQuery($sql);
redirect_to("addoffhrs.php");
}
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Edit Office Hours</title>
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
    <?php if($_SESSION["username"]!=null) {
  			include('menu.php');
        } ?>
</div>
<div id="wrapper">
<div id="profilecontent">	
	<form method="post" action="editoffhrs.php?id=<?php echo $_GET["id"];?>">
    <?php
				$data2 = ExecuteNonQuery('select semname,year FROM semester where active=1');
	      		$snm="";
				$syr="";
		        while($info2 = mysqli_fetch_assoc($data2)) 
				{
				$snm=$info2["semname"];
				$syr=$info2["year"];
		    	}
	?>
    <?php
				$data3 = ExecuteNonQuery('select * from facultyhours where fhid='.$_GET["id"]);
	            $sttime="";
				$entime="";
				$cday=array();
				while($info3 = mysqli_fetch_assoc($data3)) 
				{
					$sttime=explode(":",$info3["starttime"]);
					$entime=explode(":",$info3["endtime"]);
					$cday=explode(" ",$info3["cday"]);
				}
			
	?>
	<table>
    <tr>
    	<td colspan="3"><?php echo "<i>Current Semester:</i>".$snm." ".$syr;?></td>
    </tr>
    <tr>
    <th colspan="3" align="center">Office Hours</th>
        </tr>
                  <tr>
                        <td>Days</td>
                        <td>:</td>
                        <td>

                        <input type="checkbox" value="M" name="cbx_m" <?php if(in_array("M",$cday)) echo " checked";?> >M
                        <input type="checkbox" value="T" name="cbx_t"  <?php if(in_array("T",$cday)) echo " checked";?>>T
                        <input type="checkbox" value="W" name="cbx_w"  <?php if(in_array("W",$cday)) echo " checked";?>>W
                        <input type="checkbox" value="Th" name="cbx_th"  <?php if(in_array("Th",$cday)) echo "checked";?>>Th
                        <input type="checkbox" value="F" name="cbx_f"  <?php if(in_array("F",$cday)) echo " checked";?>>F
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
					for($i=1;$i<=12;$i++)
					{
						if(strlen($i)=="1")
						{
							$i="0".$i;
						}
						?>
                        <option value="<?php echo $i;?>" <?php if($i==$sttime[0]) echo "selected";?>><?php echo $i;?></option>
                        <?php }?>
                    </select></td>
                   <td>:</td><td> 
                   
                   <select name="ddlstmin">
                    <?php
					for($i=0;$i<=60;$i+=5)
					{
						if(strlen($i)=="1")
						{
							$i="0".$i;
						}
						?>
                        <option value="<?php echo $i;?>" <?php if($i==substr($sttime[1],0,2)) echo "selected";?>><?php echo $i;?></option>
              <?php }?>
                                       
                    </select>
                    </td><td>
                    <select name="ddlstampm" >
                   <option <?php if("AM"==substr($sttime[1],2,2)) echo "selected";?>>AM</option>
                   <option <?php if("PM"==substr($sttime[1],2,2)) echo "selected";?>>PM</option>                    
                    </select>
                    </td>
                    </tr><tr><td>
               <b>     End Time</b></td>
               <td>
                    <select name="ddlenhrs">
                    <?php
					for($i=1;$i<=12;$i++)
					{
						if(strlen($i)=="1")
						{
							$i="0".$i;
						}
						?>
                        <option  value="<?php echo $i;?>" <?php if($i==$entime[0]) echo "selected";?>><?php echo $i;?></option>
                        <?php }?>
                                       
                    </select>
                    </td>
                    <td>
                  :  </td><td>
                  
                  <select name="ddlenmin">
                    <?php
					for($i=0;$i<=60;$i+=5)
					{
						if(strlen($i)=="1")
						{
							$i="0".$i;
						}
						?>
                        <option  value="<?php echo $i;?>" <?php if($i==substr($entime[1],0,2)) echo "selected";?>><?php echo $i;?></option>
                        <?php }?>
                                       
                    </select>
                    </td>
                    <td>
                    <select name="ddlenampm">
                   <option <?php if("AM"==substr($entime[1],2,2)) echo "selected";?>>AM</option>
                   <option <?php if("PM"==substr($entime[1],2,2)) echo "selected";?>>PM</option>                    
                    </select>
                    </td></tr>
                    </table>
                </td>
            </tr>
            <tr>
            	<td colspan="3" align="center">
                <input type="hidden" name="action" value="Submit Form">
                <input type="image" src="images/update.png" name="btnupdate"><a href="addoffhrs.php"><img src="images/cancel.png"></a></td>
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