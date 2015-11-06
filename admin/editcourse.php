<?php
ini_set("display_errors","off");

include("includes/connect/php");
include("includes/DataAccess.php");
include("includes/form_functions.php");
include("includes/functions.php");
include("includes/session.php");
if (!logged_in()) {
		redirect_to("index.php");
	}

if( isset($_POST['Submit']) )
 {   
 		$errors = array();
		$required_fields = array('txtcid','txtcnm','txtcredits','tadesc');
		$errors = array_merge($errors, check_required_fields($required_fields, $_POST));			
		if (empty($errors) ) 
		{

				$i=0;
				$j=0;
				if(isset($_POST["cbxactive"]))
					$i=1;
				$pid="";	
				$cid="";
				if(isset($_POST["txtnprereq"]) && $_POST["txtnprereq"]!="")
				{
					$pcnt=CountRecords("select * from pre_req where pcname like '".$_POST["txtnprereq"]."'");
					if($pcnt>0)
					{
						$pid=GetSingleField("select prereqid from pre_req where pcname like '".$_POST["txtnprereq"]."'","prereqid");
					}
					else
					{
						$sql="insert into pre_req(pcname)values('".$_POST["txtnprereq"]."')";
						//echo $sql;
						ExecuteNonQuery($sql);
						$pid=GetSingleField("select max(prereqid) as newid from pre_req","newid");
						
					}
					
				}
				else
				{
					if($_POST["ddlprereq"]!="")
							$pid=$_POST["ddlprereq"];
					else
							$pid="0";
				}
				if(isset($_POST["txtncoreq"]) && $_POST["txtncoreq"]!="")
				{
					$ccnt=CountRecords("select * from co_req where ccname like '".$_POST["txtncoreq"]."'");
					if($ccnt>0)
					{
						$pid=GetSingleField("select coreqid from co_req where ccname like '".$_POST["txtncoreq"]."'","coreqid");
					}
					else
					{
						$sql="insert into co_req(ccname)values('".$_POST["txtncoreq"]."')";
						ExecuteNonQuery($sql);
						$cid=GetSingleField("select max(coreqid) as newid from co_req","newid");
					}
				}
				else
				{
					if($_POST["ddlcoreq"]!="")
							$cid=$_POST["ddlcoreq"];
					else
							$cid="0";
				}
				
			
					
				$str="update courses set courseno='".$_POST["txtcid"]."',coursename='".$_POST["txtcnm"]."',description='".$_POST["tadesc"]."'";
				$str.=",prereqid=".$pid.",coreqid=".$cid.",credits=".$_POST["txtcredits"].",active=$i where cid=".$_GET["id"];
				ExecuteNonQuery($str);
			//	echo $str;
				redirect_to("viewcourses.php");
						
					
		}
		else
		{
			if (count($errors) == 1) {
				$message = "There was 1 error in the form.";
			} else {
				$message = "There were " . count($errors) . " errors in the form.";
			}
		}
 }
?>


