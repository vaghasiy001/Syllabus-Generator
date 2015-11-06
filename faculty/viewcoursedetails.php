<?php
	
	//changed to ta textbox to text area.
		ob_start();
		ini_set('display_errors','off');
		require_once("includes/functions.php");
		require_once("includes/session.php");
		include("includes/DataAccess.php");  
		include("includes/connect.php");
	include("html_to_doc.inc.php");
	$version="";
	if(isset($_POST["ddlcnm"]))
	{
		$cno=GetSingleField("select courseno from courses where cid=(select cid from course_section where csid=".$_POST["ddlcnm"].")","courseno");	
		$cno=str_replace(" ","_",$cno);
		$st=substr($cno,0,1);
		if($st=="C")	
			$version=GetSingleField("select version from fileinfo where ftype='matrix' and active=1","version");
		else
			$version=GetSingleField("select version from fileinfo where ftype='gmatrix' and active=1","version");
	
	}

?>
<?php
?>
<?php	if (!logged_in()) {
		redirect_to("index.php");
	}
?>
<?php
	if(!isset($_SESSION["ddlsem4"]))
	{
		redirect_to("index.php");
	}
?>

<?php
	if(isset($_POST["ddlcnm"]) && $_POST["ddlcnm"]!="")
	{
	//	$cno=GetSingleField("select cid from course_section where csid=".$_POST["ddlcnm"],"cid");
	//	$cno=str_replace(" ","_",$cno);
		$cid=GetSingleField("select cid from course_section where csid=".$_POST["ddlcnm"],"cid");
	
		$sql="select c.courseno,c.coursename,c.credits,c.prereqid,c.coreqid,c.description from courses c,course_section cs where c.cid=cs.cid and c.cid=".$cid;
		$data=ExecuteNonQuery($sql);
		$cnm="";
		$desc="";
		$credits="";
		$prereq="";
		$coreq="";
		$sem="";
		$cno="";
		$classloc="";
		$cday="";
		$sttimehr="";
		$sttimemin="";
		$sttimeampm="";
		$entimehr="";
		$entimemin="";
		$entimeampm="";
		$cno=GetSingleField("select courseno from courses where cid=(select cid from course_section where csid=".$_POST["ddlcnm"].")","courseno");	
		$cno=str_replace(" ","_",$cno);
	
		if(mysqli_num_rows($data)>0)
		{
			while($info = mysqli_fetch_assoc($data)) 
			{
				$cnm=$info["coursename"];
				$desc=$info["description"];
				$credits=$info["credits"];
				if($info["prereqid"]=="0")
					$prereq="None";
				else
					$prereq=GetSingleField("select pcname from pre_req where prereqid=".$info["prereqid"],"pcname");
				if($info["coreqid"]=="0")
					$coreq="None";
				else
					$coreq=GetSingleField("select ccname from co_req where coreqid=".$info["coreqid"],"ccname");
				$cno=$info["courseno"];
				$cno=str_replace(" ","_",$cno);
				
				$sql="select starttime,endtime,cday,roomno from facultyhours where csid=".$_POST["ddlcnm"]." and semid=".$_SESSION["ddlsem4"]." and uid=".$_SESSION["userid"];
			//	echo $sql;
				$dt2=ExecuteNonQuery($sql);
				
				while($inf2 = mysqli_fetch_assoc($dt2)) 
				{
					$classloc=$inf2["building"]." ".$inf2["roomno"];
					$cday=explode(" ",$inf2["cday"]);
					$tmp=explode(":",$inf2["starttime"]);
					$sttimehr=$tmp[0];
					$sttimemin=substr($tmp[1],0,2);
					$sttimeampm=substr($tmp[1],-2);
					$tmp1=explode(":",$inf2["endtime"]);
					$entimehr=$tmp1[0];
					$entimemin=substr($tmp1[1],0,2);
					$entimeampm=substr($tmp1[1],-2);
					
				}	
					
				//$sem=GetSingleField("select sem
			}
			if(isset($_SESSION["ddlsem4"]))
			{
				$sql="select semname,year from semester where semid=".$_SESSION["ddlsem4"];
				$data2=ExecuteNonQuery($sql);
				while($info = mysqli_fetch_assoc($data2)) 
				{
					$sem=$info["semname"]." ".$info["year"];
				}
			}
		}
	}
