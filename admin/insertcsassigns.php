<?php
ob_start();
ini_set("display_errors","off");
include("includes/DataAccess.php");
include("includes/connect.php");
include("includes/functions.php");
require_once("includes/session.php");	
if(!isset($_SESSION))
{
	session_start();
}
		$mainrow=0;
		$filename=$_SESSION["csfilenm"];
		$row = 1;
	//	$deptocmrow=$_POST["deptocmrow"];
		$tmp=0;
		$matrixfields=array(array());
			
		$filename= "csfu/".$filename;
		if (($handle = fopen($filename, "r")) != FALSE) 
		{
			while (($data = fgetcsv($handle, 10000, ",")) !== FALSE) {
				$cols=0;
				$num = count($data);
				
		//        echo "<p> $num fields in line $row: <br /></p>\n";
				$tmp=$row-1;
				$row++;
				
						for ($c=0; $c < $num; $c++)
						 {
						
						$matrixfields[$tmp][$cols]=$data[$c];
			//	       echo $data[$c] . "&nbsp;&nbsp;&nbsp;&nbsp;";
						$cols++;
						}
				}
			 fclose($handle);
		}
//	echo "<table>";
	for($i=1;$i<=$row-1;$i++)
	{
		$cntrec=CountRecords("select * from course_section where sections='".$matrixfields[$i][0]."'");
		if($cntrec==0)
		{
			$tmp=substr($matrixfields[$i][0],0,strlen($matrixfields[$i][0])-3);
			$tmp=str_replace("_"," ",$tmp);
		//	echo "<br>";
			$cntrec1=CountRecords("select * from courses where courseno='".$tmp."'");
			if($cntrec1==0)
			{
				if(!empty($matrixfields[$i][0]) && trim($matrixfields[$i][0])!="")
				{	
				
					$sql="insert into courses(courseno,coursename,description,prereqid,coreqid,credits,active,deptid)values('".$tmp."','".$matrixfields[$i][1]."','
				The course offers prenetation of topics that are emerging as the field of computer and information science changes. The objectives and content reflect the interests of the faculty and the students relative to the topic.',23,0,".$matrixfields[$i][7].",1,1)";
 				//	echo $sql;
				ExecuteNonQuery($sql);
				}
			}
			if(!empty($matrixfields[$i][0]) && trim($matrixfields[$i][0])!="")
			{
				$cntrec2=CountRecords("select * from course_section where sections like '".strtoupper($matrixfields[$i][0])."'");
				$cid=GetSingleField("select cid from courses where courseno like '".$tmp."'","cid");
				echo $tmp;
				if($cntrec2==0)
				{
					$sql="insert into course_section(sections,active,cid)values('".$matrixfields[$i][0]."',1,".$cid.")";
				//	echo $sql;
					ExecuteNonQuery($sql);
				}
			}
		}
		
	}
	$namelist=array();
	$nmcnt=0;
	for($i=1;$i<=$row-1;$i++)
	{
		if(trim($matrixfields[$i][0])!="")
		{
			$csid=GetSingleField("select csid from course_section where sections='".$matrixfields[$i][0]."'","csid");
			$sttime=$matrixfields[$i][12];
			$entime=$matrixfields[$i][13];
			$type="lec";
			$cday=$matrixfields[$i][14];
			$rno=$matrixfields[$i][15].$matrixfields[$i][16];
			$sid=$_SESSION["ddlcssem"];
			$tmp1=explode(" ",$matrixfields[$i][5]);
			if( count($tmp1)!=3)
			{
				$namelist[$nmcnt]=$matrixfields[$i][0];
				$nmcnt++;
				continue;
			}
//				print_r($tmp1);
			$sql="select * from users where salutation like '".$tmp1[0]."' and firstname like '".$tmp1[1]."%' and lastname like '".$tmp1[2]."'";
			//echo $sql."<br>";
			$cnt2=CountRecords($sql);
			if($cnt2!=0)
			{
				$sql="select uid from users where salutation like '".$tmp1[0]."' and firstname like '".$tmp1[1]."%' and lastname like '".$tmp1[2]."'";
				$uid=GetSingleField($sql,"uid");
				$cntrec3=CountRecords("select * from facultyhours where semid=".$sid." and uid=".$uid." and csid=".$csid);
				if($cntrec3==0)
				{
					$sql="insert into facultyhours(csid,starttime,endtime,type,cday,roomno,uid,semid)values(";
					$sql.="$csid,'$sttime','$entime','$type','$cday','$rno',$uid,$sid)";
					ExecuteNonQuery($sql);
				//	echo $sql;
					
					
					$sql="select camname from cams where csid=$csid and uid=$uid and semid=$sid";
					$cnt=CountRecords($sql);
					if($cnt==0)
					{
						$sql1="insert into cams(camname,camdetails,csid,uid,semid)values('Assignments:','Students will periodically be assigned homework assignments related to software design principles, patterns and architecture, Due dates will be announced in class.',$csid,$uid,$sid),
							('Examinations/Quizzes:','Students will periodically take in-class examinations covering knowledge and application of software design principles,design patterns,architecture and related topics from the assigned readings,course notes,and homework.',$csid,$uid,$sid),
							('Project:','The project shall be staffed by individual student who will indentify design patterns/architecture present in open source software. Recommendation for areas of improvement using design patterns with rationale and implementation can be parts of the project.',$csid,$uid,$sid)";			
						ExecuteNonQuery($sql1);
					//	echo $sql1;	
					}
				}
				else
					{
						$sql="update facultyhours set starttime='".$sttime."',endtime='".$entime."',type='".$type."',cday='".$cday."',roomno='".$rno."' where csid=".$csid." and uid=".$uid." and semid=".$sid;
						ExecuteNonQuery($sql);
					//	echo $sql."<br>";
					}
					$cntrec4=CountRecords("select * from section where semid=".$sid." and uid=".$uid." and csid=".$csid);
					if($cntrec4==0)
					{
						$sql="insert into section(csid,name,uid,semid,active)values(".$csid.",'".$matrixfields[$i][1]."',".$uid.",".$sid.",1)";	
						ExecuteNonQuery($sql);
					//	echo $sql;
					}
					$sql="select camname from cams where csid=$csid and uid=$uid and semid=$sid";
					$cnt=CountRecords($sql);
					if($cnt==0)
					{
						$sql1="insert into cams(camname,camdetails,csid,uid,semid)values('Assignments:','Students will periodically be assigned homework assignments related to software design principles, patterns and architecture, Due dates will be announced in class.',$csid,$uid,$sid),
							('Examinations/Quizzes:','Students will periodically take in-class examinations covering knowledge and application of software design principles,design patterns,architecture and related topics from the assigned readings,course notes,and homework.',$csid,$uid,$sid),
							('Project:','The project shall be staffed by individual student who will indentify design patterns/architecture present in open source software. Recommendation for areas of improvement using design patterns with rationale and implementation can be parts of the project.',$csid,$uid,$sid)";			
						ExecuteNonQuery($sql1);
					//	echo $sql1;	
					}
				}
				
		}
				
	}
	//echo count($namelist);
	$_SESSION["nmlist"]=$namelist;	
	redirect_to("csassignsuccessful.php");		
	
//	echo "</table>";
?>