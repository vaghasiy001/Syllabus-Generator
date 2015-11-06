<?php
ini_set("display_errors","off");
include("includes/DataAccess.php");
include("includes/form_functions.php");
include("includes/functions.php");
include("includes/session.php");
if (!logged_in()) {
		redirect_to("index.php");
	}

?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>View Categories</title>
<link href="css/style.css" rel="stylesheet" type="text/css">
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
			<div id="viewcatdiv">
            	<div id="info">
                	<p>This page displays image categories.<span style="float:right;"><a href="addbathcategory.php"><img src="images/Add.png" alt="Add" width="50px"></a></span></p>
                </div>
              	<div id="viewbannercontent">
			<table>
            <!--	<tr>
                	<td colspan="3"><img src="images/viewbanner.png" height="100px" width="100%"></td>                   
                </tr>
                --><tr>
                	<th align="left">Category Name</th>
                    <th>Active</th>
                </tr>
                	<?php
							$data = ExecuteNonQuery('select * FROM bathcategories') or die(mysql_error());
							while($info = mysql_fetch_assoc($data)) 
							{?>
				
                <tr>
               		<td ><?php echo $info["imgcatname"];?></td>
                    <td><input type="checkbox" disabled <?php if($info["active"]==1) echo "checked";?>></td>
                	<td><a href="editbathcategory.php?id=<?php echo $info["imgcatid"]?>"><img src="images/edit.jpg" height="40px" width="40px"></a></td>
                    <td><img src="images/error.png"  height="40px" width="40px"></td>
                </tr>
 
                	<?php	
					}
					?>
                
            </table>            
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