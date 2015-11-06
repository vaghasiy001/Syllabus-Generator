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
				$obj1=$_POST["objective1"];
				$obj2=$_POST["objective2"];
				$obj3=$_POST["objective3"];
				$obj4=$_POST["objective4"];				
				$obj5=$_POST["objective5"];				
				$cnt=CountRecords("select * from objectives where subid=".$_GET["subid"]);
				if($cnt==0)
				{
					$query="insert into objectives(subid,obj1,obj2,obj3,obj4,obj5)values(";
					$query.=$_GET["subid"].",'".$obj1."','".$obj2."','".$obj3."','".$obj4."','".$obj5."')";
					$cnt1=ExecuteNonQuery($query);
					echo $query;
					 if($cnt1==1)
					{
						redirect_to("viewsubjects.php?msg=Record inserted successfully.&flag=1");
					}
					else
					{
						redirect_to("viewsubjects.php?msg=Error in inserting record..Please try later.&flag=2");
					} 
				}
				else
				{
					$getid=GetSingleField("select objid from objectives where subid=".$_GET["subid"],"objid");
				
					$query="update objectives set obj1='".$obj1."',obj2='".$obj2."',obj3='".$obj3."',obj4='".$obj4."',";
					$query.="obj5='".$obj5."' where objid=".$getid;
					$cnt1=ExecuteNonQuery($query);
				//	echo $query;
					 if($cnt1==1)
					{
						redirect_to("viewsubjects.php?msg=Record updated successfully.&flag=1");
					}
					else
					{
						redirect_to("viewsubjects.php?msg=Error in updating record..Please try later.&flag=2");
					}
				}
}
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Add Objectives</title>
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
		<form method="post" action="addobjective.php?subid=<?php echo $_GET["subid"];?>">
		 <table class="subjectobj">
         	<tr>
            <td colspan="3">
            
			<?php if (!empty($message)) {echo "<p class=message>" . $message . "</p>";} ?>
			<?php if (!empty($errors)) 			{ 
			display_errors1($errors);
			}?>
            </td>
            </tr>
         	<tr>
            <td colspan="3" height="100px" width="100%"><img src="images/objectives.jpg" width="100%" height="100px"/>
            </td>
            </tr>
            <tr>
            	<?php 	
					$subid=$_GET["subid"];
					$data = ExecuteNonQuery('select subid,subname FROM subject where subid='.$subid) or die(mysql_error()); ?>      
	        		<?php while($row = mysql_fetch_assoc($data))
					{		
                    $sid=$row['subid'];
                    $snm=$row['subname']; 
					}
					?>
            	<td class="namecol">Subject Id</td>
                <td>:</td>
                <td><b><?php echo $sid; ?></b></td>
 			</tr>
           	<tr>
            	<td class="namecol">Subject Name</td>
                <td>:</td>
            	<td><b><?php echo $snm; ?></b></td>
            </tr>
            <?php 
			$data1 = ExecuteNonQuery('select * FROM objectives where subid='.$subid) or die(mysql_error()); 
	 while($info1 = mysql_fetch_assoc( $data1 )) 
	{
		$o1=$info1["obj1"];
		$o2=$info1["obj2"];
		$o3=$info1["obj3"];
		$o4=$info1["obj4"];
		$o5=$info1["obj5"];
	}
	?>
            <tr>
            	<td class="namecol">Objective 1</td>
                <td>:</td>
                <td><input type="text" name="objective1" value="<?php echo $o1; ?>" class="inputtext"></td>
            </tr>
            <tr>
            	<td class="namecol">Objective 2</td>
                <td>:</td>
                <td><input type="text" name="objective2" value="<?php echo $o2; ?>" class="inputtext"></td>
            </tr>
            <tr>
            	<td class="namecol">Objective 3</td>
                <td>:</td>
                <td><input type="text" name="objective3" value="<?php echo $o3; ?>" class="inputtext"></td>
            </tr>
            <tr>
            	<td class="namecol">Objective 4</td>
                <td>:</td>
                <td><input type="text" name="objective4" value="<?php echo $o4; ?>" class="inputtext"></td>
            </tr>
            <tr>
            	<td class="namecol">Objective 5</td>
                <td>:</td>
                <td><input type="text" name="objective5" value="<?php echo $o5; ?>" class="inputtext"></td>
            </tr>
     		<tr>
            	<td colspan="3" align="center"><input type="submit" name="btnsubmit" value="Submit"></td>
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