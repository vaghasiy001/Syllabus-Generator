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
	if($_SESSION["usrpermission"]!=1)
		redirect_to("welcome.php"); 
?>
<?php
if(isset($_POST["btnsubmit"]))
{
		$errors = array();
		$required_fields = array('username', 'password','confirmpassword','firstname','lastname','email','office');
		$errors = array_merge($errors, check_required_fields($required_fields, $_POST));	
	
	
		if (empty($errors) ) {
				$unm=$_POST["username"];
					$pwd=$_POST["password"];
					$fnm=$_POST["firstname"];
					$lnm=$_POST["lastname"];
					$sal=$_POST["ddlsal"];
					$eml=$_POST["email"];
					$office=$_POST["office"];
					$user=$_POST["ddluser"];
					$active=$_POST["cbxactive"];
//					echo $active;
					if($active=="on")
						$active=1;
					else
						$active=0;

			$chkusrexist=CountRecords("select * from users where username='".$unm."'");
			if($chkusrexist==0)
			{
				if($_POST["password"]==$_POST["confirmpassword"])
					{
										$strinsert="insert into users(username,password,firstname,lastname,salutation,
						email,office,permission,active) values('".$unm."','".$pwd."','".$fnm."','".
						$lnm."','".$sal."','".$eml."','".$office."',b'".$user."',b'".$active."')";
						echo $strinsert;
						$cnt=ExecuteNonQuery($strinsert);
						
						if($cnt==1)
								redirect_to("viewfaculties.php?msg=Record inserted successfully.&flag=1");
						else
								redirect_to("viewfaculties.php?msg=Error in inserting record..Please try later.&flag=2");
	
					}
				else
					{
							$message = "There was 1 error in the form.";
							single_errormessage('Password and Confirm password does not match');					
					}
			}
			else
			{
						$message = "There was 1 error in the form.";
						single_errormessage('Username already exists into the database');						
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
<title>Add Faculty</title>

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
<div id="facultycontent">	

         <form action="addfaculty.php" method="post">
         		<table>
                	<tr>
                    	<td align="right" colspan="3"><a href="viewfaculties.php">Go Back</a></td>
                    </tr>
                	<tr>
                    <td colspan="3">
                    <?php if (!empty($message)) {echo "<p class=message>" . $message . "</p>";} 
						else echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
						?>
			<?php if (!empty($errors)) 			{ 
			display_errors1($errors); 
			} ?>
</td>
                    </tr>
                	<tr>
                    	<td colspan="3"><img src="images/faculty.jpg" width="100%" height="100px"></td>
                    </tr>
                	<tr>
                    	<td>User Name</td>
                        <td>:</td>
                        <td><input type="text" name="username" value="<?php echo $_POST['username']; ?>"/></td>
                    </tr>
                    <tr>
                    	<td>Password</td>
                        <td>:</td>
                        <td><input type="password" name="password"/></td>
                    </tr>
                    <tr>
                    	<td>Confirm Password</td>
                        <td>:</td>
                        <td><input type="password" name="confirmpassword" /></td>
                    </tr>
                    <tr>
                    	<td>First Name</td>
                        <td>:</td>
                        <td><input type="text" name="firstname" value="<?php echo $_POST['firstname']; ?>"/></td>
                    </tr>
                    <tr>
                    	<td>Last Name</td>
                        <td>:</td>
                        <td><input type="text" name="lastname" value="<?php echo $_POST['lastname']; ?>"/></td>
                    </tr>
                    <tr>
                    	<td>Salutaiton</td>
                        <td>:</td>
                        <td><select name="ddlsal">
                        <option>Mr</option>
                        <option>Miss</option>
                        <option>Mrs</option>
                        <option>Dr</option>
                        </select></td>
                    </tr>
                    <tr>
                    	<td>Email</td>
                        <td>:</td>
                        <td><input type="text" name="email" value="<?php echo $_POST['email']; ?>"/></td>
                    </tr>
                    <tr>
                    	<td>Office</td>
                        <td>:</td>
                        <td><input type="text" name="office" value="<?php echo $_POST['office']; ?>"/></td>
                    </tr>
                    <?php if($_SESSION["usrpermission"]==1){ ?>
                    <tr>
                    	<td>User</td>
                        <td>:</td>
                        <td>
                        		<select name="ddluser">
                            <option value="0">Faculty</option>
                            <option value="1">Administrator</option>
                            </select>
                        </td>
                    </tr>
                    <?php } ?>
                    <tr>
                    	<td>Is Active??</td>
                        <td>:</td>
                        <td><input type="checkbox" name="cbxactive" checked></td>
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