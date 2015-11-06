<?php
	ob_start();
	ini_set('display_errors','off');
	require_once("includes/session.php");
	include_once("includes/form_functions.php");
	require_once("includes/functions.php");
	include_once("includes/DataAccess.php");
?>
<?php	if (!logged_in()) {
		redirect_to("index.php");
	}
?>
<?php
if(isset($_POST["Submit"]))
{
	$errors = array();
		$required_fields = array('username','password','firstname','lastname','email','office');
		$errors = array_merge($errors, check_required_fields($required_fields, $_POST));			
		if (empty($errors) ) 
		{
				if( $_POST["ddlsal"]=="" )
				{
					show_alert("Please select dropdown");	
				}
				else
				{
					$i=0;
					$j=0;
					if(isset($_POST["cbxactive"]))
						$i=1;
					if(isset($_POST["cbxadmin"]))
 						$j=1;
 				$str="insert into users(username,password,firstname,lastname,salutation,email,officeno,office,permission,active,deptid,sm)";
				$str.="values('".$_POST["username"]."','".$_POST["password"]."','".ucfirst(strtolower($_POST["firstname"]))."','".ucfirst(strtolower($_POST["lastname"]))."','".$_POST["ddlsal"];
				$str.="','".$_POST["email"]."','".$_POST["officeno"]."','".$_POST["office"];
				$str.="',$j,$i,1,'".$_POST["ddlsm"]."')";
					echo $str;
					$cnt=ExecuteNonQuery($str);
					if($cnt==1)
					{
						redirect_to("viewusers.php");	
					}
					else
					{
						show_alert("Error in adding record..Please verify values");
					}
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
<title>Add User</title>
<link href="css/style.css" rel="stylesheet" type="text/css">

</head>

<body>

<div id="container">
            <div id="header">
				<?php include("header.html"); ?>
            </div>
<div id="wrapper">        
    <div id="main">
              <div id="leftpane">
             <?php  include("leftpane.php"); ?>
              </div>
      <div id="content">
			
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

            <div id="addbannerdiv">
            	<div id="info">
                	<p>This page create faculty logins.<span style="float:right;"><a href="viewusers.php"><img src="images/view.png" alt="view" width="50px"></a></p>
                </div>
           	<div id="contentdetail">
			<form method="post" action="adduser.php">
			<table style="width:40%">
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
                	<td>Select Department<label style="color:red;">*</label></td>
                    <td>:</td>
                    <td>Computer Science</td>
                </tr>
                <tr>
                	<td>User Name<label style="color:red;">*</label></td>
					<td>:</td>
                    <td><input type="text" name="username"></td>
                </tr>
                <tr>
                	<td>Password<label style="color:red;">*</label></td>
					<td>:</td>
                    <td><input type="password" name="password"></td>
                </tr>
				<tr>
                    	<td>Salutaiton<label style="color:red;">*</label></td>
                        <td>:</td>
                        <td><select name="ddlsal">
                        <option value="">Select</option>
                        <option value="Mr">Mr</option>
                        <option value="Ms">Miss</option>
                        <option value="Mrs">Mrs</option>
                        <option value="Dr">Dr</option>
                        </select>
                        </td>
                  </tr>
                <tr>
                	<td>First Name<label style="color:red;">*</label></td>
					<td>:</td>
                    <td><input type="text" name="firstname"></td>
                </tr>
                <tr>
                	<td>Last Name<label style="color:red;">*</label></td>
					<td>:</td>
                    <td><input type="text" name="lastname"></td>
                </tr>
                 
				  <tr>
                	<td>Email<label style="color:red;">*</label></td>
					<td>:</td>
                    <td><input type="text" name="email"></td>
                </tr>
                <tr>
                	<td>Office No</td>
					<td>:</td>
                    <td><input type="text" name="officeno"></td>
                </tr>
                <tr>
                	<td>Office<label style="color:red;">*</label></td>
					<td>:</td>
                    <td><input type="text" name="office"></td>
                </tr>
                <tr>
                	<td>Is Admin??</td>
					<td>:</td>
                    <td><input type="checkbox" name="cbxadmin"></td>
                </tr>
                <tr>
                	<td>Is Active??</td>
					<td>:</td>
                    <td><input type="checkbox" name="cbxactive" checked></td>
                </tr>
                <tr>
                	<td>Faculty Type</td>
					<td>:</td>
                    <td>
                   <select name="ddlsm">
                    <option value="S">Staff</option>
                    <option value="F" selected>Faculty</option>                   
                    <option value="A">Adjunct</option>                     
                    </select>
                    </td>
                </tr>
                <tr>
                	<td colspan="3" align="center"><input type="submit" value="Submit" name="Submit"></td>
                </tr>
            </table>            
            </form>
            </div>
            </div>
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