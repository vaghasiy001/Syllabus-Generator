<?php
	ob_start();
	ini_set('display_errors','off');
	require_once("includes/session.php");
	include_once("includes/form_functions.php");
	require_once("includes/functions.php");
	include_once("includes/DataAccess.php");
if(!isset($_SESSION))
{
	session_start();
}

?>
<?php	if (!logged_in()) {
		redirect_to("index.php");
	}
?>
<?php
if(isset($_POST["Submit"]))
{
		$filename=$_FILES["matrixfile"]["name"];
		if($filename!="")
		{
			$ext = explode(".", strtolower($filename));
			if($ext[1]!="csv")
			{
				show_alert("File extension should be .csv only.");							
			}
			else
			{
					$uploads_dir=getcwd()."/matrixfiles";
					if (!mkdir($uploads_dir, 0777, true));
							
//					echo $uploads_dir;
					if($_GET["flag"]=="0")
					{
						$_SESSION["filename"]=$filename;
						$chkrec=CountRecords("select id from fileinfo where ftype='matrix'");
						if($chkrec==0)
						{
							$_SESSION["ver"]="V1";
							$str="insert into fileinfo(filename,version,active,ftype)values('".$filename."','".$_SESSION["ver"]."',0,'matrix')";
							ExecuteNonQuery($str);
							$tmp_name = $_FILES["matrixfile"]["tmp_name"];
							$name = $_FILES["matrixfile"]["name"];
			   				if (!mkdir($uploads_dir, 0777, true))
							 {
								 	if(file_exists($uploads_dir."/".$filename))
											unlink($uploads_dir."/".$filename);
								
							   $moveResult =copy($tmp_name,"$uploads_dir/".$name);
							   if ($moveResult == true) 
							   	   {
										redirect_to("insertmatrix.php");
								   }  
							   else 
							       {
									    echo "ERROR: File not moved correctly";
								   }
							 }
						}
						else
						{
							if(isset($_POST["chkver"]))
							{
								$_SESSION["ver"]=$_POST["txtver"];
								$str="insert into fileinfo(filename,version,active,ftype)values('".$filename."','".$_SESSION["ver"]."',0,'matrix')";
								ExecuteNonQuery($str);
								
								$tmp_name = $_FILES["matrixfile"]["tmp_name"];
							    $name = $_FILES["matrixfile"]["name"];
			   					if (!mkdir($uploads_dir, 0777, true))
								 {
										if(file_exists($uploads_dir."/".$filename))
												unlink($uploads_dir."/".$filename);
									
								   $moveResult =copy($tmp_name,"$uploads_dir/".$name);
								   if ($moveResult == true) 
									   {
											redirect_to("insertmatrix.php");
									   }  
								   else 
									   {
											echo "ERROR: File not moved correctly";
									   }
								  }
								}
							else
							{
								$_SESSION["ver"]=$_POST["hfcurrver"];
								$str="delete from course_mappings where version='".strtoupper($_SESSION["ver"])."';";
								ExecuteNonQuery($str);
								
								$str="delete from dept_outcomes where version='".strtoupper($_SESSION["ver"])."';";
								ExecuteNonQuery($str);
								
								$str="delete from matrix_courses where version='".strtoupper($_SESSION["ver"])."';";  
								ExecuteNonQuery($str);
								
								$str="delete from sub_outcomes where version='".strtoupper($_SESSION["ver"])."';";
								ExecuteNonQuery($str);
								
								$str="drop table  IF EXIST matrix_".$_SESSION["ver"];
								ExecuteNonQuery($str);
				
								if (!mkdir($uploads_dir, 0777, true))
								 {
										if(file_exists($uploads_dir."/".$filename))
												unlink($uploads_dir."/".$filename);
										 $tmp_name = $_FILES["matrixfile"]["tmp_name"];
										 $name = $_FILES["matrixfile"]["name"];
										 $moveResult =copy($tmp_name,"$uploads_dir/".$name);
								   if ($moveResult == true) 
									   {
											redirect_to("insertmatrix.php");
									   }  
								   else 
									   {
											echo "ERROR: File not moved correctly";
									   }
								 }
							}
						}
					}
					else
					{
						$_SESSION["filename"]=$filename;
						$chkrec=CountRecords("select id from fileinfo where ftype='gmatrix'");
						if($chkrec==0)
						{
							$_SESSION["ver"]="V1";
							$str="insert into fileinfo(filename,version,active,ftype)values('".$filename."','".$_SESSION["ver"]."',0,'gmatrix')";
							ExecuteNonQuery($str);
							
							if (!mkdir($uploads_dir, 0777, true))
								 {
										if(file_exists($uploads_dir."/".$filename))
												unlink($uploads_dir."/".$filename);
									
									$tmp_name = $_FILES["matrixfile"]["tmp_name"];
									 $name = $_FILES["matrixfile"]["name"];
								   $moveResult =copy($tmp_name,"$uploads_dir/".$name);
								   if ($moveResult == true) 
									   {
											redirect_to("insertgmatrix.php");
									   }  
								   else 
									   {
											echo "ERROR: File not moved correctly";
									   }
								}
						}
						else
						{
							if(isset($_POST["chkver"]))
							{
								$_SESSION["ver"]=$_POST["txtver"];
								$str="insert into fileinfo(filename,version,active,ftype)values('".$filename."','".$_SESSION["ver"]."',0,'gmatrix')";
								ExecuteNonQuery($str);
								
								if (!mkdir($uploads_dir, 0777, true))
								 {
									if(file_exists($uploads_dir."/".$filename))
											unlink($uploads_dir."/".$filename);
									
									$tmp_name = $_FILES["matrixfile"]["tmp_name"];
									 $name = $_FILES["matrixfile"]["name"];
								   $moveResult =copy($tmp_name,"$uploads_dir/".$name);
								   if ($moveResult == true) 
									   {
											redirect_to("insertgmatrix.php");
									   }  
								   else 
									   {
											echo "ERROR: File not moved correctly";
									   }
								 }
								
							}
							else
							{
								$_SESSION["ver"]=$_POST["hfcurrver"];
								$str="delete from gcourse_mappings where version='".strtoupper($_SESSION["ver"])."';";
								ExecuteNonQuery($str);
								
								$str="delete from gdept_outcomes where version='".strtoupper($_SESSION["ver"])."';";
								ExecuteNonQuery($str);
								
								$str="delete from gmatrix_courses where version='".strtoupper($_SESSION["ver"])."';";  
								ExecuteNonQuery($str);
						
								$str="drop table  IF EXIST gmatrix_".$_SESSION["ver"];
								ExecuteNonQuery($str);
								
								if (!mkdir($uploads_dir, 0777, true))
								 {
										if(file_exists($uploads_dir."/".$filename))
												unlink($uploads_dir."/".$filename);
									$tmp_name = $_FILES["matrixfile"]["tmp_name"];
							 $name = $_FILES["matrixfile"]["name"];
								   $moveResult =copy($tmp_name,"$uploads_dir/".$name);
								   if ($moveResult == true) 
									   {
											redirect_to("insertgmatrix.php");
									   }  
								   else 
									   {
											echo "ERROR: File not moved correctly";
									   }
								 }
							}
						}
					}
				}
		}
		else
		{
			show_alert("Please Select filename");			
			//exit;
		}

}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Upload Matrix</title>
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
	        <div id="addbannerdiv">
            	<div id="info">
                	<p>This page will allow you to upload matrix.</p>
                </div>
           	<div id="contentdetail">
			<form method="post" action="uploadmatrix.php?flag=<?php echo $_GET["flag"];?>" enctype="multipart/form-data">
			<table>
                 <tr>
            <td>Upload file(.csv only)</td>
            <td>:</td>
            <td><input type="file" name="matrixfile"></td>
            </tr>
            <tr>
            <td>Make new version</td>
            <td>:</td>
            <?php
			$currentver="";
			if($_GET["flag"]=="1")
				$cnt=CountRecords("select filename from fileinfo where ftype='gmatrix'");
			else if($_GET["flag"]=="0")
				$cnt=CountRecords("select filename from fileinfo where ftype='matrix'");
			else
				redirect_to("index.php");
				
			if($_GET["flag"]=="1")
			{		
				if($cnt!=0)
						$currentver=GetSingleField("select version from fileinfo where ftype='gmatrix' and active=1","version");
				else
						$currentver="No Active Version";
								
			}
			else if($_GET["flag"]=="0")
			{
					if($cnt!=0)
						$currentver=GetSingleField("select version from fileinfo where ftype='matrix' active=1","version");
					else
						$currentver="No Active version";	
			}
			else
			{
				redirect_to("index.php");
			}
			$ver=$cnt+1;
			$ver="V".$ver;
			?>
            <td><input type="checkbox" name="chkver" <?php if($cnt==0) echo "checked disabled";?>>
           <input type="text" name="txtver" style="font-weight:bold;font-size:24px;color:#00F;" value="<?php echo $ver; ?>" readonly size="4"/> [Current Version:<?php if($cnt==0) echo "No version made"; else echo $currentver;?>]
           <input type="hidden" name="hfcurrver" value="<?php echo $currentver;?>">
            </td>
            </tr>        
            <tr>
            <td colspan="3" align="center">
            <input type="submit" class="btn btn-success btn-large" value="Submit Matrix" name="Submit"></td>
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