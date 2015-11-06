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
		if($_SESSION["rdbdeg"]==1)
			{
				//for graduate			
				//rdb name:	cbx_oa,cbx_ob,cbx_oc
				if(isset($_POST["cbx_oa"]))
				{
					$cnm=GetSingleField("select courseno from courses where cid=".$_POST["ddlcourse"],"courseno");
					if(strpos($cnm," "))
					{
						$cnm=str_replace(" ","_",$cnm);
					}
					$cnm=$cnm."_".$_POST["cbx_oa"];
					$cntrec=CountRecords("select sections from course_section where sections='".$cnm."'");
					if($cntrec==0)
					{
						$sql="insert into course_section(sections,active,cid)values('".$cnm."',1,".$_POST["ddlcourse"].")";
					//	echo $sql;
						ExecuteNonQuery($sql);
					}
					else
					{
						$sql="update course_section set active=1 where sections='".$cnm."'";
						ExecuteNonQuery($sql);	
						$cbx_oa="";
				//		echo $sql;
					}
				}
				else
				{
					$cnm=GetSingleField("select courseno from courses where cid=".$_POST["ddlcourse"],"courseno");
					if(strpos($cnm," "))
						{
							$cnm=str_replace(" ","_",$cnm);
						}
					$cnm=$cnm."_0A";
					$cntrec=CountRecords("select sections from course_section where sections='".$cnm."'");
					if($cntrec>0)
					{
						$sql="update course_section set active=0 where sections='".$cnm."'";
						ExecuteNonQuery($sql);	
					}
				}
				//-------------------------------------------------cbx_ob---------------------------------------------
				if(isset($_POST["cbx_ob"]))
				{
					$cnm=GetSingleField("select courseno from courses where cid=".$_POST["ddlcourse"],"courseno");
					if(strpos($cnm," "))
					{
						$cnm=str_replace(" ","_",$cnm);
					}
					$cnm=$cnm."_".$_POST["cbx_ob"];
					$cntrec=CountRecords("select sections from course_section where sections='".$cnm."'");
					if($cntrec==0)
					{
						$sql="insert into course_section(sections,active,cid)values('".$cnm."',1,".$_POST["ddlcourse"].")";
//						echo $sql;
						ExecuteNonQuery($sql);
					}
					else
					{
						$sql="update course_section set active=1 where sections='".$cnm."'";
						ExecuteNonQuery($sql);	
						$cbx_ob="";
					}
				}
				else
				{
					$cnm=GetSingleField("select courseno from courses where cid=".$_POST["ddlcourse"],"courseno");
					if(strpos($cnm," "))
						{
							$cnm=str_replace(" ","_",$cnm);
						}
					$cnm=$cnm."_0B";
					$cntrec=CountRecords("select sections from course_section where sections='".$cnm."'");
					if($cntrec>0)
					{
						$sql="update course_section set active=0 where sections='".$cnm."'";
						ExecuteNonQuery($sql);	
					}
				}
				//-------------------------------------------------------------cbx_oc----------------------------
				if(isset($_POST["cbx_oc"]))
				{
					$cnm=GetSingleField("select courseno from courses where cid=".$_POST["ddlcourse"],"courseno");
					if(strpos($cnm," "))
					{
						$cnm=str_replace(" ","_",$cnm);
					}
					$cnm=$cnm."_".$_POST["cbx_oc"];
					$cntrec=CountRecords("select sections from course_section where sections='".$cnm."'");
					if($cntrec==0)
					{
						$sql="insert into course_section(sections,active,cid)values('".$cnm."',1,".$_POST["ddlcourse"].")";
					//	echo $sql;
						ExecuteNonQuery($sql);
					}
					else
					{
						$sql="update course_section set active=1 where sections='".$cnm."'";
						ExecuteNonQuery($sql);	
					}
				}
				else
				{
					$cnm=GetSingleField("select courseno from courses where cid=".$_POST["ddlcourse"],"courseno");
					if(strpos($cnm," "))
						{
							$cnm=str_replace(" ","_",$cnm);
						}
					$cnm=$cnm."_0C";
					$cntrec=CountRecords("select sections from course_section where sections='".$cnm."'");
					if($cntrec>0)
					{
						$sql="update course_section set active=0 where sections='".$cnm."'";
						ExecuteNonQuery($sql);	
						$cbx_oc="";
					}
				}
				//------------------------------------------------------------------------------------------------
			}
			else
			{
				//for undergraduate
				    //cbx_01,cbx_02,cbx_03,cbx_04,cbx_05,cbx_1e,cbx_2e,cbx_nu
				if(isset($_POST["cbx_01"]))
				{
					$cnm=GetSingleField("select courseno from courses where cid=".$_POST["ddlcourse"],"courseno");
					if(strpos($cnm," "))
					{
						$cnm=str_replace(" ","_",$cnm);
					}
					$cnm=$cnm."_".$_POST["cbx_01"];
					$cntrec=CountRecords("select sections from course_section where sections='".$cnm."'");
					if($cntrec==0)
					{
						$sql="insert into course_section(sections,active,cid)values('".$cnm."',1,".$_POST["ddlcourse"].")";
			//			echo $sql;
						ExecuteNonQuery($sql);
					}
					else
					{
						$sql="update course_section set active=1 where sections='".$cnm."'";
				//		echo $sql;
						ExecuteNonQuery($sql);	
					}
				}
				else
				{
					$cnm=GetSingleField("select courseno from courses where cid=".$_POST["ddlcourse"],"courseno");
					if(strpos($cnm," "))
						{
							$cnm=str_replace(" ","_",$cnm);
						}
					$cnm=$cnm."_01";
					$cntrec=CountRecords("select sections from course_section where sections='".$cnm."'");
					if($cntrec>0)
					{
						$sql="update course_section set active=0 where sections='".$cnm."'";
						ExecuteNonQuery($sql);	
						$cbx_01="";
					}
				}
				if(isset($_POST["cbx_02"]))
				{
					$cnm=GetSingleField("select courseno from courses where cid=".$_POST["ddlcourse"],"courseno");
					if(strpos($cnm," "))
					{
						$cnm=str_replace(" ","_",$cnm);
					}
					$cnm=$cnm."_".$_POST["cbx_02"];
					$cntrec=CountRecords("select sections from course_section where sections='".$cnm."'");
					if($cntrec==0)
					{
						$sql="insert into course_section(sections,active,cid)values('".$cnm."',1,".$_POST["ddlcourse"].")";
			//			echo $sql;
						ExecuteNonQuery($sql);
					}
					else
					{
						$sql="update course_section set active=1 where sections='".$cnm."'";
				//		echo $sql;
						ExecuteNonQuery($sql);	
					}
				}
				else
				{
					$cnm=GetSingleField("select courseno from courses where cid=".$_POST["ddlcourse"],"courseno");
					if(strpos($cnm," "))
						{
							$cnm=str_replace(" ","_",$cnm);
						}
					$cnm=$cnm."_02";
					$cntrec=CountRecords("select sections from course_section where sections='".$cnm."'");
					if($cntrec>0)
					{
						$sql="update course_section set active=0 where sections='".$cnm."'";
						ExecuteNonQuery($sql);	
						$cbx_02="";
					}
				}
				if(isset($_POST["cbx_03"]))
				{
					$cnm=GetSingleField("select courseno from courses where cid=".$_POST["ddlcourse"],"courseno");
					if(strpos($cnm," "))
					{
						$cnm=str_replace(" ","_",$cnm);
					}
					$cnm=$cnm."_".$_POST["cbx_03"];
					$cntrec=CountRecords("select sections from course_section where sections='".$cnm."'");
					if($cntrec==0)
					{
						$sql="insert into course_section(sections,active,cid)values('".$cnm."',1,".$_POST["ddlcourse"].")";
			//			echo $sql;
						ExecuteNonQuery($sql);
					}
					else
					{
						$sql="update course_section set active=1 where sections='".$cnm."'";
				//		echo $sql;
						ExecuteNonQuery($sql);	
					}
				}
				else
				{
					$cnm=GetSingleField("select courseno from courses where cid=".$_POST["ddlcourse"],"courseno");
					if(strpos($cnm," "))
						{
							$cnm=str_replace(" ","_",$cnm);
						}
					$cnm=$cnm."_03";
					$cntrec=CountRecords("select sections from course_section where sections='".$cnm."'");
					if($cntrec>0)
					{
						$sql="update course_section set active=0 where sections='".$cnm."'";
						ExecuteNonQuery($sql);	
						$cbx_03="";
					}
				}
				
				
				if(isset($_POST["cbx_04"]))
				{
					$cnm=GetSingleField("select courseno from courses where cid=".$_POST["ddlcourse"],"courseno");
					if(strpos($cnm," "))
					{
						$cnm=str_replace(" ","_",$cnm);
					}
					$cnm=$cnm."_".$_POST["cbx_04"];
					$cntrec=CountRecords("select sections from course_section where sections='".$cnm."'");
					if($cntrec==0)
					{
						$sql="insert into course_section(sections,active,cid)values('".$cnm."',1,".$_POST["ddlcourse"].")";
			//			echo $sql;
						ExecuteNonQuery($sql);
					}
					else
					{
						$sql="update course_section set active=1 where sections='".$cnm."'";
				//		echo $sql;
						ExecuteNonQuery($sql);	
					}
				}
				else
				{
					$cnm=GetSingleField("select courseno from courses where cid=".$_POST["ddlcourse"],"courseno");
					if(strpos($cnm," "))
						{
							$cnm=str_replace(" ","_",$cnm);
						}
					$cnm=$cnm."_04";
					$cntrec=CountRecords("select sections from course_section where sections='".$cnm."'");
					if($cntrec>0)
					{
						$sql="update course_section set active=0 where sections='".$cnm."'";
						ExecuteNonQuery($sql);	
						$cbx_04="";
					}
				}
				
				if(isset($_POST["cbx_05"]))
				{
					$cnm=GetSingleField("select courseno from courses where cid=".$_POST["ddlcourse"],"courseno");
					if(strpos($cnm," "))
					{
						$cnm=str_replace(" ","_",$cnm);
					}
					$cnm=$cnm."_".$_POST["cbx_05"];
					$cntrec=CountRecords("select sections from course_section where sections='".$cnm."'");
					if($cntrec==0)
					{
						$sql="insert into course_section(sections,active,cid)values('".$cnm."',1,".$_POST["ddlcourse"].")";
			//			echo $sql;
						ExecuteNonQuery($sql);
					}
					else
					{
						$sql="update course_section set active=1 where sections='".$cnm."'";
				//		echo $sql;
						ExecuteNonQuery($sql);	
					}
				}
				else
				{
					$cnm=GetSingleField("select courseno from courses where cid=".$_POST["ddlcourse"],"courseno");
					if(strpos($cnm," "))
						{
							$cnm=str_replace(" ","_",$cnm);
						}
					$cnm=$cnm."_05";
					$cntrec=CountRecords("select sections from course_section where sections='".$cnm."'");
					if($cntrec>0)
					{
						$sql="update course_section set active=0 where sections='".$cnm."'";
						ExecuteNonQuery($sql);	
						$cbx_05="";
					}
				}
				
				if(isset($_POST["cbx_1e"]))
				{
					$cnm=GetSingleField("select courseno from courses where cid=".$_POST["ddlcourse"],"courseno");
					if(strpos($cnm," "))
					{
						$cnm=str_replace(" ","_",$cnm);
					}
					$cnm=$cnm."_1E";
					$cntrec=CountRecords("select sections from course_section where sections='".$cnm."'");
					if($cntrec==0)
					{
						$sql="insert into course_section(sections,active,cid)values('".$cnm."',1,".$_POST["ddlcourse"].")";
			//			echo $sql;
						ExecuteNonQuery($sql);
					}
					else
					{
						$sql="update course_section set active=1 where sections='".$cnm."'";
				//		echo $sql;
						ExecuteNonQuery($sql);	
					}
				}
				else
				{
					$cnm=GetSingleField("select courseno from courses where cid=".$_POST["ddlcourse"],"courseno");
					if(strpos($cnm," "))
						{
							$cnm=str_replace(" ","_",$cnm);
						}
					$cnm=$cnm."_1E";
					$cntrec=CountRecords("select sections from course_section where sections='".$cnm."'");
					if($cntrec>0)
					{
						$sql="update course_section set active=0 where sections='".$cnm."'";
						ExecuteNonQuery($sql);	
						$cbx_1e="";
					}
				}
				
				if(isset($_POST["cbx_headek"]))
				{
					$cnm=GetSingleField("select courseno from courses where cid=".$_POST["ddlcourse"],"courseno");
					if(strpos($cnm," "))
					{
						$cnm=str_replace(" ","_",$cnm);
					}
					$cnm=$cnm."_2E";
					$cntrec=CountRecords("select sections from course_section where sections='".$cnm."'");
					if($cntrec==0)
					{
						$sql="insert into course_section(sections,active,cid)values('".$cnm."',1,".$_POST["ddlcourse"].")";
			//			echo $sql;
						ExecuteNonQuery($sql);
					}
					else
					{
						$sql="update course_section set active=1 where sections='".$cnm."'";
				//		echo $sql;
						ExecuteNonQuery($sql);	
					}
				}
				else
				{
					$cnm=GetSingleField("select courseno from courses where cid=".$_POST["ddlcourse"],"courseno");
					if(strpos($cnm," "))
						{
							$cnm=str_replace(" ","_",$cnm);
						}
					$cnm=$cnm."_2E";
					$cntrec=CountRecords("select sections from course_section where sections='".$cnm."'");
					if($cntrec>0)
					{
						$sql="update course_section set active=0 where sections='".$cnm."'";
						ExecuteNonQuery($sql);	
						$cbx_2e="";
					}
				}
				
				if(isset($_POST["cbx_nu"]))
				{
					$cnm=GetSingleField("select courseno from courses where cid=".$_POST["ddlcourse"],"courseno");
					if(strpos($cnm," "))
					{
						$cnm=str_replace(" ","_",$cnm);
					}
					$cnm=$cnm."_".$_POST["cbx_nu"];
					$cntrec=CountRecords("select sections from course_section where sections='".$cnm."'");
					if($cntrec==0)
					{
						$sql="insert into course_section(sections,active,cid)values('".$cnm."',1,".$_POST["ddlcourse"].")";
			//			echo $sql;
						ExecuteNonQuery($sql);
					}
					else
					{
						$sql="update course_section set active=1 where sections='".$cnm."'";
				//		echo $sql;
						ExecuteNonQuery($sql);	
					}
				}
				else
				{
					$cnm=GetSingleField("select courseno from courses where cid=".$_POST["ddlcourse"],"courseno");
					if(strpos($cnm," "))
						{
							$cnm=str_replace(" ","_",$cnm);
						}
					$cnm=$cnm."_NU";
					$cntrec=CountRecords("select sections from course_section where sections='".$cnm."'");
					if($cntrec>0)
					{
						$sql="update course_section set active=0 where sections='".$cnm."'";
						ExecuteNonQuery($sql);	
						$cbx_nu="";
					}
				}
				
			}
	
}
?>
<?php 
if( !isset($_SESSION["rdbdeg"]))
{
	redirect_to("index.php");
}
?>
<?php
	if(isset($_POST["ddlcourse"]))
	{
		$sql="select sections from course_section where cid=".$_POST["ddlcourse"]." and active=1";
		//for graduate			
		//rdb name:	cbx_oa,cbx_ob,cbx_oc
			$cbx_oa="";
			$cbx_ob="";
			$cbx_oc="";
			$cbx_01="";
			$cbx_02="";
			$cbx_03="";
			$cbx_04="";
			$cbx_05="";
			$cbx_1e="";
			$cbx_headek="";
			$cbx_nu="";
			if($_SESSION["rdbdeg"]==1)
			{
					$csdata=ExecuteNonQuery($sql);
					while($csinfo = mysqli_fetch_assoc($csdata)) 
                    {
						$sec=substr($csinfo["sections"],-2);
						if($sec=="0A")
						{
							$cbx_oa="true";	
						}
						if($sec=="0B")
						{
							$cbx_ob="true";	
						}
						if($sec=="0C")
						{
							$cbx_oc="true";	
						}
					}
			}
			else
			{
					$csdata=ExecuteNonQuery($sql);
					while($csinfo = mysqli_fetch_assoc($csdata)) 
                    {
						$sec=substr($csinfo["sections"],-2);
						if($sec=="01")
						{
							$cbx_01="true";	
						}
						if($sec=="02")
						{
							$cbx_02="true";	
						}
						if($sec=="03")
						{
							$cbx_03="true";
						}
						if($sec=="04")
						{
							$cbx_04="true";
						}
						if($sec=="05")
						{
							$cbx_05="true";
						}
						if($sec=="1E")
						{
							$cbx_1e="true";
						}
						if($sec=="2E");
						{
							$cbx_headek="true";
						}
						if($sec=="NU")
						{
							$cbx_nu="true";
						}
					}
			}
		
	}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Add Course Section</title>
