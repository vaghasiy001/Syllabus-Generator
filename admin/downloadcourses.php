<?php
	ob_start();
	ini_set('display_errors','off');
	require_once("includes/session.php");
	include_once("includes/form_functions.php");
	require_once("includes/functions.php");
	include("html_to_doc.inc.php");
	
	include_once("includes/DataAccess.php");
?>
<?php	if (!logged_in()) {
		redirect_to("index.php");
	}
	
	function create_zip($files = array(),$destination = '',$overwrite = false) {
	//if the zip file already exists and overwrite is false, return false
	if(file_exists($destination) && !$overwrite) { return false; }
	//vars
	$valid_files = array();
	//if files were passed in...
	if(is_array($files)) {
		//cycle through each file
		foreach($files as $file) {
			//make sure the file exists
			if(file_exists($file)) {
				$valid_files[] = $file;
			}
		}
	}
	//if we have good files...
	if(count($valid_files)) {
		//create the archive
		$zip = new ZipArchive();
		if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
			return false;
		}
		//add the files
		foreach($valid_files as $file) {
			$zip->addFile($file,$file);
		}
		//debug
		//echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;
		
		//close the zip -- done!
		$zip->close();
		
		//check to make sure the file exists
		return file_exists($destination);
	}
	else
	{
		return false;
	}
}//creating zip
		
