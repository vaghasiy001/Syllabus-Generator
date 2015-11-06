<?php
ini_set("display_errors","on");
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
<title>View Departments</title>
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
                	<p>This page displays Departments.<span style="float:right;"><a href="adddepartment.php"><img src="images/Add.png" alt="Add" width="50px"></a></span></p>
                </div>
              	<div id="viewbannercontent">
			<table>
		          <tr>
                	<th align="left">Prefix</th>
                    <th>Deptartment Name</th>
            		<th>Active??</th>
                	<th>Edit</th>
                </tr>
                	<?php
							$data = ExecuteNonQuery('select * FROM dept');
							while($info = mysqli_fetch_assoc($data)) 
							{?>
				
                <tr>
               		<td ><?php echo $info["prefix"];?></td>
                    <td><?php echo $info["deptname"];?></td>
               		   <td><input type="checkbox" <?php if($info["active"]==1) echo " checked";?> disabled></td>
               		<td><a href="editdepartment.php?id=<?php echo $info["deptid"]?>"><img src="images/edit.jpg" height="40px" width="40px"></a></td>
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