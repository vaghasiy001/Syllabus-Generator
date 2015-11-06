<?php
ini_set("display_errors","off");

include("includes/connect.php");
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
				if(isset($_POST["cbxapprove"]))
					$j=1;
				if($j==1)
				{
					if($_POST["ddldept"]!="")
					{
						$preid=0;
						$coid=0;
						if(!empty(trim($_POST["txtprereq"])) && trim($_POST["txtprereq"])!="")
						{ 
							$str="insert into pre_req(pcname)values('".$_POST["txtprereq"]."')";
							$cnt=ExecuteNonQuery($str);
							if($cnt==1)
								$preid=mysqli_insert_id();
						}
						if(!empty(trim($_POST["txtcoreq"])) && trim($_POST["txtcoreq"])!="")
						{ 
							$str="insert into co_req(ccname)values('".$_POST["txtcoreq"]."')";
							$cnt=ExecuteNonQuery($str);
							if($cnt==1)
								$coid=mysqli_insert_id();
						}
						$reccnt=CountRecords("select * from courses where courseno like '".trim($_POST["txtcid"])."'");
						if($reccnt==0)
						{	
					$str="insert into courses(courseno,coursename,description,prereqid,coreqid,credits,active,deptid)values('";
					$str.=$_POST["txtcid"]."','".$_POST["txtcnm"]."','".str_replace("'","\'",$_POST["tadesc"])."',$preid,$coid,".$_POST["txtcredits"].",$i,".$_POST["ddldept"].")";
						if (mysqli_query($connection, $str)) 
							{
 							   $csid= mysqli_insert_id($connection);
    					//	  echo "New record created successfully. Last inserted ID is: " . $last_id;
								$tmp=str_replace(" ","_",$_POST["txtcid"])."_0A";
								$str="insert into course_section(sections,active,cid)values('".$tmp."',1,".$csid.")";
								ExecuteNonQuery($str);
							
								$str="delete from catelog_temp where id=".$_GET["id"];
								ExecuteNonQuery($str);
								redirect_to("viewcourses.php");
				
							} 
						else 
							{
    						echo "Error: " . $sql . "<br>" . mysqli_error($conn);
							}
						}
						else
							{
								$str="delete from catelog_temp where id=".$_GET["id"];
								$cnt=ExecuteNonQuery($str);
								if($cnt==1)
								{
									redirect_to("viewuacourses.php");
								}
							}
						
					}
					else
					{
						show_alert("Please select department");
					}
				}
				else
				{
				
				}
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
<title>Edit UnApproved Course</title>
<link href="css/style.css" rel="stylesheet" type="text/css">
  <script src="ckeditor/ckeditor.js"></script>
    <script type="text/javascript" src="js/jquery-2.1.3.min.js"></script>
          <script type="text/javascript">
		  function addprereq()
		  {
		 	window.location.replace("addprereq.php?name="+document.getElementById("txtprereq").value);
	
		  }
		  function addcoreq()
		  {
		 	window.location.replace("addcoreq.php?name="+document.getElementById("txtcoreq").value);
		 }
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
			<div id="addcatdiv">
            	<div id="info">
                	<p>This page edit unapproved course details <span style="float:right;"><a href="viewuacourses.php"><img src="images/view.png" alt="view" width="50px"></a></p>
                </div>
           	<div id="contentdetail">
			<form method="post" action="edituacourse.php?id=<?php echo $_GET["id"];?>">
            	 <?php 
				$data = ExecuteNonQuery('select * FROM catelog_temp where id='.$_GET["id"]);
							while($info = mysqli_fetch_assoc($data)) 
							{
						   		$cid=$info["courseid"];
								$cname=$info["coursename"];
								$credit=$info["credits"];
								$sem=$info["semester"];
								$cdesc=$info["coursedesc"];
								$prereq=$info["prerequisite"];
								$coreq=$info["corequisite"];
								$approve=$info["approve"];
								$ctmp=substr($cid,0,3);
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
                    <td><input type="checkbox" name="cbxactive" checked></td>
                </tr>
            	<tr>
               		<td>Department<label style="color:red;">*</label></td>
                    <td>:</td>
                    <td>
      				<?php
						$data =ExecuteNonQuery("select * from dept where active=1");
					?>
                  
                    <select name="ddldept">
                    	<option value="">Select</option>
          			<?php 	
					while($info = mysqli_fetch_assoc($data)) 
						{
						?>	
                        
                         <option value="<?php echo $info["deptid"]; ?>" <?php if(strtoupper(substr($info["deptname"],0,8))=="COMPUTER") echo "selected";?> ><?php echo $info["deptname"]; ?></option>   
        				<?php }?>
                    </select>
                    </td>
                </tr>
               <tr>
                	<td>Course Id<label style="color:red;">*</label></td>
					<td>:</td>
                    <td><input type="text" name="txtcid" value="<?php echo $cid;?>">[Ex: CIS 250 or GCIS 444]</td>
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
                       <textarea name="tadesc" id="editor1" rows="10" cols="30" >
                                     <?php echo $cdesc;?>
            						</textarea>
          						  <script>
							// Replace the <textarea id="editor1"> with a CKEditor
							// instance, using default configuration.
							CKEDITOR.replace( 'editor1' );
						</script>
							</td>
                </tr>
                  <tr>
                	<td>Prerequisite</td>
					<td>:</td>
                    <td>
                    <?php
					$flag=0;
						if($prereq!="")
						{
							$cntrec=CountRecords("select * from pre_req where pcname='".$prereq."'");
							if($cntrec==0)
							{
								$flag=1;
							}
						}
						else
						{
							$flag=1;
						}
					?>
                   <?php if($flag==1)
				   {?>
                    <input type="text" value="<?php echo $prereq;?>" id="txtprereq" name="txtprereq">
                   <a href="#" onClick="addprereq();" >Add Pre Requisite</a>
                   <?php 
				   	}else
					{
				   ?>
                	<label><?php echo $prereq;?>
                   <?php }?>
                    </td>
                </tr>
                  <tr>
                	<td>Corequisite</td>
					<td>:</td>
                    <td>
                    <?php
					$flag=0;
						if($coreq!="")
						{
							$cntrec=CountRecords("select ccname from co_req where ccname='".$coreq."'");
							if($cntrec==0)
							{
								$flag=1;
							}
						}
						else
						{
							$flag=1;
						}
					?>
                    
                   <?php if($flag==1)
				   {?>
                    <input type="text" value="<?php echo $coreq;?>" name="txtcoreq" id="txtcoreq">
                   	<a onClick="addcoreq();" href="#">Add Co-Requisite</a>
                   <?php 
				   	}else
					{
				   ?>
                   <label><?php echo $coreq;?></label>
                   <?php }?>
                    </td>
                </tr>
                  <tr>
                	<td style="width:30%;">Approve??</td>
                  	<td>:</td>
                    <td><input type="checkbox" name="cbxapprove" <?php //if($approve==1) echo "checked";?> checked></td>
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