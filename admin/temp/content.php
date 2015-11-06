<?php
	ob_start();
	ini_set('display_errors','off');
	include_once("includes/connect.php");
	require_once("includes/functions.php");
	require_once("includes/session.php");
	include("includes/DataAccess.php");  
	include_once("includes/form_functions.php");
	include("../fckeditor/fckeditor.php");
?>
<?php	if (!logged_in()) {
		redirect_to("index.php");
	}
?>
<?php 
	$cnt=CountRecords("select * from insidepage where pagename='".$_GET["page"]."'");
	if($cnt==1)
	{
		$pnm=GetSingleField("select pageietitle from insidepage where pagename='".$_GET["page"]."'","pageietitle");
		$desc=GetSingleField("select pagedesc from insidepage where pagename='".$_GET["page"]."'","pagedesc");
	}
	if(isset($_POST["Submit"]))
		{
			$errors = array();
			$required_fields = array('pagetitle');
			$errors = array_merge($errors, check_required_fields($required_fields, $_POST));			
			if (empty($errors) ) 
			{
				if($cnt==1)
				{
					$c_date = date ("Y-m-d");
					$qry="update insidepage set pageietitle='".$_POST["pagetitle"]."',pagedesc='".$_POST["editor1"]."'";
					$qry=$qry.",modifyip='".getip()."',modifydate='".$c_date."' where pagename='".$_GET["page"]."'";
				//	echo $qry;
					$cntrec=ExecuteNonQuery($qry);
					 if($cntrec==1)
					{
							redirect_to("welcome.php?msg=page content updated successfully.&flag=1");
					}
					else
					{
							redirect_to("welcome.php?msg=Error in updating page content..Please try later.&flag=2");
					} 
				}
				else
				{
					$c_date = date ("Y-m-d");
					$qry="insert into insidepage(pagename,pageietitle,pagedesc,addip,adddate)values('";
					$qry=$qry.$_GET["page"]."','".$_POST["pagetitle"]."','".$_POST["editor1"]."','".getip()."','".$c_date."')";
				//	echo $qry;
				 	$cntrec=ExecuteNonQuery($qry);
					 if($cntrec==1)
					{
							redirect_to("welcome.php?msg=page content inserted successfully.&flag=1");
					}
					else
					{
							redirect_to("welcome.php?msg=Error in inserting page content..Please try later.&flag=2");
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
<title>Page Content</title>
<link href="css/style.css" rel="stylesheet" type="text/css">
        <script src="ckeditor/ckeditor.js"></script>
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
			<div id="pagecontentdiv">
            	<div id="info">
                	<p>This page allows you to add/change the page content.<span style="float:right;"></p>
                </div>
           	<div id="contentdetail">
            			<form method="post" action="content.php?page=<?php echo $_GET["page"];?>">
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
                                <td style="width:100px;">Page Name</td>
                            	<td>:</td>
                                <td><?php echo $_GET["page"];?></td>
                            </tr>
                            <tr>
                                <td>Page Title</td>
                                <td>:</td>
                                <td><input type="text" size="40" name="pagetitle" value="<?php echo $pnm;?>"></td>
                            </tr>
                            <tr>
                                <td>Content</td>
                                <td>:</td>    
                                    <td>				
                                     <textarea name="editor1" id="editor1" rows="10" cols="50">
                                     <?php echo $desc;?>
            						</textarea>
          						  <script>
							// Replace the <textarea id="editor1"> with a CKEditor
							// instance, using default configuration.
							CKEDITOR.replace( 'editor1' );
						</script>
												</td>
                            </tr>
                      		<tr>
                            	<td colspan="3" align="center"><input type="submit" name="Submit" value="Submit Page Data"></td>
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