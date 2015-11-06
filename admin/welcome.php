<?php
	ob_start();
	ini_set('display_errors','off');
	require_once("includes/functions.php");
	require_once("includes/session.php");
	include("includes/DataAccess.php");	
?>
<?php	if (!logged_in()) {
		redirect_to("index.php");
	}
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<link href="css/style.css" rel="stylesheet" type="text/css">
<title>Welcome in Content Management System</title>
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

                  <div id="addbannerdiv">
                  <br><br>
                </div>
           	<div id="contentdetail">
              <?php
					$snm=GetSingleField("select semname from semester where active=1","semname");
					$year=GetSingleField("select year from semester where active=1","year");					
				?>
					    Current Semester: <b><?php echo $snm." ".$year;?>
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