function recursiveRemoveDirectory($directory)
{
    foreach(glob("{$directory}/*") as $file)
    {
        if(is_dir($file)) { 
            recursiveRemoveDirectory($file);
        } else {
            unlink($file);
        }
    }
    rmdir($directory);
}
	function filldata($syllabustmp)
{
	recursiveRemoveDirectory("tmpfilesloc");

	if($syllabustmp=="All")
	{
			$str="";
			$syllabusdata=ExecuteNonQuery("select * from section where semid=".$_SESSION['ddlsbssem']." and active=1");
			$sylarr=array();
			$sbscnt=0;
			
			while($syllabusinfo=mysqli_fetch_assoc($syllabusdata))
			{
				$cno=GetSingleField("select courseno from courses where cid=(select cid from course_section where csid=".$syllabusinfo["csid"].")","courseno");
				$cnot=substr($cno,0,1);
				if($cnot=="C")
					$version=GetSingleField("select version from fileinfo where active=1 and ftype='matrix'","version");
				else
					$version=GetSingleField("select version from fileinfo where active=1 and ftype='gmatrix'","version");
			
				$cnm=GetSingleField("select coursename from courses where courseno like '".$cno."'","coursename");
				$desc=GetSingleField("select description from courses where courseno like '".$cno."'","description");
				$credits=GetSingleField("select credits from courses where courseno like '".$cno."'","credits");
				$sem=GetSingleField("select semname from semester where active=1","semname");
				$pid=GetSingleField("select prereqid from courses  where courseno like '".$cno."'","prereqid");
				$cid=GetSingleField("select coreqid from courses  where courseno like '".$cno."'","coreqid");
				$prereq=GetSingleField("select pcname from pre_req  where prereqid=$pid","pcname");
				$coreq=GetSingleField("select ccname from co_req  where coreqid=$cid","ccname");
				$sql="select starttime,endtime,cday,roomno from facultyhours where uid=".$syllabusinfo["uid"]." and semid=".$_SESSION["ddlsbssem"]." and csid=".$syllabusinfo["csid"];
				 $web=GetSingleField('select website from section where sectionid='.$syllabusinfo["sectionid"],'website');
			$reqmaterials=GetSingleField('select reqmaterials from section where sectionid='.$syllabusinfo["sectionid"],'reqmaterials');
				$uid=$syllabusinfo["uid"];
				$dt2=ExecuteNonQuery($sql);
				
				while($inf2 = mysqli_fetch_assoc($dt2)) 
				{
					$classloc=$inf2["roomno"];
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
				$str="<table width='100%'><tr>
						 <td><h3><b>Gannon University</b></h3></td>
						<td style='text-align:right'><h3><b>Department of Computer and Information Science</b></h3></td>
					   </tr> 
					   </table>
					<table style='width:100%' border='1'>
				<tr>
				<td>
				
					   <table>";
					$data=ExecuteNonQuery('select * from users where uid='.$uid);
							$inst='';
							$offrno='';
							$ph='';
							$email='';
							while($info=mysqli_fetch_assoc($data))
							{
								$inst=$info['salutation'].' '.$info['firstname'].$info['lastname'];
								$offrno=$info['office'];
								$ph=$info['officeno'];
								$email=$info['email'];
							}
						$str.="<tr><td><b>Instructor:</b></td>
						<td>".$inst."</td>"."</tr><tr>
						<td><b>Office:</b></td>
						<td>".$offrno."</td>
						</tr>
						<tr>
						<td><b>Phone:</b></td>
						<td>".$ph."</td>
						</tr>
						<tr>
						<td><b>Email:</b></td>
						<td>".$email."</td>
						</tr>
					   </table>
					   </td>
					   <td>
					   <table>
						<tr>
						<td colspan='2'><b>Office Hours</b></td>
						</tr>";
					  $data1=ExecuteNonQuery('select starttime,endtime,cday from facultyhours where uid='.$uid.' and semid='.$_SESSION['ddlsbssem'].' and type=\'office\'');
						  while($info1=mysqli_fetch_assoc($data1))
						{
							 $str.="<tr>
									<td>". $info1['cday'].':'."</td>".
								   "<td>".$info1['starttime'].':'.$info1['endtime']."</td>
								</tr>";
						}
							$str.="</table></td>
        					 </tr>
        					 </table>
        	<table border='1'>
	        <tr>
            	<td>Course Title:</td>
                <td>";
				if(isset($cnm) && $cnm!='') $str.=$cnm;
				$str.="</td>".
            "</tr>
        	<tr>
            	<td>Credit Hours:</td>
                <td>";
				if(isset($credits) && $credits!='') $str.=$credits;
				$str.=' credit(s)'."</td>"."
             </tr>";
			 $str.="<tr>
            	<td>Semester:</td>
                <td>";
				if(isset($sem) && $sem!='') $str.=$sem;
				$str.="</td>
            </tr>
         	<tr>
            	<td>Class Location:</td>
                <td>";
				if(isset($classloc) && $classloc!='')
				$str.=$classloc;
				$str.="</td></tr>";
				$sday="";
     	   	if(in_array('M',$cday)) $sday.='M ';
				if(in_array('T',$cday)) $sday.='T ';
				if(in_array('W',$cday)) $sday.='W '; 
				 if(in_array('Th',$cday)) $sday.='Th '; 
				 if(in_array('F',$cday)) $sday.='F '; 
			    $str.="
             <tr>
            	<td>Class Time:</td>
                <td>";
                   $str.=$sday." ".$sttimehr.':'.$sttimemin.' '.$sttimeampm."
               <b> to</b>".$entimehr.':'.$entimemin.' '.$entimeampm."</option>
                   
                   </td>
            </tr> <tr>
            		<td>Course Description:</td>
                    
            		<td>";

									   if(isset($desc) && $desc!='')
									   {
										   $str.=$desc;
									   }
					$str.="</td></tr>";
			$str.="<tr>
            		<td>Course Learning Objectives:</td>
                    
                    <td>
                    The student will be able to<br>";
					$cnot=substr($cno,0,1);
					if($cnot=="C")
						$cntrec=CountRecords("select cno from course_mappings where cno='".str_replace(' ','_',$cno)."' and version='".strtoupper($version)."'");
					else
							$cntrec=CountRecords("select cno from gcourse_mappings where cno='".str_replace(' ','_',$cno)."' and version='".strtoupper($version)."'");
							
					$cnt2=CountRecords('select * from fclo where semid='.$_SESSION['ddlsbssem'].' and csid='.$syllabusinfo["csid"]." and uid=".$uid);
					
					if($cntrec>0)
					{
					if($cnot=="C")
						$data2=ExecuteNonQuery("select mappingname from course_mappings where cno='".str_replace(' ','_',$cno)."' and version='".strtoupper($version)."'");
					else
						$data2=ExecuteNonQuery("select mappingname from gcourse_mappings where cno='".str_replace(' ','_',$cno)."' and version='".strtoupper($version)."'");				
					
					$cnt=1;
					
							  while($info2 = mysqli_fetch_assoc($data2)) 
								 {
									$str.=$info2['mappingname'].'<br>';
					  		     }
				    }
					if($cnt2>0)
						{
							$data5=ExecuteNonQuery('select * from fclo where semid='.$_SESSION['ddlsbssem'].' and uid='.$uid.' and csid='.$syllabusinfo["csid"]);
							 while($info5 = mysqli_fetch_assoc($data5)) 
							 {
									$str.=$info5['name'].'<br>';
							  }
						}
					$str.="	
                    </td>
            </tr>";
				$str.="<tr>
            		<td>Co-requisites:</td>
            		
            		<td>";
					if(isset($coreq) && $coreq!='') $str.=$coreq;
					$str.="</td>
            </tr>
           	<tr>
            		<td>Prerequisites:</td>
            		
            		<td>";
					if(isset($prereq) && $prereq!='') $str.=$prereq;
					$str.="</td></tr>";
			//echo $str;
			$str.="<tr>
         			<td>Resources:</td>
            		<td>$web       </td>
            </tr>
			<tr>	<td>Required Textbooks:</td>
            		
            		<td>$reqmaterials
          		    </td>
            </tr>";
			 $str.=" <tr>
            		<td>Course Assessment Method:</td>
            		<td>";
					$cnt=0;
					$sql='select camid,camname,camdetails from cams where csid='.$syllabusinfo["csid"].' and semid='.$_SESSION["ddlsbssem"]." and uid=".$uid;
					$data3=ExecuteNonQuery($sql);
					//echo $sql;
					$cntrec1=mysqli_num_rows($data3);
					$cnt=mysqli_num_rows($data3);
				//	echo $cnt;
				    	$str.="<div>
 	             		<table align='left' id='camtable' style='text-align:left;' border='1' >";
                        	
						if($cnt>0)
							{
								$cnt=1;
								 while($info3 = mysqli_fetch_assoc($data3)) 
                   		 		{
									$str.="  <tr>
                                        <td>".$info3['camname']."</td>
                                        <td width='100%'>".$info3['camdetails']."
                                     </td>
                                  </tr>";
                             
			          	 		}
							}
                       $str.=" </table>
 						
                        </div>
                    </td>
            </tr>";
			   $str.=" <tr>
            		<td>Assessment Method Mappings:</td>
            		
            		<td>";
			//		$sql="select * from course_mappings where cno='".str_replace(" ","_",$cno)."'";
				//	echo $sql;
						$cnot=substr($cno,0,1);
							if($cnot=="C")
	                   			$cnt=CountRecords("select * from course_mappings where cno='".str_replace(" ","_",$cno)."'");
							else
								$cnt=CountRecords("select * from gcourse_mappings where cno='".str_replace(" ","_",$cno)."'");
								
							$cnt2=CountRecords('select * from fclo where semid='.$_SESSION["ddlsbssem"].' and csid='.$syllabusinfo["csid"]." and uid=".$uid);
							$myval=$cnt+$cnt2;
                     //      echo $myval;
						   $str.="<table align='left' border='1' style='text-align:center'>
                           	<tr>
                            	<td rowspan='2'>ASSESSMENT METHODS</td>
                                        	<td colspan=". $myval." align='center'>
                                            	OBJECTIVES
                          		          </td>
                            </tr>
                            <tr>";
                   		       for($i=1;$i<=$cnt+$cnt2;$i++)
								{
						      	$str.="<td>".$i."</td>";
 								}          
                            $str.="</tr>";
                      	$sql='select camid,camname from cams where csid='.$syllabusinfo["csid"].' and semid='.$_SESSION["ddlsbssem"]." and uid=".$uid;
							$data=ExecuteNonQuery($sql);

							 while($info = mysqli_fetch_assoc($data)) 
                 	  		 {
							$str.="	
                            <tr>
                            	<td>".$info['camname']."</td>";
								for($i=1;$i<=$myval;$i++)
                                    {
										$objnm='obj'.$i;
$t1=GetSingleField('select '.$objnm.' from fcamm where camid='.$info['camid'].' and csid='.$syllabusinfo["csid"].' and semid='.$_SESSION['ddlsbssem']." and uid=".$uid,$objnm);
									    $str.="<td>";
										if($t1=='1') 
											$str.='X';
										else
											$str.='';
										$str.="</td>";
									}
                            $str.="</tr>";
                        } 
                       		$str.="<tr>
                                <td>Department-Wide Outcomes</td>";
							$cnot=substr($cno,0,1);	
							if($cnot=="C")
	                           $data=ExecuteNonQuery('select * from matrix_'.strtolower($version)." where cno like '".str_replace(" ","_",$cno)."'");
							else
							     $data=ExecuteNonQuery('select * from matrix_'.strtolower($version)." where cno like '".str_replace(" ","_",$cno)."'");
//						  echo mysqli_num_rows($data);
	//					  echo $sql;
						   while($info=mysqli_fetch_assoc($data))
							{
								$temp='';
								if($info['col0']=='1')
								{
									$temp='CIS-1';
								}
								if($info['col1']=='1')
								{
									if($temp=='')
										$temp='CIS-2';
									else
										$temp.=','.'CIS-2';
								}
								if($info['col2']=='1')
								{
									if($temp=='')
										$temp='CIS-3';
									else
										$temp.=','.'CIS-3';
								}
								if($info['col3']=='1' || $info['col4']=='1' || $info['col5']=='1' || $info['col6']=='1' || $info['col7']=='1')
								{
									if($temp=='')
										$temp='CIS-4';
									else
										$temp.=','.'CIS-4';
								}
								if($info['col8']=='1')
								{
									if($temp=='')
										$temp='CIS-5';
									else
									$temp.=','.'CIS-5';
								}
								if($info['col9']=='1' || $info['col9']=='1')
								{
									if($temp=='')
										$temp='CIS-6';
									else
										$temp.=','.'CIS-6';		
								}
							$str.="<td>$temp</td>";
						}
						$data=ExecuteNonQuery('select * from fclo where csid='.$syllabusinfo["csid"].' and semid='.$_SESSION['ddlsbssem']." and uid=".$uid);
							$i=mysqli_num_rows($data);
								while($info=mysqli_fetch_assoc($data))
								{
									$str.="<td>".$info['docm']."</td>";
								}
							$str.="
							</tr> 
                           <tr>
                                <td>Program-Specific Outcomes</td>";
								$cnot=substr($cno,0,1);	
								if($cnot=="C")
									  $data=ExecuteNonQuery('select * from matrix_'.strtolower($version)." where cno='".str_replace(" ","_",$cno)."'");
								else
									  $data=ExecuteNonQuery('select * from gmatrix_'.strtolower($version)." where cno='".str_replace(" ","_",$cno)."'");
						 	 while($info=mysqli_fetch_assoc($data))
								{
									$temp='';
									if($info['col11']=='1' || $info['col12']=='1')
									{
										if($temp=='')
											$temp='CS-1';
										else
											$temp.=','.'CS-1';
									}
									if($info['col13']=='1' || $info['col14']=='1')
									{
										if($temp=='')
											$temp='CS-2';
										else
											$temp.=','.'CS-2';
									}
									if($info['col15']=='1')
									{
										if($temp=='')
											$temp='CS-3';
										else
											$temp.=','.'CS-3';
									}
									if($info['col16']=='1')
									{
										if($temp=='')
											$temp='CS-4';
										else
											$temp.=','.'CS-4';
									}
									if($info['col17']=='1')
									{
										if($temp=='')
											$temp='CS-5';
										else
											$temp.=','.'CS-5';
									}
									
									if($info['col18']=='1' || $info['col19']=='1' || $info['col20']=='1' || $info['col21']=='1')
									{
										if($temp=='')
											$temp='IS-1';
										else
											$temp.=','.'IS-1';
									}
									if($info['col22']=='1')
									{
										if($temp=='')
											$temp='IS-2';
										else
											$temp.=','.'IS-2';
									}
									if($info['col23']=='1' || $info['col24']=='1')
									{
										if($temp=='')
											$temp='IS-3';
										else
											$temp.=','.'IS-3';
									}
									if($info['col25']=='1')
									{
										if($temp=='')
											$temp='IS-4';
										else
											$temp.=','.'IS-4';
									}
									if($info['col26']=='1' || $info['col27']=='1' || $info['col28']=='1' || $info['col29']=='1' || $info['col30']=='1' || $info['col31']=='1')
									{
										if($temp=='')
											$temp='SE-1';
										else
											$temp.=','.'SE-1';
									}
									if($info['col32']=='1')
									{
										if($temp=='')
											$temp='SE-2';
										else
											$temp.=','.'SE-2';
									}
									if($info['col33']=='1')
									{
										if($temp=='')
											$temp='SE-3';
										else
											$temp.=','.'SE-3';
									}
										$str.="<td>$temp</td>";
							}
							 $data=ExecuteNonQuery('select * from fclo where csid='.$syllabusinfo["csid"].' and semid='.$_SESSION['ddlsbssem']." and uid=".$uid);
							$i=mysqli_num_rows($data);
							
								   while($info=mysqli_fetch_assoc($data))
								   {
                                  	$str.="<td>".$info['pocm']."</td>";
                           			}
                           $str.="</tr> 
                           </table>
	                </td>
            </tr>
			  <tr>";
			$gp=GetSingleField("select gradingpolicy from section where csid=".$syllabusinfo["csid"]." and semid=".$_SESSION['ddlsbssem']." and uid=".$uid,"gradingpolicy");
			$str.="      
    	   	<td>Grading Policy:</td>
           	<td style='text-align:center'>";
            if(isset($gp) && $gp!="") $str.=str_replace("<table>","<table border='1'>",$gp);
                	$str.="
 	                </td>
            </tr>
			 <tr>
           
            	<td>Course Policies:</td>
            	
           		<td>";
				   $sql="select coursepolicy from section where csid=".$syllabusinfo["csid"]." and semid=".$_SESSION['ddlsbssem']." and uid=".$uid;
					$cp=GetSingleField($sql,"coursepolicy");
					$str.=$cp;
					$str.="</td></tr>
					<tr>
        		    	<td>Attendance Policies:</td>
						<td>";
					$attp=GetSingleField("select attpolicy from section where csid=".$syllabusinfo["csid"]." and semid=".$_SESSION['ddlsbssem']." and uid=".$uid,"attpolicy");
					$str.=$attp;
			
                    $str.="</td></tr>
					 <tr>
            
            	<td>Academic Integrity:</td>
            	
           		<td>";
			$ai=GetSingleField("select academicintegrity from section where csid=".$syllabusinfo["csid"]." and  semid=".$_SESSION['ddlsbssem']." and uid=".$uid,"academicintegrity");
					$str.=$ai;
	                $str.="</td></tr>
					<tr>
            	<td>Course Calendar with Topics:</td>
            	
           		<td>";
			$cctopics=GetSingleField("select coursetopics from section where csid=".$syllabusinfo["csid"]." and  semid=".$_SESSION['ddlsbssem']." and uid=".$uid,"coursetopics");
			$csnm=GetSingleField("select sections from course_section where csid=".$syllabusinfo["csid"],"sections");
			$fnm=GetSingleField("select firstname from users where uid=".$uid,"firstname");
			$lnm=GetSingleField("select lastname from users where uid=".$uid,"lastname");
			$str.=$cctopics;
	       $str.="</td>
            </tr></table>";
			if (!mkdir("tmpfilesloc", 0777, true)) {
						$sylarr[$sbscnt]="tmpfilesloc/".$csnm."_".substr($fnm,0,1).substr($lnm,0,1).".doc";
						$htmltodoc= new HTML_TO_DOC();
						$htmltodoc->createDoc($str,$sylarr[$sbscnt]);
						$sbscnt++;	
					}
			}
			
						if (!mkdir("tmpzips", 0777, true)) {
								//----------------Creating zip
								$file='tmpzips/allcourses.zip';
								if(file_exists($file)){
								    unlink($file);
								}
							$result = create_zip($sylarr,'tmpzips/allcourses.zip');
						}
						
						// We'll be outputting a PDF
						header('Content-type: application/zip');
						// It will be called downloaded.pdf
						header('Content-Disposition: attachment; filename=allcourses.zip');
						// The PDF source is in original.pdf
						readfile('tmpzips/allcourses.zip');
						//	return $str;
					//	rmdir("tmpfilesloc");
			//-----------------------------
	}
	else
	{
		
	}
	
}
?>
<?php
if(!isset($_SESSION["ddlsbssem"]))
{
	redirect_to("welcome.php");
}
if(isset($_POST["Submit"]))
{
	$selvalue=$_POST['csec'];
	if(empty($selvalue[0])==1)
	{
		$str=filldata("All");
	}
	else
	{

		$str="";
			$sylarr=array();
			$sbscnt=0;
				
			foreach ($selvalue as $value)
			 {
				$uid=GetSingleField("select uid from section where sectionid=".$value,"uid");				 
				$csid=GetSingleField("select csid from section where sectionid=".$value,"csid");
				$cno=GetSingleField("select courseno from courses where cid=(select cid from course_section where csid=".$csid.")","courseno");
			$cnot=substr($cno,0,1);
			
			if($cnot=="C")
				$version=GetSingleField("select version from fileinfo where active=1 and ftype='matrix'","version");
			else
				$version=GetSingleField("select version from fileinfo where active=1 and ftype='gmatrix'","version");
	
				$cnm=GetSingleField("select coursename from courses where courseno like '".$cno."'","coursename");
				$desc=GetSingleField("select description from courses where courseno like '".$cno."'","description");
				$credits=GetSingleField("select credits from courses where courseno like '".$cno."'","credits");
				$sem=GetSingleField("select semname from semester where active=1","semname");
				$pid=GetSingleField("select prereqid from courses  where courseno like '".$cno."'","prereqid");
				$cid=GetSingleField("select coreqid from courses  where courseno like '".$cno."'","coreqid");
				$prereq=GetSingleField("select pcname from pre_req  where prereqid=$pid","pcname");
				$coreq=GetSingleField("select ccname from co_req  where coreqid=$cid","ccname");
				$sql="select starttime,endtime,cday,roomno from facultyhours where csid=".$csid." and semid=".$_SESSION["ddlsbssem"]." and uid=".$uid;
				 $web=GetSingleField('select website from section where sectionid='.$value,'website');
			$reqmaterials=GetSingleField('select reqmaterials from section where sectionid='.$value,'reqmaterials');

				$dt2=ExecuteNonQuery($sql);
				
				while($inf2 = mysqli_fetch_assoc($dt2)) 
				{
					$classloc=$inf2["roomno"];
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
				$str="<table width='100%'><tr>
						 <td><h3><b>Gannon University</b></h3></td>
						<td style='text-align:right'><h3><b>Department of Computer and Information Science</b></h3></td>
					   </tr> 
					   </table>
					<table style='width:100%' border='1'>
				<tr>
				<td>
				
					   <table>";
					$data=ExecuteNonQuery('select * from users where uid='.$uid);
							$inst='';
							$offrno='';
							$ph='';
							$email='';
							while($info=mysqli_fetch_assoc($data))
							{
								$inst=$info['salutation'].' '.$info['firstname'].$info['lastname'];
								$offrno=$info['office'];
								$ph=$info['officeno'];
								$email=$info['email'];
							}
						$str.="<tr><td><b>Instructor:</b></td>
						<td>".$inst."</td>"."</tr><tr>
						<td><b>Office:</b></td>
						<td>".$offrno."</td>
						</tr>
						<tr>
						<td><b>Phone:</b></td>
						<td>".$ph."</td>
						</tr>
						<tr>
						<td><b>Email:</b></td>
						<td>".$email."</td>
						</tr>
					   </table>
					   </td>
					   <td>
					   <table>
						<tr>
						<td colspan='2'><b>Office Hours</b></td>
						</tr>";
					  $data1=ExecuteNonQuery('select starttime,endtime,cday from facultyhours where uid='.$uid.' and semid='.$_SESSION['ddlsbssem'].' and type=\'office\'');
						  while($info1=mysqli_fetch_assoc($data1))
						{
							 $str.="<tr>
									<td>". $info1['cday'].':'."</td>".
								   "<td>".$info1['starttime'].':'.$info1['endtime']."</td>
								</tr>";
						}
							$str.="</table></td>
        					 </tr>
        					 </table>
        	<table border='1'>
	        <tr>
            	<td>Course Title:</td>
                <td>";
				if(isset($cnm) && $cnm!='') $str.=$cnm;
				$str.="</td>".
            "</tr>
        	<tr>
            	<td>Credit Hours:</td>
                <td>";
				if(isset($credits) && $credits!='') $str.=$credits;
				$str.=' credit(s)'."</td>"."
             </tr>";
			 $str.="<tr>
            	<td>Semester:</td>
                <td>";
				if(isset($sem) && $sem!='') $str.=$sem;
				$str.="</td>
            </tr>
         	<tr>
            	<td>Class Location:</td>
                <td>";
				if(isset($classloc) && $classloc!='')
				$str.=$classloc;
				$str.="</td>
            </tr>";
			 $str.="
             <tr>
            	<td>Class Time:</td>
                
                <td>";
				$sday="";
                if(in_array('M',$cday)) $sday='M ';
				if(in_array('T',$cday)) $sday.='T ';
				if(in_array('W',$cday)) $sday.='W '; 
				 if(in_array('Th',$cday)) $sday.='Th '; 
				 if(in_array('F',$cday)) $sday.='F '; 
				   $str.=$sday." ".$sttimehr.':'.$sttimemin.' '.$sttimeampm."
               <b> to</b>".$entimehr.':'.$entimemin.' '.$entimeampm."</option>
                   
                   </td>
            </tr> <tr>
            		<td>Course Description:</td>
                    
            		<td>";

									   if(isset($desc) && $desc!='')
									   {
										   $str.=$desc;
									   }
					$str.="</td></tr>";
			$str.="<tr>
            		<td>Course Learning Objectives:</td>
                    
                    <td>
                    The student will be able to<br>";
					$cnot=substr($cno,0,1);
					if($cnot=="C")
						$cntrec=CountRecords("select cno from course_mappings where cno='".str_replace(' ','_',$cno)."' and version='".strtoupper($version)."'");
					else
						$cntrec=CountRecords("select cno from gcourse_mappings where cno='".str_replace(' ','_',$cno)."' and version='".strtoupper($version)."'");
		
					
					$cnt2=CountRecords('select * from fclo where semid='.$_SESSION['ddlsbssem'].' and csid='.$csid." and uid=".$uid);
					
					if($cntrec>0)
					{
					$cnot=substr($cno,0,1);
					if($cnot=="C")
						$data2=ExecuteNonQuery("select mappingname from course_mappings where cno='".str_replace(' ','_',$cno)."' and version='".strtoupper($version)."'");
					else
						$data2=ExecuteNonQuery("select mappingname from gcourse_mappings where cno='".str_replace(' ','_',$cno)."' and version='".strtoupper($version)."'");				
					$cnt=1;
					
							  while($info2 = mysqli_fetch_assoc($data2)) 
								 {
									$str.=$info2['mappingname'].'<br>';
					  		     }
				    }
					if($cnt2>0)
						{
							$data5=ExecuteNonQuery('select * from fclo where semid='.$_SESSION['ddlsbssem'].' and csid='.$csid." and uid=".$uid);
							 while($info5 = mysqli_fetch_assoc($data5)) 
							 {
									$str.=$info5['name'].'<br>';
							  }
						}
					$str.="	
                    </td>
            </tr>";
				$str.="<tr>
            		<td>Co-requisites:</td>
            		
            		<td>";
					if(isset($coreq) && $coreq!='') $str.=$coreq;
					$str.="</td>
            </tr>
           	<tr>
            		<td>Prerequisites:</td>
            		
            		<td>";
					if(isset($prereq) && $prereq!='') $str.=$prereq;
					$str.="</td></tr>";
			//echo $str;
			$str.="<tr>
         			<td>Resources:</td>
            		<td>$web       </td>
            </tr>
			<tr>	<td>Required Textbooks:</td>
            		
            		<td>$reqmaterials
          		    </td>
            </tr>";
			 $str.=" <tr>
            		<td>Course Assessment Method:</td>
            		<td>";
					$cnt=0;
					$sql='select camid,camname,camdetails from cams where csid='.$csid.' and semid='.$_SESSION["ddlsbssem"]." and uid=".$uid;
					$data3=ExecuteNonQuery($sql);
					//echo $sql;
					$cntrec1=mysqli_num_rows($data3);
					$cnt=mysqli_num_rows($data3);
				//	echo $cnt;
				    	$str.="<div>
 	             		<table align='left' id='camtable' style='text-align:left;' border='1' >";
                        	
						if($cnt>0)
							{
								$cnt=1;
								 while($info3 = mysqli_fetch_assoc($data3)) 
                   		 		{
									$str.="  <tr>
                                        <td>".$info3['camname']."</td>
                                        <td width='100%'>".$info3['camdetails']."
                                     </td>
                                  </tr>";
                             
			          	 		}
							}
                       $str.=" </table>
 						
                        </div>
                    </td>
            </tr>";
			   $str.=" <tr>
            		<td>Assessment Method Mappings:</td>
            		
            		<td>";
			//		$sql="select * from course_mappings where cno='".str_replace(" ","_",$cno)."'";
				//	echo $sql;
						$cnot=substr($cno,0,1);
						if($cnot=="C")
                   			$cnt=CountRecords("select * from course_mappings where cno='".str_replace(" ","_",$cno)."'");
						else
							$cnt=CountRecords("select * from gcourse_mappings where cno='".str_replace(" ","_",$cno)."'");
							
								
							$cnt2=CountRecords('select * from fclo where semid='.$_SESSION["ddlsbssem"].' and uid='.$uid.' and csid='.$csid);
							$myval=$cnt+$cnt2;
                     //      echo $myval;
						   $str.="<table align='left' border='1' style='text-align:center'>
                           	<tr>
                            	<td rowspan='2'>ASSESSMENT METHODS</td>
                                        	<td colspan=". $myval." align='center'>
                                            	OBJECTIVES
                          		          </td>
                            </tr>
                            <tr>";
                   		       for($i=1;$i<=$cnt+$cnt2;$i++)
								{
						      	$str.="<td>".$i."</td>";
 								}          
                            $str.="</tr>";
                      	$sql='select camid,camname from cams where csid='.$csid.' and semid='.$_SESSION["ddlsbssem"]." and uid=".$uid;
							$data=ExecuteNonQuery($sql);

							 while($info = mysqli_fetch_assoc($data)) 
                 	  		 {
							$str.="	
                            <tr>
                            	<td>".$info['camname']."</td>";
								for($i=1;$i<=$myval;$i++)
                                    {
										$objnm='obj'.$i;
										$t1=GetSingleField('select '.$objnm.' from fcamm where camid='.$info['camid'].' and csid='.$csid.' and semid='.$_SESSION['ddlsbssem'],$objnm);
									    $str.="<td>";
										if($t1=='1') 
											$str.='X';
										else
											$str.='';
										$str.="</td>";
									}
                            $str.="</tr>";
                        } 
                       		$str.="<tr>
                                <td>Department-Wide Outcomes</td>";
							$cnot=substr($cno,0,1);
							if($cnot=="C")	
	                           $data=ExecuteNonQuery('select * from matrix_'.strtolower($version)." where cno like '".str_replace(" ","_",$cno)."'");
							 else
							   $data=ExecuteNonQuery('select * from gmatrix_'.strtolower($version)." where cno like '".str_replace(" ","_",$cno)."'");
//						  echo mysqli_num_rows($data);
	//					  echo $sql;
						   while($info=mysqli_fetch_assoc($data))
							{
								$temp='';
								if($info['col0']=='1')
								{
									$temp='CIS-1';
								}
								if($info['col1']=='1')
								{
									if($temp=='')
										$temp='CIS-2';
									else
										$temp.=','.'CIS-2';
								}
								if($info['col2']=='1')
								{
									if($temp=='')
										$temp='CIS-3';
									else
										$temp.=','.'CIS-3';
								}
								if($info['col3']=='1' || $info['col4']=='1' || $info['col5']=='1' || $info['col6']=='1' || $info['col7']=='1')
								{
									if($temp=='')
										$temp='CIS-4';
									else
										$temp.=','.'CIS-4';
								}
								if($info['col8']=='1')
								{
									if($temp=='')
										$temp='CIS-5';
									else
									$temp.=','.'CIS-5';
								}
								if($info['col9']=='1' || $info['col9']=='1')
								{
									if($temp=='')
										$temp='CIS-6';
									else
										$temp.=','.'CIS-6';		
								}
							$str.="<td>$temp</td>";
						}
						$data=ExecuteNonQuery('select * from fclo where csid='.$csid.' and uid='.$uid.' and semid='.$_SESSION['ddlsbssem']);
							$i=mysqli_num_rows($data);
								while($info=mysqli_fetch_assoc($data))
								{
									$str.="<td>".$info['docm']."</td>";
								}
							$str.="
							</tr> 
                           <tr>
                                <td>Program-Specific Outcomes</td>";
							$cnot=substr($cno,0,1);
						if($cnot=="C")	
                            	  $data=ExecuteNonQuery('select * from matrix_'.strtolower($version)." where cno='".str_replace(" ","_",$cno)."'");
							else
								  $data=ExecuteNonQuery('select * from gmatrix_'.strtolower($version)." where cno='".str_replace(" ","_",$cno)."'");
						 	 while($info=mysqli_fetch_assoc($data))
								{
									$temp='';
									if($info['col11']=='1' || $info['col12']=='1')
									{
										if($temp=='')
											$temp='CS-1';
										else
											$temp.=','.'CS-1';
									}
									if($info['col13']=='1' || $info['col14']=='1')
									{
										if($temp=='')
											$temp='CS-2';
										else
											$temp.=','.'CS-2';
									}
									if($info['col15']=='1')
									{
										if($temp=='')
											$temp='CS-3';
										else
											$temp.=','.'CS-3';
									}
									if($info['col16']=='1')
									{
										if($temp=='')
											$temp='CS-4';
										else
											$temp.=','.'CS-4';
									}
									if($info['col17']=='1')
									{
										if($temp=='')
											$temp='CS-5';
										else
											$temp.=','.'CS-5';
									}
									
									if($info['col18']=='1' || $info['col19']=='1' || $info['col20']=='1' || $info['col21']=='1')
									{
										if($temp=='')
											$temp='IS-1';
										else
											$temp.=','.'IS-1';
									}
									if($info['col22']=='1')
									{
										if($temp=='')
											$temp='IS-2';
										else
											$temp.=','.'IS-2';
									}
									if($info['col23']=='1' || $info['col24']=='1')
									{
										if($temp=='')
											$temp='IS-3';
										else
											$temp.=','.'IS-3';
									}
									if($info['col25']=='1')
									{
										if($temp=='')
											$temp='IS-4';
										else
											$temp.=','.'IS-4';
									}
									if($info['col26']=='1' || $info['col27']=='1' || $info['col28']=='1' || $info['col29']=='1' || $info['col30']=='1' || $info['col31']=='1')
									{
										if($temp=='')
											$temp='SE-1';
										else
											$temp.=','.'SE-1';
									}
									if($info['col32']=='1')
									{
										if($temp=='')
											$temp='SE-2';
										else
											$temp.=','.'SE-2';
									}
									if($info['col33']=='1')
									{
										if($temp=='')
											$temp='SE-3';
										else
											$temp.=','.'SE-3';
									}
										$str.="<td>$temp</td>";
							}
							 $data=ExecuteNonQuery('select * from fclo where csid='.$csid.' and uid='.$uid.' and semid='.$_SESSION['ddlsbssem']);
							$i=mysqli_num_rows($data);
							
								   while($info=mysqli_fetch_assoc($data))
								   {
                                  	$str.="<td>".$info['pocm']."</td>";
                           			}
                           $str.="</tr> 
                           </table>
	                </td>
            </tr>
			  <tr>";
			$gp=GetSingleField("select gradingpolicy from section where sectionid=".$value,"gradingpolicy");
			$str.="      
    	   	<td>Grading Policy:</td>
           	<td style='text-align:center'>";
            if(isset($gp) && $gp!="") $str.=str_replace("<table>","<table border='1'>",$gp);
                	$str.="
 	                </td>
            </tr>
			 <tr>
           
            	<td>Course Policies:</td>
            	
           		<td>";
				   $sql="select coursepolicy from section where sectionid=".$value;
					$cp=GetSingleField($sql,"coursepolicy");
					$str.=$cp;
					$str.="</td></tr>
					<tr>
        		    	<td>Attendance Policies:</td>
						<td>";
					$attp=GetSingleField("select attpolicy from section where sectionid=".$value,"attpolicy");
					$str.=$attp;
			
                    $str.="</td></tr>
					 <tr>
            
            	<td>Academic Integrity:</td>
            	
           		<td>";
			$ai=GetSingleField("select academicintegrity from section where sectionid=".$value,"academicintegrity");
					$str.=$ai;
	                $str.="</td></tr>
					<tr>
            	<td>Course Calendar with Topics:</td>
            	
           		<td>";
			$cctopics=GetSingleField("select coursetopics from section where sectionid=".$value,"coursetopics");
			$csnm=GetSingleField("select sections from course_section where csid=".$csid,"sections");
			$fnm=GetSingleField("select firstname from users where uid=".$uid,"firstname");
			$lnm=GetSingleField("select lastname from users where uid=".$uid,"lastname");
			$str.=$cctopics;
	       $str.="</td>
            </tr></table>";
		//	echo $str;
			if (!mkdir("tmpfileloc", 0777, true)) {
						$sylarr[$sbscnt]="tmpfileloc/".$csnm."_".substr($fnm,0,1).substr($lnm,0,1).".doc";
						$htmltodoc= new HTML_TO_DOC();
						$htmltodoc->createDoc($str,$sylarr[$sbscnt]);
						$sbscnt++;	
					}
				 
			}
				if (!mkdir("tmpzip", 0777, true)) {
								//----------------Creating zip
						$file='tmpzip/allcourses.zip';
								if(file_exists($file)){
								    unlink($file);
								}
							$result = create_zip($sylarr,'tmpzip/allcourses.zip');
						}
						// We'll be outputting a PDF
						header('Content-type: application/zip');
						// It will be called downloaded.pdf
						header('Content-Disposition: attachment; filename=allcourses.zip');
						// The PDF source is in original.pdf
						readfile('tmpzip/allcourses.zip');
	}
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Download Courses</title>
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
                	<p>This page will allows you to download course syllabus</p>
                </div>
           	<div id="contentdetail">
			<form method="post" action="downloadcourses.php" name="form1">
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
                
        <?php
		//$cnt=CountRecords("select sections from course_section where semid=".$_SESSION["ddlsem1"]);
		//if($cnt==0)
		//{
			$data1 = ExecuteNonQuery('select sectionid,csid,name from section where semid='.$_SESSION["ddlsbssem"]." and active=1");
	//	}
		?>
                <tr>
   				<td>
                Sections</td>
                <td>:</td>
                <td>
                <select multiple size="15" name="csec[]">
            		<option value="">All</option>
				<?php
                    while($info1 = mysqli_fetch_assoc($data1)) 
                    {
					$csnm=GetSingleField("select sections from course_section where csid=".$info1["csid"],"sections");	
					?>	 
					   <option value="<?php echo $info1["sectionid"];?>"><?php echo "(".$csnm.")".$info1["name"]; ?></option>		
                    <?php
					}
                ?>   
                </select>
                </td>
   				 
                </tr>
                <tr>
                    <td colspan="3" align="center"><input type="submit" value="Download" name="Submit"></td>
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