<?php
ini_set("display_errors","off");
include("includes/DataAccess.php");
include("includes/connect.php");
if(!isset($_SESSION))
{
	session_start();
}

	require_once("includes/session.php");

		$mainrow=0;
		$filename=$_SESSION["filename"];
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
/*echo "<table>";
for($i=0;$i<$row;$i++)
{
		echo "<tr>";
	
	for($j=0;$j<$cols;$j++)
	{
		echo "<td>".$matrixfields[$i][$j]."</td>";
	}
	echo "</tr>";
	
}
echo "</table>";
*/

//-----------------------------------------------------UPLOAD MAPPINGS------------------------------------------------------------------------------
				
		$flag=0;

		$tnm="matrix_$ver";
				$strqry="create table $tnm(";
				$strcols="";
				for($i=0;$i<$cols-3;$i++)
				{
					$strcols.="col$i int,";
				}
				$strqry=$strqry.$strcols."requiredcol text,cno text,rowno int,version text)";
				echo $strqry."<br><br>";
			//	ExecuteNonQuery($strqry);
			$strinsert="insert into $tnm(";
				for($i=0;$i<$cols-3;$i++)
				{
					$strinsert.="col$i,";
				}
				$strinsert.="requiredcol,cno,rowno,version)values<br>";
		//	echo $strinsert;
			$temp=0;
		for($i=2;$i<count($matrixfields);$i++,$temp++)
		{
			if(strtoupper(substr($matrixfields[$i][0],0,4))=="CIS_" || strtoupper(substr($matrixfields[$i][0],0,4))=="ECE_"  || strtoupper(substr($matrixfields[$i][0],0,5))=="GCIS_")
			{
				$flag=$matrixfields[$i][0];
				$temp--;
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
				$strinsert.="'".$matrixfields[$i][$j]."','$flag',$temp,'$ver'),<br>";
		}
		echo $strinsert;			
				
				
?>