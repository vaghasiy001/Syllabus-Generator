<?php
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
		$filename="matrixfiles/".$_SESSION["filename"];
		$row = 1;
	//	$deptocmrow=$_POST["deptocmrow"];
		$tmp=0;
		$ver=$_SESSION["ver"];
		$delarray=array();
		$matrixfields=array(array());
		$flag=0;
		if (($handle = fopen($filename, "r")) != FALSE) 
		{
			while (($data = fgetcsv($handle, 10000000, ",")) !== FALSE) {
				$cols=0;
				$num = count($data);
		//        echo "<p> $num fields in line $row: <br /></p>\n";
				$tmp=$row-1;
				$row++;
				
						for ($c=0; $c < $num; $c++)
						 {
						
						$matrixfields[$tmp][$cols]=$data[$c];
				      //  echo $data[$c] . "&nbsp;&nbsp;&nbsp;&nbsp;";
						$cols++;
						}
				}
			 fclose($handle);
		}


//---------------------------------------------------------------UPLOAD COURSES-------------------------------------------------------------------------
$strqry="insert into matrix_courses(cno,cname,version)values";
		for($i=0;$i<$row;$i++)
		{
	//		echo "<tr>";
	if(strtoupper(substr($matrixfields[$i][0],0,4))=="CIS_" || strtoupper(substr($matrixfields[$i][0],0,4))=="ECE_"  || strtoupper(substr($matrixfields[$i][0],0,5))=="GCIS_" )
			{
		//		echo "<td>".$matrixfields[$i][0].",".$matrixfields[$i+1][0]."</td>";
				
				if(substr_count($matrixfields[$i][0],"_")==2)
				{
					$matrixfields[$i][0]=substr($matrixfields[$i][0], 0, -3);
				}
				$strqry.="('".$matrixfields[$i][0]."','".$matrixfields[$i+1][0]."','$ver'),";				
           	}
			
		//	echo "</tr>";			
		}
		$strqry=substr($strqry,0,strlen($strqry)-1);
	//	echo $strqry."<br><br>";
		ExecuteNonQuery($strqry);
//----------------------------------------------------------UPLOAD COURSE OUTCOMES--------------------------------------------------------------



	$strqry="insert into course_mappings(cno,mappingname,version)values";
		$tmpcno="";
		for($i=2;$i<$row-1;$i++)
		{
		//	echo "<tr>";
		//		echo "<td>".$matrixfields[0][$i]."</td>";
			//	echo "</tr>";	
			if(strtoupper(substr($matrixfields[$i][0],0,4))=="CIS_" || strtoupper(substr($matrixfields[$i][0],0,4))=="ECE_"  || strtoupper(substr($matrixfields[$i][0],0,5))=="GCIS_" )
			{
				$tmpcno=$matrixfields[$i][0];
				continue;
			}
		
	//	$tmp=mysqli_real_escape_string($connection,$matrixfields[$i][1]);
		$tmp=preg_replace('/[^A-Za-z0-9 \-\.]/', '', $matrixfields[$i][1]); // Removes special chars.
		$strqry.="('".$tmpcno."','".$tmp."','$ver'),";		
		//$strqry.="('".$tmpcno."','".str_replace(")","\)",str_replace("'","\'",$matrixfields[$i][1]))."','$ver'),";		
		}
		$strqry=substr($strqry,0,strlen($strqry)-1);
	//	echo $strqry."<br><br>";
		ExecuteNonQuery($strqry);

//   preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.

//-------------------------------------------------------UPLOAD DEPARTMENT OUTCOMES-----------------------------------------------------------------------
			$strqry="insert into dept_outcomes(docname,prefix,version)values";
				for($i=2;$i<$cols-1;$i++)
					{
			//			echo "<tr>";
				//			echo "<td>".$matrixfields[0][$i]."</td>";
			//				echo "</tr>";			
					$tmp=strpos($matrixfields[0][$i],")");
					$tmp1=substr($matrixfields[0][$i],0,$tmp+1);
					$strqry.="('".$matrixfields[0][$i]."','".str_replace(")","\)",$tmp1)."','$ver'),";		
					}
					$strqry=substr($strqry,0,strlen($strqry)-1);
					//echo $strqry."<br><br>";
					ExecuteNonQuery($strqry);
//--------------------------------------------------------UPLOAD SUB OUTCOMES---------------------------------------------------------------------
				
				
				$strqry="insert into sub_outcomes(docmid,socmname,version)values";
			//	echo "<tr><th>Course Outcomes</th></tr>";
				for($i=2;$i<$cols-1;$i++)
				{
				//	echo "<tr>";
				//	echo "<td>".$matrixfields[$i][1]."</td>";
			//		echo "</tr>";			
			//		echo $matrixfields[0][$i];
					$id=GetSingleField("select docmid from dept_outcomes where docname='".$matrixfields[0][$i]."'","docmid");
					$strqry.="(".$id.",'".str_replace(")","\)",$matrixfields[1][$i])."','$ver'),";		
				}
				$strqry=substr($strqry,0,strlen($strqry)-1);
			//	echo $strqry."<br><br>";
				ExecuteNonQuery($strqry);
			
//-----------------------------------------------------UPLOAD MAPPINGS------------------------------------------------------------------------------
							
		$flag=0;

		$tnm="matrix_$ver";
				$strqry="create table $tnm(";
				$strcols="";
				for($i=0;$i<$cols-3;$i++)
				{
					$strcols.="col$i int,";
				}
				$strqry=$strqry.$strcols."requiredcol text,cno text,objno int,version text)";
			//	echo $strqry."<br><br>";
				ExecuteNonQuery($strqry);
			$strinsert="insert into $tnm(";
				for($i=0;$i<$cols-3;$i++)
				{
					$strinsert.="col$i,";
				}
				$strinsert.="requiredcol,cno,objno,version)values";
			//echo $strinsert;
			$temp=0;
		for($i=2;$i<count($matrixfields);$i++,$temp++)
		{
			if(strtoupper(substr($matrixfields[$i][0],0,4))=="CIS_" || strtoupper(substr($matrixfields[$i][0],0,4))=="ECE_"  || strtoupper(substr($matrixfields[$i][0],0,5))=="GCIS_")
			{
				$flag=$matrixfields[$i][0];
				$temp=0;
				continue;
			}
			else
			{
						$strinsert.="(";
			}
			for($j=2;$j<$cols-1;$j++)
				{
						if(trim($matrixfields[$i][$j])=="")
							$strinsert.="0,";
						else
							$strinsert.="1,";
				}
				$strinsert.="'".$matrixfields[$i][$j]."','$flag',++$temp,'$ver'),";
		}
			$strinsert=substr($strinsert,0,strlen($strinsert)-1);
			
	//	echo $strinsert;			
		ExecuteNonQuery($strinsert);
	redirect_to("thankyou.php?flag=0");
				
?>