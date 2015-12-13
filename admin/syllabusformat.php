<?php
	ob_start();
	ini_set('display_errors','off');
	require_once("includes/session.php");

	include_once("includes/form_functions.php");
	require_once("includes/functions.php");
	include("html_to_doc.inc.php");

	include_once("includes/DataAccess.php");
			$str="";
			$syllabusdata=ExecuteNonQuery("select * from section where semid=".$_SESSION['ddlsbssem']." and active=1");
			$sylarr=array();
			$cnt=0;
			while($syllabusinfo=mysqli_fetch_assoc($syllabusdata))
			{
				$version=GetSingleField("select version from fileinfo where active=1","version");
				$cno=GetSingleField("select courseno from courses where cid=(select cid from course_section where csid=".$syllabusinfo["csid"].")","courseno");
				$cnm=GetSingleField("select coursename from courses where courseno like '".$cno."'","coursename");
				$desc=GetSingleField("select description from courses where courseno like '".$cno."'","description");
				$credits=GetSingleField("select credits from courses where courseno like '".$cno."'","credits");
				$sem=GetSingleField("select semname from semester where active=1","semname");
				$pid=GetSingleField("select prereqid from courses  where courseno like '".$cno."'","prereqid");
				$cid=GetSingleField("select coreqid from courses  where courseno like '".$cno."'","coreqid");
				$prereq=GetSingleField("select pcname from pre_req  where prereqid=$pid","pcname");
				$coreq=GetSingleField("select ccname from co_req  where coreqid=$cid","ccname");
				$sql="select starttime,endtime,cday,building,roomno from facultyhours where csid=".$syllabusinfo["csid"]." and semid=".$_SESSION["ddlsbssem"];
				 $web=GetSingleField('select website from section where csid='.$syllabusinfo["csid"],'website');
			$reqmaterials=GetSingleField('select reqmaterials from section where csid='.$syllabusinfo["csid"],'reqmaterials');
		
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
				$str="<table width='100%'><tr>
						 <td><h3><b>Gannon University</b></h3></td>
						<td style='text-align:right'><h3><b>Department of Computer and Information Science</b></h3></td>
					   </tr> 
					   </table>
					<table style='width:100%' border='1'>
				<tr>
				<td>
				
					   <table>";
					$data=ExecuteNonQuery('select * from users where uid='.$syllabusinfo['uid']);
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
					  $data1=ExecuteNonQuery('select starttime,endtime,cday from facultyhours where uid='.$syllabusinfo["uid"].' and semid='.$_SESSION['ddlsbssem'].' and type=\'office\'');
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
            </tr>
     	    <tr>
            	<td>Class Day:</td>
                
                <td>";
				if(in_array('M',$cday)) $str.='M ';
				if(in_array('T',$cday)) $str.='T ';
				if(in_array('W',$cday)) $str.='W '; 
				 if(in_array('Th',$cday)) $str.='Th '; 
				 if(in_array('F',$cday)) $str.='F '; 
				 if(in_array('Sat',$cday)) $str.='Sat '; 
				 if(in_array('Sun',$cday)) $str.='Sun '; 
                $str.="</td>
            </tr>
             <tr>
            	<td>Class Time:</td>
                
                <td>";
                   $str.=$sttimehr.':'.$sttimemin.' '.$sttimeampm."
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
					
					$cntrec=CountRecords("select cno from course_mappings where cno='".str_replace(' ','_',$cno)."' and version='".strtoupper($version)."'");
					$cnt2=CountRecords('select * from fclo where semid='.$_SESSION['ddlsem4'].' and uid='.$_SESSION['a_userid'].' and csid='.$_POST['ddlcnm']);
					
					if($cntrec>0)
					{
					$data2=ExecuteNonQuery("select mappingname from course_mappings where cno='".str_replace(' ','_',$cno)."' and version='".strtoupper($version)."'");
					$cnt=1;
					
							  while($info2 = mysqli_fetch_assoc($data2)) 
								 {
									$str.=$info2['mappingname'].'<br>';
					  		     }
				    }
					if($cnt2>0)
						{
							$data5=ExecuteNonQuery('select * from fclo where semid='.$_SESSION['ddlsem4'].' and uid='.$_SESSION['a_userid'].' and csid='.$_POST['ddlcnm']);
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
					$sql='select camid,camname,camdetails from cams where csid='.$syllabusinfo["csid"].' and semid='.$_SESSION["ddlsbssem"];
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
                   			$cnt=CountRecords("select * from course_mappings where cno='".str_replace(" ","_",$cno)."'");
							$cnt2=CountRecords('select * from fclo where semid='.$_SESSION["ddlsbssem"].' and csid='.$syllabusinfo["csid"]);
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
                      	$sql='select camid,camname from cams where csid='.$syllabusinfo["csid"].' and semid='.$_SESSION["ddlsbssem"];
							$data=ExecuteNonQuery($sql);

							 while($info = mysqli_fetch_assoc($data)) 
                 	  		 {
							$str.="	
                            <tr>
                            	<td>".$info['camname']."</td>";
								for($i=1;$i<=$myval;$i++)
                                    {
										$objnm='obj'.$i;
										$t1=GetSingleField('select '.$objnm.' from fcamm where camid='.$info['camid'].' and csid='.$syllabusinfo["csid"].' and semid='.$_SESSION['ddlsbssem'],$objnm);
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
						$data=ExecuteNonQuery('select * from fclo where csid='.$syllabusinfo["csid"].' and semid='.$_SESSION['ddlsbssem']);
							$i=mysqli_num_rows($data);
								while($info=mysqli_fetch_assoc($data))
								{
									$str.="<td>".$info['docm']."</td>";
								}
							$str.="
							</tr> 
                           <tr>
                                <td>Program-Specific Outcomes</td>";
                              $data=ExecuteNonQuery('select * from matrix_'.strtolower($version)." where cno='".str_replace(" ","_",$cno)."'");
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
							 $data=ExecuteNonQuery('select * from fclo where csid='.$syllabusinfo["csid"].' and semid='.$_SESSION['ddlsbssem']);
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
			$gp=GetSingleField("select gradingpolicy from section where csid=".$syllabusinfo["csid"]." and semid=".$_SESSION['ddlsbssem'],"gradingpolicy");
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
				   $sql="select coursepolicy from section where csid=".$syllabusinfo["csid"]." and semid=".$_SESSION['ddlsbssem'];
					$cp=GetSingleField($sql,"coursepolicy");
					$str.=$cp;
					$str.="</td></tr>
					<tr>
        		    	<td>Attendance Policies:</td>
						<td>";
					$attp=GetSingleField("select attpolicy from section where csid=".$syllabusinfo["csid"]." and semid=".$_SESSION['ddlsbssem'],"attpolicy");
					$str.=$attp;
			
                    $str.="</td></tr>
					 <tr>
            
            	<td>Academic Integrity:</td>
            	
           		<td>";
			$ai=GetSingleField("select academicintegrity from section where csid=".$syllabusinfo["csid"]." and  semid=".$_SESSION['ddlsbssem'],"academicintegrity");
					$str.=$ai;
	                $str.="</td></tr>
					<tr>
            	<td>Course Calendar with Topics:</td>
            	
           		<td>";
			$cctopics=GetSingleField("select coursetopics from section where csid=".$syllabusinfo["csid"]." and  semid=".$_SESSION['ddlsbssem'],"coursetopics");
			$str.=$cctopics;
           $str.="</td>
            </tr></table>";
						$sylarr[$cnt]="tmpfileloc/".$syllabusinfo["sectionid"].".doc";
						$htmltodoc= new HTML_TO_DOC();
						$htmltodoc->createDoc($str,$sylarr[$cnt]);
						$cnt++;
					
				
			echo $str;
			}

			//----------------Creating zip
			/*			$result = create_zip($sylarr,'tmpfileloc/test.zip');
						// We'll be outputting a PDF
						header('Content-type: application/zip');
						// It will be called downloaded.pdf
						header('Content-Disposition: attachment; filename=test.zip');
						// The PDF source is in original.pdf
						readfile('test.zip');*/
						//	return $str;
			//-----------------------------
	?>