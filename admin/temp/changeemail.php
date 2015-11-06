<?php
	ob_start();
	ini_set('display_errors','off');
	require_once("includes/functions.php");
	require_once("includes/session.php");
	include("includes/DataAccess.php");  
	include_once("includes/form_functions.php");

if (!logged_in()) {
		redirect_to("index.php");
	}
if( isset($_POST['Submit']) )
 {
			$errors = array();
			$required_fields = array('nemail');
			$errors = array_merge($errors, check_required_fields($required_fields, $_POST));			
		if (empty($errors) ) 
		{
		
				$c_date = date ("Y-m-d");
				$qry="update users set email='".$_POST["nemail"]."',modifyip='".getip()."',modifydate='".$c_date."' where id=".$_SESSION["userid"];   	 
				$cnt=ExecuteNonQuery($qry);
				if($cnt==1)
						redirect_to("changeemail.php?msg=Record updated successfully.&flag=1");
				else
						redirect_to("changeemail.php?msg=Error in updating record..Please try later.&flag=2");
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
<title>Change Email</title>
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
                    <img src="images/success.png" width="25px" height="25px"><?php echo $_GET["msg"]; ?> 
                </div>
                <?php } ?>
                <?php if($_GET["flag"]==2) { ?>
                <div id="diverror">
                    <img src="images/cross.png" width="25px" height="25px"><?php echo $_GET["msg"]; ?>
                </div>
                <?php }?>
        <div id="info">
            <p>This page will change the admin email.</p>
                </div>
 					<?php 
				$data = ExecuteNonQuery('select * FROM users where id='.$_SESSION["userid"]) or die(mysql_error());
							while($info = mysql_fetch_assoc($data)) 
							{
						   		$email=$info["email"];	
						    }?>
			<form method="post" action="changeemail.php">
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
                	<td colspan="3"><img src="images/email-concept.jpg" height="70px" width="100%"></td>
                </tr>

            	<tr>
                	<td>Old Email</td>
                    <td>:</td>
                    <td><?php echo $email;?></td>
                </tr>
                <tr>
                	<td>New Email</td>
                    <td>:</td>
                    <td><input type="text" name="nemail"></td>
                </tr>
                <tr>
                	<td colspan="3"><input type="submit" value="Submit" name="Submit"></td>
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