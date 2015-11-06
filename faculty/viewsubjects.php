<?php
		ob_start();
		ini_set('display_errors','off');
		require_once("includes/functions.php");
		require_once("includes/session.php");
		include("includes/DataAccess.php");  
?>
<?php
?>
<?php	if (!logged_in()) {
		redirect_to("index.php");
	}
?>
<?php
if(isset($_POST["btnshow"]))
{
	
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>View Subjects</title>
<link href="css/menu.css" rel="stylesheet" type="text/css">
<script src="js/jquery-latest.min.js"></script>
<script type='text/javascript' src='js/menu_jquery.js'></script>
<link href="css/style.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="containermain">
<div id="header">
	<div id="banner">
	<?php include('header.html'); ?>
    </div>
    <?php if($_SESSION["username"]!=null) {
  			include('menu.php');
        } ?>
</div>
<div id="wrapper">
<?php if($_GET["flag"]==1) { ?>
                <div id="divsuccess">
                    <img src="images/success.png" width="25px" height="25px"><?php echo $_GET["msg"]; ?> 
                </div>
                <?php } ?>
                <?php if($_GET["flag"]==2) { ?>
                <div id="diverror">
                    <img src="images/cross.png" width="25px" height="25px"><?php echo $_GET["msg"]; ?> 
                </div>
                <?php }?>
	<div id="viewsubjects">
    		
			<form method="post" action="viewsubjects.php">			
			<table border="1">
			<tr>
            <td <?php if($_SESSION["usrpermission"]=="1") echo "colspan=6"; else  echo "colspan=4";?> align="center" style="padding-top:20px;border:none;">        <b>Select Faculty:</b>
            <?php 
                    $data2 = ExecuteNonQuery('select uid,salutation,firstname,lastname from users');
                    ?>
          
            <select name="ddlfaculty">
            		<option>Select</option>
                    <?php    while($info2 = mysqli_fetch_assoc($data2)) 
                   		 {?>
            <option value="<?php echo $info2["uid"]; ?>" <?php if($info2["uid"]==$_POST["ddlfaculty"]) echo "selected";?>><?php echo $info2["salutation"]." ".$info2["firstname"]." ".$info2["lastname"];?></option>		

                         <?php }?>
            
            </select>
            <?php 
                    $data1 = ExecuteNonQuery('select semid,semname,active,year from semester order by sortorder');
                    ?>
         <b>Select Semester:</b>
            
            <select name="ddlsem">
                            <option value="">Select</option>
                      <?php
                            while($info1 = mysqli_fetch_assoc($data1)) 
                   		 {?>
                            <option value="<?php echo $info1["semid"]; ?>" <?php if($info1["active"]=="1") echo "selected";?>><?php echo $info1["semname"]." ".$info1["year"];?></option>		
                    	<?php
                        }
                        ?>
             		  </select>
            <input type="submit" value="Show" name="btnshow">
            </td>
          
                </tr>
        		<?php if(isset($_POST["btnshow"])){?>
                <tr>
                <th>Subject Section</th>
                <th>Subject Name</th>
                <th>Pre-Req</th>
                <th>Co-Reqs</th>
                <th>Credits</th>
				 </tr>
                
                <?php 
				$sql="SELECT cs.sections,c.coursename,c.credits,c.prereqid,c.coreqid FROM section s,course_section cs,courses c where s.csid=cs.csid and cs.cid=c.cid and s.uid=".$_POST["ddlfaculty"]." and s.semid=".$_POST["ddlsem"] ;
		//		echo $sql;
				$data3=ExecuteNonQuery($sql);
				  while($info3 = mysqli_fetch_assoc($data3)) 
                   		 {
							 if($info3["prereqid"]!="0")
									$prereq=GetSingleField("select pcname from pre_req where prereqid=".$info3["prereqid"],"pcname");
							   if($info3["coreqid"]!="0")
									$coreq=GetSingleField("select ccname from co_req where coreqid=".$info3["coreqid"],"ccname");
						?>
                         
          			      <tr>
               <td><?php echo $info3["sections"];?></td>
                         <td><?php echo $info3["coursename"];?></td>
                         <td><?php echo $prereq;?></td>
                         <td><?php echo $coreq;?></td>
                         <td><?php echo $info3["credits"];?></td>                                                                                                   
					      </tr>
          
            			<?php }
				?>
                <?php }
				else
				{?>
				 <tr>
                <th colspan="5">No courses assigned.</th>
				<?php 
				}
				?>
            </table>
            </form>
    </div>
</div>
<div id="footer">
	<?php include("footer.html"); ?>
</div>
</div>
</body>
</html>