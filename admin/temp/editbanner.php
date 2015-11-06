<?php

	ob_start();
	ini_set('display_errors','off');
	include_once("includes/connect.php");
	require_once("includes/functions.php");
	require_once("includes/session.php");
	include("includes/DataAccess.php");  
	include_once("includes/form_functions.php");

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
			$required_fields = array('bannernm');
			$errors = array_merge($errors, check_required_fields($required_fields, $_POST));			
		if (empty($errors) ) 
		{
			$bnm=$_POST["bannernm"];
			$visible=$_POST["cbvisible"];
			$dispord=$_POST["dispord"];
			$existord=GetSingleField("select disporder from banners where id=".$_GET["id"],"disporder");
		//	echo $dispord." " . $existord;
						if(!file_exists($_FILES['bannerimage']['tmp_name']) || !is_uploaded_file($_FILES['bannerimage']['tmp_name']))
						{//file not inside
							if($exitstord!=$dispord)
							{
								$i=0;
								if($visible=="on")
									$i=1;
								$qry="update banners set disporder=".$existord." where disporder=".$dispord;
							//	echo $qry."  ";
								ExecuteNonQuery($qry);
										$c_date = date ("Y-m-d");
								$qry="update banners set bannername='".$bnm."',visible=b'".$i."',disporder=".$dispord.",modifyip='".getip()."',modifydate='".$c_date."' where id=".$_GET["id"];
									echo $qry."  ";
									$cntrec=ExecuteNonQuery($qry);
									echo $cntrec;
									if($cntrec==1)
									{
										redirect_to("viewbanners.php");
									}
									else
									{
										echo "test";
									}
						
							}
							else
							{
									$i=0;
									if($visible=="on")
										$i=1;
									
									$qry="update banners set bannername='".$bnm."',visible=b'".$i."' where id=".$_GET["id"];
								//	echo $qry."  ";
									ExecuteNonQuery($qry);
									$c_date = date ("Y-m-d");
									$qry="update banners set bannername='".$bnm."',visible=b'".$i."',disporder=".$dispord.",modifyip='".getip()."',modifydate='".$c_date."' where id=".$_GET["id"];
									$cntrec=ExecuteNonQuery($qry);
									if($cntrec==1)
									{
										redirect_to("viewbanners.php");
									}
									else
									{
									}
							}
						}
						else
						{
							if($exitstord!=$dispord)
							{
				
								$i=0;
								if($visible=="on")
									$i=1;
								$qry="update banners set disporder=".$existord." where disporder=".$dispord;
								//echo $qry."  ";
								ExecuteNonQuery($qry);
									
							}
							
						
					
									include('includes/SimpleImage.php'); 
								   $image = new SimpleImage(); 
								   $image->load($_FILES['bannerimage']['tmp_name']);
									$image->resize(1024,250); 
									
									$image1 =$_FILES["bannerimage"]["name"];
									$fnm=getfilename($image1);
									$ext1=getExtension($image1);
									$dt=getdt();
									$tmp="../banners/large/".$fnm."_".$dt.".".$ext1;
									$image->save($tmp);
									
									$tmp1="../banners/thumb/".$fnm."_".$dt.".".$ext1;
									$image->resize(240,55); 
									$image->save($tmp1);
										
									$tmp1=	$fnm."_".$dt.".".$ext1;
									$i=0;
									if($_POST["cbvisible"]=="on")
										$i=1;
									$c_date = date ("Y-m-d");
									$str="update banners set bannername='".$bnm."',imagetitle='".$tmp1."',visible=b'".$i."',modifyip='".getip()."',modifydate='".$c_date."',disporder=".$dispord." where id=".$_GET["id"];
					//				echo $str;
									$cnt=ExecuteNonQuery($str);
									if($cnt==1)
									{
										redirect_to("viewbanners.php");	
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
<link href="css/style.css" rel="stylesheet" type="text/css">
<title>Edit Banner</title>
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
                	<p>This page edit banner details which rotates automatically front end side.<span style="float:right;"><a href="viewbanners.php"><img src="images/view.png" alt="view" width="50px"></a></p>
                </div>
           	<div id="contentdetail">
			<form method="post" action="editbanner.php?id=<?php echo $_GET["id"];?>" enctype="multipart/form-data">
            <?php 
				$data = ExecuteNonQuery('select * FROM banners where id='.$_GET["id"]) or die(mysql_error());
							while($info = mysql_fetch_assoc($data)) 
							{
						   		$bnm=$info["bannername"];
								$imgnm=$info["imagetitle"];
								$dispord=$info["disporder"];
								$visible=$info["visible"];
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


            </td>
            </tr>

            	<tr>
                	<td colspan="3"><img src="images/banner-icon_1.png" height="100px" width="100%"></td>
                </tr>
                <tr>
                	<td>Banner Name</td>
					<td>:</td>
                    <td><input type="text" name="bannernm" value="<?php echo $bnm;?>"></td>
                </tr>
                <tr>
                	<td>Existing Banner</td>
                    <td>:</td>
                    <td><img src="../banners/thumb/<?php echo $imgnm;?>"></td>
                </tr>
				<tr>
                	<td>Upload Banner</td>
					<td>:</td>
                    <td><input type="file" name="bannerimage"></td>
                </tr>
            	<tr>
                	<td>Visible</td>
					<td>:</td>
                    <td><input type="checkbox" name="cbvisible" <?php if($visible==1) echo "checked";?>></td>
                </tr>
            	<tr>
                	<td>Display Order</td>
                    <td>:</td>
                        <td>
                        <?php
						$cntrec=CountRecords("select disporder from banners");
						$i=1;
						?>
                        <select name="dispord">
       
                        <?php while($i<=$cntrec){ ?>  
                        <option value="<?php echo $i;?>" <?php if($i==$dispord) echo "selected";?>><?php echo $i;?></option>
                        <?php $i++;}?>
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