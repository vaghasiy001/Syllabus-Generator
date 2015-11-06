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
		
			$data = ExecuteNonQuery('select * from objectives where objid='.$q) or die(mysql_error());
			 while($info = mysql_fetch_assoc($data)) 
						 {?>    
                                 <br>
                                 <div style="background-color:#F0F;width:100%;font-weight:bold;">Course Outcomes</div>
        	                     <ul>
                                 <?php
                                  echo "<li>".$info["obj1"]."</li>";
                                  echo "<li>".$info["obj2"]."</li>";
                                  echo "<li>".$info["obj3"]."</li>";
                                  echo "<li>".$info["obj4"]."</li>";
                                  echo "<li>".$info["obj5"]."</li>";
                                  echo "</ul>";
							}
			
			$data = ExecuteNonQuery('select * from outcomes_main where subid='.$q) or die(mysql_error());
			 while($info = mysql_fetch_assoc($data)) 
			     {?>
                 <form method="post" action="#">    
                 <br>
           
                 <?php echo "<div style='color:#390;'>".$info["mainobj"]."</div>" ?>
                 <div style="background-color:#F0F;width:100%;font-weight:bold;">
                            Outcomes
                             <div style="float:right;font-size:12px;">
                                CO1 CO2 C03 C04 C05
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
							echo "<tr><td>".$cnt."</td><td WIDTH=80% style=font-size:14px>".$info1["subobj"]."</td>";
							echo "<td align=right;>";
							echo "<div style=float:right>";
							echo "<input type=checkbox name=mycb[]>";
							echo "<input type=checkbox name=mycb[]>";
							echo "<input type=checkbox name=mycb[]>";
							echo "<input type=checkbox name=mycb[]>";
   							echo "<input type=checkbox name=mycb[]>";
							echo "</div>";
							echo "</td>";
    						$cnt++;
						//	echo "</td></tr>";
					 }
					 echo "</table>";
			}
		   ?> 
        <div style="text-align:center">   <input type="submit" value="Submit Your Subject Survey"> </div>
        </form>

</body>
</html>