<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Edit Unapprove Course</title>
<link href="css/style.css" rel="stylesheet" type="text/css">
  <script src="ckeditor/ckeditor.js"></script>
    <script type="text/javascript" src="js/jquery-2.1.3.min.js"></script>
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
			<div id="addcatdiv">
            	<div id="info">
                	<p>This page edit course details of image<span style="float:right;"><a href="viewcourses.php"><img src="images/view.png" alt="view" width="50px"></a></p>
                </div>
           	<div id="contentdetail">
			<form method="post" action="editcourse.php?id=<?php echo $_GET["id"];?>">
            	 <?php 
				$data = ExecuteNonQuery('select * FROM courses where cid='.$_GET["id"]);
							while($info = mysqli_fetch_assoc($data)) 
							{
						   		$cid=$info["courseno"];
								$cname=$info["coursename"];
								$credit=$info["credits"];
								$cdesc=$info["description"];
								$prereq=$info["prereqid"];
								$coreq=$info["coreqid"];
								$active=$info["active"];
								$did=$info["deptid"];
							}?>
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
			    <tr>
                	<td>Active??</td>
                  	<td>:</td>
                    <td><input type="checkbox" name="cbxactive" <?php if($active==1) echo "checked";?>></td>
                </tr>
            	<tr>
               		<td>Department<label style="color:red;">*</label></td>
                    <td>:</td>
                    <td>
      				<b>Computer & Information Science</b>
                    </td>
                </tr>
               <tr>
                	<td>Course Id<label style="color:red;">*</label></td>
					<td>:</td>
                    <td><input type="text" name="txtcid" value="<?php echo $cid;?>">[Ex: CIS_250 or GCIS_444]</td>
                </tr>
                <tr>
                	<td>Course Name<label style="color:red;">*</label></td>
					<td>:</td>
                    <td><input type="text" name="txtcnm" size="100" value="<?php echo $cname;?>"></td>
                </tr>
                <tr>
                	<td>Credit(s)<label style="color:red;">*</label></td>
					<td>:</td>
                    <td><input type="text" name="txtcredits" maxlength="2" size="2" value="<?php echo $credit; ?>">[Digits only]</td>
                </tr>
               
                <tr>
                	<td>Course Description<label style="color:red;">*</label></td>
					<td>:</td>
                    <td>      
                       <textarea name="tadesc" id="tadesc" rows="10" cols="30" >
                                     <?php echo $cdesc;?>
            						</textarea>
          						  <script>
							// Replace the <textarea id="editor1"> with a CKEditor
							// instance, using default configuration.
							CKEDITOR.config.toolbar_MA=[ ['Source','-','Cut','Copy','Paste','-','Undo','Redo','RemoveFormat','-','Link','Unlink','Anchor','-','Image','Table','HorizontalRule','SpecialChar'], '/', ['Format','Templates','Bold','Italic','Underline','-','Superscript','-',['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],'-','NumberedList','BulletedList','-','Outdent','Indent'] ];
							CKEDITOR.replace( 'tadesc',
            {   toolbar:'MA'    }
         );
						</script>
							</td>
                </tr>
                <tr>
                    <td>Pre-Requisites</td>
                    <td>:</td>
                    <td>
                    
                    <?php
						$data =ExecuteNonQuery("select * from pre_req");
					?>
                    <table>
                    <tr><td>New</td><td>:</td>
                    <td>
							<input type="text" size="60" name="txtnprereq">                   
                    </td>
                   </tr>
                    <tr>
                    <td>Existing</td><td>:</td>
                    <td> <select name="ddlprereq">
                    	<option value="0">Select</option>
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
                         <option value="<?php echo $info["prereqid"]; ?>" <?php if($info["prereqid"]==$prereq) echo " selected";?> ><?php echo $npcnm; ?></option>   
          				<?php }?>
                    </select>
                    </td>
                    
                    </tr>
                    </table>
                </tr>
                
                <td>Co-requisites</td>
                <td>:</td>
                <td>
                	<table>
                    <tr>
                    <td width="60">New</td><td>:</td>
                    <td>
							<input type="text" size="60" name="txtncoreq">                   
                    </td>
                   </tr>
                    <tr>
                   		<td>Existing</td>
                        <td>:</td>
                        <td>
                        <?php
                            $data =ExecuteNonQuery("select * from co_req");
                        ?>
                        <select name="ddlcoreq">
                            <option value="0">Select</option>
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
                             <option value="<?php echo $info["coreqid"]; ?>" <?php if($info["coreqid"]==$coreq) echo " selected";?>><?php echo $nccnm; ?></option>   
                            <?php }?>
                        </select>
    
                        
                        </td>
                    </tr>
                    </table>
					
                
                </td>
                </tr>
                
                <tr>
                	<td colspan="3" align="center"><input type="submit" value="Submit Changes" name="Submit"></td>
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