<?php
		ob_start();
		ini_set('display_errors','off');
		require_once("inc/functions.php");
		include("inc/DataAccess.php"); 
?>
<!DOCTYPE html>
<html>

    <head>
		<?php include("includes/header.html");?>
        <title>Course Details</title>
        <style type="text/css">
		.form-control {
    width:300px;
}
		</style>
	        <script>
		/*$(document).ready(function(){
			$("#courseinfo").hide();
			$("#btnshow").click(function(){
							$("#courseinfo").show(2000);
			});
		});*/
		</script>

    </head>

    <body>
<?php include("includes/nav.html");?>
        <main class="site-content">
	<form method="post">
	<!-- About content loads first -->
	<article class="about-content">
    <?php
		$sem=GetSingleField("select semid from semester where active=1","semid");
			$data1 = ExecuteNonQuery('select sectionid,csid,name from section where semid='.$sem." and active=1");
			?>
			<select class="nav-course-select" name="ddlcourse">
					<option value="">Select Course...</option>
					<?php
                            while($info1 = mysqli_fetch_assoc($data1)) 
                   		 {
							 $cid=GetSingleField("select sections from course_section where csid=".$info1["csid"],"sections");
							 ?>
                            <option value="<?php echo $info1["sectionid"]; ?>" <?php if($_POST["ddlcourse"]==$info1["sectionid"]) echo "selected"; ?>><?php echo "(".$cid.")".$info1["name"]; ?></option>		
                    	<?php
                        }
                        ?>
     			</select>
    <input type="submit" value="View" class="btn btn-lg btn-primary" id="btnshow" name="submit">
    <?php if(isset($_POST["submit"]) && $_POST["ddlcourse"]!="")
	{

	$sql="select * from section where sectionid=".$_POST["ddlcourse"];
	$data2=ExecuteNonQuery($sql);
	$csnm="";
	$cnm="";
	$sal="";
	$fnm="";
	$lnm="";
	$prnm="";
	$crnm="";
	$credits="";
	$rno="";
	$cday="";
	$sttime="";
	$entime="";
	$cdesc="";
	$reqm="";
	$web="";
	$uid="";
	$csid="";
	$gp="";
	$cp="";
	$ap="";
	$ai="";
	$ct="";
		while($info2=mysqli_fetch_assoc($data2))
		{
			$csid=$info2["csid"];
			$uid=$info2["uid"];
			$pid=GetSingleField("select prereqid from courses where cid=(select cid from course_section where csid=".$info2["csid"].")","prereqid");
			$cid=GetSingleField("select coreqid from courses where cid=(select cid from course_section where csid=".$info2["csid"].")","coreqid");
			$cdesc=GetSingleField("select description from courses where cid=(select cid from course_section where csid=".$info2["csid"].")","description");
			$prnm=GetSingleField("select pcname from pre_req where prereqid=".$pid,"pcname");
			$crnm=GetSingleField("select ccname from co_req where coreqid=".$cid,"ccname");
			$credits=GetSingleField("select credits from courses where cid=(select cid from course_section where csid=".$info2["csid"].")","credits");
			$csnm=GetSingleField("select sections from course_section where csid=".$info2["csid"],"sections");
			$cnm=$info2["name"];
			$reqm=$info2["reqmaterials"];
			$sal=GetSingleField("select salutation from users where uid=".$info2["uid"],"salutation");
			$fnm=GetSingleField("select firstname from users where uid=".$info2["uid"],"firstname");
			$lnm=GetSingleField("select lastname from users where uid=".$info2["uid"],"lastname");
			$email=GetSingleField("select email from users where uid=".$info2["uid"],"email");
			$rno=GetSingleField("select roomno from facultyhours where csid=".$info2["csid"],"roomno");
			$cday=GetSingleField("select cday from facultyhours where csid=".$info2["csid"],"cday");
			$sttime=GetSingleField("select starttime from facultyhours where csid=".$info2["csid"],"starttime");
			$entime=GetSingleField("select endtime from facultyhours where csid=".$info2["csid"],"endtime");
			$web=$info2["website"];
			$gp=$info2["gradingpolicy"];
			$cp=$info2["coursepolicy"];
			$ap=$info2["attpolicy"];
			$ai=$info2["academicintegrity"];
			$ct=$info2["coursetopics"];
		}

	?>
     	<h2 class="wow fadeIn">Select Course</h2>  
		<div id="courseinfo">
        <div class="content-row wow fadeIn">   
			<div class="content-column">
				Course Name
			</div>
			<div class="content-column">
            <?php echo $csnm.": ". $cnm;?>
				<!--CIS 355: Visual Database Programming-->
			</div>
		</div>
		
        <div class="content-row wow fadeIn">   
			<div class="content-column">
				Instructor
			</div>
			<div class="content-column">
				<ul class="instructor-info">
                    <li><?php echo $sal.".".$fnm." ".$lnm;?></li>
                    <li><?php echo $email;?></li>
                </ul>
			</div>
		</div>
		
		<div class="content-row wow fadeIn">   
			<div class="content-column">
				Course Info
			</div>
			<div class="content-column">
				<ul><li>Prerequisites: <?php if($prnm=="") echo "None"; else echo $prnm;?></li>
                <li>Co-requisites:<?php if($crnm=="") echo "None"; else echo $crnm; ?></li>
                <li>Credits: <?php echo $credits;?></li>
                <li><?php echo "Room No: ".$rno." [".$cday."] ".$sttime." - ".$entime;?></li></ul>
			</div>
		</div>
		
		<div class="content-row wow fadeIn">   
			<div class="content-column">
				Course Description
			</div>
			<div class="content-column"  style="text-align:justify;">
				This course emphasizes the development of database systems and their application in multi-tiered systems. The student develops desktop and web-based database applications. Typical coverage includes event-driven programming.
			</div>
		</div>
		
		<div class="content-row wow fadeIn">   
			<div class="content-column">
				Course Objectives
			</div>
			<div class="content-column">
				<ul><li>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</li><li>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</li><li>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</li></ul>
			</div>
		</div>
		
		<div class="content-row wow fadeIn">   
			<div class="content-column">
				Required Materials
			</div>
			<div class="content-column">
				 <?php echo $reqm;?>
                
			</div>
		</div>
		<div class="content-row wow fadeIn">   
			<div class="content-column">
				Resources
			</div>
			<div class="content-column">
				<?php echo $reqm;?>
               
			</div>
		</div>
		
		<div class="content-row wow fadeIn">   
			<div class="content-column">
				Assessment Methods
			</div>
			<div class="content-column">
				<?php
				$sql="select camname from cams where csid=".$csid." and uid=".$uid." and semid=".$sem;
				$data3=ExecuteNonQuery($sql);
				?>
                <ul>
                <?php while($info3=mysqli_fetch_assoc($data3)){
					?>
                	<li><?php echo $info3["camname"];?></li>
                    <?php }?>
                </ul>
			</div>
		</div>
		
		<div class="content-row wow fadeIn">   
			<div class="content-column">
				Grading Policy
			</div>
			<div class="content-column">
				<?php echo $gp;?>
			</div>
		</div>
	<!--	
		<div class="content-row wow fadeIn">   
			<div class="content-column">
				Grading Scale
			</div>
			<div class="content-column">
				<ul class="grading-scale-list"><li>Undergraduates</li><li>A : 90 ~ 100</li><li>B+ : 85 ~ 89</li><li>B : 80 ~ 84</li><li>C+ : 75 ~ 79</li><li>C : 70 ~ 74</li><li>D : 60 ~ 69</li><li>F : 0 ~ 59</li></ul><ul class="grading-scale-list"><li>Graduates</li><li>A+ : 95 ~ 100</li><li>A : 90 ~ 94</li><li>A- : 87 ~ 89</li><li>B+ : 83 ~ 86</li><li>B : 80 ~ 82</li><li>B- : 77 ~ 79</li><li>C+ : 73 ~ 76</li><li>C : 70 ~ 72</li></ul>
			</div>
		</div>
    -->    
		<div class="content-row wow fadeIn">   
			<div class="content-column">
				Course Policies
			</div>
			<div class="content-column"  style="text-align:justify;">
				<?php echo $cp;?>
			
			</div>
		</div>
        <div class="content-row wow fadeIn">   
			<div class="content-column">
				Attendance Policies
			</div>
			<div class="content-column"  style="text-align:justify;">
				<?php echo $ap;?>
			
			</div>
		</div>
		<div class="content-row wow fadeIn">   
			<div class="content-column">
				Academic Intigrity
			</div>
			<div class="content-column"  style="text-align:justify;">
			<?php echo $ai;?>
			</div>
		</div>
        
		<div class="content-row wow fadeIn">   
			<div class="content-column">
				Course Topics
			</div>
			<div class="content-column"  style="text-align:justify;">
				   <?php echo $ct;?>
			</div>
		</div>
	</div>

	</article>
    <?php }?>
</form>
	</main>

<?php include("includes/footer.html");?>
    </body>

</html>