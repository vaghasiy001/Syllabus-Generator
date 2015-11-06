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
		$required_fields = array('objective1', 'objective2','objective3','objective4','objective5');
		$errors = array_merge($errors, check_required_fields($required_fields, $_POST));
		
		if ( empty($errors) ) {
				$obj1=$_POST["subjectcode"];
				$obj2=$_POST["subjectname"];
				$obj3=$_POST["month"];
				$obj4=$_POST["year"];				
			
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
<title>Add Objectives</title>
<link href="css/menu.css" rel="stylesheet" type="text/css">
<script src="js/jquery-latest.min.js"></script>
<script type='text/javascript' src='js/menu_jquery.js'></script>

<script type="text/javascript" src="js/jquery-1.4.1.min.js"></script>
<script>
function showUser(str)
{
if (str=="")
  {
  document.getElementById("txtHint").innerHTML="";
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
    }
  }
xmlhttp.open("GET","facultyobjectives.php?q="+str,true);
xmlhttp.send();
}
</script>


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
<?php if (!empty($message)) {echo "<p class=\"message\" style=color:red align=center>" . $message . "</p>";} ?>
			<?php if (!empty($errors)) 			{ 
			display_errors1($errors);
			}?>
<div id="addobjective">
		<form method="post" action="addobjective.php">
		 <table width="100%">
         	<tr>
            <td colspan="3" height="100px" width="100%"><img src="images/objectives.jpg" width="100%" height="100px"/>
            </td>
            </tr>
           <?php if($_SESSION["usrpermission"]==1) {?>
         <tr>
            	<td class="namecol">Select Faculty</td>
            	<td>:</td>
                   <?php
				$data = ExecuteNonQuery('select * FROM users') or die(mysql_error()); 
				// $info = mysql_fetch_array($data);
				  ?>
            		<td><select  onchange="showUser(this.value)" name="ddlfac" style="text-transform:uppercase;">
						<option value="">Select</option>					
					<?php 	
						 while($info = mysql_fetch_assoc( $data )) 
							 {?> 
                    <option value="<?php echo $info['userid']; ?>"><?php echo $info['firstname']." ".$info["lastname"]; ?></option>
                    <?php } ?>
                    </select></td>
            </tr>
            <?php } else {?>
          	
			<!-- If user is not administrator -->
			
			<?php }?>
            </table>
           <!--<div id="txtHint"></div>-->
           <table id="txtHint"></table>
        	 
            </form>
</div>
</div>
<div id="footer">
	<?php include("footer.html"); ?>
</div>
</div>
</body>
</html>