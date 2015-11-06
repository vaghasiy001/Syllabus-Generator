<?php
	ob_start();
	ini_set('display_errors','off');
	include_once("includes/connect.php");
	require_once("includes/functions.php");
	require_once("includes/session.php");
	include("includes/DataAccess.php");  
	include_once("includes/form_functions.php");
?>
<?php	if (!logged_in()) {
		redirect_to("index.php");
	}
?>
<?php
if(isset($_POST["btnsubmit"]))
{
		$errors = array();
		$required_fields = array('firstname','lastname','email','office','officeno');
		$errors = array_merge($errors, check_required_fields($required_fields, $_POST));	
	
	
		if (empty($errors) ) 
		{
					$fnm=$_POST["firstname"];
					$lnm=$_POST["lastname"];
					$sal=$_POST["ddlsal"];
					$eml=$_POST["email"];
					$office=$_POST["office"];
					$active=$_POST["cbxactive"];
					$offno=$_POST["officeno"];
					$strinsert="update users set firstname='".$fnm;
					$strinsert.="',lastname='".$lnm."',salutation='".$sal."',email='".$eml."',office='".$office."',officeno='".$offno;
					$strinsert.="' where uid=".$_GET["id"];
//					echo $strinsert;
					$cnt=ExecuteNonQuery($strinsert);
										
					if($cnt==1)
							{
									redirect_to("viewprofile.php");
							}
							
		}
		else
		{
			if (count($errors) == 1) {
				$message = "There was 1 error in the form.";
			} else {
				$message = "There were " . count($errors) . " errors in the form.";
			}
		}
		
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Edit Faculty</title>

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
<?php if(!empty($_GET["id"])){
				$data = ExecuteNonQuery('select * FROM users where uid='.$_GET["id"]);
				 while($info1 = mysqli_fetch_assoc( $data ))
				 {
					$fn=$info1["firstname"];
					$ln=$info1["lastname"];
					$un=$info1["username"];
					$sl=$info1["salutation"];
					$eml=$info1["email"];
					$ofc=$info1["office"];
					$ofn=$info1["officeno"];
					$act=$info1["active"];	 
				 }
		}
 ?>

<div id="facultycontent">	

         <form action="editfaculty.php?id=<?php echo $_GET["id"]?>" method="post">
         		<table>
                	<tr>
                    	<td colspan="3" style="color:red;"><?php if(isset($message)){echo $message;}?></td>
                    </tr>
	                <tr>
                    	<td align="right" colspan="3"><a href="viewprofile.php">Go Back</a></td>
                    </tr>
                	<tr>
                    
                    </tr>
                    <tr>
                    	<td colspan="3"><img src="images/faculty.jpg" width="100%" height="100px"></td>
                    </tr>
                	<tr>
                    	<td>User Name</td>
                        <td>:</td>
                        <td><input type="text" name="username" value="<?php echo $un; ?>" disabled/></td>
                    </tr>
                    <tr>
                    	<td>First Name</td>
                        <td>:</td>
                        <td><input type="text" name="firstname" value="<?php echo $fn; ?>"/></td>
                    </tr>
                    <tr>
                    	<td>Last Name</td>
                        <td>:</td>
                        <td><input type="text" name="lastname" value="<?php echo $ln; ?>"/></td>
                    </tr>
                    <tr>
                    	<td>Salutaiton</td>
                        <td>:</td>
                        <td><select name="ddlsal">
                        <option <?php if($sl=="Mr") echo "selected"; ?>>Mr</option>
                        <option <?php if($sl=="Miss") echo "selected"; ?>>Miss</option>
                        <option <?php if($sl=="Mrs") echo "selected"; ?>>Mrs</option>
                        <option <?php if($sl=="Dr") echo "selected"; ?>>Dr</option>
                        </select></td>
                    </tr>
                    <tr>
                    	<td>Email</td>
                        <td>:</td>
                        <td><input type="text" name="email" value="<?php echo $eml; ?>"/></td>
                    </tr>
                    <tr>
                    	<td>Office</td>
                        <td>:</td>
                        <td><input type="text" name="office" value="<?php echo $ofc; ?>"/></td>
                    </tr>
                    <tr>
                    	<td>Office No</td>
                        <td>:</td>
                        <td><input type="text" name="officeno" value="<?php echo $ofn; ?>"/></td>
                    </tr>
                    
                    <tr>
                    	<td>Is Active??</td>
                        <td>:</td>
                        <td><input type="checkbox" name="cbxactive" <?php if($act=="1") echo "checked"; ?> disabled></td>
                    </tr>
                    <tr>
                    	<td colspan="3" align="center"><input type="submit" value="Submit" name="btnsubmit"></td>
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