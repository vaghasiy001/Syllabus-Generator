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

function getfilename($str) {
         $i = strrpos($str,".");
         if (!$i) { return ""; }
         $l = strlen($str) - $i;
         $name = substr($str,0,$i);
         return $name;
 }
function getdt()
{
	$date=date('mdy');	
	$time=date('His');
	$datetime=$date.$time;
	return $datetime;
}
 function getExtension($str) {
         $i = strrpos($str,".");
         if (!$i) { return ""; }
         $l = strlen($str) - $i;
         $ext = substr($str,$i+1,$l);
         return $ext;
 }
 

if( isset($_POST['Submit']) )
 {   	  
			$errors = array();
			$required_fields = array('imgnm');
			$errors = array_merge($errors, check_required_fields($required_fields, $_POST));			
		if (empty($errors) ) 
		{
						if(!file_exists($_FILES['photoimage']['tmp_name']) || !is_uploaded_file($_FILES['photoimage']['tmp_name']))
						{//file not inside
										$message = "Please upload image";
						}
						else
						{	
							if(empty($_POST["ddcatid"]))
							{
								$message="Please select category";
							}
							else
							{
							   include('includes/SimpleImage.php'); 
								$image1 =$_FILES["photoimage"]["name"];
								$fnm=getfilename($image1);
								$ext1=getExtension($image1);
								$dt=getdt();
							
							   $image = new SimpleImage(); 
							   $image->load($_FILES['photoimage']['tmp_name']);
								$image->resize(375,375); 	
								
							//	$t1=str_replace("_fullsize","",$fnm);
								
								$tmp="../usrfiles/medium/".$fnm."_".$dt."_preview.".$ext1;
								$image->save($tmp);
							
								$tmp1="../usrfiles/thumb/".$fnm."_".$dt."_thumbnail.".$ext1;
								$image->resize(75,75); 
								$image->save($tmp1);
									
								move_uploaded_file($_FILES['photoimage']['tmp_name'],"../usrfiles/large/".$fnm."_".$dt."_fullsize.".$ext1);
						
								$tmp1=	$fnm."_".$dt.".".$ext1;
								$cntrec=CountRecords("select disporder from images");
								if($cntrec==0)
								{
									$dispord=1;
								//	echo "cnt0...".$dispord;
								}
								else
								{
										$rowSQL = mysql_query( "SELECT MAX( disporder ) AS max FROM images;" );
										$row = mysql_fetch_array( $rowSQL );
										$dispord = $row['max'];
										$dispord=$dispord+1;
							//			echo "cnt0...".$dispord;				
								}
								$i=0;
								if($_POST["cbvisible"]=="on")
									$i=1;
								$c_date = date ("Y-m-d");
								$str="insert into images(imagename,imagetitle,disporder,visible,addip,adddate,imgcatid)values('";
								$str.=$_POST["imgnm"]."','".$tmp1."',".$dispord.",b'".$i."','". getip()."','".$c_date."',".$_POST["ddcatid"].")";
								//echo $str;
								$cnt=ExecuteNonQuery($str);
								if($cnt==1)
								{
									redirect_to("viewimages.php");	
								}
							}
							
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
<title>Add Image</title>
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
			<div id="addbannerdiv">
            	<div id="info">
                	<p>This page allows you to add images.<span style="float:right;"><a href="viewimages.php"><img src="images/view.png" alt="view" width="50px"></a></p>
                </div>
           	<div id="contentdetail">
			<form method="post" action="addimage.php" enctype="multipart/form-data">
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

            	<tr>
                	<td colspan="3"><img src="images/banner-icon_1.png" height="100px" width="100%"></td>
                </tr>
                <tr>
                	<td>Select Category</td>
					<td>:</td>
                    <td>
                    <select name="ddcatid">
                	<option value="">Select</option>
                    <?php
							$data = ExecuteNonQuery('select * FROM imagecategories where active=1') or die(mysql_error());
							while($info = mysql_fetch_assoc($data)) 
							{?>
                            <option value="<?php echo $info["imgcatid"]?>"><?php echo $info["imgcatname"]; ?></option>
                            <?php } ?>
					</select>
                    
                    </td>
                </tr>
                <tr>
                	<td>Image Name</td>
					<td>:</td>
                    <td><input type="text" name="imgnm"></td>
                </tr>
				<tr>
                	<td>Upload Image</td>
					<td>:</td>
                    <td><input type="file" name="photoimage"></td>
                </tr>
            	<tr>
                	<td>Visible</td>
					<td>:</td>
                    <td><input type="checkbox" name="cbvisible"></td>
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