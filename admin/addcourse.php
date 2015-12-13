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
	if(isset($_POST['spCourse'])){  $spActive = $_POST['spCourse'];}
	$i=0;
	if(isset($_POST["cbxactive"]))
		$i=1;
	$cid=$_POST["txtcid"];
	$cnm=$_POST["txtcnm"];
	$cdet=$_POST["tacdetails"];
	if($_POST["ddlpre"]=="")
		$prereq=0;
	else
		$prereq=$_POST["ddlpre"];
	if($_POST["ddlcoreq"]=="")
		$coreq=0;
	else
		$coreq=$_POST["ddlcoreq"];
	if(trim($_POST["txtprereq"])!="" && isset($_POST["txtprereq"]))
	{
		$cntrec=CountRecords("select * from pre_req where pcname like '".$_POST["txtprereq"]."'");
		if($cntrec==1)
		{
			$prereq=GetSingleField("select prereqid from pre_req where pcname like '".$_POST["txtprereq"]."'","prereqid");
		}
		else
		{
			$sql="insert into pre_req(pcname)values('".str_replace("'","\'",$_POST["txtprereq"])."')";
			ExecuteNonQuery($sql);
			$prereq=GetSingleField("SELECT MAX(prereqid) as newid from pre_req","newid");	
		//	echo $prereq;
		}
	}
	if(trim($_POST["txtcoreq"])!="" && isset($_POST["txtcoreq"]))
	{
		$cntrec=CountRecords("select * from co_req where ccname like '".$_POST["txtcoreq"]."'");
		if($cntrec==1)
		{
			$coreq=GetSingleField("select coreqid from co_req where ccname like '".$_POST["txtcoreq"]."'","coreqid");
		}
		else
		{
			$sql="insert into co_req(ccname)values('".str_replace("'","\'",$_POST["txtcoreq"])."')";
			$cnt=ExecuteNonQuery($sql);
		
			$coreq=GetSingleField("SELECT MAX(coreqid) as newid from co_req","newid");	
		}
	}
	$credits=$_POST["txtcredits"];
//	$cdet=str_replace("'","\'",$cdet);
	$sql="insert into courses(courseno,coursename,special,description,prereqid,coreqid,credits,active,deptid)values(";
	$sql.="'".strtoupper($cid)."','".$cnm."',$spActive,'$cdet',$prereq,$coreq,$credits,$i,1)";
//	echo $sql;
	ExecuteNonQuery($sql);
	redirect_to("viewcourses.php");
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Add course</title>
<link href="css/style.css" rel="stylesheet" type="text/css">
        <script src="ckeditor/ckeditor.js"></script>
        <script type="text/javascript" src="js/jquery-2.1.3.min.js"></script>
        <script>
        	$(document).ready(function(e) {
            });
        </script>
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
            	<div id="info">
                	<p>This page allows you to add course.<span style="float:right;"><a href="viewcourses.php"><img src="images/view.png" alt="view" width="50px"></a></p>
                </div>
           	<div id="contentdetail">
			<form method="post" action="addcourse.php">
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
                   <?php 
					$dept=GetSingleField("select deptid from users where uid=".$_SESSION["a_userid"],"deptid");
					$deptname= GetSingleField("select deptname from dept where deptid=".$dept,"deptname");
					?>
                <tr>
                	<td>Department</td>
					<td>:</td>
                    <td style="font-weight:bold;"><?php echo $deptname;?></td>
                </tr>
         
                <tr>
                	<td>Active</td>
					<td>:</td>
                    <td><input type="checkbox" name="cbxactive" checked>
                    </td>
                </tr>
                <tr>
                	<td>Course Id</td>
					<td>:</td>
                    <td><input type="text" name="txtcid" >[Ex: CIS 250 or GCIS 444]</td>
                </tr>
                <tr>
                	<td>Course Name</td>
					<td>:</td>
                    <td><input type="text" name="txtcnm" size="100"></td>
                </tr>
                <tr>
					<td colspan="3">Tick if it's a Special Course : <input type="checkbox" value="1" name="spCourse"></td>
                </tr>
                <tr>
                	<td>Credit(s)</td>
					<td>:</td>
                    <td><input type="text" name="txtcredits" maxlength="2" size="2">[Digits only]</td>
                </tr>
                <tr>
                	<td>Course Details</td>
					<td>:</td>
                    <td>      
                       <textarea name="tacdetails" id="editor1" rows="10" cols="50">
                                     <?php echo $desc;?>
            						</textarea>
          			  <script>
							// Replace the <textarea id="editor1"> with a CKEditor
							// instance, using default configuration.
				//			CKEDITOR.replace( 'editor1' );
							CKEDITOR.config.toolbar_MA=[ ['Source','-','Cut','Copy','Paste','-','Undo','Redo','RemoveFormat','-','Link','Unlink','Anchor','-','Image','Table','HorizontalRule','SpecialChar'], '/', ['Format','Templates','Bold','Italic','Underline','-','Superscript','-',['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],'-','NumberedList','BulletedList','-','Outdent','Indent'] ];
							CKEDITOR.replace( 'editor1',
            {   toolbar:'MA'    }
         );
						</script>
							</td>
                </tr>
         		<tr>
                    <td>Pre-Requisites</td>
                    <td>:</td>
                    <td><input type="text" name="txtprereq" value="<?php if(isset($_POST["txtprereq"])!="") echo $_POST["txtprereq"];?>">
                    <?php
						$data =ExecuteNonQuery("select * from pre_req");
					?>
                    <select name="ddlpre">
                    	<option value="">Select</option>
          			<?php 	
							$npcnm="";

					while($info = mysqli_fetch_assoc($data)) 
						{
							if(strlen($info["pcname"])>50)
							{
								$npcnm=substr($info["pcname"],0,50)."..";							
							}
							else
							{
									$npcnm=$info["pcname"];							
							}
						?>	
                         <option value="<?php echo $info["prereqid"]; ?>" <?php if($info["prereqid"]==$_POST["ddlpre"]) " selected"; ?>><?php echo $npcnm; ?></option>   
          				<?php }?>
                    </select>
                    </td>
                </tr>
                
                <td>Co-requisites</td>
                <td>:</td>
                <td><input type="text" name="txtcoreq" value="<?php if(isset($_POST["txtcoreq"])!="") echo $_POST["txtcoreq"];?>">
					<?php
                            $data =ExecuteNonQuery("select * from co_req");
                        ?>
                        <select name="ddlcoreq">
                            <option value="">Select</option>
                        <?php 	
						$nccnm="";
					
                        while($info = mysqli_fetch_assoc($data)) 
                            {
							if(strlen($info["ccname"])>50)
							{
								$nccnm=substr($info["ccname"],0,50)."..";							
							}
							else
							{
									$nccnm=$info["ccname"];							
							}	
                            ?>	
                           <option value="<?php echo $info["coreqid"]; ?>" <?php if($info["coreqid"]==$_POST["ddlcoreq"]) " selected"; ?>><?php echo $nccnm; ?></option>   
                            <?php }?>
                        </select>
     
                
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