<?php
ini_set("display_errors","off");
include("includes/connect/php");
include("includes/DataAccess.php");
include("includes/form_functions.php");
include("includes/functions.php");
include("includes/session.php");
if (!logged_in()) {
		redirect_to("index.php");
	}

function getfilename($str) {
         $i = strrpos($str,".");
         if (!$i) { return ""; }
         $l = strlen($str) - $i;
         $name = substr($str,0,$i);
         return $name;
 }
function getdt()
{
	$date=date('mdy');	
	$time=date('His');
	$datetime=$date.$time;
	return $datetime;
}
 function getExtension($str) {
         $i = strrpos($str,".");
         if (!$i) { return ""; }
         $l = strlen($str) - $i;
         $ext = substr($str,$i+1,$l);
         return $ext;
 }
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<link href="css/style.css" rel="stylesheet" type="text/css">
<title>Image Details</title>
<script src="js/jquery-1.10.2.min.js"></script>
	<script src="js/lightbox-2.6.min.js"></script>

	<script>
	var _gaq = _gaq || [];
	_gaq.push(['_setAccount', 'UA-2196019-1']);
	_gaq.push(['_trackPageview']);

	(function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	})();
	</script>

	<script src="js/modernizr.custom.js"></script>

	<link rel="shortcut icon" href="img/demopage/favicon.ico">
	<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Karla:400,700">
<!--	<link rel="stylesheet" href="css/screen.css" media="screen"/>-->
	<link rel="stylesheet" href="css/lightbox.css" media="screen"/>
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
            	<div id="info">
                	<p>This page displays image details.<span style="float:right;"><a href="viewimages.php">
                    <img src="images/view.png" width="50px" alt="view">
                    </a></p>
                </div>

      
			<?php
					$data = ExecuteNonQuery('select * FROM images where imageid='.$_GET["id"]) or die(mysql_error());
							while($info = mysql_fetch_assoc($data)) 
							{
								$imgnm=$info["imagename"];
								$ititle=$info["imagetitle"];
								$dispord=$info["disporder"];
								$visible=$info["visible"];
								$addip=$info["addip"];
								$adddate=$info["adddate"];
								$modifyip=$info["modifyip"];
								$modifydt=$info["modifydate"];
								$fnm=getfilename($ititle);
								$ext=getExtension($ititle);
							}?>
		  <div id="detailscontent">
  
    			<table>
                	<tr>
                    	<td width="20%">Image Name</td>
                        <td>:</td>
                        <td><?php echo $imgnm; ?></td>
                    </tr>
                    <tr>
                    	<td>Image</td>
                        <td>:</td>
                        <td>
                        
                        <a href="../usrfiles/large/<?php echo $fnm."_fullsize.".$ext; ?>" data-lightbox="example-1"><img src="../usrfiles/thumb/<?php echo $fnm."_thumbnail.".$ext; ?>" alt="thumb-1" width="75" height="75"/></a>
                        <span><i><b>(Click on image to enlarge)</b></i></span>
                        </td>
                    </tr>
                    <tr>
                    	<td>Display Order</td>
                        <td>:</td>
                        <td><?php echo $dispord; ?></td>
                    </tr>
                    <tr>
                    	<td>Visible</td>
                        <td>:</td>
                        <td><?php $vb=($visible==0)?"No":"Yes"; echo $vb; ?></td>
                    </tr>
                    <tr>
                    	<td>Add Ip</td>
                        <td>:</td>
                        <td><?php echo $addip; ?></td>
                    </tr>
                    <tr>
                    	<td>Add Date</td>
                        <td>:</td>
                        <td><?php
						$adddate=date("m-d-Y", strtotime($adddate));	
						 echo $adddate; ?></td>
                    </tr>
                    <tr>
                    	<td>Modify Ip</td>
                        <td>:</td>
                        <td><?php
						if($modifyip=="")
							echo "-";
						else
						{
							echo $modifyip;
						}?></td>
                    </tr>
                    <tr>
                    	<td>Modify Date</td>
                        <td>:</td>
                        <td><?php
						if($modifydt=="")
							echo "-";
						else
						{
							$modifydt=date("m-d-Y", strtotime($modifydt));	
							echo $modifydt;
						} ?></td>
                    </tr>
                </table>	
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