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
<title>View Courses</title>
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
                	<p>This page displays approved courses.<span style="float:right;"><a href="addcourse.php"><img src="images/Add.png" alt="Add" width="50px"></a></span></p>
                </div>
              	<div id="viewbannercontent">
                <form method="post" name="form1">
                <div style="width:100%;text-align:center;">
                	<b>View Courses:<select onChange="document.form1.submit();" name="ddllevel">
                        <option value="All" <?php if($_POST["ddllevel"]=="All") echo "selected";?>>All</option>
                        <option value="CIS" <?php if($_POST["ddllevel"]=="CIS") echo "selected";?>>CIS</option>
                        <option value="GCIS" <?php if($_POST["ddllevel"]=="GCIS") echo "selected";?>>GCIS</option>
                    </select></b>
                    </form>
                    </div>
			<table>
		          <tr>
                    <th>Course Id</th>
            		<th>Name</th>
                	<th>Edit</th>
                </tr>
                	<?php
							if(isset($_POST["ddllevel"]))
							{
								if($_POST["ddllevel"]=="All")
									$data = ExecuteNonQuery('select * FROM courses order by courseno');
								else if($_POST["ddllevel"]=="CIS")
									$data = ExecuteNonQuery("select * FROM courses where courseno like 'CIS%' order by courseno");
								else if($_POST["ddllevel"]=="GCIS")
									$data = ExecuteNonQuery("select * FROM courses where courseno like 'GCIS%' order by courseno");
							}
							else
							{
								$data = ExecuteNonQuery('select * FROM courses order by courseno');
							}
							while($info = mysqli_fetch_assoc($data)) 
							{?>
				
                <tr>
               		<td><?php echo $info["courseno"];?></td>
               		<td><?php echo $info["coursename"];?></td>
                    <td><a href="editcourse.php?id=<?php echo $info["cid"]?>"><img src="images/edit.jpg" height="40px" width="40px"></a></td>
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