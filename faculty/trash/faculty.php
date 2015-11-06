<?php
ini_set('display_errors','off');

		require_once("includes/functions.php");
		require_once("includes/session.php");
?>
<? ob_start(); ?>
<?php	if (!logged_in()) {
		redirect_to("index.php");
	}
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>View Faculties</title>
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
<div id="facultycontent">
		<?php 		
					include("includes/DataAccess.php");
			//		$data=ExecuteNonQuery("select * from 
									
		 ?>		
         <form>
         		<table>
                	<tr>
                    	<td colspan="3"><img src="images/faculty.jpg" width="100%" height="100px"></td>
                    </tr>
                	<tr>
                    	<td>User Name</td>
                        <td>:</td>
                        <td><input type="text"/></td>
                    </tr>
                    <tr>
                    	<td>User Password</td>
                        <td>:</td>
                        <td><input type="password"/></td>
                    </tr>
                    <tr>
                    	<td>First Name</td>
                        <td>:</td>
                        <td><input type="text"/></td>
                    </tr>
                    <tr>
                    	<td>Last Name</td>
                        <td>:</td>
                        <td><input type="text"/></td>
                    </tr>
                    <tr>
                    	<td>Salutaiton</td>
                        <td>:</td>
                        <td><select>
                        <option>Mr</option>
                        <option>Miss</option>
                        <option>Mrs</option>
                        <option>Dr</option>
                        </select></td>
                    </tr>
                    <tr>
                    	<td>Email</td>
                        <td>:</td>
                        <td><input type="text"/></td>
                    </tr>
                    <tr>
                    	<td>Office</td>
                        <td>:</td>
                        <td><input type="text"/></td>
                    </tr>
                    <?php if($_SESSION["usrpermission"]==1){ ?>
                    <tr>
                    	<td>User</td>
                        <td>:</td>
                        <td>
                        	<select>
                            <option>Faculty</option>
                            <option>Administrator</option>
                            </select>
                        </td>
                    </tr>
                    <?php } ?>
                    <tr>
                    	<td>Is Active??</td>
                        <td>:</td>
                        <td><input type="checkbox"></td>
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