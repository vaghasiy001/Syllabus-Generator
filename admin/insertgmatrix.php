<?php
ini_set("display_errors","off");
	require_once("includes/session.php");
	include_once("includes/form_functions.php");
	require_once("includes/functions.php");
	include_once("includes/DataAccess.php");
	include("includes/connect.php");
if(!isset($_SESSION))
{
	session_start();
}
		$mainrow=0;
//		echo $_SESSION["filename"];
		$filename=$_SESSION["filename"];
		$row = 1;
	//	$deptocmrow=$_POST["deptocmrow"];
		$tmp=0;
		$ver=$_SESSION["ver"];
		$delarray=array();
		$matrixfields=array(array());
		$flag=0;
		$filename="matrixfiles/".$_SESSION["filename"];
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
				      //  echo $data[$c] . "&nbsp;&nbsp;&nbsp;&nbsp;";
						$cols++;
						}
				}
			 fclose($handle);
		}


//---------------------------------------------------------------UPLOAD COURSES-------------------------------------------------------------------------
//done
$strqry="insert into gmatrix_courses(cno,cname,version)values";
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

//not done

	$strqry="insert into gcourse_mappings(cno,mappingname,version)values";
		$tmpcno="";
		for($i=6;$i<$row-1;$i++)
		{
		//	echo "<tr>";
		//		echo "<td>".$matrixfields[0][$i]."</td>";
			//	echo "</tr>";	
			if(strtoupper(substr($matrixfields[$i][0],0,5))=="GCIS_")
			{
				$tmpcno=$matrixfields[$i][0];
				continue;
			}
		
	//		preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $tmp);
				$tmp=preg_replace('/[^A-Za-z0-9 \-\.]/', '', $matrixfields[$i][1]);
			$strqry.="('".$tmpcno."','".$tmp."','$ver'),";		
		//$strqry.="('".$tmpcno."','".str_replace(")","\)",str_replace("'","\'",$matrixfields[$i][1]))."','$ver'),";		
		}
		$strqry=substr($strqry,0,strlen($strqry)-1);
	//	echo $strqry;
		ExecuteNonQuery($strqry);



//-------------------------------------------------------UPLOAD DEPARTMENT OUTCOMES-----------------------------------------------------------------------
//done
			$strqry="insert into gdept_outcomes(docname,prefix,version)values";
				for($i=2;$i<$cols-1;$i++)
					{
			//			echo "<tr>";
				//			echo "<td>".$matrixfields[0][$i]."</td>";
			//				echo "</tr>";			
					$tmp=strpos($matrixfields[4][$i],")");
					$tmp1=substr($matrixfields[4][$i],0,$tmp+1);
					$strqry.="('".$matrixfields[4][$i]."','".str_replace(")","\)",$tmp1)."','$ver'),";		
					}
					$strqry=substr($strqry,0,strlen($strqry)-1);
			//		echo $strqry."<br><br>";
				ExecuteNonQuery($strqry);
			
//-----------------------------------------------------UPLOAD MAPPINGS------------------------------------------------------------------------------
//not done							
		$flag=0;

		$tnm="gmatrix_$ver";
				$strqry="create table $tnm(";
				$strcols="";
				for($i=0;$i<$cols-2;$i++)
				{
					$strcols.="col$i int,";
				}
				$strqry=$strqry.$strcols."cno text,objno int,version text)";
				//echo $strqry."<br><br>";
				ExecuteNonQuery($strqry);
			$strinsert="insert into $tnm(";
				for($i=0;$i<$cols-2;$i++)
				{
					$strinsert.="col$i,";
				}
				$strinsert.="cno,objno,version)values";
		//	echo $strinsert;
			$temp=0;
		for($i=6;$i<count($matrixfields);$i++,$temp++)
		{
			if(substr($matrixfields[$i][0],0,5)=="GCIS_")
			{
				$flag=$matrixfields[$i][0];
				$temp=0;
				continue;
			}
			else
			{
						$strinsert.="(";
			}
			for($j=2;$j<=$cols-1;$j++)
				{
						if(trim($matrixfields[$i][$j])=="")
							$strinsert.="0,";
						else
							$strinsert.="1,";
				}
				$temp++;
				$strinsert.="'$flag',$temp,'$ver'),";
		}
			$strinsert=substr($strinsert,0,strlen($strinsert)-1);
			
	//	echo $strinsert;			
		ExecuteNonQuery($strinsert);
	redirect_to("thankyou.php?flag=1");
				
?>