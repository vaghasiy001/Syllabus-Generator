<?php
ob_start();
	
		ini_set('display_errors','on');
		require_once("includes/functions.php");
		require_once("includes/session.php");
		include_once("includes/DataAccess.php");	
		include_once("includes/form_functions.php");

?>

<?php
	if(isset($_POST["submit"]))
	{
	
		$errors = array();
		$required_fields = array('username','password');
		$errors = array_merge($errors, check_required_fields($required_fields, $_POST));	
	if (empty($errors) ) {

		$unm=$_POST["username"];
		$pwd=$_POST["password"];
		$cnt=CountRecords("select * from users where username='".$unm."' and password='".$pwd."' and permission=1");
	//	echo ".............".$usrid."asdas ".$permission;
		if($cnt>0)
			{
				$usrid=GetSingleField("select uid from users where username='".$unm."' and password='".$pwd."'","uid");	
				$permission=GetSingleField("select permission from users where username='".$unm."' and password='".$pwd."'","permission");
				$activefld=GetSingleField("select active from users where username='".$unm."' and password='".$pwd."'","active");
				
				if($activefld=="1")
				{
					  $_SESSION["username"]=$unm;
					  $_SESSION["userid"]=$usrid;
					  $_SESSION["usrpermission"]=$permission;
					  redirect_to('welcome.php');
				}
				else
				{
					  $message="You are not active user..Please contact administrator";							
				}
 			}
			else
			{
					$message="Username or password is incorrect...";
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
			ob_end_clean();
	}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Admin Login</title>

<link href="css/style.css" rel="stylesheet" type="text/css">
</head>

<body>
<div id="container">
            <div id="header">
                <div id="logo">
                	<img src="images/gubanner.png" height="200px" width="1024px">
                </div>
                <div id="navbar">
                </div>
            </div>
<div id="wrapper">
<div id="loginmain">
    <div id="logincontent">
    <form method="post" action="index.php">
    <table>
    	<tr>
    	<td colspan="3" align="center" style="color:#F00;">
        
		<?php if (!empty($message)) {echo  $message;}
			else
				{ echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";} 
			?>
			<?php if (!empty($errors)) 			{ 
			display_errors1($errors); 
			}
			 ?>

        </td>
	    </tr>
        <tr>
        	<td colspan="3"><img src="images/login_top.jpg" height="100px" width="300px"></td>
        </tr>
        <tr>
    	<td>User Name</td>
        <td>:</td>
        <td><input type="text" name="username"></td>
     </tr>
     <tr>
     	<td>Password</td>
        <td>:</td>
        <td><input type="password" name="password"></td>
   	 </tr>
     <tr>
     	<td colspan="3" align="center"><input type="submit" value="submit" name="submit">
        </td>
 	</tr>
             <tr>
     			<td colspan="3" align="center">
         			<a href="../faculty/index.php" style="float:right;font-size:12px;">Faculty Login</a>
				</td>
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
    <div id="contact">
    </div>
    <div id="network">
    </div>
</div>
</div>
</body>
</html>