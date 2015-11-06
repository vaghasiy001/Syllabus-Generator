<?php
ini_set('display_errors','off');

 session_start(); ?>
<?php
		require_once("includes/functions.php");
		require_once("includes/session.php");
		require_once("includes/connect.php");
		include("includes/DataAccess.php");  
		
?>

<? ob_start(); ?>
<?php	if (!logged_in()) {
		redirect_to("index.php");
	}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<link href="css/menu.css" rel="stylesheet" type="text/css">
<script src="js/jquery-latest.min.js"></script>
<script type='text/javascript' src='js/menu_jquery.js'></script>
<link href="css/style.css" rel="stylesheet" type="text/css">

<title>Untitled Document</title>
</head>

<body>
            <?php
			$q = intval($_GET['q']);
			
			$data = ExecuteNonQuery('select * from objectives where subid='.$q) or die(mysql_error());
			$cnt1=mysql_num_rows($data);
			$flag=0;
			if($cnt1==0)
			{
				$flag=1;
			?>
						    <?php if($_SESSION["usrpermission"]=="1"){?>
                            <div style="background-color:#E84F0C;width:100%;font-weight:bold;" align="right">
                            <a href="addobjective.php?subid=<?php echo $q; ?>">Click here</a> to add objective</div>
                            </div>
			             	<?php }?>
                            <?php if($_SESSION["usrpermission"]!="1"){?>
                            <ul>
                            	<li><b>No Objective for this subject..Please contact administrator to add objective for this subject</b></li>
     						</ul>           			
                            <?php }else{?>
                            <ul>
                            	<li>No Objective for this subject</li>
     						</ul>           			
                            <?php }?>
								
<?php			}
			else
			{
					while($info = mysql_fetch_assoc($data)) 
						 { 
						 $obj1=$info["obj1"];
						 $obj2=$info["obj2"];
						 $obj3=$info["obj3"];
						 $obj4=$info["obj4"];
						 $obj5=$info["obj5"];
						 if(empty($obj1) && empty($obj2) && empty($obj3) && empty($obj4) && empty($obj5))
						 {
							$flag=1;	 
						 ?>
							    <div style="background-color:#E84F0C;width:100%;font-weight:bold;" align="right">
                            <a href="addobjective.php?subid=<?php echo $q; ?>">Click here</a> to add objective</div>
                            </div>
			             	<ul>
                            	<li><b>No Objective for this subject<b></li>
     						</ul>
						 <?php
                         }
						 else
						 {
						  ?>
									<br>
									<div style="background-color:#E84F0C;width:100%;font-weight:bold;">Course Outcomes</div>
									<ul>
									  <?php

										if(!empty($obj1)) echo "<li>".$obj1."</li>";
										if(!empty($obj2)) echo "<li>".$obj2."</li>";
										if(!empty($obj3))  echo "<li>".$obj3."</li>";
										if(!empty($obj4))  echo "<li>".$obj4."</li>";
										if(!empty($obj5))  echo "<li>".$obj5."</li>";
								 }
						
								  echo "</ul>";
						 }
			}
			if($flag==0)
			{
			$data = ExecuteNonQuery('select * from outcomes_main') or die(mysql_error());
			 while($info = mysql_fetch_assoc($data)) 
			     {?>
                 <form method="post" action="editcoursesurvey.php">    
                 <br>
           		<input type="hidden" value=<?php echo $q; ?> name="subid"> 
                 <?php echo "<div style='color:#004C0D;font-weight:bold'>".$info["mainobj"]."</div>" ?>
                 <div style="background-color:#E84F0C;width:100%;font-weight:bold;">
                            Outcomes
                             <div style="float:right;font-size:12px;">
                                <table>
                                <tr>
						           <?php if(!empty($obj1)){?><td>CO1</td><?php }?>
                                   <?php if(!empty($obj2)){?><td>CO2</td><?php }?>
                                   <?php if(!empty($obj3)){?><td>CO3</td><?php }?>
                                   <?php if(!empty($obj4)){?><td>CO4</td><?php }?>
                                   <?php if(!empty($obj5)){?><td>CO5</td><?php }?>
                                </tr>
                                </table>
                             </div>
                 </div>
                 	
         			<?php 
					$data1 = ExecuteNonQuery('select * from outcomes_detail where ocmmainid='.$info["ocmmainid"]) or die(mysql_error());
					$cnt=1;
					 echo "<table width=100%;padding-top:2px;>";
					 $cnt=1;
					 while($info1 = mysql_fetch_assoc($data1)) 
				     {?>    
                     		<?php
							$cntcbx=0;
							echo "<tr><td>".$cnt."</td><td WIDTH=80% style=font-size:14px>".$info1["subobj"]."</td>";
							echo "<td align=right;>";
							echo "<div style=float:right>";
							$noofrec=CountRecords("select * from outcomes_result where subid=".$q." and ocmdetailid=".$info1["ocmdetailid"]);
							if($noofrec>0)
							{
$data2=ExecuteNonQuery("select obj1,obj2,obj3,obj4,obj5 from outcomes_result where subid=".$q." and ocmdetailid=".$info1["ocmdetailid"]);
							 while($info2 = mysql_fetch_assoc($data2)) 
							 {?> 
                             <?php if(!empty($obj1)){?>
                    		<input type=checkbox name=mycb<?php echo $info1["ocmdetailid"] ?>[<?php echo $cntcbx; ?>]
                             <?php if($info2["obj1"]=="1") echo "checked"; ?>> <?php }?>
                            <?php $cntcbx++;?>
                            
							<?php if(!empty($obj2)){?>
							<input type=checkbox name=mycb<?php echo $info1["ocmdetailid"] ?>[<?php echo $cntcbx; ?>]
                            <?php if($info2["obj2"]=="1") echo "checked"; ?>> <?php }?>
                            <?php $cntcbx++;?>
                            
                            <?php if(!empty($obj3)){?>
							<input type=checkbox name=mycb<?php echo $info1["ocmdetailid"] ?>[<?php echo $cntcbx; ?>]
                            <?php if($info2["obj3"]=="1") echo "checked"; ?>> <?php }?>
                            <?php $cntcbx++;?>
                            
                            <?php if(!empty($obj4)){?>
							<input type=checkbox name=mycb<?php echo $info1["ocmdetailid"] ?>[<?php echo $cntcbx; ?>]
                            <?php if($info2["obj4"]=="1") echo "checked"; ?>> <?php }?>
                            <?php $cntcbx++;?>
                            
                            <?php if(!empty($obj5)){?>
   							<input type=checkbox name=mycb<?php echo $info1["ocmdetailid"] ?>[<?php echo $cntcbx; ?>]
                            <?php if($info2["obj5"]=="1") echo "checked"; ?>> <?php }?>
							<?php 
							 }
							}
							else 
							{?>
            				
							<?php if(!empty($obj1)){?>            	
							<input type=checkbox name=mycb<?php echo $info1["ocmdetailid"] ?>[<?php echo $cntcbx; ?>]> <?php }?>
                            <?php $cntcbx++;?>
                            
							<?php if(!empty($obj2)){?>
							<input type=checkbox name=mycb<?php echo $info1["ocmdetailid"] ?>[<?php echo $cntcbx; ?>]> <?php }?>
                            <?php $cntcbx++; ?>
                            
                            <?php if(!empty($obj3)){?>
							<input type=checkbox name=mycb<?php echo $info1["ocmdetailid"] ?>[<?php echo $cntcbx; ?>]> <?php }?>
                            <?php $cntcbx++; ?>
							
							<?php if(!empty($obj4)){?>
                            <input type=checkbox name=mycb<?php echo $info1["ocmdetailid"] ?>[<?php echo $cntcbx; ?>]> <?php }?>
                            <?php $cntcbx++; ?>
                            
   							<?php if(!empty($obj5)){?>
                            <input type=checkbox name=mycb<?php echo $info1["ocmdetailid"] ?>[<?php echo $cntcbx; ?>]> <?php }?>
                            <?php } ?>
						<?php	echo "</div>";
							echo "</td>";
    						$cnt++;
						//	echo "</td></tr>";
					 }
					 echo "</table>";
			}
			
		   ?> 
        <div style="text-align:center">   <input type="submit" value="Submit Your Subject Survey" name="btnsubmit"> </div>
        <?php }?>
        </form>

</body>
</html>