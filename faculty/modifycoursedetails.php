<?php
    ob_start();
    ini_set('display_errors','off');
    require_once("includes/functions.php");
    require_once("includes/session.php");
    include("includes/DataAccess.php");  
    
    if(isset($_GET["csid"]))
    {
        $_POST["ddlcnm"]=$_GET["csid"];	
    }
	
    if (!logged_in()) {
        redirect_to("index.php");
    }

    if(!isset($_SESSION["ddlsem3"]))
    {
        redirect_to("index.php");
    }

    if(isset($_POST["btnsubmit"]))
    {
        $cno="";
        $cid = "";
        $special="";
        $sql = "SELECT c.courseno, c.special, c.cid FROM course_section cs,courses c WHERE c.cid=cs.cid AND cs.csid=".$_POST["ddlcnm"];
        $data=ExecuteNonQuery($sql);
        while($info = mysqli_fetch_assoc($data)) 
        {
            $cno = $info["courseno"];
            $cid = $info["cid"];
            $special = $info["special"];
        }        
        if(isset($cno))
        {
            $myc=substr($cno,0,1);
            if($myc=="C")
            {
                $ver=GetSingleField("select version from fileinfo where active=1 and ftype='matrix'","version");
                $stpoint=CountRecords("select cno from course_mappings where cno='".str_replace(" ","_",$cno)."' and version='".$ver."'");
                if($stpoint!=0)
                    $stpoint++;
            }
            else
            {
                $ver=GetSingleField("select version from fileinfo where active=1 and ftype='hmatrix'","version");
                $stpoint=CountRecords("select cno from gcourse_mappings where cno='".str_replace(" ","_",$cno)."' and version='".$ver."'");
                if($stpoint!=0)
                    $stpoint++;
            }
        }
        else
        {
            $stpoint=1;
        }
        
        if($special == true){
            $sql = "update courses set description='".mysqli_real_escape_string($connection,$_POST["tacdetails"])."',prereqid=".$_POST["ddlprereq"].",coreqid=".$_POST["ddlcoreq"]." where cid=".$cid;
            ExecuteNonQuery($sql);
        }
        
        $savedId = "";
        for($i=$stpoint;$i<=10;$i++)
        {
            $tval="txtclo_".$i."_0";
            if(isset($_POST[$tval]) && trim($_POST[$tval])!="")
            {
                $sql="insert into fclo(name,semid,uid,csid)values('".$_POST[$tval]."',".$_SESSION["ddlsem3"].",".$_SESSION["userid"].",".$_POST["ddlcnm"].")";
                $insertedId = ReturnInsertedID($sql);
                $savedId = $savedId.$insertedId.",";
            }
            else
            {
                $sql="select * from fclo where (semid=".$_SESSION["ddlsem3"]." and uid=".$_SESSION["userid"]." and csid=".$_POST["ddlcnm"].")";
                $cntrec=CountRecords($sql);
                if($cntrec!="0")
                {
                    $sql1=ExecuteNonQuery("select * from fclo where (semid=".$_SESSION["ddlsem3"]." and uid=".$_SESSION["userid"]." and csid=".$_POST["ddlcnm"].")");
                    while($data4=mysqli_fetch_assoc($sql1))
                    {
                        $tval1="txtclo_".$i."_".$data4["fcloid"];
                        if(isset($_POST[$tval1]) && trim($_POST[$tval1])!="")
                        {
                            $sql="update fclo set name='".$_POST[$tval1]."' where fcloid=".$data4["fcloid"];
                            ExecuteNonQuery($sql);
                            $savedId = $savedId.$data4["fcloid"].",";
                        }
                    }
                }
            }
        }

        if($savedId != "") {
            $savedId = rtrim($savedId, ',');
            $sql="delete from fclo where fcloid not in(".$savedId.") and semid=".$_SESSION["ddlsem3"]." and uid=".$_SESSION["userid"]." and csid=".$_POST["ddlcnm"];
            ExecuteNonQuery($sql);
        }

        $savedIds = "";
        for($i=1;$i<=$_POST["hfcamtot"];$i++)
        {
            $tval1="txtcam_".$i."_0";
            $tval2="tacam_".$i."_0";
            if(isset($_POST[$tval1]) && trim($_POST[$tval1])!="")
            {
                $sql="insert into cams(camname,camdetails,csid,uid,semid)values('".$_POST[$tval1]."','".$_POST[$tval2]."',".$_POST["ddlcnm"].",".$_SESSION["userid"].",".$_SESSION["ddlsem3"].")";
                $insertedId = ReturnInsertedID($sql);
                $savedIds = $savedIds.$insertedId.",";
            }
            else
            {
                $hfval = "hfcam_".$i;
                if(isset($_POST[$hfval]) && trim($_POST[$hfval])!="")
                {
                    $tval1="txtcam_".$i."_".$_POST[$hfval];
                    $tval2="tacam_".$i."_".$_POST[$hfval];
                    if(trim($_POST[$tval1]) != "") {
                        $sql="update cams set camname='".$_POST[$tval1]."',camdetails='".$_POST[$tval2]."' where camid=".$_POST[$hfval];
                        ExecuteNonQuery($sql);
                        $savedIds = $savedIds.$_POST[$hfval].",";
                    }
                }
            }	
        }

        if($savedIds != "") {
            $savedIds = rtrim($savedIds, ',');
            $sql="delete from cams where camid not in(".$savedIds.") and semid=".$_SESSION["ddlsem3"]." and uid=".$_SESSION["userid"]." and csid=".$_POST["ddlcnm"];
            ExecuteNonQuery($sql);
        }

        $sql="update section set reqmaterials='".mysqli_real_escape_string($connection,$_POST["tabooks"])."',website='".mysqli_real_escape_string($connection,$_POST["taresources"])."',coursepolicy='".mysqli_real_escape_string($connection,$_POST["tacp"])."',attpolicy='".mysqli_real_escape_string($connection,$_POST["taap"])."',academicintegrity='".mysqli_real_escape_string($connection,$_POST["taai"])."',coursetopics='".mysqli_real_escape_string($connection,$_POST["tacc"])."' where (csid=".$_POST["ddlcnm"]." and uid=".$_SESSION["userid"]." and semid=".$_SESSION["ddlsem3"].")";
        ExecuteNonQuery($sql);
        $str="";
        if(isset($_POST["cbx1"]))
                $str.="M";
        if(isset($_POST["cbx2"]))
                $str.=" T";
        if(isset($_POST["cbx3"]))
                $str.=" W";
        if(isset($_POST["cbx4"]))
                $str.=" Th";
        if(isset($_POST["cbx5"]))
                $str.=" F";
        $st=$_POST["ddlsthr"].":".$_POST["ddlstmin"].$_POST["ddlstampm"];
        $en=$_POST["ddlenhr"].":".$_POST["ddlenmin"].$_POST["ddlenampm"];
        $cnt=CountRecords("select * from facultyhours where csid=".$_POST["ddlcnm"]." and uid=".$_SESSION["userid"]." and semid=".$_SESSION["ddlsem3"]);

        if($cnt==0)
        {
            $sql="insert into facultyhours(csid,starttime,endtime,type,cday,roomno,uid,semid)values(".$_POST["ddlcnm"].",'".$st."','".$en."','lec','".$str."','".$_POST["txtcloc"];
            $sql.="',".$_SESSION["userid"].",".$_SESSION["ddlsem3"].")";
            ExecuteNonQuery($sql);
        }
        else
        {
            $sql="update facultyhours set starttime='".$st."',endtime='".$en."',cday='".$str."',roomno='".$_POST["txtcloc"]."' where csid=".$_POST["ddlcnm"]." and uid=".$_SESSION["userid"]." and semid=".$_SESSION["ddlsem3"];
            ExecuteNonQuery($sql);
        }
        $_SESSION["mapcsid"]=$_POST["ddlcnm"];
        $query_string = array('flag'=>1,
                              'msg'=>'Course details are saved successfully.');
        redirect_to("mapcams.php?".http_build_query($query_string));
    }

    if(isset($_POST["ddlcnm"]))
    {
        if($_POST["ddlcnm"]!=0) {
            $cno=GetSingleField("select cid from course_section where csid=".$_POST["ddlcnm"],"cid");
            $sql="select c.courseno,c.coursename,c.special,c.credits,c.prereqid,c.coreqid,c.description from courses c,course_section cs where c.cid=cs.cid and c.cid=".$cno;
            $data=ExecuteNonQuery($sql);
            $cnm="";
            $special="";
            $desc="";
            $credits="";
            $prereq="";
            $prereqid="";
            $coreq="";
            $coreqid="";
            $sem="";
            $cno="";
            $classloc="";
            $cday="";
            $sttimehr="";
            $sttimemin="";
            $sttimeampm="";
            $entimehr="";
            $entimemin="";
            $entimeampm="";
            if(mysqli_num_rows($data)>0)
            {
                while($info = mysqli_fetch_assoc($data)) 
                {
                    $cnm=$info["coursename"];
                    $desc=$info["description"];
                    $special = $info["special"];
                    $credits=$info["credits"];
                    $prereqid=$info["prereqid"];
                    $coreqid=$info["coreqid"];
                    if($info["prereqid"]=="0")
                        $prereq="None";
                    else
                        $prereq=GetSingleField("select pcname from pre_req where prereqid=".$info["prereqid"],"pcname");
                    if($info["coreqid"]=="0")
                        $coreq="None";
                    else
                        $coreq=GetSingleField("select ccname from co_req where coreqid=".$info["coreqid"],"ccname");
                    $cno=$info["courseno"];

                    $sql="select starttime,endtime,cday,roomno from facultyhours where csid=".$_POST["ddlcnm"]." and semid=".$_SESSION["ddlsem3"]." and uid=".$_SESSION["userid"];
                    $dt2=ExecuteNonQuery($sql);

                    while($inf2 = mysqli_fetch_assoc($dt2)) 
                    {
                        $classloc=$inf2["roomno"];
                        $cday=explode(" ",$inf2["cday"]);
                        $tmp=explode(":",$inf2["starttime"]);
                        $sttimehr=$tmp[0];
                        $sttimemin=substr($tmp[1],0,2);
                        $sttimeampm=substr($tmp[1],-2);
                        $tmp1=explode(":",$inf2["endtime"]);
                        $entimehr=$tmp1[0];
                        $entimemin=substr($tmp1[1],0,2);
                        $entimeampm=substr($tmp1[1],-2);
                    }
                }
                if(isset($_SESSION["ddlsem3"]))
                {
                    $sql="select semname,year from semester where semid=".$_SESSION["ddlsem3"];
                    $data2=ExecuteNonQuery($sql);
                    while($info = mysqli_fetch_assoc($data2)) 
                    {
                        $sem=$info["semname"]." ".$info["year"];
                    }
                }
            }
        }
    }
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Modify Course Details and Mappings</title>
<link href="css/menu.css" rel="stylesheet" type="text/css">
<script src="js/jquery-latest.min.js"></script>
<script src="ckeditor/ckeditor.js"></script>

