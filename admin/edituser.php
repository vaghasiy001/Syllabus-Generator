<?php
ini_set("display_errors","off");

include("includes/connect/php");
include("includes/DataAccess.php");
include("includes/form_functions.php");
include("includes/functions.php");
include("includes/session.php");
if (!logged_in()) {
		redirect_to("index.php");
	}

if( isset($_POST['Submit']) )
 {   
 		$errors = array();
		$required_fields = array('username','firstname','lastname','email','office');
	
		$errors = array_merge($errors, check_required_fields($required_fields, $_POST));			
		if (empty($errors) ) 
		{

					if($_POST["ddlsal"]=="")
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

		$str="update users set username='".$_POST["username"]."',firstname='".$_POST["firstname"];				
	      $str.="',lastname='".$_POST["lastname"]."',salutation='".$_POST["ddlsal"]."',email='".$_POST["email"]."',officeno='".$_POST["officeno"]."',office='".$_POST["office"]."',permission=$j,active=$i,sm='".$_POST["ddlsm"]."' where uid=".$_GET["id"];
				
				echo $str;
				ExecuteNonQuery($str);
			redirect_to("viewusers.php");	
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
<title>Edit User</title>
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
			<div id="addcatdiv">
            	<div id="info">
                	<p>This page edit user details<span style="float:right;"><a href="viewusers.php"><img src="images/view.png" alt="view" width="50px"></a></p>
                </div>
           	<div id="contentdetail">
			<form method="post" action="edituser.php?id=<?php echo $_GET["id"];?>">
            	 <?php 
				$data = ExecuteNonQuery('select * FROM users where uid='.$_GET["id"]);
							while($info = mysqli_fetch_assoc($data)) 
							{
						   		$username=$info["username"];
								$firstname=$info["firstname"];
								$lastname=$info["lastname"];
								$sal=$info["salutation"];
								$email=$info["email"];
								$officeno=$info["officeno"];
								$office=$info["office"];
								$isadmin=$info["permission"];
								$isactive=$info["active"];
							}?>

			<table style="width:60%;">
              <tr>
            <td colspan="3" >
            <?php if (!empty($message)) {echo "<p class=message>" . $message . "</p>";}
				else {echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";}
			 ?>
			<?php if (!empty($errors)) 	
					{ 
			display_errors1($errors); 
			} ?>
            <tr>
                	<td>Select Department<label style="color:red;">*</label></td>
                    <td>:</td>
                    <td>
      		Computer Science
                    </td>
                </tr>
                <tr>
                	<td>User Name<label style="color:red;">*</label></td>
					<td>:</td>
                    <td><input type="text" name="username" value="<?php echo $username; ?>" ></td>
                </tr>
        <!--        <tr>
                	<td>Password<label style="color:red;">*</label></td>
					<td>:</td>
                    <td><input type="password" name="password"></td>
                </tr>-->
				<tr>
                    	<td>Salutaiton<label style="color:red;">*</label></td>
                        <td>:</td>
                        <td><select name="ddlsal">
                        <option value="">Select</option>
                        <option value="Mr" <?php if($sal=="Mr") echo "Selected"; ?>>Mr</option>
                        <option value="Ms" <?php if($sal=="Miss") echo "Selected"; ?>>Miss</option>
                        <option value="Mrs" <?php if($sal=="Mrs") echo "Selected"; ?>>Mrs</option>
                        <option value="Dr" <?php if($sal=="Dr") echo "Selected"; ?>>Dr</option>
                        </select>
                        </td>
                  </tr>
                <tr>
                	<td>First Name<label style="color:red;">*</label></td>
					<td>:</td>
                    <td><input type="text" name="firstname" value="<?php echo $firstname;?>"></td>
                </tr>
                <tr>
                	<td>Last Name<label style="color:red;">*</label></td>
					<td>:</td>
                    <td><input type="text" name="lastname" value="<?php echo $lastname;?>"></td>
                </tr>
                 
				  <tr>
                	<td>Email<label style="color:red;">*</label></td>
					<td>:</td>
                    <td><input type="text" name="email" value="<?php echo $email;?>"></td>
                </tr>
                <tr>
                	<td>Office No</td>
					<td>:</td>
                    <td><input type="text" name="officeno" value="<?php echo $officeno;?>"></td>
                </tr>
             
                <tr>
                	<td>Office<label style="color:red;">*</label></td>
					<td>:</td>
                    <td><input type="text" name="office"  value="<?php echo $office;?>"></td>
                </tr>
                <tr>
                	<td>Is Admin??</td>
					<td>:</td>
                    <td><input type="checkbox" name="cbxadmin" <?php if($isadmin==1) echo "checked";?>></td>
                </tr>
                <tr>
                	<td>Is Active??</td>
					<td>:</td>
                    <td><input type="checkbox" name="cbxactive" <?php if($isactive==1) echo "checked";?>></td>
                </tr>
                   <tr>
                	<td>Type of User</td>
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