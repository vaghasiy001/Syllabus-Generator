<?php
function GetMaxId($cname,$table)
{
		include("connect.php");
	    $highest_id = MySQLi_Result(mysql_query("SELECT MAX($cname) FROM $table"), 0);
		return $highest_id;
}
function GetSingleField($query,$field)
{
		include("connect.php");
		$cnt=mysqli_query($connection,$query);
		$row = mysqli_fetch_array($cnt);
		return $row[$field];
}
function CountRecords($query)
{
			include("connect.php");
			$res=mysqli_query($connection,$query);
			$cnt=mysqli_num_rows($res);
			return $cnt;
}
function CheckExistRecord($query)
{
			include("connect.php");
			$res=mysqli_query($connection,$query);
			while($row = mysqli_fetch_array($res)){
				$cntrec=$row['COUNT(ocmresid)'];
			}
			return $cntrec;

}
function ExecuteNonQuery($query)
{
		include("connect.php");
		$records=mysqli_query($connection,$query);
		return $records;
}
function backup_tables($tables = '*')
{
	
	include("connect.php");
	$link = mysql_connect(DB_SERVER,DB_USER,DB_PASS);
	mysql_select_db(DB_NAME,$link);
	
	//get all of the tables
	if($tables == '*')
	{
		$tables = array();
		$result = mysql_query('SHOW TABLES');
		while($row = mysql_fetch_row($result))
		{
			$tables[] = $row[0];
		}
	}
	else
	{
		$tables = is_array($tables) ? $tables : explode(',',$tables);
	}
	
	//cycle through
	foreach($tables as $table)
	{
		$result = mysql_query('SELECT * FROM '.$table);
		$num_fields = mysql_num_fields($result);
		
		$return.= 'DROP TABLE '.$table.';';
		$row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
		$return.= "\n\n".$row2[1].";\n\n";
		
		for ($i = 0; $i < $num_fields; $i++) 
		{
			while($row = mysql_fetch_row($result))
			{
				$return.= 'INSERT INTO '.$table.' VALUES(';
				for($j=0; $j<$num_fields; $j++) 
				{
					$row[$j] = addslashes($row[$j]);
					$row[$j] = ereg_replace("\n","\\n",$row[$j]);
					if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
					if ($j<($num_fields-1)) { $return.= ','; }
				}
				$return.= ");\n";
			}
		}
		$return.="\n\n\n";
	}
	
	$old_date = date('l, F d y h:i:s');              // returns Saturday, January 30 10 02:06:34
$old_date_timestamp = strtotime($old_date);
$new_date = date('m-d-Y', $old_date_timestamp); 
	
	$handle = fopen('dbbackup/db-backup_'.$new_date.'.sql','w+');
	fwrite($handle,$return);
	fclose($handle);
}
?>