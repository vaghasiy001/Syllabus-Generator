<?php

	ob_start();
	ini_set('display_errors','off');
	require_once("includes/session.php");
	include_once("includes/form_functions.php");
	require_once("includes/functions.php");
	include_once("includes/DataAccess.php");
?>
<?php	if (!logged_in()) {
		redirect_to("index.php");
	}
?>
<?php
if(isset($_POST["Submit"]))
{
	$selvalue=$_POST['select1'];
	if ($selvalue){
	 foreach ($selvalue as $value){echo 'You selected ',$value,'<br />';}
	}
/*	$selvalue1=$_POST['select2'];
	echo $selvalue1;
	if ($selvalue1){
	 foreach ($selvalue1 as $value){echo 'You selected ',$value,'<br />';}
	}*/
}
?>
<?php
if(isset($_POST["ddlfac"]))
{
}
?>
<?php
	if(!isset($_SESSION["ddlsem1"]))
	{
		redirect_to("index.php");
	}

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Assign Faculty Subjects</title>
<link href="css/style.css" rel="stylesheet" type="text/css">
<script src="js/jquery-2.1.3.min.js"></script>

<script>
/*
$(document).ready(function(e) {
    $("#btnadd").click(function(){
		if($("#select1").val()!=null)
		{
			var count=$("#select1 :selected").length;
				var hexvalues = [];
				var labelvalues = [];
				
				$('#select1 :selected').each(function(i, selectedElement) {
				 hexvalues[i] = $(selectedElement).val();
				 labelvalues[i] = $(selectedElement).text();
				$("#select2").append("<option>"+labelvalues[i]+"</option>");
				$("#select1 option:selected").remove();
				});
		}
	});
	$("#btnremove").click(function(){
		if($("#select2").val()!=null)
		{	
			var count=$("#select2 :selected").length;
				var hexvalues = [];
				var labelvalues = [];
				
				$('#select2 :selected').each(function(i, selectedElement) {
				 hexvalues[i] = $(selectedElement).val();
				 labelvalues[i] = $(selectedElement).text();
				$("#select1").append("<option>"+labelvalues[i]+"</option>");
				$("#select2 option:selected").remove();
				});
		}
	});
});*/
</script>

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
			
<?php if($_GET["flag"]==1) { ?>
                <div id="divsuccess">
                    <img src="images/success.jpg" width="25px" height="25px"><?php echo $_GET["msg"]; ?> 
                </div>
                <?php } ?>
                <?php if($_GET["flag"]==2) { ?>
                <div id="diverror">
                    <img src="images/error.png" width="25px" height="25px"><?php echo $_GET["msg"]; ?> 
                </div>
                <?php }?>

            <div id="addbannerdiv">
            	<div id="info">
                	<p>This page will assign subjects to faculties.<span style="float:right;"><a href="#"><img src="images/view.png" alt="view" width="50px"></a></p>
                </div>
           	<div id="contentdetail">
			<form method="post" action="assignsection.php" name="form1">
			<table>
                  <tr>
            <td colspan="3" >
            <?php if (!empty($message)) {echo "<p class=message>" . $message . "</p>";}
				else {echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";}
			 ?>
			<?php if (!empty($errors)) 	
					{ 
			display_errors1($errors); 
			} ?>


            </td>
            </tr>
               
                <tr>
                	<td  width="60px">Select Faculty</td>
					<td  width="10px">:</td>
                    <td>
        <?php 
		$dept=GetSingleField("select deptid from users where uid=".$_SESSION["a_userid"],"deptid");
		$data = ExecuteNonQuery('select uid,firstname,lastname from users where deptid='.$dept);
		
		?>
           <select name="ddlfac" onChange="document.form1.submit();">
          <?php
		  		while($info = mysqli_fetch_assoc($data)) 
		{?>
				<option value="<?php echo $info["uid"]; ?>" <?php if($_POST["ddlfac"]==$info["uid"]) echo "selected";?>><?php echo $info["firstname"]." ".$info["lastname"];?></option>		
		<?php
			}
			?>
                    </select>
                    </td>
                </tr>
        <?php
		$cnt=CountRecords("select sections from course_section where semid=".$_SESSION["ddlsem1"]);
		if($cnt==0)
		{
			$data1 = ExecuteNonQuery('select csid,sections from course_section');
		}

		?>
                <tr>
   				<td align="right">
               	<br>
                Sections
                <select size="10" id="select1" name="select1[]" multiple>
             <?php
		  		while($info1 = mysqli_fetch_assoc($data1)) 
		{?>
				<option value="<?php echo $info1["csid"];?>"><?php echo $info1["sections"];?></option>		
		<?php
			}
			?>   
                </select>
                </td>
                <td align="center">
                    <input type="button" id="btnadd" value=">"><br>
                    <input type="button" id="btnremove" value="<">
                </td>
                <td>
                <br>
                Selected Courses<br>
                <select size="10" id="select2" name="select2[]" multiple>
                
                </select>
                </td>

                </tr>
                <tr>
                	<td>&nbsp;</td>
                    <td><input type="submit" value="Submit" name="Submit"></td>
                    <td>&nbsp;</td>
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