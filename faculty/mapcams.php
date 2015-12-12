<?php
	  		
		ob_start();
		ini_set('display_errors','off');
		require_once("includes/functions.php");
		require_once("includes/session.php");
		include("includes/DataAccess.php");  
		include("includes/connect.php");
		 $version=GetSingleField("select version from fileinfo where ftype='matrix' and active=1","version");
		
?>
<?php
?>
<?php	if (!logged_in()) {
		redirect_to("index.php");
	}
?>
<?php
	if(!isset($_SESSION["mapcsid"]))
	{
		redirect_to("index.php");
	}
?>
<?php
if(isset($_POST["btnsubmit"]))
{
		$cnm=GetSingleField("select courseno from courses where cid=(select cid from course_section where csid=".$_SESSION["mapcsid"].")","courseno");	
		$cnm=str_replace(" ","_",$cnm);
		$cnt=CountRecords("select * from cams where semid=".$_SESSION["ddlsem3"]." and uid=".$_SESSION["userid"]." and csid=".$_SESSION["mapcsid"]);
		if(substr($cnm,0,1)=="C")
			$cnt3=CountRecords("select * from course_mappings where cno='".str_replace(" ","_",$cnm)."' and version='$version'");
		else
			$cnt3=CountRecords("select * from gcourse_mappings where cno='".str_replace(" ","_",$cnm)."' and version='$version'");
		$cnt4=CountRecords("select * from fclo where semid=".$_SESSION["ddlsem3"]." and uid=".$_SESSION["userid"]." and csid=".$_SESSION["mapcsid"]);

		$strobj="";
		for($j=1;$j<=$cnt3+$cnt4;$j++)
		{
			$strobj.="obj$j,";	
		}
					$strobj=substr($strobj,0,-1);
		$sql="select camid,camname from cams where csid=".$_SESSION["mapcsid"]." and uid=".$_SESSION["userid"]." and semid=".$_SESSION["ddlsem3"];
		$data=ExecuteNonQuery($sql);
		 while($info = mysqli_fetch_assoc($data)) 
         {
			$str="insert into fcamm(camid,$strobj,semid,uid,csid)values(".$info["camid"].",";
			for($j=1;$j<=$cnt3+$cnt4;$j++)
			{
				$t="cbx_".$info["camid"]."_".$j;
				if(isset($_POST[$t]))
				{
					$str.="1,";	
				}
				else
				{
					$str.="0,";
				}
			}
			$str=substr($str,0,-1);
			$str.=",".$_SESSION["ddlsem3"].",".$_SESSION["userid"].",".$_SESSION["mapcsid"].")";
			$sql="select * from fcamm where camid=".$info["camid"]." and csid=".$_SESSION["mapcsid"]." and uid=".$_SESSION["userid"]." and semid=".$_SESSION["ddlsem3"];
			$cntfcamm=CountRecords($sql);
			if($cntfcamm==0)
				{
					echo $str;
					ExecuteNonQuery($str);
				}
				else
				{
					$str1="update fcamm set ";
					for($j=1;$j<=$cnt3+$cnt4;$j++)
					{
						$flag="0";
						$t="cbx_".$info["camid"]."_".$j;
						if(isset($_POST[$t]))
						{
							$flag="1";
						}
						$str1.="obj$j=$flag,";	
					}
					$str1=substr($str1,0,-1);

					$str1.=" where camid=".$info["camid"]." and csid=".$_SESSION["mapcsid"]." and uid=".$_SESSION["userid"]." and semid=".$_SESSION["ddlsem3"];
					ExecuteNonQuery($str1);				
				}
		 }
		$sql="select * from fclo where csid=".$_SESSION["mapcsid"]." and uid=".$_SESSION["userid"]." and semid=".$_SESSION["ddlsem3"];
		$i=CountRecords($sql);
		if($i!=0)
		{
			$data=ExecuteNonQuery($sql);
			while($info=mysqli_fetch_assoc($data))
			{
				$temp="txtdocm_".$info["fcloid"];
				$temp1="txtpocm_".$info["fcloid"];
				if(isset($_POST[$temp]) || isset($_POST[$temp1]))
				{
				$sql="update fclo set docm='".$_POST[$temp]."',pocm='".$_POST[$temp1]."' where fcloid=".$info["fcloid"];
				ExecuteNonQuery($sql);		
				}
			}
		}
$sql="update section set gradingpolicy='".$_POST["tagp"]."' where (csid=".$_SESSION["mapcsid"]." and uid=".$_SESSION["userid"]." and semid=".$_SESSION["ddlsem3"].")";
//echo $sql;
ExecuteNonQuery($sql);
redirect_to("welcome.php");		
	
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Modify Course Details and Mappings</title>
<link href="css/menu.css" rel="stylesheet" type="text/css">
<script src="js/jquery-latest.min.js"></script>
               <script src="ckeditor/ckeditor.js"></script>

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
<?php if($_GET["flag"]==1) { ?>
                <div id="divsuccess">
                    <img src="images/success.png" width="25px" height="25px"><?php echo $_GET["msg"]; ?> 
                </div>
                <?php } ?>
                <?php if($_GET["flag"]==2) { ?>
                <div id="diverror">
                    <img src="images/cross.png" width="25px" height="25px"><?php echo $_GET["msg"]; ?> 
                </div>
                <?php }?>
	<div id="viewsubjects">
    		<?php
			?>
			<form method="post" name="form1" action="mapcams.php">
                            <table><tr><td><a href="modifycoursedetails.php<?php if(isset($_GET["semid"]) && isset($_GET["csid"])) { echo ("?semid=".$_GET["semid"]."&csid=".$_GET["csid"]); } ?>">Go back to modify course details</a></td></tr></table>
            <?php
				$sql="select courseno from courses where cid=(select cid from course_section where csid=".$_SESSION["mapcsid"].")";
				$cno=GetSingleField($sql,"courseno");
			
			?>
			<table border="1">
        	 <tr>
            <td colspan="3">
            <?php
				
					$cnm=GetSingleField("select courseno from courses where cid=(select cid from course_section where csid=".$_SESSION["mapcsid"].")","courseno");	
					$cnm=str_replace(" ","_",$cnm);
					
					$myc=substr($cnm,0,1);
					if($myc=="C")
					{
						$cntrec=CountRecords("select cno from course_mappings where cno='".str_replace(" ","_",$cnm)."' and version='$version'");
					}
					else
					{
						$cntrec=CountRecords("select cno from gcourse_mappings where cno='".str_replace(" ","_",$cnm)."' and version='$version'");
					}
					if($cntrec>0)
					{
					$myc=substr($cnm,0,1);
					if($myc=="C")
					{	
					$data2=ExecuteNonQuery("select mappingname from course_mappings where cno='".str_replace(" ","_",$cnm)."' and version='$version'");
					}
					else
					{
						$data2=ExecuteNonQuery("select mappingname from gcourse_mappings where cno='".str_replace(" ","_",$cnm)."' and version='$version'");
					}
						echo "<ul>";
				
					  while($info2 = mysqli_fetch_assoc($data2)) 
                   		 {
				?>
						   <?php echo "<li>".$info2["mappingname"]."</li>";
					     }
					}
					
					$cnt2=CountRecords("select * from fclo where semid=".$_SESSION["ddlsem3"]." and uid=".$_SESSION["userid"]." and csid=".$_SESSION["mapcsid"]);
					if($cnt2>0)
						{
						$data3=ExecuteNonQuery("select name from fclo where semid=".$_SESSION["ddlsem3"]." and uid=".$_SESSION["userid"]." and csid=".$_SESSION["mapcsid"]);
						  while($info3 = mysqli_fetch_assoc($data3)) 
							 {
					?>
				   <?php echo "<li>".$info3["name"]."</li>";
							 }
						}
						echo "</ul>";	 
			
				 ?>
            </td>
             </tr>
             
             <tr>
            		<td>Assessment Method Mappings</td>
            		<td>:</td>
            		<td>
                    <?php
					$myc=substr($cnm,0,1);
					if($myc=="C")
					{	
						$cnt=CountRecords("select cocmid from course_mappings where cno='".$cnm."' and version='".$version."'");
					}
					else
					{
						$cnt=CountRecords("select cocmid from gcourse_mappings where cno='".$cnm."' and version='".$version."'");
					}
					$sql="select * from fclo where semid=".$_SESSION["ddlsem3"]." and uid=".$_SESSION["userid"]." and csid=".$_SESSION["mapcsid"];
//					echo $sql;
					$cnt2=CountRecords($sql);
					?>
                           <table align="left" border="1">
                           	<tr>
                            	<td rowspan="2">ASSESSMENT METHODS</td>
                                
                                        	<td colspan="<?php echo $cnt+$cnt2;?>" align="center">
                                            	OBJECTIVES
                          		          </td>
                            </tr>
                            <tr>
                            <?php
							
				
							for($i=1;$i<=$cnt+$cnt2;$i++)
							{
							?>
                               	<td><?php 	echo "#". $i; ?></td>
                             <?php }?>          
                            </tr>
                        	<?php
							$sql="select camid,camname from cams where csid=".$_SESSION["mapcsid"]." and uid=".$_SESSION["userid"]." and semid=".$_SESSION["ddlsem3"];
							$data=ExecuteNonQuery($sql);
							 while($info = mysqli_fetch_assoc($data)) 
                 	  		 {
							?>
                        
                            <tr>
                            	<td><?php echo $info["camname"]; ?></td>
									 <?php 	for($i=1;$i<=$cnt+$cnt2;$i++)
                                    {
										$objnm="obj".$i;
										$t1=GetSingleField("select $objnm from fcamm where camid=".$info["camid"]." and csid=".$_SESSION["mapcsid"]." and uid=".$_SESSION["userid"]." and semid=".$_SESSION["ddlsem3"],$objnm);
										 ?>
                                        <td><input type="checkbox" name="<?php echo "cbx_".$info["camid"]."_".$i; ?>" <?php if($t1=="1") echo " checked";?>></td>
                                    <?php }?>
                            </tr>
                        <?php
						 } ?>
                       		<tr>
                                <td>Department-Wide Outcomes</td>
                           <?php
						   if($myc=="C")
						   {
						   $data=ExecuteNonQuery("select * from matrix_".strtolower($version)." where cno='".$cnm."'");
						   }
						   else
						   {
							   $data=ExecuteNonQuery("select * from gmatrix_".strtolower($version)." where cno='".$cnm."'");
						   }
						   if($myc=="C")
						   {
						   while($info=mysqli_fetch_assoc($data))
							{
								$temp="";
								if($info["col0"]=="1")
								{
									$temp="CIS-1";
								}
								if($info["col1"]=="1")
								{
									if($temp=="")
										$temp="CIS-2";
									else
										$temp.=","."CIS-2";
								}
								if($info["col2"]=="1")
								{
									if($temp=="")
										$temp="CIS-3";
									else
										$temp.=","."CIS-3";
								}
								if($info["col3"]=="1" || $info["col4"]=="1" || $info["col5"]=="1" || $info["col6"]=="1" || $info["col7"]=="1")
								{
									if($temp=="")
										$temp="CIS-4";
									else
										$temp.=","."CIS-4";
								}
								if($info["col8"]=="1")
								{
									if($temp=="")
										$temp="CIS-5";
									else
									$temp.=","."CIS-5";
								}
								if($info["col9"]=="1" || $info["col9"]=="1")
								{
									if($temp=="")
										$temp="CIS-6";
									else
										$temp.=","."CIS-6";		
								}?>
							<td><?php echo $temp;?></td>
								
						<?php 	}
						   }
						   else
						   {
							   while($info=mysqli_fetch_assoc($data))
							{
								$temp="";
								if($info["col0"]=="1")
								{
									$temp="GCIS-1";
								}
								if($info["col1"]=="1")
								{
									if($temp=="")
										$temp="GCIS-2";
									else
										$temp.=","."GCIS-2";
								}
								if($info["col2"]=="1")
								{
									if($temp=="")
										$temp="GCIS-3";
									else
										$temp.=","."GCIS-3";
								}
								if($info["col3"]=="1")
								{
									if($temp=="")
										$temp="GCIS-4";
									else
										$temp.=","."GCIS-4";
								}
								if($info["col4"]=="1")
								{
									if($temp=="")
										$temp="GCIS-5";
									else
										$temp.=","."GCIS-5";
								}
								if($info["col5"]=="1")
								{
									if($temp=="")
										$temp="GCIS-6";
									else
										$temp.=","."GCIS-6";
								}
								if($info["col6"]=="1")
								{
									if($temp=="")
										$temp="GCIS-7";
									else
										$temp.=","."GCIS-7";
								}
								if($info["col7"]=="1")
								{
									if($temp=="")
										$temp="GCIS-8";
									else
										$temp.=","."GCIS-8";
								}
								if($info["col8"]=="1")
								{
									if($temp=="")
										$temp="GCIS-9";
									else
										$temp.=","."GCIS-9";
								}
								if($info["col9"]=="1")
								{
									if($temp=="")
										$temp="ACs-1";
									else
										$temp.=","."ACS-1";
								}
								if($info["col10"]=="1")
								{
									if($temp=="")
										$temp="ACS-2";
									else
										$temp.=","."ACS-2";
								}
								if($info["col11"]=="1")
								{
									if($temp=="")
										$temp="ACS-3";
									else
										$temp.=","."ACS-3";
								}
								if($info["col12"]=="1")
								{
									if($temp=="")
										$temp="IS-1";
									else
										$temp.=","."IS-1";		
								}
								if($info["col13"]=="1" || $info["col14"]=="1" || $info["col15"]=="1" )
								{
									if($temp=="")
										$temp="IS-2";
									else
										$temp.=","."IS-2";		
								}
								if($info["col16"]=="1")
								{
									if($temp=="")
										$temp="SE-1";
									else
										$temp.=","."SE-1";		
								}
								if($info["col17"]=="1")
								{
									if($temp=="")
										$temp="SE-2";
									else
										$temp.=","."SE-2";		
								}
								if($info["col18"]=="1")
								{
									if($temp=="")
										$temp="SE-3";
									else
										$temp.=","."SE-3";		
								}
								if($info["col19"]=="1")
								{
									if($temp=="")
										$temp="SE-4";
									else
										$temp.=","."SE-4";		
								}
								if($info["col20"]=="1")
								{
									if($temp=="")
										$temp="WD-1";
									else
										$temp.=","."WD-1";		
								}
								if($info["col21"]=="1")
								{
									if($temp=="")
										$temp="WD-2";
									else
										$temp.=","."WD-2";		
								}
								if($info["col22"]=="1")
								{
									if($temp=="")
										$temp="WD-3";
									else
										$temp.=","."WD-3";		
								}
								?>
							<td><?php echo $temp;?></td>
								
						<?php 	}
						   }
					
						$data=ExecuteNonQuery("select * from fclo where csid=".$_SESSION["mapcsid"]." and uid=".$_SESSION["userid"]." and semid=".$_SESSION["ddlsem3"]);
							$i=mysqli_num_rows($data);
							if($i==0)
							{
								?>
    <!--                            	<td><input type="text" size="5" name="txtdocm_0"></td>-->
								
							<?php }
							else
							{
								while($info=mysqli_fetch_assoc($data))
								{
								?>
									<td><input type="text" size="5" name="txtdocm_<?php echo $info["fcloid"];?>" value="<?php echo $info["docm"];?>"></td>
						   <?php }
							}
							?>
                        	</tr> 
                           <tr>
                                <td>Program-Specific Outcomes</td>
                                <?php
								$data=ExecuteNonQuery("select * from matrix_".strtolower($version)." where cno='".$cnm."'");
						  if($myc=="C")
						   {
								$data=ExecuteNonQuery("select * from matrix_".strtolower($version)." where cno='".$cnm."'");
						   }
						   else
						   {
								$data=ExecuteNonQuery("select * from gmatrix_".strtolower($version)." where cno='".$cnm."'");
						   }
						    if($myc=="C")
						   {
								 while($info=mysqli_fetch_assoc($data))
								{
									$temp="";
									if($info["col11"]=="1" || $info["col12"]=="1")
									{
										if($temp=="")
											$temp="CS-1";
										else
											$temp.=","."CS-1";
									}
									if($info["col13"]=="1" || $info["col14"]=="1")
									{
										if($temp=="")
											$temp="CS-2";
										else
											$temp.=","."CS-2";
									}
									if($info["col15"]=="1")
									{
										if($temp=="")
											$temp="CS-3";
										else
											$temp.=","."CS-3";
									}
									if($info["col16"]=="1")
									{
										if($temp=="")
											$temp="CS-4";
										else
											$temp.=","."CS-4";
									}
									if($info["col17"]=="1")
									{
										if($temp=="")
											$temp="CS-5";
										else
											$temp.=","."CS-5";
									}
									
									if($info["col18"]=="1" || $info["col19"]=="1" || $info["col20"]=="1" || $info["col21"]=="1")
									{
										if($temp=="")
											$temp="IS-1";
										else
											$temp.=","."IS-1";
									}
									if($info["col22"]=="1")
									{
										if($temp=="")
											$temp="IS-2";
										else
											$temp.=","."IS-2";
									}
									if($info["col23"]=="1" || $info["col24"]=="1")
									{
										if($temp=="")
											$temp="IS-3";
										else
											$temp.=","."IS-3";
									}
									if($info["col25"]=="1")
									{
										if($temp=="")
											$temp="IS-4";
										else
											$temp.=","."IS-4";
									}
									if($info["col26"]=="1" || $info["col27"]=="1" || $info["col28"]=="1" || $info["col29"]=="1" || $info["col30"]=="1" || $info["col31"]=="1")
									{
										if($temp=="")
											$temp="SE-1";
										else
											$temp.=","."SE-1";
									}
									if($info["col32"]=="1")
									{
										if($temp=="")
											$temp="SE-2";
										else
											$temp.=","."SE-2";
									}
									if($info["col33"]=="1")
									{
										if($temp=="")
											$temp="SE-3";
										else
											$temp.=","."SE-3";
									}?>
										<td><?php echo $temp;?></td>	
							<?php	}
							
						   }
						    else
						   {
							   $temp="";
							   	   while($info=mysqli_fetch_assoc($data))
								   {
									   if($info["col9"]=="1")
										{
											if($temp=="")
												$temp="ACS-1";
											else
												$temp.=","."ACS-1";
										}
										if($info["col10"]=="1")
										{
											if($temp=="")
												$temp="ACS-2";
											else
												$temp.=","."ACS-2";
										}
										if($info["col11"]=="1")
										{
											if($temp=="")
												$temp="ACS-3";
											else
												$temp.=","."ACS-3";
										}?>
                                        	<td><?php echo $temp;?></td>	
								 <?php
								   }
						   }
						    $data=ExecuteNonQuery("select * from fclo where csid=".$_SESSION["mapcsid"]." and uid=".$_SESSION["userid"]." and semid=".$_SESSION["ddlsem3"]);
							$i=mysqli_num_rows($data);
							
							if($i==0)
							{
						    ?>
                           	<!--	<td><input type="text" size="5" name="txtpocm_0"></td>-->
                            
							   <?php }else
                               {
								   while($info=mysqli_fetch_assoc($data))
								   {
                               ?>
                                  	<td><input type="text" size="5" name="txtpocm_<?php echo $info["fcloid"];?>" value="<?php echo $info["pocm"];?>"></td>
                            <?php 
									}
                               }
                               ?>
                        	</tr> 
                           </table>
	                </td>
            </tr>
        <tr>
             <?php
			$gp=GetSingleField("select gradingpolicy from section where csid=".$_SESSION["mapcsid"]." and uid=".$_SESSION["userid"]." and semid=".$_SESSION["ddlsem3"],"gradingpolicy");
			
			
						
			 ?>
            	<td>Grading Policy</td>
            	<td>:</td>
           		<td>
                <textarea name="tagp" id="tagp" rows="10" cols="50">
            <?php if(isset($gp) && $gp!="") echo $gp; else {?>
                	<table>
                    <tr>
                    <td style="vertical-align:top;">
                        <table border="1">
                            <tr>
                                <th>Grade Componenent</th>
                                <th>Weight</th>
                            </tr>
                        <?php
						$data=ExecuteNonQuery("select camid,camname from cams where csid=".$_SESSION["mapcsid"]." and uid=".$_SESSION["userid"]." and semid=".$_SESSION["ddlsem3"]);
					
					//	$cnt=1;
						while($info=mysqli_fetch_assoc($data))
						{
						?>
                        <tr>
                                <th><?php echo $info["camname"];?></th>
                                <th>[NN%]</th>
                            </tr>
                        <?php
						//	$cnt++;
						 }?>
                        </table>
                       </td>
                       <td><!-- Grading criteria-->
                       		<table border="1">
                            <?php
						
							$substrc=substr($cno,0,1);
						
							if($substrc=="G")
							{
							 ?>
                            	
                                <tr>
                                	<th>Course Score</th>
                                	<th>Graduate Grade</th>
                                </tr>
                           		<tr>
                                	<td>>=98</td>
                                    <td>A+</td>
                              	</tr>
                                <tr>
                                	<td>93-97</td>
                                    <td>A</td>
                              	</tr>
                                <tr>
                                	<td>90-92</td>
                                    <td>A-</td>
                              	</tr>
                                <tr>
                                	<td>87-89</td>
                                    <td>B+</td>
                              	</tr>
                                <tr>
                                	<td>84-86</td>
                                    <td>B</td>
                              	</tr>
                                <tr>
                                	<td>80-83</td>
                                    <td>B-</td>
                              	</tr>
                                <tr>
                                	<td>75-79</td>
                                    <td>C+</td>
                              	</tr>
                                <tr>
                                	<td>70-74</td>
                                    <td>C</td>
                              	</tr>
                                <tr>
                                	<td><=69</td>
                                    <td>F</td>
                              	</tr>
                            
                            <?php }else { ?>
                            	<tr>
                                	<th>Course Score</th>
                                	<th>Undergraduate Grade</th>
                                </tr>
                           		<tr>
                                	<td>>=98</td>
                                	<td>A+</td>
                              	</tr>
                                <tr>
                                	<td>93-97</td>
                                	<td>A</td>
                              	</tr>
                                <tr>
                                	<td>90-92</td>
                                	<td>A-</td>
                              	</tr>
                                <tr>
                                	<td>87-89</td>
                                	<td>B+</td>
                              	</tr>
                                <tr>
                                	<td>84-86</td>
                                	<td>B</td>
                              	</tr>
                                <tr>
                                	<td>80-83</td>
                                	<td>B-</td>
                              	</tr>
                                <tr>
                                	<td>75-79</td>
                                	<td>C+</td>
                              	</tr>
                                <tr>
                                	<td>70-74</td>
                                	<td>C</td>
                              	</tr>
                                <tr>
                                	<td>65-69</td>
                                	<td>C-</td>
                              	</tr>
                                <tr>
                                	<td>60-64</td>
                                	<td>D</td>
                              	</tr>
                                <tr>
                                	<td>60</td>
                                	<td>F</td>
                              	</tr>
                            <?php }
							
							?>
                            </table>
                       </td>
                       </tr>
                       </table>
                       <?php }?>
                </textarea>
            			  <script>
							// Replace the <textarea id="editor1"> with a CKEditor
							// instance, using default configuration.
							CKEDITOR.config.toolbar_MA=[ ['Source','-','Cut','Copy','Paste','-','Undo','Redo','RemoveFormat','-','Link','Unlink','Anchor','-','Image','Table','HorizontalRule','SpecialChar'], '/', ['Format','Templates','Bold','Italic','Underline','-','Superscript','-',['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],'-','NumberedList','BulletedList','-','Outdent','Indent'] ];
							CKEDITOR.replace( 'tagp',
            {   toolbar:'MA'    }
         );
						</script>
	                </td>
            </tr>

       
       
       
           	<tr>
            <td colspan="3" align="center"><input type="submit" name="btnsubmit" onClick="document.form1.submit();" value="Insert Mappings"></td>
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