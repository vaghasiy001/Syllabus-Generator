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
						$strinsert="insert into subject(subjectcode,subname,userid,month,year)values('";
						$strinsert.=$scode."','".$sname."',".$uid.",'".$mon."',".$year.")";
						$cnt=ExecuteNonQuery($strinsert);
						if($cnt==1)
							redirect_to("viewsubjects.php?msg=Record inserted successfully.&flag=1");
						else
							redirect_to("viewsubjects.php?msg=Error in inserting record..Please try later.&flag=2");
					}
				else
					{
						$strinsert="insert into subject(subjectcode,subname,month,year)values('";
						$strinsert.=$scode."','".$sname."','".$mon."',".$year.")";
						$cnt=ExecuteNonQuery($strinsert);
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
<div id="subjectcontent">
		<form method="post" action="addsubject.php">
		 <table>
         	<tr>
             	<td align="right" colspan="3"><a href="viewsubjects.php">Go Back</a></td>
            </tr>
         	<tr>
            <td colspan="3" >
			<?php if (!empty($message)) {echo "<p class=message>" . $message . "</p>";}
					else { echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";}
			 ?>
			<?php if (!empty($errors)) 			{ 
			display_errors1($errors);
			}?>
			</td>
            </tr>
         	<tr>
            <td colspan="3" height="100px" width="100%"><img src="images/icon_book.png" width="100%" height="100px"/>
            </td>
            </tr>
            <tr>
            	<td>Subject Code</td>
                <td>:</td>
                <td><input type="text" name="subjectcode" value="<?php echo $_POST["subjectcode"]; ?>"></td>
            </tr>
            <tr>
            	<td>Subject Name</td>
                <td>:</td>
                <td><input type="text" name="subjectname" value="<?php echo $_POST["subjectname"]; ?>"></td>
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
                    <option value="<?php echo $info['userid']; ?>"><?php echo $info['firstname']." ".$info['lastname']; ?></option>
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
                    <option>JAN</option>
                    <option>FEB</option>
                    <option>MAR</option>
                    <option>APR</option>
                    <option>MAY</option>
                    <option>JUN</option>
                    <option>JUL</option>
                    <option>AUG</option>
                    <option>SEP</option>
                    <option>OCT</option>
                    <option>NOV</option>
                    <option>DEC</option>
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