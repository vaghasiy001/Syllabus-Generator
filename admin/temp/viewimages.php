<?php
ini_set("display_errors","off");
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
<title>View Images</title>
<link href="css/style.css" rel="stylesheet" type="text/css">

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
			<div id="viewbannerdiv">
            	<div id="info">
                	<p>This page displays banner uploaded into website.<span style="float:right;"><a href="addimage.php"><img src="images/Add.png" alt="Add" width="50px"></a></span></p>
                </div>
              	<div id="viewbannercontent">
			<table>
            <!--	<tr>
                	<td colspan="3"><img src="images/viewbanner.png" height="100px" width="100%"></td>                   
                </tr>
                --><tr>
                	<th>Image Name</th>
                    <th>Image</th>
					<th>Display Order</th>
                    <th>Visible</th>
                    <th>&nbsp;</th>
                </tr>
                	<?php
							$data = ExecuteNonQuery('select * FROM images order by disporder') or die(mysql_error());
							
							while($info = mysql_fetch_assoc($data)) 
							{
									$tit=getfilename($info["imagetitle"]);
						$ext=getExtension($info["imagetitle"]);
						
								?>
					
               		 <tr>
               		<td><a href="viewimagedetails.php?id=<?php echo $info["imageid"];?>"><?php echo $info["imagename"];?></a></td>
                        <td>
                           <a href="../usrfiles/large/<?php echo $tit."_fullsize.".$ext; ?>" data-lightbox="example-1"><img src="../usrfiles/thumb/<?php echo $tit."_thumbnail.".$ext; ?>" alt="thumb-1" width="75" height="75"/>
                     
                        </td>
                        <td><?php echo $info["disporder"]; ?></td>
                        <td><input type="checkbox" disabled <?php if($info["visible"]==1) echo "checked";?>></td>
						<td><a href="editimage.php?id=<?php echo $info["imageid"];?>"><img src="images/edit.png" height="60px" width="60px"></a></td>
                </tr>
                	<?php	}
					?>
                
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