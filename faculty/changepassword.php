<?php
	ob_start();
	ini_set('display_errors','off');
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
					$required_fields = array('oldpassword', 'newpassword','confirmpassword');
					$errors = array_merge($errors, check_required_fields($required_fields, $_POST));			
					$opwd=$_POST["oldpassword"];
					$npwd=$_POST["newpassword"];
					$cpwd=$_POST["confirmpassword"];
					$cnt=CountRecords("select * from users where uid=".$_SESSION["userid"]." and password='".$opwd."'");
					if($cnt==1)
					{
						$sql="update users set password='".$npwd."' where  uid=".$_SESSION["userid"];
						ExecuteNonQuery($sql);
						redirect_to("logout.php");
					}
					else
					{
						$message="Incorrect Entry";
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
	
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Change Password</title>
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

<?php if($_GET["flag"]==1) { ?>
                <div id="divsuccess">
                    <img src="images/success.jpg" width="25px" height="25px"><?php
					if(empty($_GET["msg"]))
						echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					else	
						echo $_GET["msg"]; 
					 
					 ?> 
                </div>
                <?php } ?>
               
			<div id="chgpwdcontent">
			<form method="post" action="changepassword.php">
		<table>
   	        <td colspan="3" >
			 </td>
            </tr>
            
        	<tr>
            	<td colspan="3" align="center"><img src="images/ChangePassword.png" width="300px" height="100px"></td>
            </tr>
            <tr>
            	<td>Old Password</td>
                <td>:</td>
                <td><input type="password" name="oldpassword"></td>
            </tr>
            <tr>
            	<td>New Password</td>
                <td>:</td>
                <td><input type="password" name="newpassword"></td>
            </tr>
            <tr>
            	<td>Confirm Password</td>
                <td>:</td>
                <td><input type="password" name="confirmpassword"></td>
            </tr>
            <tr>
            	<td colspan="3" align="center"><input type="submit" value="Change Password" name="btnsubmit"></td>
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