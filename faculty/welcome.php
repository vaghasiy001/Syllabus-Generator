<?php
	ob_start();
	ini_set('display_errors','off');
		require_once("includes/functions.php");
		require_once("includes/session.php");
		require_once("includes/connect.php");
		include("includes/DataAccess.php");  
?>
<?php	if (!logged_in()) {
		redirect_to("index.php");
	}
 $semid=GetSingleField("select semid from semester where active=1","semid");
				
?>
<?php
if(isset($_GET["flag"]) && $_GET["flag"]=="1" )
{
		$_SESSION["ddlsem7"]=$semid;
		redirect_to("addoffhrs.php");

}
if(isset($_GET["flag"]) && $_GET["flag"]=="2" )
{
		redirect_to("viewdoorschedule.php");

}
if(isset($_GET["flag"]) && $_GET["flag"]=="3" )
{
			$_SESSION["ddlsem4"]=$semid;
		redirect_to("viewcoursedetails.php");

}
if(isset($_GET["semid"]) && isset($_GET["csid"]))
{
	$_SESSION["ddlsem3"]=$semid;
	redirect_to("modifycoursedetails.php?semid=".$_SESSION["ddlsem3"]."&csid=".$_GET["csid"]);
	
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Home Page</title>
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
            <form method="post" action="welcome.php">
	<table border="1" align="left" style="width:50%;margin-left:20px;"">
<?php
				 $semid=GetSingleField("select semid from semester where active=1","semid");
				$data2 = ExecuteNonQuery('select semname,year FROM semester where active=1');
	      		$snm="";
				$syr="";
		        while($info2 = mysqli_fetch_assoc($data2)) 
				{
				$snm=$info2["semname"];
				$syr=$info2["year"];
		    		 
				}
?>
				<tr>
                <td colspan="3"><i>Current Semester :</i><?php echo $snm . "  ".$syr;?><br>
<i><b style="color:#630;">M:</b><span style="font-size:10px;">Mappings, </span><i><b style="color:#603;">RM:</b><span style="font-size:10px;">Required Materials,</span><b style="color:#603;">Web:</b><span style="font-size:10px;">Resources,</span><b style="color:#603;">GP:</b><span style="font-size:10px;">Grading Policy,</span><b style="color:#603;">CP:</b><span style="font-size:10px;">Course Policies,</span><br><b style="color:#603;">AI:</b><span style="font-size:10px;">Academic Integrity,</span><b style="color:#603;">CT:</b><span style="font-size:10px;">Course Topics</span></i>
                </td>
                </tr>
            	<tr>
                <th>Course</th>
                <th>Name</th>
                <th><center>Status</center>
                 <table style="font-size:15px;width:100%" cellpadding="3px">
                    	<td>M</td>
                    	<td>RM</td>
	                    <td>Web</td>
                        <td>GP</td>
                        <td>CP</td>
                        <td>AI</td>
                        <td>CT</td>
                        </table>
                </th>
                </tr>
                 <?php 

				$data = ExecuteNonQuery('select * FROM section where uid='.$_SESSION["userid"]." and semid=".$semid." and active=1");
	            while($info = mysqli_fetch_assoc($data)) 
				{
					$cntrecords=CountRecords("select * from fcamm where csid=".$info["csid"]." and semid=".$info["semid"]." and uid=".$info["uid"]);
					if($cntrecords>0)
					{
					$sql="SELECT SUM(obj1) + SUM(obj2) + SUM(obj3)+ SUM(obj4)+ SUM(obj5)+ SUM(obj6)+ SUM(obj7)+ SUM(obj8)+ SUM(obj9)+ SUM(obj10) as 'Total'
FROM fcamm where csid=".$info["csid"]." and semid=".$info["semid"]." and uid=".$info["uid"];
					//echo $sql;
					$sfld=GetSingleField($sql,"Total");
						if($sfld!=0)
							$cntrecords=1;
						else
							{
								$cntrecords=0;
							}
					}
					else
					{
						$cntrecords=0;
					}

					$csnm=GetSingleField("select sections from course_section where csid=".$info["csid"],"sections");
				 ?>
				 <tr>
                <td><?php echo $csnm;?> </td>
                <td><a name="hlnk" href="welcome.php?semid=<?php echo $semid;?>&csid=<?php echo $info["csid"];?>" onClick="document.forms.submit();"> <?php echo $info["name"]; ?></a> </td>
				<td align="center">
				<table border="1" width="100%">
                   
                    <tr>
                    	<td>
                        <?php if($cntrecords==1){ ?> 
                                     <img src="images/susscess.png">
                            <?php }else{ ?>         
                                     <img src="images/fail.png">
                            <?php }?>                     
                        </td>
                        <td>
							<?php if(!is_null($info["reqmaterials"]) && trim($info["reqmaterials"])!=""){ ?> 
                                     <img src="images/susscess.png">
                            <?php }else{ ?>         
                                     <img src="images/fail.png">
                            <?php }?>                     
                        </td>
                       
	                    <td>
							<?php if(!is_null($info["website"]) && trim($info["website"])!=""){ ?> 
                                     <img src="images/susscess.png">
                            <?php }else{ ?>         
                                     <img src="images/fail.png">
                            <?php }?>
                        
                        </td>
                        <td>
							<?php if(!is_null($info["gradingpolicy"]) && trim($info["gradingpolicy"])!=""){ ?> 
                            		<?php
										$gp= $info["gradingpolicy"];
										//echo $gp;
										$sql="select camname from cams where csid=".$info["csid"]." and uid=".$_SESSION["userid"]." and semid=".$semid;
										$data4=ExecuteNonQuery($sql);
										$flag=0;
										while($info4=mysqli_fetch_assoc($data4))
										{
											
											if (strpos($gp,'[NN%]') !== false) {
												$flag=1;
											}
										}
										if($flag==0)
										{
									?>
                             		<img src="images/susscess.png">
                                    <?php }
									else{
									?>
                                    <img src="images/fail.png">
                            <?php
									}
									}else{ ?>         
                                     <img src="images/fail.png">
                            <?php }?>
                        </td>
                        <td>
							<?php if(!is_null($info["coursepolicy"]) && trim($info["coursepolicy"])!=""){ ?> 
                                     <img src="images/susscess.png">
                            <?php }else{ ?>         
                                     <img src="images/fail.png">
                            <?php }?>
                        </td>
                        <td>
							<?php if(!is_null($info["attpolicy"]) && trim($info["attpolicy"])!=""){ ?> 
                                     <img src="images/susscess.png">
                            <?php }else{ ?>         
                                     <img src="images/fail.png">
                            <?php }?>
                        </td>
                        <td>
							<?php if(!is_null($info["coursetopics"]) && trim($info["coursetopics"])!=""){ ?> 
                                     <img src="images/susscess.png">
                            <?php }else{ ?>         
                                     <img src="images/fail.png">
                            <?php }?>
                        </td>
                    </tr>
                    </table>
				
				<!--	<img src="images/close_icon.png" height="30px" width="30px">
            	    <img src="images/check-icon.png" height="30px" width="30px">
                -->
				
			</td>
                </tr>
				<?php }?>
                </table>
	<table border="1" style="width:45%;margin-right:20px;" align="right">
<?php
				$data3 = ExecuteNonQuery('select starttime,endtime,cday FROM facultyhours where type="office" and uid='.$_SESSION["userid"]." and semid=".$semid);
	      		$cnthrs=mysqli_num_rows($data3);
			    if($cnthrs==0)
				{?>
                <tr>
		                <td colspan="3"><i>Add office hours for :<a href="welcome.php?flag=1" onClick="document.forms.submit()"><?php echo $snm." ".$syr;?></a></i></td>                
                </tr>
					
				<?php }else{
?>
				<tr>
                <td colspan="3"><i>Office Hours for <a href="welcome.php?flag=1" name="aoffhr" onClick="document.forms.submit();"><?php echo $snm." ".$syr;?></a></i></td>
                </tr>
            	<tr>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Days</th>
                </tr>
                <tr>
                <?php 
				while($info3 = mysqli_fetch_assoc($data3)) 
				{?>
                <tr>
                <td><?php echo $info3["starttime"]; ?></td>
				<td><?php echo 	$info3["endtime"]; ?></td>
                <td><?php echo $info3["cday"];	 ?></td>
				</tr>
				
				<?php }
				}
				?>
				<tr>
                <td  colspan="3">&nbsp;</td>
                </tr>
                <tr>
                <td colspan="3"><i>Class Schedule for <a href="welcome.php?flag=2" onClick="document.forms.submit()"><?php echo $snm." ".$syr;?></a></i></td>
                </tr>
                <tr>
                <td  colspan="3">&nbsp;</td>
                </tr>
                 <tr>
                <td colspan="3"><i>Export Syllabus for <a href="welcome.php?flag=3" onClick="document.forms.submit()"><?php echo $snm." ".$syr;?></a></i></td>
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