<link href="css/style.css" rel="stylesheet" type="text/css">
<script type="text/javascript">
$(document).ready(function(e) {
    $("#btnsubmit").click(function(e){
        var taresources=document.getElementById("taresources").value;
        var tagp=document.getElementById("tagp").value;
        var tacp=document.getElementById("tacp").value;
        var taap=document.getElementById("taap").value;
        var taai=document.getElementById("taai").value;
        var tacc=document.getElementById("tacc").value;
        if(taresources=="")
        {
            alert("Please enter course resources");
            return false;
        }
        if(tacp=="")
        {
            alert("Please enter course policies");
            return false;
        }
        if(taap=="")
        {
            alert("Please enter attendance policies");
            return false;
        }
        if(taai=="")
        {
            alert("Please enter academic integrity");
            return false;
        }
        if(tacc=="")
        {
            alert("Please enter course calendar with topics");
            return false;
        }	
    });
});
$(document).ready(function(e) {
    var camcnt;
    var  clocnt;
    var clotot=document.getElementById("hfclotot").value;
    if(clotot==0)
        clocnt=0;
    else
        clocnt = clotot;
    var camtot=document.getElementById("hfcamtot").value;
    if(camtot==0)
        camcnt=0;
    else
        camcnt = camtot;
    $("#addcamrow").click(function(e){
        if(camcnt==10)
        {
            alert("Can not add more than 10 row");
        }
        else
        {
            camcnt++;
            var temp1="txtcam_"+(++camtot)+"_0";
            var temp2="tacam_"+camtot+"_0";
            $('#camtable tr:last').after('<tr><td><input type="text" id='+temp1+' name='+temp1+'></td><td><textarea rows=2 cols=40 id='+temp2+' name='+temp2+'></textarea></td></tr>');
            $('#hfcamtot').val(camcnt);
        }
    });
    $("#removecamrow").click(function(e){
        if(camcnt>3)
        {
            $('#camtable tr:last').remove();
            camcnt--;
            --camtot;
            $('#hfcamtot').val(camcnt);
        }
    });
    $("#addclorow").click(function(e){
        if(clocnt==0)
        {
            var temp="txtclo_0_0";
            $('#clotable').prepend('<tr><td><input type="text" size=70 id='+temp+' name='+temp+'></td></tr>');
            clocnt++;
        }
        else
        {
            if(clocnt==10)
            {
                alert("Can not add more than 10 row");
            }
            else
            {
                var temp="txtclo_"+(++clotot)+"_0";
                $('#clotable tr:last').after('<tr><td><input type="text" size=70 id='+temp+' name='+temp+'></td></tr>');
                clocnt++;
            }
        }
        $("#hfclotot").val(clocnt);
    });

    $("#removeclorow").click(function(e){
        if(clocnt>3)
        {
            $("#clotable tr:last").remove();
            --clotot;
            clocnt--;
            $("#hfclotot").val(clocnt);
        }
    });
});
</script>
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
    		<?php 
				$per=GetSingleField('select permission from users where userid=$_POST["permission"]',"permission");
			?>
			<form method="post" name="form1" action="modifycoursedetails.php">			
			<table border="1">
							<?php 
					$data1 = ExecuteNonQuery('select csid,name from section where semid='.$_SESSION["ddlsem3"]." and uid=".$_SESSION["userid"]." and active=1");
			        ?>
   
            <tr>
            	<td colspan="3">
               	  Course:
             
                    <select name="ddlcnm" onChange="document.form1.submit();">
               			<option value="">Select</option>     
                     <?php
                            while($info1 = mysqli_fetch_assoc($data1)) 
                   		 {
							 $cid=GetSingleField("select sections from course_section where csid=".$info1["csid"],"sections");
							 ?>
                            <option value="<?php echo $info1["csid"]; ?>" <?php if($_POST["ddlcnm"]==$info1["csid"] || $_GET["csid"]==$info1["csid"]) echo "selected"; ?>><?php echo "(".$cid.")".$info1["name"]; ?></option>		
                    	<?php
                        }
                        ?>
             		 
                    </select>
      
		<!--			<input type="submit" value="View Details" name="btnviewdetails">      -->   
                </td>
             
            </tr>
        	<tr>
            	<td>Course Title</td>
              	<td>:</td>
                <td><input type="text" value="<?php if(isset($cnm) && $cnm!="") echo $cno." ".$cnm; ?>" disabled size="70"></td>
            </tr>
        	<tr>
            	<td>Credit Hours</td>
                <td>:</td>
                <td><input type="text" value="<?php if(isset($credits) && $credits!="") echo $credits." credit(s)"; ?>" disabled></td>
             </tr>
         	<tr>
            	<td>Semester</td>
                <td>:</td>
                <td><input type="text" value="<?php if(isset($sem) && $sem!="") echo $sem; ?>" disabled></td>
            </tr>
         	<tr>
            	<td>Class Location</td>
                <td>:</td>
                <td><input type="text" value="<?php if(isset($classloc) && $classloc!="") echo $classloc;?>" name="txtcloc"></td>
            </tr>
     	    <tr>
            	<td>Class Day</td>
                <td>:</td>
                <td>
                    <input type="checkbox" value="M" <?php if(in_array("M",$cday)) echo "checked"; ?> name="cbx1">M
                    <input type="checkbox" value="T" <?php if(in_array("T",$cday)) echo "checked"; ?> name="cbx2">T
                    <input type="checkbox" value="W" <?php if(in_array("W",$cday)) echo "checked"; ?> name="cbx3">W
                    <input type="checkbox" value="Th" <?php if(in_array("TH",$cday)) echo "checked"; ?> name="cbx4">TH
                    <input type="checkbox" value="F" <?php if(in_array("F",$cday)) echo "checked"; ?> name="cbx5">F
                </td>
            </tr>
             <tr>
            	<td>Class Time</td>
                <td>:</td>
                <td><b>Start Time </b>
                <select name="ddlsthr">
                    <?php
					for($i=1;$i<=12;$i++)
					{
						if(strlen($i)=="1")
						{
							$i="0".$i;
						}
						?>
                        <option <?php if($sttimehr==$i) echo "selected"?> value="<?php echo $i;?>"><?php echo $i;?></option>
                        <?php }?>
                    </select>
                   : <select name="ddlstmin">
                    <?php
					for($i=0;$i<=60;$i+=5)
					{
						if(strlen($i)=="1")
						{
							$i="0".$i;
						}
						?>
                        <option <?php if($sttimemin==$i) echo "selected"?> value="<?php echo $i;?>"><?php echo $i;?></option>
                        <?php }?>
                                       
                    </select>
                    <select name="ddlstampm">
                   <option <?php if($sttimeampm=="AM") echo "selected"?> value="AM">AM</option>
                   <option <?php if($sttimeampm=="PM") echo "selected"?> value="PM">PM</option>                    
                    </select>
               <b>     End Time</b>
                    <select name="ddlenhr">
                    <?php
					for($i=1;$i<=12;$i++)
					{
						if(strlen($i)=="1")
						{
							$i="0".$i;
						}
						?>
                        <option  <?php if($entimehr==$i) echo "selected"?> value="<?php echo $i;?>"><?php echo $i;?></option>
                        <?php }?>
                                       
                    </select>
                  :  <select name="ddlenmin">
                    <?php
					for($i=0;$i<=60;$i+=5)
					{
						if(strlen($i)=="1")
						{
							$i="0".$i;
						}
						?>
                        <option  <?php if($entimemin==$i) echo "selected"?> value="<?php echo $i;?>"><?php echo $i;?></option>
                        <?php }?>
                                       
                    </select>
                    
                    <select name="ddlenampm">
                   <option  <?php if($entimeampm=="AM") echo "selected"?> value="AM">AM</option>
                   <option  <?php if($entimeampm=="PM") echo "selected"?> value="PM">PM</option>                    
                    </select>
                </td>
            </tr>
            <tr>
            		<td>Course Description</td>
                    <td>:</td>
            		<td>
						   <textarea name="tacdetails" rows="10" cols="50" <?php if($special==false){ echo "disabled";} ?>>
                               <?php
                                    if(isset($desc) && $desc!="")
                                    {
										echo $desc;
                                    }
                                ?>
							</textarea>
                                    <script>
							// Replace the <textarea id="editor1"> with a CKEditor
							// instance, using default configuration.
							CKEDITOR.config.toolbar_MA=[ ['Source','-','Cut','Copy','Paste','-','Undo','Redo','RemoveFormat','-','Link','Unlink','Anchor','-','Image','Table','HorizontalRule','SpecialChar'], '/', ['Format','Templates','Bold','Italic','Underline','-','Superscript','-',['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],'-','NumberedList','BulletedList','-','Outdent','Indent'] ];
							CKEDITOR.replace( 'tacdetails',
            {   toolbar:'MA'    }
         );
							//CKEDITOR.replace( 'tacdetails' );
						</script>
          			</td>
            </tr>
       		<tr>
            		<td>Course Learning Objectives</td>
                    <td>:</td>
                    <td>
                    The student will be able to<br>
                    <div style="width:100%;float:left;">
                    <table align="left" style="width:60%" id="clotable">
                    <?php
					$tmpcr=substr($cno,0,1);
					$cnt2=CountRecords("select * from fclo where semid=".$_SESSION["ddlsem3"]." and uid=".$_SESSION["userid"]." and csid=".$_POST["ddlcnm"]);
					if($tmpcr=="C")
					{
						$ver=GetSingleField("SELECT version FROM fileinfo where active=1 and ftype='matrix'","version");
						$cntrec=CountRecords("select * from course_mappings where cno='".str_replace(" ","_",$cno)."' and version='".$ver."'");
				
						if($cntrec>0)
						{
							$data2=ExecuteNonQuery("select mappingname from course_mappings where cno='".str_replace(" ","_",$cno)."' and version='".$ver."'");
							$cnt=1;
						
								  while($info2 = mysqli_fetch_assoc($data2)) 
									 {
									?>
								<tr>
									<td>
									<input type="text" name="<?php echo "txtclo_".$cnt;?>" value="<?php echo $info2["mappingname"];?>" size="70" disabled>
									<input type="hidden" value="<?php echo ($cntrec+$cnt2);?>" name="hfclotot" id="hfclotot">
									</td>
								</tr>
							   <?php 
								$cnt++;
							   }
						}
						else
						{
							$cntrec=CountRecords("select * from course_mappings where cno='".str_replace(" ","_",$cno)."' and version='".$ver."'");
						?>
                        		<input type="hidden" value="<?php echo ($cntrec+$cnt2);?>" name="hfclotot" id="hfclotot">
							
						<?php 
						}
					}
					else
					{
						$ver=GetSingleField("SELECT version FROM fileinfo where active=1 and ftype='gmatrix'","version");
						$cntrec=CountRecords("select * from gcourse_mappings where cno='".str_replace(" ","_",$cno)."' and version='".$ver."'");

						if($cntrec>0)
						{
							$data2=ExecuteNonQuery("select mappingname from gcourse_mappings where cno='".str_replace(" ","_",$cno)."' and version='".$ver."'");
							$cnt=1;
							
								  while($info2 = mysqli_fetch_assoc($data2)) 
									 {
											?>
										<tr>
											<td>
											<input type="text" name="<?php echo "txtclo_".$cnt;?>" value="<?php echo $info2["mappingname"];?>" size="70" disabled>
											<input type="hidden" value="<?php echo ($cntrec+$cnt2);?>" name="hfclotot" id="hfclotot">
											</td>
										</tr>
									   <?php 
										$cnt++;
								   }
						}
						else
						{?>
                        					<input type="hidden" value="<?php echo ($cntrec+$cnt2);?>" name="hfclotot" id="hfclotot">
						<?php 
						}
					}
					if($cnt2>0)
                                        {
                                            $data5=ExecuteNonQuery("select * from fclo where semid=".$_SESSION["ddlsem3"]." and uid=".$_SESSION["userid"]." and csid=".$_POST["ddlcnm"]);
                                            while($info5 = mysqli_fetch_assoc($data5)) 
                                            {
                                                $cnt++;
                                              ?>
                                            <tr>
                                                <td>
                                                <input type="text" value="<?php echo $info5["name"];?>" size="70" id="<?php echo "txtclo_".$cnt."_".$info5["fcloid"];?>" name="<?php echo "txtclo_".$cnt."_".$info5["fcloid"];;?>">
                                                </td>
                                            </tr>		
                                        <?php 
                                            }
                                        }
					?>
                    </table>
                    <table align="left" style="width:40%;float:left">
                    <tr>
                    	<td><input type="button" value="Add row" id="addclorow"></td>
                    	<td><input type="button" value="Remove row" id="removeclorow"></td>
                    </tr>
                    </table>
                    </div>
                    </td>
            </tr>
            <tr>
                <td>Co-requisites</td>
                <td>:</td>
                <td>
                    <?php if($special == false) { ?>
                    <input type="text" value="<?php if(isset($coreq) && $coreq!="") echo $coreq; ?>" disabled>
                    <?php }
                          else { ?>
                    <select name="ddlcoreq">
                        <option value="0">Select</option>
                    <?php
                    $data=ExecuteNonQuery("select * from co_req");    
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
                         <option value="<?php echo $info["coreqid"]; ?>" <?php if($info["coreqid"]==$coreqid) echo " selected";?>><?php echo $nccnm; ?></option>
                          <?php } }?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Prerequisites</td>
                <td>:</td>
                <td>
                    <?php if($special == false) { ?>
                    <input type="text" value="<?php if(isset($prereq) && $prereq!="") echo $prereq; ?>" disabled>
                    <?php }
                          else { ?>
                    <select name="ddlprereq">
                        <option value="0">Select</option>
                    <?php
                    $data=ExecuteNonQuery("select * from pre_req");    
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
                         <option value="<?php echo $info["prereqid"]; ?>" <?php if($info["prereqid"]==$prereqid) echo " selected";?> ><?php echo $npcnm; ?></option>   
                          <?php } }?>
                    </select>
                </td>
            </tr>
            <tr>
            <?php
			if(isset($_POST["ddlcnm"]))
			$web=GetSingleField("select website from section where csid=.".$_POST["ddlcnm"]." and uid=".$_SESSION["userid"]." and semid=".$_SESSION["ddlsem3"],"website");
			 ?>
            		<td>Resources</td>
            		<td>:</td>
            		<td>
                            <?php
				$tares=GetSingleField("select website from section where csid=".$_POST["ddlcnm"]." and uid=".$_SESSION["userid"]." and semid=".$_SESSION["ddlsem3"],"website");
				?>
                      <textarea name="taresources" rows="10" cols="50" id="taresources">
                      <?php if(isset($tares) && $tares!="") { echo $tares; } else { ?>
                    	<table border="1" cellpadding="1" cellspacing="1" style="height:29px; width:500px">
                            <tbody>
                                <tr>
                                    <td>
                                    <ul>
                                        <li>Course Website</li>
                                    </ul>
                                    </td>
                                    <td>https://gannon.blackboard.com</td>
                                </tr>
                            </tbody>
                        </table>
                      <?php }?>
                       </textarea>
                        <script>
							// Replace the <textarea id="editor1"> with a CKEditor
							// instance, using default configuration.
							CKEDITOR.config.toolbar_MA=[ ['Source','-','Cut','Copy','Paste','-','Undo','Redo','RemoveFormat','-','Link','Unlink','Anchor','-','Image','Table','HorizontalRule','SpecialChar'], '/', ['Format','Templates','Bold','Italic','Underline','-','Superscript','-',['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],'-','NumberedList','BulletedList','-','Outdent','Indent'] ];
							CKEDITOR.replace( 'taresources',
            {   toolbar:'MA'    }
         );
						</script>
                     </td>
            </tr>
			<tr>
            <?php
			if(isset($_POST["ddlcnm"]))
			$reqmaterials=GetSingleField("select reqmaterials from section where csid=".$_POST["ddlcnm"]." and uid=".$_SESSION["userid"]." and semid=".$_SESSION["ddlsem3"],"reqmaterials");
			 ?>
            		<td>Required Textbooks</td>
            		<td>:</td>
            		<td>
                         <textarea name="tabooks" rows="10" cols="50" id="tabooks">
                         <?php if(isset($reqmaterials) && $reqmaterials!="") echo $reqmaterials;?>
                         </textarea>
                          <script>
							// Replace the <textarea id="editor1"> with a CKEditor
							// instance, using default configuration.
							CKEDITOR.config.toolbar_MA=[ ['Source','-','Cut','Copy','Paste','-','Undo','Redo','RemoveFormat','-','Link','Unlink','Anchor','-','Image','Table','HorizontalRule','SpecialChar'], '/', ['Format','Templates','Bold','Italic','Underline','-','Superscript','-',['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],'-','NumberedList','BulletedList','-','Outdent','Indent'] ];
							CKEDITOR.replace( 'tabooks',
            {   toolbar:'MA'    }
         );
						</script>
          		    </td>
            </tr>
            <tr>
            		<td>Course Assessment Method</td>
            		<td>:</td>
            		<td>
                    <?php
					$cnt=0;
					if(isset($_POST["ddlcnm"]))
					{
					$sql="select camid,camname,camdetails from cams where csid=".$_POST["ddlcnm"]." and uid=".$_SESSION["userid"]." and semid=".$_SESSION["ddlsem3"];
					$data3=ExecuteNonQuery($sql);
					//echo $sql;
					$cntrec1=mysqli_num_rows($data3);
					$cnt=mysqli_num_rows($data3);
				//	echo $cnt;
					}
					 ?>
                    	<div style="width:100%">
 	             		<table align="left" id="camtable" style="float:left;width:60%">
                        	<tr>
                            	<th align="left">Name</th>
                                <th align="left">Description</th>
                            </tr>
                            <?php if($cnt>0)
							{
								$cnt=1;
								 while($info3 = mysqli_fetch_assoc($data3)) 
                   		 		{
								
								?>
							<tr>
                            	<td>
                                <input type="hidden" name="<?php echo "hfcam_$cnt";?>" value="<?php echo $info3["camid"]?>">
                                <input type="text" name="<?php echo "txtcam_$cnt"."_".$info3["camid"];?>" value="<?php echo $info3["camname"]; ?>"></td>
                            	<td><textarea rows="2" name="<?php echo "tacam_$cnt"."_".$info3["camid"];; ?>" cols="40"><?php echo $info3["camdetails"];?></textarea></td>
                          </tr>	
							<?php	
								 $cnt++;
								 }
                            }else
                                {?>
                                
                            <tr>
                            	<td><input type="text" name="txtcam_1_0"></td>
                            	<td><input type="text" name="tacam_1_0"></td>
                            </tr>
                            <tr>
                            	<td><input type="text" name="txtcam_2_0"></td>
                            	<td><input type="text" name="tacam_2_0"></td>
                            </tr>
                            <tr>
                            	<td><input type="text" name="txtcam_3_0"></td>
                            	<td><input type="text" name="tacam_3_0"></td>
                           </tr>
                      	 	<?php }?>
                        </table>
                        <input type="hidden" value="<?php if(!isset($_POST["btnsubmit"])) {echo (($cntrec1 > 0) ? $cntrec1 : 3);} ?>" name="hfcamtot" id="hfcamtot" />
 						<table style="float:left;width:40%">
                        	<tr>
                            	<td rowspan="4" valign="bottom" >
                                	<input type="button" value="Add Row" id="addcamrow">
                                	<input type="button" value="Remove Row" id="removecamrow">
                                </td>
                            </tr>
                        </table>
                        </div>
                    </td>
            </tr>
           
            <tr>
           
            	<td>Course Policies</td>
            	<td>:</td>
           		<td>
                <?php
				$cp=GetSingleField("select coursepolicy from section where csid=".$_POST["ddlcnm"]." and uid=".$_SESSION["userid"]." and semid=".$_SESSION["ddlsem3"],"coursepolicy");
				?>
                <textarea name="tacp" id="tacp" rows="10" cols="50">
                        <?php if(isset($cp) && $cp!="") echo $cp;?>
    				
                </textarea>
            			  <script>
							// Replace the <textarea id="editor1"> with a CKEditor
							// instance, using default configuration.
						CKEDITOR.config.toolbar_MA=[ ['Source','-','Cut','Copy','Paste','-','Undo','Redo','RemoveFormat','-','Link','Unlink','Anchor','-','Image','Table','HorizontalRule','SpecialChar'], '/', ['Format','Templates','Bold','Italic','Underline','-','Superscript','-',['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],'-','NumberedList','BulletedList','-','Outdent','Indent'] ];
							CKEDITOR.replace( 'tacp',
            {   toolbar:'MA'    }
         );
						</script>
	                </td>
            </tr>
            <tr>
            	<td>Attendance Policies</td>
            	<td>:</td>
           		<td>
                 <?php
			if(isset($_POST["ddlcnm"]))
			$attp=GetSingleField("select attpolicy from section where csid=".$_POST["ddlcnm"]." and uid=".$_SESSION["userid"]." and semid=".$_SESSION["ddlsem3"],"attpolicy");
			 ?><!-- attendance policy text area-->
                <textarea name="taap" id="taap" rows="10" cols="50">
              <?php if(isset($attp) && $attp!="") echo $attp; else {?>
    
                <u>Attendance -</u>
                <ul>
                <li>I expect students to attend and actively participate.</li>
                <li>Three <b>inadequately excused absences</b> from class will result in a grade of F.</li>
               	<li>Attendance implies attention. The student will devote his/her entire attention to the subject matter being covered during the class period. The practice of Instant Messaging and use of cell phone or other device during class time is a breach of the attendance policy.</li>
                <li>
                You need to be in class, and paying attention in order to know which assignment is to be undertaken as well as the specified due date for the assignment.<br>
                <b>You are not to be using the computers unless indicated by me.</b>
                </li>
                <li>
                I expect students to be courteous to one another, listen attentively, exhibit mature, adult behaviours, to the class in general, to one another, and to me.
                </li>
                </ul>
                <?php }?>
                </textarea>
            			  <script>
							// Replace the <textarea id="editor1"> with a CKEditor
							// instance, using default configuration.
							CKEDITOR.config.toolbar_MA=[ ['Source','-','Cut','Copy','Paste','-','Undo','Redo','RemoveFormat','-','Link','Unlink','Anchor','-','Image','Table','HorizontalRule','SpecialChar'], '/', ['Format','Templates','Bold','Italic','Underline','-','Superscript','-',['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],'-','NumberedList','BulletedList','-','Outdent','Indent'] ];
							CKEDITOR.replace( 'taap',
            {   toolbar:'MA'    }
         );
						</script>
	                </td>
            </tr>
            <tr>
            
            	<td>Academic Integrity</td>
            	<td>:</td>
           		<td>
                  <?php
			if(isset($_POST["ddlcnm"]))
			$ai=GetSingleField("select academicintegrity from section where csid=".$_POST["ddlcnm"]." and uid=".$_SESSION["userid"]." and semid=".$_SESSION["ddlsem3"],"academicintegrity");
			 ?>
                <textarea name="taai" id="taai" rows="10" cols="50">
              <?php if(isset($ai) && $ai!="") echo $ai; else {?>
    
                All submissions must be the result of each individual's effort, unless I authorize team effort.
                <ul>
                	<li>
                    Cheating on any examination, quiz, or assignment will be, in the <b>first instance</b>, cause for <b>a failing grade (0)</b> on the component.  Upon <b>a second occurrence, a failing grade for the course (F)</b> will be given, at least.
                    </li>
       				<li>
                    Depending upon the severity of the situation, further action may be pursued.
                    </li>
       				<li>
                    This policy applies to the <b>individual(s) receiving the information</b> as well as to <b>individual(s) supplying the information.</b>                   </li>
                    <li>
                    Talking over your ideas and getting comments about your logic or your errors are <b>not</b> forms of cheating.
                    </li>
                    <li>
                    Having other individuals <b>write</b> or <b>code sections</b> of your work, <b>copying</b> and <b>editing</b> others' work or code, or collaborating together to get a "consensus" product for an assignment <b>are</b> examples of cheating. 
                    </li>
                    <li>
                    If ever you are in doubt as to whether I will perceive the assistance you give a student as an instance of cheating, then ask the instructor first about it.
                    </li>
                </ul>
                Please refer to Academic Integrity Policy section in the catalog for further details.
            <?php }?>
                </textarea>
            			  <script>
							// Replace the <textarea id="editor1"> with a CKEditor
							// instance, using default configuration.
							CKEDITOR.config.toolbar_MA=[ ['Source','-','Cut','Copy','Paste','-','Undo','Redo','RemoveFormat','-','Link','Unlink','Anchor','-','Image','Table','HorizontalRule','SpecialChar'], '/', ['Format','Templates','Bold','Italic','Underline','-','Superscript','-',['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],'-','NumberedList','BulletedList','-','Outdent','Indent'] ];
							CKEDITOR.replace( 'taai',
            {   toolbar:'MA'    }
         );
						</script>
	                </td>
            </tr>
       		<tr>
            	<td>Course Calendar with Topics</td>
            	<td>:</td>
           		<td>
                <?php
			if(isset($_POST["ddlcnm"]))
			$cctopics=GetSingleField("select coursetopics from section where csid=".$_POST["ddlcnm"]." and uid=".$_SESSION["userid"]." and semid=".$_SESSION["ddlsem3"],"coursetopics");
			 ?>
                <textarea name="tacc" id="tacc" rows="10" cols="50">
                <?php if(isset($cctopics) && $cctopics!="") echo $cctopics;?>
    
                </textarea>
            			  <script>
							// Replace the <textarea id="editor1"> with a CKEditor
							// instance, using default configuration.
							CKEDITOR.config.toolbar_MA=[ ['Source','-','Cut','Copy','Paste','-','Undo','Redo','RemoveFormat','-','Link','Unlink','Anchor','-','Image','Table','HorizontalRule','SpecialChar'], '/', ['Format','Templates','Bold','Italic','Underline','-','Superscript','-',['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],'-','NumberedList','BulletedList','-','Outdent','Indent'] ];
							CKEDITOR.replace( 'tacc',
            {   toolbar:'MA'    }
         );
						</script>
	                </td>
            </tr>
           	<tr>
            <td colspan="3" align="center"><input type="submit" id="btnsubmit" name="btnsubmit" onClick="document.form1.submit();" value="Proceed to Course Assessment Method Mappings"<?php if($_POST["ddlcnm"]==0) {echo " disabled";} ?>></td>
            </tr>
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
