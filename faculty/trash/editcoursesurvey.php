<?php session_start(); ?>
<?php
ini_set('display_errors','off');

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
<?php 
	if(isset($_POST["btnsubmit"]))
	{
		$subid=$_POST["subid"];
		$cntrec=CountRecords("select * from outcomes_Result where subid=".$subid);
		if($cntrec==0)
		{
			 $query="insert into outcomes_result (ocmdetailid,obj1,obj2,obj3,obj4,obj5,subid,userid)values";
	   		 $data = ExecuteNonQuery('select * from outcomes_main') or die(mysql_error());
		     while($info = mysql_fetch_assoc($data)) 
			{
		$data1 = ExecuteNonQuery('select * from outcomes_detail where ocmmainid='.$info["ocmmainid"]) or die(mysql_error());
			 while($info1 = mysql_fetch_assoc($data1)) 
				    {
							$myarr=array();
							$temp="mycb".$info1["ocmdetailid"];
							$query=$query."(".$info1["ocmdetailid"].",";
							
							for ($i=0; $i<5; $i++)
							{
								$myarr[$i]=$_POST[$temp][$i];
							}
	//						echo "<table><tr>";
							$res1=empty($myarr[0])?"0":"1";
		//					echo "<td style=width:20px;text-align:right;>".$res1."</td>";
							$res2=empty($myarr[1])?"0":"1";
			//				echo "<td style=width:20px;text-align:right;>".$res2."</td>";
							$res3=empty($myarr[2])?"0":"1";
				//			echo "<td style=width:20px;text-align:right;>".$res3."</td>";
							$res4=empty($myarr[3])?"0":"1";
					//		echo "<td style=width:20px;text-align:right;>".$res4."</td>";
 							$res5=empty($myarr[4])?"0":"1";
					//		echo "<td style=width:20px;text-align:right;>".$res5."</td>";
					//		echo "</tr></table>";
						$query=$query."b'".$res1."',b'".$res2."',b'".$res3."',b'".$res4."',b'".$res5."',".$subid.",".$_SESSION["userid"]."),";	
					}
			}
					$query=substr($query, 0, -1);
					$cnt=ExecuteNonQuery($query);
					echo $query;
	 		if($cnt>0)
			{
					redirect_to("editcoursesurvey.php?msg=Survey inserted successfully&flag=1");
			}
			else
			{
					redirect_to("editcoursesurvey.php?msg=Error in inserting survey&flag=2");
			}
		}
		else
				{

				 $data = ExecuteNonQuery('select * from outcomes_main') or die(mysql_error());
				 while($info = mysql_fetch_assoc($data)) 
				{
	 				$data1 = ExecuteNonQuery('select * from outcomes_detail where ocmmainid='.$info["ocmmainid"]) or die(mysql_error());
					 while($info1 = mysql_fetch_assoc($data1)) 
				    {
				 $ocmresid=GetSingleField("select * from outcomes_result where subid=".$subid." and ocmdetailid=".$info1["ocmdetailid"],"ocmresid");
							$myarr=array();
							$temp="mycb".$info1["ocmdetailid"];		
							for ($i=0; $i<5; $i++)
							{
								$myarr[$i]=$_POST[$temp][$i];
							}
	//						echo "<table><tr>";
							$res1=empty($myarr[0])?"0":"1";
		//					echo "<td style=width:20px;text-align:right;>".$res1."</td>";
							$res2=empty($myarr[1])?"0":"1";
			//				echo "<td style=width:20px;text-align:right;>".$res2."</td>";
							$res3=empty($myarr[2])?"0":"1";
				//			echo "<td style=width:20px;text-align:right;>".$res3."</td>";
							$res4=empty($myarr[3])?"0":"1";
					//		echo "<td style=width:20px;text-align:right;>".$res4."</td>";
 							$res5=empty($myarr[4])?"0":"1";
					//		echo "<td style=width:20px;text-align:right;>".$res5."</td>";
					//		echo "</tr></table>";
							$query="update outcomes_result set obj1=b'".$res1."',obj2=b'".$res2."',obj3=b'".$res3;
							$query=$query."',obj4=b'".$res4."',obj5=b'".$res5."' where ocmresid=".$ocmresid;
							$cnt=ExecuteNonQuery($query);
					}
				}
//						$query=substr($query, 0, -1);
//						echo $query;
						if($cnt>0)
						{
								redirect_to("editcoursesurvey.php?msg=Survey updated successfully?flag=1");
						}
						else
						{
								redirect_to("editcoursesurvey.php?msg=Error in updating survey?flag=2");
						}			

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
	else
	{
		document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
	}
  }
xmlhttp.open("GET","subjectobjective.php?q="+str,true);
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
            		<td><select  onchange="showUser(this.value)" name="ddlsub">
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
    <div id="txtHint"><b>Course objectives will be listed here</b></div>
   </div>
</div>
<div id="footer">
	<?php include("footer.html"); ?>
</div>
</div>
</body>
</html>