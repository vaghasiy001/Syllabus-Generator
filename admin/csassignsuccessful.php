<?php
	ini_set('display_errors','off');
	require_once("includes/session.php");
	if(!isset($_SESSION))
{
	session_start();
}

?>
<?php	if (!logged_in()) {
		redirect_to("index.php");
	}
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Uploaded Successful</title>
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
                	<p>Thank you</p>
                </div>
           	<div id="contentdetail">
            <p align="center">
            <img src="images/thankyou.gif" width="100px" height="100px" /><br>
			</p>
            <?php
			$nmarray=array();
			$nmarray=$_SESSION["nmlist"];
			count($nmarray);
			if(count($nmarray)!=0)
			{?>
				<div style="color:red;font-size:12px">Unsuccessful Inserted Course Numbers:<br><?php 
				for($i=0;$i<count($nmarray);$i++)
				{
					if($i%4==0)
						echo "<br>";
						
					if($i==count($nmarray)-1)
						echo "<i>".$nmarray[$i]."</i>";
					else
						echo "<i>".$nmarray[$i]."</i>".",";
				}
				?>
				</div>	
			<?php
            }
			?>
            <p align="center"><h2><b><i>
            
            <br>
            Thank you. Faculty subject assignment sheet uploaded successfully.</i></b></h2></p>
            
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