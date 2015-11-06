<?php session_start(); ?>
<?php
ini_set('display_errors','off');

		require_once("includes/functions.php");
		require_once("includes/session.php");
		require_once("includes/connect.php");
		include("includes/DataAccess.php");  
		include("includes/form_functions.php");  		
?>

<? ob_start(); ?>
<?php	if (!logged_in()) {
		redirect_to("index.php");
	}
?>
<?php 
	if(isset($_POST["btnsubmit"]))
	{
		$subid=$_POST["ddlsub"];
		$subrid=$_POST["ddlsubr"];
			if(!empty($subid) || !empty($subrid))
			{ 
				if($subid!=$subrid)
				{
							$q1=CountRecords("select * from objectives where subid =".$subid);
							$flag=0;
							if($q1==0)
							{
								$flag=1;
							}
							else
							{
								$q2=ExecuteNonQuery("select * from objectives where subid=".$subid);
								while($inf1 = mysql_fetch_assoc($q2)) 
								{	 	
									if(empty($inf1["obj1"]) && empty($inf1["obj2"]) && empty($inf1["obj3"]) && empty($inf1["obj4"]) && empty($inf1["obj5"])) 
									{
										$flag=1;
									}
								}
							}
							
						if($flag==0)
						{
								$cntrec=CountRecords("select * from outcomes_Result where subid=$subid");
								if($cntrec==0)
								{	
									$query=ExecuteNonQuery("select * from outcomes_result where subid=$subid");
							//	echo $query;
									$strinsert="insert into outcomes_result(ocmdetailid,obj1,obj2,obj3,obj4,obj5,subid,userid)values";
									while($info2 = mysql_fetch_assoc( $query )) 
									{
											$strinsert.="(";
											$strinsert.=$info2["ocmdetailid"].",b'".$info2["obj1"]."',b'".$info2["obj2"]."',b'".$info2["obj3"]."',b";
											$strinsert.="'".$info2["obj4"]."',b'".$info2["obj5"]."',".$subrid.",".$_SESSION["userid"]."),";				
									}
									$strinsert=substr($strinsert, 0, -1);
									echo $strinsert;
									$cnt=ExecuteNonQuery($strinsert);
									if($cnt>0)
									{
											redirect_to("retrivesurvey.php?msg=Survey inserted successfully&flag=1");
									}
									else
									{
											redirect_to("retrivesurvey.php?msg=Error in inserting survey&flag=2");
									} 
								}
								else
								{
									$ddlsub=$_POST["ddlsub"];
									$ddlsubr=$_POST["ddlsubr"];
									$data = ExecuteNonQuery('select * from outcomes_main') or die(mysql_error());
										 while($info = mysql_fetch_assoc($data)) 
										{
											$data1 = ExecuteNonQuery('select * from outcomes_detail where ocmmainid='.$info["ocmmainid"]) or die(mysql_error());
											 while($info1 = mysql_fetch_assoc($data1)) 
											{
										 $ocmresid=GetSingleField("select * from outcomes_result where subid=".$ddlsub." and ocmdetailid=".$info1["ocmdetailid"],"ocmresid");
										$ocmresid1=GetSingleField("select * from outcomes_result where subid=".$ddlsubr." and ocmdetailid=".$info1["ocmdetailid"],"ocmresid");	
											$data2=ExecuteNonQuery("select * from outcomes_result where ocmresid=$ocmresid");
											while($info2 = mysql_fetch_assoc($data2)) 
											{
								//				echo $info2["obj1"]." ".$info2["obj2"]." ".$info2["obj3"]." ".$info2["obj4"]." ".$info2["obj5"]."<br>";
												$res1=$info2["obj1"];
												$res2=$info2["obj2"];
												$res3=$info2["obj3"];
												$res4=$info2["obj4"];
												$res5=$info2["obj5"];
													$query="update outcomes_result set obj1=b'".$res1."',obj2=b'".$res2."',obj3=b'".$res3; 
													$query=$query."',obj4=b'".$res4."',obj5=b'".$res5."' where ocmresid=".$ocmresid1;
													$cnt=ExecuteNonQuery($query);
											}
						
											}
										}
						//						$query=substr($query, 0, -1);
									//			echo $query;
												if($cnt>0)
												{
														redirect_to("retrivesurvey.php?msg=Survey updated successfully?flag=1");
												}
												else
												{
														redirect_to("retrivesurvey.php?msg=Error in updating survey?flag=2");
												}			
									
								}
						}
						else
						{
								$name=GetSingleField("select subname from subject where subid=".$subid,"subname");
								show_alert("Please enter objective of '".$name."\'");
						}
				}
				else
				{
					show_alert("Subject selection same on both side");					
				}
			}
			else
			{
				show_alert("please select subject from both list");
			}
	}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Course Assesment Survey</title>
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
xmlhttp.open("GET","surveydetail.php?q="+str,true);
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
	<form action="retrivesurvey.php" method="post">

		<div id="subseldiv">
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
          	<td>Select Course</td>
            <td>:</td>	
                <?php
				$data = ExecuteNonQuery('select * FROM subject where userid='. mysql_real_escape_string($_SESSION["userid"])) or die(mysql_error()); 
				// $info = mysql_fetch_array($data);
				  ?>
            		<td><select  onchange="showUser(this.value)" name="ddlsub">
						<option value="">Select</option>					
					<?php 	
						 while($info = mysql_fetch_assoc( $data )) 
							 {?> 
                    <option value="<?php echo $info['subid']; ?>"><?php echo $info['subname']."(".$info['month']." ".$info['year'].")"; ?></option>
                    <?php } ?>
                    </select></td>
          	    <td><b>Retrives into</b></td>
                <td>
                    <?php
				$data1 = ExecuteNonQuery('select * FROM subject where userid='. mysql_real_escape_string($_SESSION["userid"])) or die(mysql_error()); 
				// $info = mysql_fetch_array($data);
				  ?>
                	<td><select name="ddlsubr">
						<option value="">Select</option>					
					<?php 	
						 while($info1 = mysql_fetch_assoc( $data1 )) 
							 {?> 
                    <option value="<?php echo $info1['subid']; ?>"><?php echo $info1['subname']."(".$info1['month']." ".$info1['year'].")"; ?></option>
                    <?php } ?>
                    </select></td>
                </td>
                <td>
                	<input type="submit" value="Retrive" name="btnsubmit">
                </td>
                </tr>
                
            </table>
            </div>
            

    <div id="txtHint"><b>Course objectives will be listed here</b></div>
   </form>
   </div>
  
</div>
<div id="footer">
	<?php include("footer.html"); ?>
</div>
</div>
</body>
</html>