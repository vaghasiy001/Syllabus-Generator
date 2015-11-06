<?php
	ob_start();
	ini_set('display_errors','on');
	ini_set('log_errors',1);
	require_once("includes/session.php");
	include_once("includes/form_functions.php");
	require_once("includes/functions.php");
	include_once("includes/DataAccess.php");
if(!isset($_SESSION))
{
	session_start();
}

?>
<?php	if (!logged_in()) {
		redirect_to("index.php");
	}
?>
<?php



if(isset($_POST["Submit"]))
{
			
	$filename=$_FILES["matrixfile"]["name"];
	//print $_SERVER[$filename];
	//$temp =  file_get_contents ($filename);
//	echo $temp;
	
		$_SESSION["csfilenm"]=$filename;
	//	echo $filename;
	
		if($filename!="")
		{
			$ext = explode(".", strtolower($filename));
			if($ext[1]!="csv")
			{
				show_alert("File extension should be .csv only.");							
			}
			else
			{
				$uploads_dir = getcwd().'/csfu';
				if(file_exists($uploads_dir."/".$filename))
					unlink($uploads_dir."/".$filename);
		
			   $tmp_name = $_FILES["matrixfile"]["tmp_name"];
				$name = $_FILES["matrixfile"]["name"];
				$moveResult =copy($tmp_name,"$uploads_dir/".$name);
				if ($moveResult == true) {
//					echo "File has been moved from " . $tmp_name . " to" . $name;
					redirect_to("insertcsassigns.php");
				} else {
					 echo "ERROR: File not moved correctly";
				}
				
			}
		}else
		{
			show_alert("Please select filename");
		}
		
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Upload Live Excel File</title>
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
                	<p>This page will allow you to upload assigned course section faculty excel file.<br><span style="color:red;">(Live excel .csv file only)</span></p>
                </div>
           	<div id="contentdetail">
			<form method="post" action="uploadcsassigns.php" enctype="multipart/form-data">
			<table>
                 <tr>
            <td>Upload file(.csv only)</td>
            <td>:</td>
            <td><input type="file" name="matrixfile"></td>
            </tr>
            <tr>
            <td colspan="3" align="center">
            <input type="submit" class="btn btn-success btn-large" value="Submit Schedule" name="Submit"></td>
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