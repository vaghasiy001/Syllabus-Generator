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
<?php if(isset($_POST["btnsubmit"]))
		{
			$errors = array();
			$required_fields = array('oldpassword', 'newpassword','confirmpassword');
			$errors = array_merge($errors, check_required_fields($required_fields, $_POST));			
			$opwd=$_POST["oldpassword"];
			$npwd=$_POST["newpassword"];
			$cpwd=$_POST["confirmpassword"];
		
		if (empty($errors) ) 
		{
				if($npwd==$cpwd)
				{
					$cntrec=CountRecords("select * from users where id=".$_SESSION["a_userid"]." and password='".$opwd."'");
					echo $cntrec;
					if($cntrec>0)
					{
						$query="update users set password='".$npwd."' where id=".$_SESSION["a_userid"];
						$ctrec=ExecuteNonQuery($query);
						if($ctrec==1)
							{
					//		redirect_to("changepassword.php?msg=Password successfully.&flag=1");
							redirect_to("logout.php");
							}
						else
							{
							redirect_to("changepassword.php?msg=Error in updating password..Please try later.&flag=2");
							}
						
					}
					else
					{
							$message="password is incorrect...Please try later.";	
					}
				}
				else
				{
						$message = "There was 1 error in the form.";
						single_errormessage('Password and Confirm password does not match');					
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
<link href="css/style.css" rel="stylesheet" type="text/css">
<title>Change Password</title>
</head>
<body>

<div id="container">
            <div id="header">
				<?php include("header.html"); ?>
            </div>

<div id="wrapper">        
    
<?php if($_GET["flag"]==1) { ?>
                <div id="divsuccess">
                    <img src="images/success.jpg" width="25px" height="25px"><?php echo $_GET["msg"]; ?> 
                </div>
                <?php } ?>
                <?php if($_GET["flag"]==2) { ?>
                <div id="diverror">
                    <img src="images/error.png" width="25px" height="25px"><?php echo $_GET["msg"]; ?> 
                </div>
                <?php }?>

    <div id="main">
      <div id="leftpane">
     <?php  include("leftpane.php"); ?>
      </div>
      <div id="content">
        <div id="info">
            <p>This page will change the admin password.</p>
                </div>
<form method="post" action="changepassword.php">

			<table>
 			 <tr>
            <td colspan="3" >
            <?php if (!empty($message)) {echo "<p class=message>" . $message . "</p>";}
				else {echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";}
			 ?>
			<?php if (!empty($errors)) 	
					{ 
			display_errors1($errors); 
			} ?>

            </td>
            </tr>
           		<tr>
                	<td colspan="3"><img src="images/password.jpg" height="70px" width="100%"></td>
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
</div>
<div id="footer">
	<div id="footernav">
	<?php include("footer.html");?>
    </div>
</div>
</div>
</body>
</html>