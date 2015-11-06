<?php
ini_set("display_errors","off");
include("includes/DataAccess.php");
include("includes/session.php");
if (!logged_in()) {
		redirect_to("index.php");
	}

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>View BackUps</title>
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
      <?php if($_GET["flag"]==1) { ?>
                <div id="divsuccess">
                    <img src="images/success.png" width="25px" height="25px"><?php echo $_GET["msg"]; ?> 
                </div>
                <?php } ?>
                <?php if($_GET["flag"]==2) { ?>
                <div id="diverror">
                    <img src="images/cross.png" width="25px" height="25px"><?php echo $_GET["msg"]; ?>
                </div>
                <?php }?>
    <div id="viewfaculties">

			<div id="viewbannerdiv">
            	<div id="info">
                	<p>This page displays database backups.<span style="float:right;"><a href="addbackup.php"><img src="images/Add.png" alt="Add" width="50px"></a></span></p>
                </div>
              	<div id="viewbannercontent">
				<table>
<!--            	<tr>
                	<td colspan="3"><img src="images/viewbanner.png" height="100px" width="100%"></td>                   
                </tr>
    -->          <tr>
                	<th>Id</th>
                    <th>Dabatabase Bacup Name</th>
                    <th>Delete</th>
                </tr>
                <?php
    	$data = ExecuteNonQuery('select * FROM dbbackup order by id desc') or die(mysql_error());
			$cnt=mysql_num_rows($data);
	        if($cnt==0)
			{?>
				<td colspan="3" style="text-align:center;font-weight:bold">No BackUp stored into the database</td>
			<?php }
			else
			{
			    		while($info = mysql_fetch_assoc($data)) 
							{?>
			
                	<tr>
							<td><?php echo $info["id"];?></td>
                        <td><a href="dbbackup/<?php echo $info["backupname"];?>"><?php echo $info["backupname"];?></a></td>
              			<td><a href="deletebackup.php?id=<?php echo $info["id"];?>" onclick="return confirm('Are you sure you want to delete?')"><img src="images/error.png" height="30px" width="30px"></a></td>
                	<?php	}
			}
					?>
                    </tr>
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