<?php
		ob_start();
		ini_set('display_errors','off');
		require_once("includes/functions.php");
		require_once("includes/session.php");
		include("includes/DataAccess.php");  
?>
<?php
?>
<?php	if (!logged_in()) {
		redirect_to("index.php");
	}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>View Schedule & Syllabus</title>
<link href="css/menu.css" rel="stylesheet" type="text/css">
<script src="js/jquery-latest.min.js"></script>
<script type='text/javascript' src='js/menu_jquery.js'></script>
<link href="css/style.css" rel="stylesheet" type="text/css">
<link href="../stylesheets/bootstrap.min.css" rel="stylesheet" type="text/css">
</head>
<?php if(isset($_POST["btnclear"]))
{
	// Specify the target directory and add forward slash
	$dir = $_SESSION["username"]."/";
	// Open the directory
	$dirHandle = opendir($dir);
	// Loop over all of the files in the folder
	while ($file = readdir($dirHandle)) {
		// If $file is NOT a directory remove it
		if(!is_dir($file)) {
			unlink ("$dir"."$file"); // unlink() deletes the files
		}
	}
	// Close the directory
	closedir($dirHandle);
}
?>

<body>
<div id="containermain">
<div id="header">
<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
	<script src="assets/js/script.js"></script>
	<link href="assets/css/styles.css" rel="stylesheet"/>

	<div id="banner">
	<?php include('header.html'); ?>
    </div>
    <?php if($_SESSION["username"]!=null) {
  			include('menu.php');
        } ?>
</div>
<div id="wrapper">
<div align="right">
<form method="post">
<input type="submit" value="Clear All Files" class="btn btn-danger" name="btnclear" onClick="return confirm('Are you sure you want to remove all files from your folder?');">
</form>
</div>
	<div class="filemanager">

		<div class="search">
			<input type="search" placeholder="Find a file.." />
		</div>

		<div class="breadcrumbs"></div>

		<ul class="data"></ul>

		<div class="nothingfound">
			<div class="nofiles"></div>
			<span>No files here.</span>
		</div>

	</div>	            
			
</div>
<div id="footer">
	<?php include("footer.html"); ?>
</div>
</div>
</body>
</html>