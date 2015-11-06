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
	if(!isset($_SESSION))
{
	session_start();
}

?>
<?php
if(isset($_POST["Submit"]))
{
	if(isset($_SESSION["ver"]))
	{
		if(isset($_POST["cbxactive"]))
		{
			if($_GET["flag"]=="0")
			{
				$cnt=CountRecords("select * from fileinfo where ftype='matrix'");
				if($cnt!=0)
					ExecuteNonQuery("update fileinfo set active=0 where ftype='matrix'");

				$sql="update fileinfo set active=1 where version='".$_SESSION["ver"]."' and ftype='matrix'";
				ExecuteNonQuery($sql);
				redirect_to("welcome.php");
			}
			else if($_GET["flag"]=="1")
			{
				$cnt=CountRecords("select * from fileinfo where ftype='gmatrix'");
				if($cnt!=0)
					ExecuteNonQuery("update fileinfo set active=0 where ftype='gmatrix'");
	
				$sql="update fileinfo set active=1 where version='".$_SESSION["ver"]."' and ftype='gmatrix'";
				ExecuteNonQuery($sql);
				redirect_to("welcome.php");
				
			}
		}
	}
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Thank You</title>
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
		    <div id="addbannerdiv">
            	<div id="info">
                	<p>Version imported successfully.</p>
                </div>
           	<div id="contentdetail">
			<form method="post" action="thankyou.php?flag=<?php echo $_GET["flag"];?>">
			    <p align="center">
            <img src="images/thankyou.gif" width="100px" height="100px" /><br>
			</p>
        
            <table>
              <tr>
              <td><input type="checkbox" name="cbxactive"></td>
			  <td>:</td>
              <td><h3><b><i>Click here to activate version</i></b></h3></td>	
              </tr>
                <tr>
                	<td colspan="3"><input type="submit" value="Submit" name="Submit"></td>
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