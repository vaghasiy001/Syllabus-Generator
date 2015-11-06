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
<title>View Subjects</title>
<link href="css/menu.css" rel="stylesheet" type="text/css">
<script src="js/jquery-latest.min.js"></script>
<script type='text/javascript' src='js/menu_jquery.js'></script>
<link href="css/style.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="containermain">
<div id="header">
	<div id="banner">
	<?php include('header.html'); ?>
    </div>
    <?php if($_SESSION["username"]!=null) {
  			include('menu.php');
        } ?>
</div>
<div id="wrapper">
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
	<div id="viewsubjects">
    		<?php 
				$per=GetSingleField('select permission from users where userid=$_POST["permission"]',"permission");
			?>
			<form method="post" action="viewsubjects.php">			
			<table border="1">
			<tr>
            <td align="center" style="padding-top:20px;border:none;">
          <b>Select Semester:</b>
            
             <select name="ddlsem">
        	<option value="">Select</option>
           <option value="Summer 2015">Summer 2015</option>
           <option value="Summer 2015">Fall 2015</option>
           <option value="Summer 2015">Spring 2016</option>
           <option value="Summer 2015">Summer 2016</option>
           <option value="Summer 2015">Fall 2016</option>
           
            </select>
            <b>Select Subject:</b>
            
                          <select>
                    <option>(GCIS 500)Applied Statistics </option>
                    <option>(GCIS 505)Object-Oriented Problem Solving in C++</option>
                    <option>(GCIS 506)Object-Oriented Programming in Java </option>
                    </select>
      
         
            <input type="button" value="Show">
            </td>
          
                </tr>
    
            </table>
            </form>
    </div>
</div>
<div id="footer">
	<?php include("footer.html"); ?>
</div>
</div>
</body>
</html>