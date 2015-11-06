<?php
ini_set('display_errors','off');
	require_once("includes/functions.php");
		require_once("includes/session.php");
		include("includes/DataAccess.php");
		
?>
<? ob_start(); ?>
<?php	if (!logged_in()) {
		redirect_to("index.php");
	}
?>

<?php 
$fnm=GetSingleField("select firstname from users where uid=".$_SESSION["userid"],"firstname");
$lnm=GetSingleField("select lastname from users where uid=".$_SESSION["userid"],"lastname");
$email=GetSingleField("select email from users where uid=".$_SESSION["userid"],"email");
$office=GetSingleField("select office from users where uid=".$_SESSION["userid"],"office");
$officeno=GetSingleField("select officeno from users where uid=".$_SESSION["userid"],"officeno");
$semnm=GetSingleField("select semname from semester where active=1","semname");
$year=GetSingleField("select year from semester where active=1","year");
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">

<title>Door Schedule</title>
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
  <div class="Section1">
  <?php 
  $semid=GetSingleField("select semid from semester where active=1","semid");
 	  ?>
  <table class="table table-bordered table-hover" style="color:rgb(128,0,0);" border="1" width="100%">
  		<tr>
        <td colspan="3"><img src="images/ganonlogo.jpg"></td>
         <td colspan="3"><table width="100%"><tr><td><h2>Semester: <?php echo $semnm." ".$year;?></h2></td><td align="right"><a href="exportschedule.php"><img src="images/export.png"></a></td></tr></table></td>
        </tr>
        <tr>
        	<td colspan="3" align="left">
        	<table align="left" style="color:rgb(128,0,0);width:100%;">
            	<tr>
                <td>Name:</td>
                 <td><?php echo "<b>".$fnm." ".$lnm."</b>";?></td>
                </tr>
                <tr>
                <td>Department:</td>
               <td><b>Computer & Information Science</b></td>
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
        <tr  style="background-color:rgb(128,0,0);color:#FFF;">
<!--        <th>COURSE # TITLE</th>-->
        <th>MONDAY</th>
        <th>TUESDAY</th>
        <th>WEDNESDAY</th>
        <th>THURSDAY</th>
         <th>FRIDAY</th>
          <th>SATURDAY</th>
       </tr>
       <?php
	   $sql='select * from facultyhours where semid='.$semid." and uid=".$_SESSION["userid"]." order by STR_TO_DATE(starttime, '%l:%i%p')";
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
        <tr>
        	<td colspan="6" style="background-color:rgb(128,0,0);">&nbsp;</td>
        </tr>
        <tr>
        	<td colspan="2">
            IF I AM NOT AVAILABLE CONTACT:
            </td>
            <td colspan="2">
            NAME
            </td>
            <td>
            PHONE
            </td>
            <td>
            ROOM
            </td>
        </tr>
        <tr>
        	<td colspan="2">
           DEAN:
            </td>
            <td colspan="2">
            WILLIAM SCHELLER II, PH.D.
            </td>
            <td>
			871-7582
            </td>
            <td>
            CBI-STE. 200E
            </td>
        </tr>
        <tr>
        	<td colspan="2">
            PROVOST &	 VICE PRESIDENT
FOR ACADEMIC AFFAIRS:
			</td>
            <td colspan="2">
			CAROLYNN MASTERS, PH.D.
            </td>
            <td>
            871-7341
            </td>
            <td>
			OLD MAIN 205
            </td>
        </tr>
        <tr>
        <td colspan="6" align="center" style="font-size:9px;">© 2005 Gannon University</td>
        </tr>
    </table>
  </div>
 
</div>
<div id="footer">
	<?php include("footer.html"); ?>
</div>
</div>
</body>
</html>