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
        <title>Faculty Timings</title>
        <script>
	/*	$(document).ready(function(){
			$(".table").hide();
			$("#btntang").click(function(){
							$(".table").show(3000);
			});
		});*/
		</script>
	</head>

    <body>
<?php include("includes/nav.html");?>
        <main class="site-content">
	<?php
		  $semid=GetSingleField("select semid from semester where active=1","semid");
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

    <input type="submit" value="View" class="btn btn-lg btn-primary" id="btntang" name="submit">
<?php if(isset($_POST["submit"]) && $_POST["ddlfaculty"]!=""){
		$fnm=GetSingleField("select firstname from users where uid=".$_POST["ddlfaculty"],"firstname");
		$lnm=GetSingleField("select lastname from users where uid=".$_POST["ddlfaculty"],"lastname");
		$email=GetSingleField("select email from users where uid=".$_POST["ddlfaculty"],"email");
		$office=GetSingleField("select office from users where uid=".$_POST["ddlfaculty"],"office");
		$officeno=GetSingleField("select officeno from users where uid=".$_POST["ddlfaculty"],"officeno");
		$semnm=GetSingleField("select semname from semester where active=1","semname");
		$year=GetSingleField("select year from semester where active=1","year");
	?>
    	<h2 class="wow fadeIn">Faculty Schedule</h2>  
	</article>
    <div class="table-responsive">
    <table class="table table-bordered table-hover">
    <tr>
        <td colspan="3"><img src="img/ganonlogo.jpg"></td>
         <td colspan="3"><h2>Semester:<?php echo $semnm." ".$year;?></h2></td>
        </tr>
          <tr>
        	<td colspan="3" align="left">
        	<table align="left" style="color:rgb(128,0,0);">
            	<tr>
                <td>Name:</td>
                 <td><?php echo "<b>".$fnm." ".$lnm."</b>";?></td>
                </tr>
                <tr>
                <td>Department:</td>
               <td><b>Computer Science</b></td>
                </tr>
                <tr>
                <td>Email:</td>
                <td><?php echo "<b>".$email."</b>"; ?></td>
                </tr>
            </table>
            </td>
            <td colspan="3" align="left" style="color:rgb(128,0,0);" valign="top">
                   <table align="left">
                        <tr>
                        <td>Office:</td>
                         <td><?php echo "<b>".$office."</b>";?></td>
                        </tr>
                        <tr>
                        <td>Phone No:</td>
                       <td><?php echo "<b>".$officeno."</b>";?></td>
                        </tr>
                       </table>
            </td>
        </tr>
    <thead>
        <tr>
        <th>MONDAY</th>
        <th>TUESDAY</th>
        <th>WEDNESDAY</th>
        <th>THURSDAY</th>
         <th>FRIDAY</th>
         <th>SATURDAY</th>         
       </tr>
       </thead>
        <tbody>
        <?php
	   $sql='select * from facultyhours where semid='.$semid." and uid=".$_POST["ddlfaculty"]." order by STR_TO_DATE(starttime, '%l:%i%p')";
	   $data = ExecuteNonQuery($sql);
	   $cnt=mysqli_num_rows($data);
	   if($cnt>0)
	   {
	   ?>
       <?php 
	   while($info=mysqli_fetch_assoc($data)){
		$tp=$info["type"];
		$cday=explode(" ",$info["cday"]);   
		   ?>
        <tr>
            <td align="center">
				<?php if(in_array("M",$cday))
				{	
				if($tp=='lec'){
                        $csnm=GetSingleField("select sections from course_section where csid=".$info["csid"],"sections");
                        echo $csnm."<br>";
                    }else
                    {
                        echo "Office Hours<br>";
                    }
					echo $info["starttime"]." to ".$info["endtime"];
				}
				?>
            </td>
            <td align="center">
            <?php if(in_array("T",$cday))
				{	
				if($tp=='lec'){
                        $csnm=GetSingleField("select sections from course_section where csid=".$info["csid"],"sections"); 
						echo $csnm."<br>";
                    }else
                    {
                        echo "Office Hours<br>";
                    }
					echo $info["starttime"]." to ".$info["endtime"];
				}
				?>
            </td>
            <td align="center">
            <?php if(in_array("W",$cday))
				{	
				if($tp=='lec'){
                        $csnm=GetSingleField("select sections from course_section where csid=".$info["csid"],"sections");
						 echo $csnm."<br>";
                    }else
                    {
                        echo "Office Hours<br>";
                    }
					echo $info["starttime"]." to ".$info["endtime"];
				}
				?>
            
            </td>
            <td align="center">
            <?php if(in_array("Th",$cday))
				{	
				if($tp=='lec'){
                        $csnm=GetSingleField("select sections from course_section where csid=".$info["csid"],"sections");
						 echo $csnm."<br>";
                    }else
                    {
                        echo "Office Hours<br>";
                    }
					echo $info["starttime"]." to ".$info["endtime"];
				}
				?>
            </td>
            <td  align="center">
            <?php if(in_array("F",$cday))
				{	
				if($tp=='lec'){
                        $csnm=GetSingleField("select sections from course_section where csid=".$info["csid"],"sections"); echo $csnm."<br>";
                    }else
                    {
                        echo "Office Hours<br>";
                    }
					echo $info["starttime"]." to ".$info["endtime"];
				}
				?>
            </td>
             <td align="center">
             <?php if(in_array("S",$cday))
				{	
				if($tp=='lec'){
                        $csnm=GetSingleField("select sections from course_section where csid=".$info["csid"],"sections");
						 echo $csnm."<br>";
                    }else
                    {
                        echo "Office Hours<br>";
                    }
					echo $info["starttime"]." to ".$info["endtime"];
				}
				?>
             </td>
        </tr>
        <?php }
	   }
	   else
	   {
		   for($i=0;$i<4;$i++)
		   {
			echo "<tr>";
				echo "<td>&nbsp;</td>";
				echo "<td>&nbsp;</td>";
				echo "<td>&nbsp;</td>";
				echo "<td>&nbsp;</td>";
				echo "<td>&nbsp;</td>";
				echo "<td>&nbsp;</td>";
			echo "</tr>";
																			   
		   }
	   }
		?>
    	</tbody>
    </table>
    </div>
    <?php }?>
    </form>
    
	</main>

<?php include("includes/footer.html");?>
    </body>

</html>