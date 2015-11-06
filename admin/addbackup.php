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
if(isset($_POST["Submit"]))
{
			backup_tables();
			$old_date = date('l, F d y h:i:s');              // returns Saturday, January 30 10 02:06:34
			$old_date_timestamp = strtotime($old_date);
			$new_date = date('m-d-Y', $old_date_timestamp); 
			$str='db-backup_'.$new_date;	
			$qry="insert into dbbackup(backupname)values('".$str.".sql')";
			$cnt=ExecuteNonQuery($qry);
			if($cnt>0)
			{
				redirect_to("viewbackups.php");
			}
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Add Back Up</title>
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
                	<p>This page add the database backups.<span style="float:right;"><a href="viewbackups.php"><img src="images/view.png" alt="view" width="50px"></a></p>
                </div>
           	<div id="contentdetail">
			<form method="post" action="addbackup.php">
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
<!--
            	<tr>
                	<td colspan="3"><img src="images/banner-icon_1.png" height="100px" width="100%"></td>
    -->                
                </tr>
                <?php
					$old_date = date('l, F d y h:i:s');              // returns Saturday, January 30 10 02:06:34
					$old_date_timestamp = strtotime($old_date);
					$new_date = date('m-d-Y', $old_date_timestamp); 
					$str='db-backup_'.$new_date;	

				?>
                <tr>
                	<td>Backup Name</td>
					<td>:</td>
                    <td><input type="text" name="backupnm" value="<?php echo $str;?>" disabled></td>
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