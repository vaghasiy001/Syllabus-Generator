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
		if($_POST["ddlsem1"]!="")
		{
			$_SESSION["ddlcssem"]=$_POST["ddlsem1"];
			redirect_to("uploadcsassigns.php");
		}
		else
		{
			show_alert("Please select semester");
		}
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Select Semester</title>
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
                	<p>This page allows you to upload faculty schedule and details like class timing,room no etc.</p>
                </div>
           
            <div id="contentdetail">
			<form method="post" action="csselectsem.php">
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
                	<td>Select Semester</td>
					<td>:</td>
                    <td>
					<?php 
                    $data1 = ExecuteNonQuery('select semid,semname,year,active from semester order by sortorder');
                    ?>
                       <select name="ddlsem1">
                       <option value="">Select</option>
                      <?php
                            while($info1 = mysqli_fetch_assoc($data1)) 
                   		 {?>
                            <option value="<?php echo $info1["semid"]; ?>" <?php if($info1["active"]=="1") echo "selected";?>><?php echo $info1["semname"]." ".$info1["year"];?></option>		
                    	<?php
                        }
                        ?>
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