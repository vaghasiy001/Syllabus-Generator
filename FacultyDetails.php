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
       <title>Faculty Details</title>
           <script>
	/*	$(document).ready(function(){
			$("#facultyinfo").hide();
			$("#btnshow").click(function(){
							$("#facultyinfo").show(2000);
			});
		});*/
		</script>

        </head>
    
        <body>
    <?php include("includes/nav.html");?>
            <main class="site-content">
        <?php
			$data1 = ExecuteNonQuery('select uid,firstname,lastname from users');
			?>
	<form method="post">
        <!-- About content loads first -->
        <article class="about-content">
                <select class="nav-course-select" name="ddlfaculty">
                        <option value="">Select Faculty...</option>
						<?php
                            while($info1 = mysqli_fetch_assoc($data1)) 
                   		 	{
							 ?>
                      		<option value="<?php echo $info1["uid"];?>" <?php if($_POST["ddlfaculty"]==$info1["uid"]) echo "selected";?>><?php echo $info1["firstname"]." ".$info1["lastname"];?></option>       
                       <?php }?>
                    </select>
		
    <input type="submit" value="View" class="btn btn-lg btn-primary" id="btnshow" name="submit">
         <?php if(isset($_POST["submit"]) && $_POST["ddlfaculty"]!="")
		 {
			 $sal=GetSingleField("select salutation from users where uid=".$_POST["ddlfaculty"],"salutation");
			 $fnm=GetSingleField("select firstname from users where uid=".$_POST["ddlfaculty"],"firstname");
			$lnm=GetSingleField("select lastname from users where uid=".$_POST["ddlfaculty"],"lastname");
			$email=GetSingleField("select email from users where uid=".$_POST["ddlfaculty"],"email");
			$office=GetSingleField("select office from users where uid=".$_POST["ddlfaculty"],"office");
			$officeno=GetSingleField("select officeno from users where uid=".$_POST["ddlfaculty"],"officeno");
			?>
            <h2 class="wow fadeIn">Select Faculty</h2>  
            <div id="facultyinfo">
        <div class="content-row wow fadeIn">   
			<div class="content-column">
				Instructor
			</div>
			<div class="content-column">
				<!--<figure class="instructor-img-holder"><img src="img/global/cannell.png" alt="cannell"></figure>-->
                <ul class="instructor-info">
                	<li><?php echo $sal." ".$fnm." ".$lnm;?></li>
                    <li><?php echo $email;?></li>
                </ul>
			</div>
		</div>
		
		<div class="content-row wow fadeIn">   
			<div class="content-column">
				Office
			</div>
			<div class="content-column">
				<ul><li><?php echo $office;?></li></ul>
			</div>
		</div>
		
		<div class="content-row wow fadeIn">   
			<div class="content-column">
				Phone
			</div>
			<div class="content-column">
				<ul><li><?php echo $officeno;?></li></ul>
			</div>
		</div>
		
		<div class="content-row wow fadeIn">   
			<div class="content-column">
				Office Hours
			</div>
            <?php
			$data2 = ExecuteNonQuery("select cday,starttime,endtime from facultyhours where type='office' and uid=".$_POST["ddlfaculty"]);
			?>

			<div class="content-column">
				<ul>
                <?php while($info2=mysqli_fetch_assoc($data2))
				{?>
                    <li><?php echo $info2["cday"]." ".$info2["starttime"]."-".$info2["endtime"]; ?></li>
                <?php }?>
                </ul>
			</div>
		</div>
		
		<div class="content-row wow fadeIn">   
			<div class="content-column">
				Class Times
			</div>
		<?php
			$data3 = ExecuteNonQuery("select roomno,csid,cday,starttime,endtime from facultyhours where type='lec' and uid=".$_POST["ddlfaculty"]);
			?>

        	<div class="content-column">
            	<ul>
                 <?php while($info3=mysqli_fetch_assoc($data3))
				{
				$csnm=GetSingleField("select sections from course_section where csid=".$info3["csid"],"sections");
				?>
                   <li><?php echo $info3["cday"]." ".$info3["starttime"]."-".$info3["endtime"]."(CourseNo:".$csnm.", Room:".$info3["roomno"]; ?></li>
            <?php }?>
                </ul>
			</div>
		</div>
        <?php }?>

        </article>
        </form>
        </main>
    
    <?php include("includes/footer.html");?>
        </body>
    
    </html>