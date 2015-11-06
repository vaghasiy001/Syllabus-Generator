<?php
ini_set("display_errors","off");
include("includes/connect/php");
include("includes/DataAccess.php");
include("includes/form_functions.php");
include("includes/functions.php");

include("includes/session.php");
if (!logged_in()) {
		redirect_to("index.php");
	}

if( isset($_POST['Submit']) )
 {   
 		$errors = array();
		$required_fields = array('imgcatname');
		$errors = array_merge($errors, check_required_fields($required_fields, $_POST));			
		if (empty($errors) ) 
		{
			$i=0;
					if($_POST["cbactive"]=="on")
						$i='true';
				
					$c_date = date("Y-m-d");
					$str="insert into buildingcategories(imgcatname,active,addip,adddate)values('";
					$str.=$_POST["imgcatname"]."','".$i."','". getip()."','".$c_date."')";
			//		echo $str;
					$cnt=ExecuteNonQuery($str);
					if($cnt==1)
					{
						redirect_to("viewbuildingcategories.php");	
					}
		}
		else
		{
			if (count($errors) == 1) {
				$message = "There was 1 error in the form.";
			} else {
				$message = "There were " . count($errors) . " errors in the form.";
			}		
			
		}
 }
?>


<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Add Category</title>
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
			<div id="addcatdiv">
            	<div id="info">
                	<p>This page add the category of image<span style="float:right;"><a href="viewcategories.php"><img src="images/view.png" alt="view" width="50px"></a></p>
                </div>
           	<div id="contentdetail">
			<form method="post" action="addbuildingcategory.php">
			<table>
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

         <!--   	<tr>
                	<td colspan="3"><img src="images/banner-icon_1.png" height="100px" width="100%"></td>
                    
                </tr>-->
                <tr>
                	<td>Category Name</td>
					<td>:</td>
                    <td><input type="text" name="imgcatname"></td>
                </tr>
		   	<tr>
                	<td>Active</td>
					<td>:</td>
                    <td><input type="checkbox" name="cbactive"></td>
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