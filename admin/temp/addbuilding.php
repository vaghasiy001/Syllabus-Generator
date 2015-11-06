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
	$required_fields = array('buildingname');
	$errors = array_merge($errors, check_required_fields($required_fields, $_POST));	
	if (empty($errors) )
	 {
		 $i=0;
		 if(isset($_POST["cbxactive"]))
		 {
			 $i=1;
		 }
		$str="insert into buildings(bname,active)values('".$_POST["buildingname"]."',$i)";
		$cnt=ExecuteNonQuery($str);
		if($cnt==1)
		{
			redirect_to("viewbuildings.php");	
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
<title>Add Department</title>
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
                	<p>This page add buildings.<span style="float:right;"><a href="viewbuildings.php"><img src="images/view.png" alt="view" width="50px"></a></p>
                </div>
           	<div id="contentdetail">
			<form method="post" action="addbuilding.php">
			<table style="width:60%">
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
                	<td>Active??</td>
                  	<td>:</td>
                    <td><input type="checkbox" name="cbxactive"></td>
                </tr>
            	<tr>
                	<td>Building Name</td>
                  	<td>:</td>
                    <td><input type="text" name="buildingname">  [Ex: Zurn,Waldron etc]</td>
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