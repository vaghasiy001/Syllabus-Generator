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
<title>View Users</title>
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
                	<p>This page views users.<span style="float:right;"><a href="adduser.php"><img src="images/Add.png" alt="Add" width="50px"></a></span></p>
                </div>
              	<div id="viewbannercontent">
			<table>
		          <tr>
                	<th>First Name</th>
                    <th>Last Name</th>
            		<th>Email</th>
            		<th>Cell Phone</th>
            	    <th>Active??</th>
                    <th>Admin??</th>
                	<th>Edit</th>
                    <th>Delete</th>
                </tr>
                	<?php
							$data = ExecuteNonQuery('select * FROM users order by lastname');
							while($info = mysqli_fetch_assoc($data)) 
							{?>
				
                <tr>
               		<td ><?php echo $info["firstname"];?></td>
                    <td><?php echo $info["lastname"];?></td>
               		 <td><?php echo $info["email"];?></td>
               		 <td><?php echo $info["officeno"];?></td>
               	       <td><input type="checkbox" <?php if($info["active"]==1) echo " checked";?> disabled></td>
               	       <td><input type="checkbox" <?php if($info["permission"]==1) echo " checked";?> disabled></td>
                	<td><a href="edituser.php?id=<?php echo $info["uid"]?>"><img src="images/edit.jpg" height="40px" width="40px"></a></td>
                	<td><a href="deleteuser.php?uid=<?php echo $info["uid"];?>">
		                    <input type="image" src="images/close_icon.png" onClick="return confirm('Are you sure you want to delete this user?')"></a>
                    </td>
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