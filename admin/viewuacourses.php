<?php
ini_set("display_errors","off");
include("includes/DataAccess.php");
include("includes/form_functions.php");
include("includes/functions.php");
include("includes/session.php");
if (!logged_in()) {
		redirect_to("index.php");
	}

?>

<?php
if(isset($_POST["submit"]))
{
			$data1 = ExecuteNonQuery('select * FROM catelog_temp');
			$tmparray=array(array());
			$i=0;
			while($info1 = mysqli_fetch_assoc($data1)) 
			{
				$preid=0;
				$coid=0;
							if(trim($info1["prerequisite"])!="" && !is_null($info1["prerequisite"]) && strlen(trim($info1["prerequisite"]))>0)
							{
								$cntrec=CountRecords("select * from pre_req where pcname='".trim($info1["prerequisite"])."'");
								if($cntrec==0)
								{
									$str="insert into pre_req(pcname)values('".trim($info1["prerequisite"])."')";
									$cnt=ExecuteNonQuery($str);
									if($cnt==1)
										$preid=mysqli_insert_id();	
								}
								else
								{
									$preid=GetSingleField("select prereqid from pre_req where pcname='".$info1["prerequisite"]."'","prereqid");
								}
							}
							if(trim($info1["corequisite"])!="" && !is_null($info1["corequisite"]) && strlen(trim($info1["corequisite"]))>0)
							{
								$cntrec=CountRecords("select * from co_req where ccname='".trim($info1["corequisite"])."'");
							
								if($cntrec==0)
								{
									$str="insert into co_req(ccname)values('".trim($info1["corequisite"])."')";
									$cnt=ExecuteNonQuery($str);
									if($cnt==1)
										$coid=mysqli_insert_id();
								}
								else
								{
									$sql="select coreqid from co_req where ccname='".trim($info1["corequisite"])."'";
								//	echo $sql."<br>";
									$coid=GetSingleField($sql,"coreqid");
						
								}
							}
				$cnt=CountRecords("select * from courses where courseno='".$info1["courseid"]."'");	
				if($cnt>0)
				{
					$cid=GetSingleField("select cid from courses where courseno='".$info1["courseid"]."'","cid");	
			//		$tmparray[$i][0]=$info1["courseid"];
				//	$tmparray[$i][1]=$info1["coursename"];
				//	$i++;
					$str="update courses set courseno='".$info1["courseid"]."',coursename='".$info1["coursename"]."',description='".str_replace("'","\'",$info1["coursedesc"])."',prereqid=$preid,coreqid=$coid,credits=".$info1["credits"].",set active=1, set deptid=1 where cid=".$cid;
					//echo $str."<br>";
					ExecuteNonQuery($str);
					$str1="update catelog_temp set approve=1 where id=".$info1["id"];
				//	echo $str1."<br>";
					ExecuteNonQuery($str1);
	
				}
				else
				{
					
					$str="insert into courses(courseno,coursename,description,prereqid,coreqid,credits,active,deptid)values('";
					$str.=$info1["courseid"]."','".$info1["coursename"]."','".str_replace("'","\'",$info1["coursedesc"])."',$preid,$coid,".$info1["credits"].",1,1)";
				//	echo $str."<br>";
					ExecuteNonQuery($str);
					$str1="update catelog_temp set approve=1 where id=".$info1["id"];
				//	echo $str1."<br>";
					ExecuteNonQuery($str1);
				}
			}
			
}

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>View UnApproved Courses</title>
<link href="css/style.css" rel="stylesheet" type="text/css">
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
			<div id="viewcatdiv">
            	<div id="info">
                	<p>This page displays unapproved courses details.</p>
                </div>
              	<div id="viewbannercontent">
                <form method="post" action="viewuacourses.php">
                <div align="right" style="width:100%">
					<input type="submit" value="Approve All" align="right" name="submit" onClick="return confirm('Are you sure you want to approve all courses??');">
        		</div>
            <table>
		          <tr>
                	<th align="left">Course Id</th>
                    <th>Course Name</th>
            		<th>Credit</th>
                 	<th>Approve??</th>
                    <th>Edit</th>
                </tr>
                	<?php
					$data = ExecuteNonQuery('select * FROM catelog_temp');
					while($info = mysqli_fetch_assoc($data)) 
					{?>
				<tr>
                        <td ><?php echo $info["courseid"];?></td>
                        <td><?php echo $info["coursename"];?></td>
                        <td><?php echo $info["credits"];?></td>
                  		<td><input type="checkbox" <?php if($info["approve"]==1) echo "checked" ?> disabled></td>
                        <td><a href="edituacourse.php?id=<?php echo $info["id"]?>"><img src="images/edit.jpg" height="40px" width="40px"></a></td>
	            </tr>
             	<?php	
					}
					?>
                
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