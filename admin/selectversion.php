<?php
	ob_start();
	require_once("includes/connect.php");
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
if(isset($_POST["submit"]))
{
	if($_POST["slctver"]!="")
	{
		
		if($_GET["flag"]=="1")
			{
				$sql="update fileinfo set active=0 where ftype='gmatrix'";	
				ExecuteNonQuery($sql);
				$sql="update fileinfo set active=1 where ftype='gmatrix' and version='".$_POST["slctver"]."'";
				ExecuteNonQuery($sql);
			}
		else
			{
			$sql="update fileinfo set active=0 where ftype='matrix'";	
			ExecuteNonQuery($sql);
			$sql="update fileinfo set active=1 where ftype='matrix' and version='".$_POST["slctver"]."'";
			ExecuteNonQuery($sql);
			}
			$msg="Version Activated";
			show_alert($msg);


	}
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Select Version</title>
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
                	<p>Select version</p>
                </div>
         	 <form name="myform" method="post" action="selectversion.php?flag=<?php echo $_GET['flag'];?>">
				<?php
				if($_GET["flag"]=="1")
					$data=ExecuteNonQuery("select * from fileinfo where ftype='gmatrix'");
				else
					$data=ExecuteNonQuery("select * from fileinfo where ftype='matrix'");
	
				?>
      		
     		<table>
            <tr>
            <td colspan="3">
            <?php
				if($_GET["flag"]=="1")
					echo "<b>Activate version for Graduate</b>";
				else
					echo "<b>Activate version for Under Graduate</b>";
			?>
            </td>
            <tr>
                <td>Activate Version </td>
                <td>:</td>
                <td>
                <select name="slctver">
                    <option value="">Select</option>
                    <?php while($info=mysqli_fetch_assoc($data)){ ?>
                    <option value="<?php echo $info["version"]; ?>" <?php if($info["active"]=="1") echo "selected";?>><?php echo $info["version"]; ?></option>
                    <?php }?>
                </select></td>
            </tr>
            <tr><td colspan="3" align="center">
            <input type="submit" name="submit" value="Activate Version">
            </td>
            </tr>
            </table>
            
        </form>
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