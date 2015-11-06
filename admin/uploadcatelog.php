<?php
	ob_start();
	ini_set('display_errors','off');
	require_once("includes/session.php");
	include_once("includes/form_functions.php");
	require_once("includes/functions.php");
	include_once("includes/DataAccess.php");
?>
<?php	if (!logged_in()) {
		redirect_to("index.php");
	}
?>
<?php
if(isset($_POST["Submit"]))
{
			if(isset($_POST["rbdegree"]) && $_POST["rbdegree"]=="G" )
		{
			if(strlen($_POST["txtprefix"])>3)
			{
			$prefix=$_POST["txtprefix"];
			$filename=$_FILES["pdfdoc"]["name"];
			$mpath="";
			$mtype=php_uname('m');/* Machine type */
			if(strpos($mtype,'64'))
				$mpath=str_replace("\\","/",getcwd())."/xpdf/bin64/pdftotext";
			else
				$mpath=str_replace("\\","/",getcwd())."/xpdf/bin32/pdftotext";
			$mpath=$mpath." ".$filename;
			$content=shell_exec($mpath.' -');
			$content=(string)nl2br($content);
			$pos=0;
			$j=0;
		
				$pieces = explode("<br />", $content);
				$ii=0;
				$courses=array(array());
								
				for($i=0;$i<count($pieces);$i++)
				{
				$jj=0;
			
					//echo $pieces[$i]."<br>";
						$pos=strpos($pieces[$i],$prefix);
						if($pos==true)
						{
							$pos1=strpos($pieces[$i],'credit');
							$strtemp=substr($pieces[$i],0,5);	
								if($pos1==true && trim($strtemp)==$prefix)
								{
									$strcno=substr($pieces[$i],0,13);
									$postmp=strpos($strcno,"-");
									if($postmp==true)
										{
											$courses[$ii][$jj]=substr($pieces[$i],0,13);
											$jj++;
										}
									else
										{
											$courses[$ii][$jj]=substr($pieces[$i],0,9);
											$jj++;
						
										}
									if($postmp==true)
										{
											$courses[$ii][$jj]=substr($pieces[$i],13,strpos($pieces[$i],'credit')-14);
											$jj++;
										}
									else
										{
											$courses[$ii][$jj]=substr($pieces[$i],9,strpos($pieces[$i],'credit')-11);
											$jj++;
										}
									$courses[$ii][$jj]=substr($pieces[$i],strpos($pieces[$i],'credit')-2,9);
									$jj++;
									
								
								
									$st=strpos($pieces[$i],"credits,")+9;
									$loc=strpos($pieces[$i]," ",$st);
									
									$courses[$ii][$jj]=substr($pieces[$i],$st,$loc-$st);
									$jj++;
								
								
									$courses[$ii][$jj]=$pieces[$i];
									$jj++;
									$pos=strpos($pieces[$i],"Prerequisite:");
									if($pos==true)
									{
										$st=$pos+strlen("Prerequisite:");
										$tm=substr($pieces[$i],$st,30);
										$p=strpos($tm,"or");
										$p1=strpos($tm,"and");
										if($p1==true)
										{
												$courses[$ii][$jj]=substr($pieces[$i],strlen("Prerequisite:")+$pos,22);
												$jj++;
										}
										else if($p==true)
										{
												$courses[$ii][$jj]=substr($pieces[$i],strlen("Prerequisite:")+$pos,22);
												$jj++;
										}
										else
										{
											$courses[$ii][$jj]=substr($pieces[$i],strlen("Prerequisite:")+$pos,9);
											$jj++;
										}
									}
									else
									{
										$courses[$ii][$jj]="";
										$jj++;	
									}
									$pos=strpos($pieces[$i],"Co-requisite:");
									if($pos==true)
									{
										$courses[$ii][$jj]=substr($pieces[$i],strlen("Co-requisite:")+$pos,9);
										$jj++;
	}
									else
									{
										$courses[$ii][$jj]="";
										$jj++;
									}
									$ii++;
								}
						}
				}
					
				$str="insert into catelog_temp(courseid,coursename,credits,semester,coursedesc,prerequisite,corequisite,approve) values";
				for($i=0;$i<$ii;$i++)
				{
					$len=strlen($courses[$i][0]);
					$tmp=substr($courses[$i][4],0,$len);
					
					if(strcmp($tmp,$courses[$i][0])==0)
					{
						$courses[$i][4]=substr($courses[$i][4],$len,strlen($courses[$i][4]));
					}
					$len=strlen($courses[$i][1]);
					$tmp=substr($courses[$i][4],0,$len);
					
					if(strcmp($tmp,$courses[$i][1])==0)
					{
						$courses[$i][4]=substr($courses[$i][4],$len,strlen($courses[$i][4]));
					}
					$len=strlen($courses[$i][2]);
					$tmp=substr($courses[$i][4],0,$len);
					
					if(strcmp($tmp,$courses[$i][2])==0)
					{
						$courses[$i][4]=substr($courses[$i][4],$len,strlen($courses[$i][4]));
					}
					$courses[$i][5]=trim($courses[$i][5]);
					$tmp=substr($courses[$i][5],0,1);
					if($tmp==" " || $tmp=="(" || $tmp==")")
					{
						$courses[$i][5]=substr($courses[$i][5],1,strlen($courses[$i][5]));		
					}
					$str.="(";
					for($j=0;$j<7;$j++)
					{
						$str.="'".str_replace("'","\'",trim($courses[$i][$j]))."',";
					}
					
					
						$str=rtrim($str, ",");
						$str.=",0),";
						}
						$str=rtrim($str, ",");
					
					//	echo $str;
							$cnt=ExecuteNonQuery($str);
							redirect_to("viewuacourses.php");	
			}
		}
		else if(isset($_POST["rbdegree"]) && $_POST["rbdegree"]=="UG" )
		{
			$prefix=$_POST["txtprefix"];
			$filename=$_FILES["pdfdoc"]["name"];
			$content=shell_exec('c:/wamp/www/pdfex1/xpdf/bin64/pdftotext '.$filename.' -');
			$content=(string)nl2br($content);
			$pieces = explode("<br />", $content);
				echo "<table border='2'>";
				echo "<tr>
				<th>Course</th>
				<th>Course Name</th>
				<th>Credits</th>
				<th>Semester</th>
				<th>Course Description</th>
				<th>Prerequisites</th>
				<th>Co-requisite</th>
				</tr>";
			
			for($i=0;$i<count($pieces);$i++)
			{
//				echo $pieces[$i]."<br>";
				$tmpstr="";
				$pos=strpos($pieces[$i],$prefix);
				if($pos==true)
				{
					$pos1=strpos($pieces[$i],$prefix);
					if($pos1==true)
					{
						$cnttmp=1;
						$flg=0;
						while($cnttmp<=10)
						{
								$pos2=strpos($pieces[$i+$cnttmp],"Prerequisites: ");
								if($pos2==true)
								{
									$flg=1;
									break;
								}
								$pos2=strpos($pieces[$i+$cnttmp],"Corequisite: ");
								if($pos2==true)
								{
									$flg=1;
									break;
								}
								$pos2=strpos($pieces[$i+$cnttmp],"Co-requisite: ");
								if($pos2==true)
								{
									$flg=1;
									break;
								}
								$pos2=strpos($pieces[$i+$cnttmp],"Prerequisite: ");
								if($pos2==true)
								{
									$flg=1;
									break;
								}
								
								$pos2=strpos($pieces[$i+$cnttmp]," credit,Spring");
								if($pos2==true)
								{
									$flg=1;
									break;
								}
								
								$pos2=strpos($pieces[$i+$cnttmp]," credit,Fall");
								if($pos2==true)
								{
									$flg=1;
									break;
								}
								
								$pos2=strpos($pieces[$i+$cnttmp]," credits, Spring");
								if($pos2==true)
								{
									$flg=1;
									break;
								}
								$pos2=strpos($pieces[$i+$cnttmp]," credits, Fall");
								if($pos2==true)
								{
									$flg=1;
									break;
								}
								
								$pos2=strpos($pieces[$i+$cnttmp]," credit");
								if($pos2==true)
								{
									$flg=1;
									break;
								}
								$pos2=strpos($pieces[$i+$cnttmp]," credits");
								if($pos2==true)
								{
									$flg=1;
									break;
								}
								$cnttmp++;
						}
						
						if($flg==1)
						{
								$val=strlen($prefix)+1;
								if(substr( trim($pieces[$i]), 0, $val ) === "$prefix ")
									{
										$splitstr=explode(":",$pieces[$i]);
										{
											echo "<tr>";
											echo "<td>$splitstr[0]</td>";
											echo "<td>$splitstr[1]</td>";
											echo "<td></td>";
											echo "<td></td>";
											echo "<td></td>";
											echo "<td></td>";
											echo "<td></td>";
											echo "</tr>";
						
										}
									}
						}
					}
									
				}
			}
			echo "</table>";
		}

}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Upload PDF Catelog</title>
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
           	<div id="contentdetail">
			<form method="post" action="uploadcatelog.php" enctype="multipart/form-data">
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
              <tr>
            <td>Upload pdf</td>
            		<td>:</td>
             
            <td><input type="file" name="pdfdoc"></td>
            </tr>
        	<tr>
                <td>Degree</td>
            		<td>:</td>
             
            	<td><input type="radio" name="rbdegree" value="UG">Under Graduate<input type="radio" name="rbdegree" value="G" >Graduate</td>
            </tr>
            <tr>
            	<td>Prefix</td>
             		<td>:</td>
             
                <td>
                	<input type="text" size="4" name="txtprefix">[Ex:GCIS,CIS,MGMT,SCMS etc]
                </td>
            </tr>
                <tr>
                	<td colspan="3" align="center"><input type="submit" value="Submit" name="Submit"></td>
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