<link href="css/style.css" rel="stylesheet" type="text/css">

</head>

<body>
<?php
//echo $cbx_2e;?>
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
                	<p>This page create course sections.<span style="float:right;"><a href="#"><img src="images/view.png" alt="view" width="50px"></a></p>
                </div>
           	<div id="contentdetail">
			<form method="post" action="addcoursesection.php" name="form1">
			<table>
                  <tr>
            <td colspan="3" >
            <?php if (!empty($message)) {

				echo "<p class=message>" . $message . "</p>";}
				else {echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";}
			 ?>
			<?php if (!empty($errors)) 	
					{ 
			display_errors1($errors); 
			} ?>


            </td>
            </tr>
                   <tr>
                	<td>Select Course</td>
					<td>:</td>
                    <td>
                  		<?php 
						if($_SESSION["rdbdeg"]=="1")
							$data2 = ExecuteNonQuery("select cid,courseno,coursename from courses where courseno like 'G%'");
						else
							$data2 = ExecuteNonQuery("select cid,courseno,coursename from courses where courseno not like 'G%'");
						
                    ?>
                       <select onChange="document.form1.submit();" name="ddlcourse">
                      <option value="">Select</option>
					  <?php
                            while($info2 = mysqli_fetch_assoc($data2)) 
                   		 {?>
                            <option value="<?php echo $info2["cid"];?>" <?php if($info2["cid"]==$_POST["ddlcourse"]) echo "selected"; ?>><?php echo "(".$info2["courseno"].") ".$info2["coursename"];?></option>		
                    	<?php
                        }
                        ?>
                       </select>
                    </td>
                </tr>
                <tr>
                	<td>No of Sections</td>
					<td>:</td>
                    <td>
                   		<table>
                         <?php if($_SESSION["rdbdeg"]=="0")
						 {?>
                         	<tr>
                            <td>UnderGraduate</td>
                            <td>:</td>
                            <td>
                         
                            <input type="checkbox" name="cbx_01" value="01" <?php if($cbx_01=="true" && $cbx_01!="") echo "checked";?>>01
                            <input type="checkbox" name="cbx_02" value="02" <?php if($cbx_02=="true" && $cbx_02!="") echo "checked";?>>02
                            <input type="checkbox" name="cbx_03" value="03" <?php if($cbx_03=="true" && $cbx_03!="") echo "checked";?>>03
                            <input type="checkbox" name="cbx_04" value="04" <?php if($cbx_04=="true" && $cbx_04!="") echo "checked";?>>04
                            <input type="checkbox" name="cbx_05" value="05" <?php if($cbx_05=="true" && $cbx_05!="") echo "checked";?>>05
                            <input type="checkbox" name="cbx_1e" value="1E" <?php if($cbx_1e=="true" && $cbx_1e!="") echo "checked";?>>1E
                            <input type="checkbox" name="cbx_headek" value="2E" <?php if($cbx_headek=="true" && $cbx_headek!="") echo "checked";?>>2E
                            <input type="checkbox" name="cbx_nu" value="NU" <?php if($cbx_nu=="true" && $cbx_nu!="") echo "checked";?>>NU

                            </td>
                            </tr>
                            <?php }else {?>
                                    <tr>
                                    <td>Graduate</td>
                                    <td>:</td>
                                    <td>
                                    <input type="checkbox" name="cbx_oa" value="0A" <?php if($cbx_oa=="true" && $cbx_oa!="") echo " checked";?>>0A
                                    <input type="checkbox" name="cbx_ob" value="0B" <?php if($cbx_ob=="true" && $cbx_ob!="") echo " checked";?>>0B
                                    <input type="checkbox" name="cbx_oc" value="0C"  <?php if($cbx_oc=="true" && $cbx_oc!="") echo " checked";?>>0C
                                    </td>
                                    </tr>
                            <?php }?>
                            </table>
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