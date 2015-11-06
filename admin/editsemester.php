<?php
	ob_start();
	ini_set('display_errors','off');
	require_once("includes/session.php");
	include_once("includes/form_functions.php");
	require_once("includes/functions.php");
	include_once("includes/DataAccess.php");
?>
<?php	if (!logged_in()) {
		redirect_to("index.php");
	}
?>
<?php
if(!isset($_GET["id"]))
{
	redirect_to("index.php");
}
if(isset($_POST["Submit"]))
{
					$i=0;
					if(isset($_POST["cbxactive"]))
					{
						ExecuteNonQuery("update semester set active=0");
						$i=1;
					}
				$crec=CountRecords("select * from semester");	
				$currsord=GetSingleField("select sortorder from semester where semid=".$_GET["id"],"sortorder");	
				if($currsord==$_POST["ddlso"])
				{
						$updateold="update semester set active=".$i." where semid=".$_GET["id"];
						ExecuteNonQuery($updateold);
//						echo $updateold."<br>";
				}
				else
				{
				$stmpid=GetSingleField("select semid from semester where sortorder=".$_POST["ddlso"],"semid");	
				$sql="update semester set sortorder=".$currsord." where semid=".$stmpid; 
				ExecuteNonQuery($sql);
		//	echo $sql."<br>";
				$sql="update semester set sortorder=".$_POST["ddlso"].",active=$i where semid=".$_GET["id"]; 
				ExecuteNonQuery($sql);
			//				echo $sql;
				}
				redirect_to("viewsemesters.php");
		
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Edit Semester</title>
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
                    <img src="images/success.jpg" width="25px" height="25px"><?php echo $_GET["msg"]; ?> 
                </div>
                <?php } ?>
                <?php if($_GET["flag"]==2) { ?>
                <div id="diverror">
                    <img src="images/error.png" width="25px" height="25px"><?php echo $_GET["msg"]; ?> 
                </div>
                <?php }?>

            <div id="addbannerdiv">
            	<div id="info">
                	<p>This page edits semester details.<span style="float:right;"><a href="viewsemesters.php"><img src="images/view.png" alt="view" width="50px"></a></p>
                </div>
                <?php
				$data=ExecuteNonQuery("select * from semester where semid=".$_GET["id"]);
				$snm="";
				$syr="";
				$active="";
				$sortord="";
				while($info=mysqli_fetch_assoc($data))
				{
					$snm=$info["semname"];
					$syr=$info["year"];
					$active=$info["active"];
					$sortord=$info["sortorder"];
				}
				?>
           	<div id="contentdetail">
			<form method="post" action="editsemester.php?id=<?php echo $_GET["id"]; ?>">
			<table style="width:40%">
                  <tr>
            <td colspan="3" >
            <?php if (!empty($message)) {echo "<p class=message>" . $message . "</p>";}
				else {echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";}
			 ?>
			<?php if (!empty($errors)) 	
					{ 
			display_errors1($errors); 
			} ?>
			

            </td>
            </tr>
                <tr>
                	<td>Sem Name</td>
					<td>:</td>
                    <td>
                    <select name="ddlsem" disabled>
                    <option <?php if(strcmp("Spring",$snm)==0) echo "selected";?>>Spring</option>
                    <option <?php if("Summer"==$snm) echo "selected";?>>Summer</option>
                    <option <?php if("Fall"==$snm) echo "selected";?>>Fall</option>                    
                    </select>
                    </td>
                </tr>
                <tr>
                	<td>Year<label style="color:red;">*</label></td>
					<td>:</td>
                    <td><input type="text" name="txtyear" size="4" max="4" value="<?php echo $syr;?>" disabled></td>
                </tr>
			    <tr>
                	<td>Is Active?</td>
					<td>:</td>
                    <td><input type="checkbox" name="cbxactive" <?php if($active=="1") echo "checked";?>></td>
                </tr>
<?php 
					$cntrec=CountRecords("select * from semester");
?>
                <tr>
                	<td>Sortorder</td>
					<td>:</td>
                    <td>
                   <select name="ddlso">
			       <?php
						for($i=1;$i<=$cntrec;$i++)
						{?>
                	    <option <?php if($i==$sortord) echo "selected";?>><?php echo $i;?></option>
                    <?php }?>
                    </select>
                    </td>
                </tr>
                
                <tr>
                	<td colspan="3" align="center"><input type="submit" value="Submit" name="Submit"></td>
                </tr>
            </table>            
            </form>
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