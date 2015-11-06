<?php 
ini_set('display_errors','off');

session_start(); ?>
<?php
		require_once("includes/functions.php");
		require_once("includes/session.php");
		require_once("includes/connect.php");
		include("includes/DataAccess.php");  
		
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
<title>Course Assesment Survery</title>
<link href="css/menu.css" rel="stylesheet" type="text/css">
<script src="js/jquery-latest.min.js"></script>
<script type='text/javascript' src='js/menu_jquery.js'></script>
<link href="css/style.css" rel="stylesheet" type="text/css">
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
xmlhttp.open("GET","temp.php?q="+str,true);
xmlhttp.send();
}
</script>
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
<div id="surveycontent">
		<form>
		<div id="subseldiv">
        	<table>
            	<tr>
          	<td>Select Course</td>
            <td>:</td>	
                <?php
				$data = ExecuteNonQuery('select * FROM subject where userid='. mysql_real_escape_string($_SESSION["userid"])) or die(mysql_error()); 
				// $info = mysql_fetch_array($data);
				  ?>
            		<td><select  onchange="showUser(this.value)">
						<option value="">Select</option>					
					<?php 	
						 while($info = mysql_fetch_assoc( $data )) 
							 {?> 
                    <option value="<?php echo $info['subid']; ?>"><?php echo $info['subname']; ?></option>
                    <?php } ?>
                    </select></td>
          	    </tr>
            </table>
            </div>
            
            </form>
    <div id="txtHint"><b>Person info will be listed here.</b></div>
   </div>
</div>
<div id="footer">
	<?php include("footer.html"); ?>
</div>
</div>
</body>
</html>