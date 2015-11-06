<?php
	ob_start();
	ini_set('display_errors','off');
	require_once("includes/functions.php");
		require_once("includes/session.php");
		include("includes/DataAccess.php");  
	include_once("includes/form_functions.php");

	?>
<?php	if (!logged_in()) {
		redirect_to("index.php");
	}
?>
<?php
if(isset($_POST["btnsubmit"]))
{
		$errors = array();
		$required_fields = array('subjectcode', 'subjectname');
		$errors = array_merge($errors, check_required_fields($required_fields, $_POST));
		
		if ( empty($errors) ) {
				$scode=$_POST["subjectcode"];
				$sname=$_POST["subjectname"];
				$mon=$_POST["month"];
				$year=$_POST["year"];				
			
				if($_SESSION["usrpermission"]=="1")
					{
						$uid=$_POST["user"];
						$mid=$_GET["id"];
						$strupdate="update subject set subjectcode='".$scode."',subname='".$sname."',";
						$strupdate=$strupdate."userid=".$uid.",month='".$mon."',year=".$year." where subid=".$mid;
						$cnt=ExecuteNonQuery($strupdate);
						echo $strupdate;
						if($cnt==1)
							redirect_to("viewsubjects.php?msg=Record updated successfully.&flag=1");
						else
							redirect_to("viewsubjects.php?msg=Error in updating record..Please try later.&flag=2");
					}
				else
					{
						$strupdate="update subject set subjectcode='".$scode."',subname='".$sname."',";
						$strupdate=$strupdate."month='".$mon."',year=".$year." where subid=".$_GET["id"];
					//	echo $strupdate;
						$cnt=ExecuteNonQuery($strupdate);
						if($cnt==1)
							redirect_to("viewsubjects.php?msg=Record inserted successfully.&flag=1");
						else
							redirect_to("viewsubjects.php?msg=Error in inserting record..please try later.&flag=2");
					}
		}
		else
		{
			if (count($errors) == 1) {
				$message = "There was 1 error in the form.";
			} else {
				$message = "There were " . count($errors) . " errors in the form.";
			}
		}
}
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Add Subject</title>
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
<?php if(!empty($_GET["id"])){
				$data = ExecuteNonQuery('select * FROM subject where subid='.$_GET["id"]) or die(mysql_error()); 
				 while($info1 = mysql_fetch_assoc( $data ))
				 {
					$scd=$info1["subjectcode"];
					$snm=$info1["subname"];
					$ui=$info1["userid"];
					$mn=$info1["month"];
					$yr=$info1["year"];	 
				 }
		}
 ?>
<div id="subjectcontent">
		<form method="post" action="editsubject.php?id=<?php echo $_GET["id"];?>">
		 <table>
       		<tr>
            <td colspan="3">
            <?php if (!empty($message)) {echo "<p class=message>" . $message . "</p>";} ?>
			<?php if (!empty($errors)) 			{ 
			display_errors1($errors);
			}?>

            </td>
            </tr>
       		
         	<tr>
             	<td align="right" colspan="3"><a href="viewsubjects.php">Go Back</a></td>
            </tr>	
         	<tr>
            <td colspan="3" height="100px" width="100%"><img src="images/icon_book.png" width="100%" height="100px"/>
            </td>
            </tr>
            <tr>
            	<td>Subject Code</td>
                <td>:</td>
                <td><input type="text" name="subjectcode" value="<?php echo $scd; ?>"></td>
            </tr>
            <tr>
            	<td>Subject Name</td>
                <td>:</td>
                <td><input type="text" name="subjectname" value="<?php echo $snm; ?>"></td>
            </tr>
			 <?php if($_SESSION["usrpermission"]==1){ ?>
            <tr>
            	<td width="100px">Select Faculty</td>
            	<td>:</td>
                <?php 
				$data = ExecuteNonQuery('select userid,firstname,lastname FROM users') or die(mysql_error()); 
				 ?>
                <td style="text-transform:uppercase;"><select name="user">
						<option value="">Select</option>					
					<?php 	
						 while($info = mysql_fetch_assoc( $data )) 
							 {?> 
                    <option <?php if($info["userid"]==$ui){ echo "selected ";} echo "value=".$info["userid"]; ?>><?php echo $info['firstname']." ".$info['lastname']; ?></option>
                    <?php } ?>
                    </select></td>       	
                <?php ?>
            </tr>
            <?php } ?>
            <tr>
            	<td>Select Month</td>
                <td>:</td>
                <td>
                	<select name="month">
                    <option <?php if($mn == 'JAN')echo "selected="?>>JAN</option>
                    <option  <?php if($mn == 'FEB')echo "selected="?>>FEB</option>
                    <option  <?php if($mn == 'MAR')echo "selected="?>>MAR</option>
                    <option  <?php if($mn == 'APR')echo "selected="?>>APR</option>
                    <option  <?php if($mn == 'MAY')echo "selected="?>>MAY</option>
                    <option  <?php if($mn == 'JUN')echo "selected="?>>JUN</option>
                    <option  <?php if($mn == 'JUL')echo "selected="?>>JUL</option>
                    <option  <?php if($mn == 'AUG')echo "selected="?>>AUG</option>
                    <option  <?php if($mn == 'SEP')echo "selected="?>>SEP</option>
                    <option  <?php if($mn == 'OCT')echo "selected="?>>OCT</option>
                    <option  <?php if($mn == 'NOV')echo "selected="?>>NOV</option>
                    <option  <?php if($mn == 'DEC')echo "selected="?>>DEC</option>
                    </select>
                </td>
            </tr>
			<tr>
            	<?php $temp=date('Y', strtotime('+0 year')); ?>
            	<td>Select Year</td>
                <td>:</td>
                <td>
                	<select name="year">
                    	<option><?php echo $temp; ?></option>
                        <option><?php echo $temp+1; ?></option>
                        <option><?php echo $temp+2; ?></option>
                        <option><?php echo $temp+3; ?></option>
                        <option><?php echo $temp+4; ?> </option>
                    </select>
                </td>
            </tr>
            <tr>
            	<td colspan="3" align="center"><input type="submit" value="Submit" name="btnsubmit"></td>
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