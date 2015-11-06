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
		$required_fields = array('txtprereq');
		$errors = array_merge($errors, check_required_fields($required_fields, $_POST));			
		if (empty($errors) ) 
		{

				$str="update pre_req set pcname='".$_POST["txtprereq"]."' where prereqid=".$_GET["id"];
		//		echo $str;
				$cnt=ExecuteNonQuery($str);
				if($cnt==1)
				{
					redirect_to("viewprereqs.php");	
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
<title>Edit Co-Requisite</title>
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
                	<p>This page edit Co-Requisite details of</p>
                </div>
           	<div id="contentdetail">
			<form method="post" action="editprereq.php?id=<?php echo $_GET["id"];?>">
            	 <?php 
				$data = ExecuteNonQuery('select * FROM pre_req where prereqid='.$_GET["id"]);
							while($info = mysqli_fetch_assoc($data)) 
							{
						   		$pcname=$info["pcname"];
							}?>

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
			  	 <tr>
                	<td>Pre-Requisite Name</td>
					<td>:</td>
                    <td><input type="text" name="txtprereq" value="<?php echo $pcname;?>"> </td>
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