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
if(!isset($_SESSION["ddlsem1"]))
{
	redirect_to("welcome.php");
}
if(isset($_POST["Submit"]))
{
	$selvalue=$_POST['csec'];
	$uid=$_POST["ddlfac"];
	if(empty($selvalue[0])==1)
	{
			$sid=$_SESSION["ddlsem1"];
			$deactqry="update section set active=0 where uid=$uid and semid=$sid";
			ExecuteNonQuery($deactqry);
	}
	else
	{
			$sid=$_SESSION["ddlsem1"];
			$deactqry="update section set active=0 where uid=$uid and semid=$sid";
			ExecuteNonQuery($deactqry);
			 foreach ($selvalue as $value)
			 {
				 $cnm=GetSingleField("select coursename from courses where cid=(select cid from course_section where csid=(select csid from course_section where csid=".$value."))","coursename");
				$cntrec=CountRecords("select csid from section where uid=$uid and semid=$sid and csid=$value");
			//	echo "select csid from section where uid=$uid and semid=$sid and csid='$value'<br>";
				if($cntrec==0)
				{
					$sql="insert into section(csid,name,uid,semid,active)values($value,'".$cnm."',$uid,$sid,1)";
					//echo $sql;
					$cnt=ExecuteNonQuery($sql);
				}
				else
				{
					$sql="update section set active=1 where csid=$value and uid=$uid and semid=$sid";
				//	echo $sql;
					ExecuteNonQuery($sql);
				}
			//-----------------Default course assessment methods----------------------------
				$sql="select camname from cams where csid=$value and uid=$uid and semid=$sid";
				$cnt=CountRecords($sql);
				if($cnt==0)
				{
					$sql1="insert into cams(camname,camdetails,csid,uid,semid)values('Assignments:','Students will periodically be assigned homework assignments related to software design principles, patterns and architecture, Due dates will be announced in class.',$value,$uid,$sid),
					('Examinations/Quizzes:','Students will periodically take in-class examinations covering knowledge and application of software design principles,design patterns,architecture and related topics from the assigned readings,course notes,and homework.',$value,$uid,$sid),
					('Project:','The project shall be staffed by individual student who will indentify design patterns/architecture present in open source software. Recommendation for areas of improvement using design patterns with rationale and implementation can be parts of the project.',$value,$uid,$sid)";			
					ExecuteNonQuery($sql1);
				}
				//--------------------------------------------------------------------------
		//	redirect_to("welcome.php");
			}
	}
}
?>
<?php
if(isset($_POST["ddlfac"]))
{
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Assign Subject to Faculties</title>
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
                	<p>This page will assign subjects to faculties.<br>
                    <span style="color:red;">Always use Ctrl key to select multiple courses</span>
                    </p>
                </div>
           	<div id="contentdetail">
			<form method="post" action="assignsection.php" name="form1">
			<table style="width:100%">
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
			$data = ExecuteNonQuery('select uid,firstname,lastname from users where deptid='.$dept." order by lastname");
		?>
           <select name="ddlfac" onChange="document.form1.submit();">
          	<option value="">Select</option>
		  <?php
		  		while($info = mysqli_fetch_assoc($data)) 
		{?>
				<option value="<?php echo $info["uid"]; ?>" <?php if($_POST["ddlfac"]==$info["uid"]) echo "selected";?>><?php echo $info["firstname"]." ".$info["lastname"];?></option>		
		<?php
			}
			?>
                    </select>                    <input type="submit" value="Assign Course Section" name="Submit">
                    </td>
                </tr>
        <?php
		$csarray=array();
		if(isset($_POST["ddlfac"]) && $_POST["ddlfac"]!="")
		{
			$sql="select csid from section where semid=".$_SESSION["ddlsem1"]." and uid=".$_POST["ddlfac"];
			$cnt=CountRecords($sql);
		
			if($cnt>0)
			{
				$data2=ExecuteNonQuery("select csid from section where semid=".$_SESSION["ddlsem1"]." and active=1 and uid=".$_POST["ddlfac"]);
				$i=0;
				while($info2 = mysqli_fetch_assoc($data2)) 
				{
					$csarray[$i]=$info2["csid"];
					$i++;
				}
			}
		}
		//$cnt=CountRecords("select sections from course_section where semid=".$_SESSION["ddlsem1"]);
		//if($cnt==0)
		//{
			$data1 = ExecuteNonQuery('select c.coursename,cs.csid,cs.sections from course_section cs,courses c where c.cid=cs.cid and cs.active=1');
	//	}
		?>
                <tr>
   				<td>
                Sections</td>
                <td>:</td>
                <td>
                <select multiple size="25" name="csec[]"<?php if(!isset($_POST["ddlfac"])) echo "disabled"; ?>>
            		<option value="">Select</option>
				<?php
                    while($info1 = mysqli_fetch_assoc($data1)) 
                    {?>	 
					   <option value="<?php echo $info1["csid"];?>" <?php if(in_array($info1["csid"],$csarray)) echo "selected='selected'";?>><?php echo "(".$info1["sections"].")".$info1["coursename"];?></option>		
                    <?php
					}
                ?>   
                </select>
                </td>
   				 
            
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