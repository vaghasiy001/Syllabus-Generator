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
		if(isset($_POST["rdbdegree"]))
		{
		//	$_SESSION["ddlsem"]=$_POST["ddlsem"];
			$_SESSION["rdbdeg"]=$_POST["rdbdegree"];
			if($_POST["rdbdegree"]=="1")
				redirect_to("selectversion.php?flag=1");
			else
				redirect_to("selectversion.php?flag=0");
		}
		else
		{
			show_alert("Please select degree");
		}
//	}
//	else
//	{
//		show_alert("Please select Degree");
//	}
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Select Degree</title>
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
                	<p><b>Select Degree-></b>Select Version</p>
                </div>
           	<div id="contentdetail">
			<form method="post">
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
                	<td>Degree??</td>
                  	<td>:</td>
                    <td><input type="radio" name="rdbdegree" value="0">Under Graduate<input type="radio" name="rdbdegree" value="1">Graduate</td>
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