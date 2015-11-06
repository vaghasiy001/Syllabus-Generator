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
		$required_fields = array('txtyear');
		$errors = array_merge($errors, check_required_fields($required_fields, $_POST));			
		if (empty($errors) ) 
		{
					$i=0;
					if(isset($_POST["cbxactive"]))
					{
						ExecuteNonQuery("update semester set active=0");
						$i=1;
					}
				$crec=CountRecords("select * from semester");		
				$str="insert into semester(semname,year,active,sortorder)";
				$str.="values('".$_POST["ddlsem"]."','".$_POST["txtyear"]."',$i,".++$crec.")";
//					echo $str;
					$cnt=ExecuteNonQuery($str);
					if($cnt==1)
					{
						redirect_to("viewsemesters.php");	
					}
					else
					{
						show_alert("Error in adding record..Please verify values");
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
<title>Add backup</title>
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
                	<p>This page adds new semester.<span style="float:right;"><a href="viewsemesters.php"><img src="images/view.png" alt="view" width="50px"></a></p>
                </div>
           	<div id="contentdetail">
			<form method="post" action="addsemester.php">
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
                	<td>Sem Name</td>
					<td>:</td>
                    <td>
                    <select name="ddlsem">
                    <option>Spring</option>
                    <option>Summer</option>
                    <option>Fall</option>                    
                    </select>
                    </td>
                </tr>
                <tr>
                	<td>Year<label style="color:red;">*</label></td>
					<td>:</td>
                    <td><input type="text" name="txtyear" size="4" max="4"></td>
                </tr>
			    <tr>
                	<td>Is Active??</td>
					<td>:</td>
                    <td><input type="checkbox" name="cbxactive"></td>
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