if(isset($_POST["export"]) && $_POST["ddlcnm"]!="")
{
$old_date = date('l, F d y h:i:s');              // returns Saturday, January 30 10 02:06:34
			$old_date_timestamp = strtotime($old_date);
			$new_date = date('mdYhis', $old_date_timestamp); 
	$cno=GetSingleField("select courseno from courses where cid=(select cid from course_section where csid=".$_POST["ddlcnm"].")","courseno");	
		$cno=str_replace(" ","_",$cno);
		
		
		$htmltodoc= new HTML_TO_DOC();
		$htmltodoc->createDoc($_POST["tafinal"],$_SESSION["username"]."/".$cno."_".$new_date);
		redirect_to("coursesyllabus.php");
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>View Course Details</title>
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
    		<form method="post" name="form1" action="viewcoursedetails.php">			
	<table>
    						<?php 	
					$data1 = ExecuteNonQuery('select csid,name from section where semid='.$_SESSION["ddlsem4"]." and uid=".$_SESSION["userid"]." and active=1");
			        ?>
   
            <tr>
            	<td colspan="3">
               	  Select Course:
             
                    <select name="ddlcnm" onChange="document.form1.submit();">
               			<option value="">Select</option>     
                     <?php
                            while($info1 = mysqli_fetch_assoc($data1)) 
                   		 {
							 $cid=GetSingleField("select sections from course_section where csid=".$info1["csid"],"sections");
							 ?>
                            <option value="<?php echo $info1["csid"]; ?>" <?php if($_POST["ddlcnm"]==$info1["csid"]) echo "selected"; ?>><?php echo "(".$cid.")".$info1["name"]; ?></option>		
                    	<?php
                        }
                        ?>
             		 
                    </select>
                    <input type="submit" name="export" value="Export Syllabus">
		<!--			<input type="submit" value="View Details" name="btnviewdetails">      -->   
                </td>
             
            </tr>
   
    </table>
    <?php if(isset($_POST["ddlcnm"])){?>
 	<textarea name="tafinal" id="tafinal">
     <table width="100%">
     <tr>
    	 <td><h3><b>Gannon University</b></h3></td>
		<td style="text-align:right"><h3><b>Department of Computer and Information Science</b></h3></td>
       </tr> 
       </table>
<!--       <div style="width:100%">-->
<table style="width:100%" border="1">
<tr>
<td>
       <table>
       	<?php		
			$data=ExecuteNonQuery("select * from users where uid=".$_SESSION["userid"]);
			$inst="";
			$offrno="";
			$ph="";
			$email="";
			while($info=mysqli_fetch_assoc($data))
			{
				$inst=$info["salutation"]." ".$info["firstname"].$info["lastname"];
				$offrno=$info["office"];
				$ph=$info["officeno"];
				$email=$info["email"];
			}

		?>
        <tr>
        <td><b>Instructor:</b></td>
        <td><?php echo $inst;?></td>
        </tr>
        <tr>
        <td><b>Office:</b></td>
        <td><?php echo $offrno;?></td>
        </tr>
        <tr>
        <td><b>Phone:</b></td>
        <td><?php echo $ph;?></td>
        </tr>
        <tr>
        <td><b>Email:</b></td>
        <td><?php echo $email;?></td>
        </tr>
       </table>
       </td>
       <td>
       <table>
       	<tr>
        <td colspan="2"><b>Office Hours</b></td>
        </tr>
        <?php 
	$data1=ExecuteNonQuery("select starttime,endtime,cday from facultyhours where uid=".$_SESSION["userid"]." and semid=".$_SESSION["ddlsem4"]." and type='office'");
		?>
        <?php while($info1=mysqli_fetch_assoc($data1))
		{?>
        <tr>
        	<td><?php echo $info1["cday"].":";?></td>
           <td><?php echo $info1["starttime"].":".$info1["endtime"];?></td>
        </tr>
        <?php }?>
			
         </table>
         </td>
         </tr>
         </table>
	<!--	</div>-->
        	<table border="1">
	        <tr>
            	<td>Course Title:</td>
              	
                <td><?php if(isset($cnm) && $cnm!="") echo str_replace("_"," ",$cno).": ".$cnm; ?></td>
            </tr>
        	<tr>
            	<td>Credit Hours:</td>
                
                <td><?php if(isset($credits) && $credits!="") echo $credits." credit(s)"; ?></td>
             </tr>
         	<tr>
            	<td>Semester:</td>
                
                <td><?php if(isset($sem) && $sem!="") echo $sem; ?></td>
            </tr>
         	<tr>
            	<td>Class Location:</td>
                
                <td><?php if(isset($classloc) && $classloc!="") echo $classloc;?></td>
            </tr>
             <tr>
            	<td>Class Time:</td>
                
                <td>
                    <?php 
					for($i=0;$i<count($cday);$i++)
					{
						echo $cday[$i]." ";
					}
					echo $sttimehr.":".$sttimemin." ".$sttimeampm;?>
               <b> to</b>
        		<?php echo $entimehr.":".$entimemin." ".$entimeampm;?></option>
                   
                   </td>
            </tr>
            <tr>
            		<td>Course Description:</td>
                    
            		<td>
            <?php
									   if(isset($desc) && $desc!="")
									   {
										   echo $desc;
									   }
									   ?>
                		</td>
            </tr>
       		<tr>
            		<td>Course Learning Objectives:</td>
                    
                    <td>
                    The student will be able to<br>
                    <?php
					$frstc=substr($cno,0,1);
					if($frstc=="C")
						$cntrec=CountRecords("select cno from course_mappings where cno='".str_replace(" ","_",$cno)."' and version='".$version."'");
					else
						$cntrec=CountRecords("select cno from gcourse_mappings where cno='".str_replace(" ","_",$cno)."' and version='".$version."'");
					
					$cnt2=CountRecords("select * from fclo where semid=".$_SESSION["ddlsem4"]." and uid=".$_SESSION["userid"]." and csid=".$_POST["ddlcnm"]);
					
					if($cntrec>0)
					{
					if($frstc=="C")
						$data2=ExecuteNonQuery("select mappingname from course_mappings where cno='".str_replace(" ","_",$cno)."' and version='".$version."'");
					else
						$data2=ExecuteNonQuery("select mappingname from gcourse_mappings where cno='".str_replace(" ","_",$cno)."' and version='".$version."'");
					$cnt=1;
					
							  while($info2 = mysqli_fetch_assoc($data2)) 
								 {
								?>
								<?php echo $info2["mappingname"]."<br>";?>
						   <?php 
							   }
				    }
					if($cnt2>0)
						{
							$data5=ExecuteNonQuery("select * from fclo where semid=".$_SESSION["ddlsem4"]." and uid=".$_SESSION["userid"]." and csid=".$_POST["ddlcnm"]);
							 while($info5 = mysqli_fetch_assoc($data5)) 
							 {
						 	$cnt++;
						
							  ?>
									<?php echo $info5["name"]."<br>";?>
							<?php 
							}
						}
					?>
                    </td>
            </tr>
           	<tr>
            		<td>Co-requisites:</td>
            		
            		<td><?php if(isset($coreq) && $coreq!="") echo $coreq; ?></td>
            </tr>
           	<tr>
            		<td>Prerequisites:</td>
            		
            		<td><?php if(isset($prereq) && $prereq!="") echo $prereq; ?></td>
            </tr>
            <tr>
            <?php
			$sql="select website from section where csid=".$_POST["ddlcnm"]." and uid=".$_SESSION["userid"]." and semid=".$_SESSION["ddlsem4"];
			$web=GetSingleField($sql,"website");
			 ?>
            		<td>Resources:</td>
            		
            		<td><?php echo $web;?>
                                    </td>
            </tr>
			<tr>
            <?php
			if(isset($_POST["ddlcnm"]))
			$reqmaterials=GetSingleField("select reqmaterials from section where csid=".$_POST["ddlcnm"]." and uid=".$_SESSION["userid"]." and semid=".$_SESSION["ddlsem4"],"reqmaterials");
			 ?>
            		<td>Required Textbooks:</td>
            		
            		<td>
                        <?php if(isset($reqmaterials) && $reqmaterials!="") echo $reqmaterials;?>
          		    </td>
            </tr>
            <tr>
            		<td>Course Assessment Method:</td>
            		
            		<td>
                    <?php
					$cnt=0;
					if(isset($_POST["ddlcnm"]))
					{
					$sql="select camid,camname,camdetails from cams where csid=".$_POST["ddlcnm"]." and uid=".$_SESSION["userid"]." and semid=".$_SESSION["ddlsem4"];
					$data3=ExecuteNonQuery($sql);
					//echo $sql;
					$cntrec1=mysqli_num_rows($data3);
					$cnt=mysqli_num_rows($data3);
				//	echo $cnt;
					}
					 ?>
                    	<div>
 	             		<table align="left" id="camtable" style="text-align:left;" border="1" >
                        	
                            <?php if($cnt>0)
							{
								$cnt=1;
								 while($info3 = mysqli_fetch_assoc($data3)) 
                   		 		{?>
                                    <tr>
                                        <td><?php echo $info3["camname"]; ?></td>
                                        <td width="100%"><?php echo $info3["camdetails"];?>
                                     </td>
                                  </tr>
                             
			          	 	<?php }
							}?>
                        </table>
 						
                        </div>
                    </td>
            </tr>
           <tr>
            		<td>Assessment Method Mappings:</td>
            		
            		<td>
                    <?php
							$frstc=substr($cno,0,1);
							if($frstc=="C")
								$cnt=CountRecords("select cocmid from course_mappings where cno='".$cno."' and version='".$version."'");
							else
								$cnt=CountRecords("select cocmid from gcourse_mappings where cno='".$cno."' and version='".$version."'");
								
								
							$cnt2=CountRecords("select * from fclo where semid=".$_SESSION["ddlsem4"]." and uid=".$_SESSION["userid"]." and csid=".$_POST["ddlcnm"]);?>
                           <table align="left" border="1" style="text-align:center">
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
							$sql="select camid,camname from cams where csid=".$_POST["ddlcnm"]." and uid=".$_SESSION["userid"]." and semid=".$_SESSION["ddlsem4"];
							$data=ExecuteNonQuery($sql);
							 while($info = mysqli_fetch_assoc($data)) 
                 	  		 {
							?>
                        
                            <tr>
                            	<td><?php echo $info["camname"]; ?></td>
									 <?php 	for($i=1;$i<=$cnt+$cnt2;$i++)
                                    {
										$objnm="obj".$i;
										$t1=GetSingleField("select $objnm from fcamm where camid=".$info["camid"]." and csid=".$_POST["ddlcnm"]." and uid=".$_SESSION["userid"]." and semid=".$_SESSION["ddlsem4"],$objnm);
										 ?>
                                        <td><?php if($t1=="1") echo "X"; ?></td>
                                    <?php }?>
                            </tr>
                        <?php
						 } ?>
                       		<tr>
                                <td>Department-Wide Outcomes</td>
                           <?php
						   $frstc=substr($cno,0,1);
						   if($frstc=="C")
						   		$data=ExecuteNonQuery("select * from matrix_".strtolower($version)." where cno='".$cno."'");
							else
								$data=ExecuteNonQuery("select * from gmatrix_".strtolower($version)." where cno='".$cno."'");	
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
						$data=ExecuteNonQuery("select * from fclo where csid=".$_POST["ddlcnm"]." and uid=".$_SESSION["userid"]." and semid=".$_SESSION["ddlsem4"]);
							$i=mysqli_num_rows($data);
							if($i!=0)
							{
								while($info=mysqli_fetch_assoc($data))
								{
								?>
									<td><?php echo $info["docm"];?></td>
						   <?php }
							}
							?>
                        	</tr> 
                           <tr>
                                <td>Program-Specific Outcomes</td>
                                <?php
							   $frstc=substr($cno,0,1);
								if($frstc=="C")
									$data=ExecuteNonQuery("select * from matrix_".strtolower($version)." where cno='".$cno."'");
								else
									$data=ExecuteNonQuery("select * from gmatrix_".strtolower($version)." where cno='".$cno."'");			 
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
							<?php	}?>
							 <?php
						 $data=ExecuteNonQuery("select * from fclo where csid=".$_POST["ddlcnm"]." and uid=".$_SESSION["userid"]." and semid=".$_SESSION["ddlsem4"]);
							$i=mysqli_num_rows($data);
							
							if($i!=0)
							{
						 		   while($info=mysqli_fetch_assoc($data))
								   {
                               ?>
                                  	<td><?php echo $info["pocm"];?></td>
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
			if(isset($_POST["ddlcnm"]))
			$gp=GetSingleField("select gradingpolicy from section where csid=".$_POST["ddlcnm"]." and uid=".$_SESSION["userid"]." and semid=".$_SESSION["ddlsem4"],"gradingpolicy");
			 ?>
            	<td>Grading Policy:</td>
            	
           		<td style="text-align:center">
            <?php if(isset($gp) && $gp!="") echo str_replace("<table>","<table border='1'>",$gp);?>
                	
 	                </td>
            </tr>
            <tr>
           
            	<td>Course Policies:</td>
            	
           		<td>
                   <?php
				   $sql="select coursepolicy from section where csid=".$_POST["ddlcnm"]." and uid=".$_SESSION["userid"]." and semid=".$_SESSION["ddlsem4"];
					$cp=GetSingleField($sql,"coursepolicy");
					echo $cp;
			 ?>

                     </td>
            </tr>
            <tr>
            	<td>Attendance Policies:</td>
            	
           		<td>
                 <?php
			if(isset($_POST["ddlcnm"]))
			$attp=GetSingleField("select attpolicy from section where csid=".$_POST["ddlcnm"]." and uid=".$_SESSION["userid"]." and semid=".$_SESSION["ddlsem4"],"attpolicy");
			echo $attp;
			 ?>
             <!-- attendance policy text area-->
                    </td>
            </tr>
            <tr>
            
            	<td>Academic Integrity:</td>
            	
           		<td>
                  <?php
			if(isset($_POST["ddlcnm"]))
			$ai=GetSingleField("select academicintegrity from section where csid=".$_POST["ddlcnm"]." and uid=".$_SESSION["userid"]." and semid=".$_SESSION["ddlsem4"],"academicintegrity");
			 ?>
              <?php if(isset($ai) && $ai!="") echo $ai;?>
	                </td>
            </tr>
       		<tr>
            	<td>Course Calendar with Topics:</td>
            	
           		<td>
                <?php
			if(isset($_POST["ddlcnm"]))
			$cctopics=GetSingleField("select coursetopics from section where csid=".$_POST["ddlcnm"]." and uid=".$_SESSION["userid"]." and semid=".$_SESSION["ddlsem4"],"coursetopics");
			 ?>
                <?php if(isset($cctopics) && $cctopics!="") echo $cctopics;?>
           </td>
            </tr>
             </table>
            </textarea>

               <script>
							// Replace the <textarea id="editor1"> with a CKEditor
							// instance, using default configuration.
							CKEDITOR.config.toolbar_MA=[ ['Source','-','Cut','Copy','Paste','-','Undo','Redo','RemoveFormat','-','Link','Unlink','Anchor','-','Image','Table','HorizontalRule','SpecialChar'], '/', ['Format','Templates','Bold','Italic','Underline','-','Superscript','-',['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],'-','NumberedList','BulletedList','-','Outdent','Indent'] ];
							CKEDITOR.config.height="3000px";
							CKEDITOR.replace( 'tafinal',
            {   toolbar:'MA'    }
         );
							//CKEDITOR.replace( 'tacdetails' );
						</script>
                        <?php }?>
            </form>
    </div>
</div>
<div id="footer">
	<?php include("footer.html"); ?>
</div>
</div>
</body>
</html>