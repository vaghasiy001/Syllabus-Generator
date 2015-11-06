<?php
ini_set("display_errors","off");
include("includes/DataAccess.php");
include("includes/form_functions.php");
include("includes/functions.php");
include("includes/session.php");
include("includes/connect.php");
if (!logged_in()) {
		redirect_to("index.php");
	}

?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Status Indicator</title>
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
                	<p>This page views status of syllabus.<br>
                    	  <span style="font-size:12px;"><i><b>M:</b>Mappings, <i><b>RM:</b>Required Materials,<b>Web:</b>Resources,<b>GP:</b>Grading Policy,<b>CP:</b>Course Policies,<b>AI:</b>Academic Integrity,<b>CT:</b>Course Topics</i></span>
                    </p>
                </div>
              	<div id="viewbannercontent">
			<table>
		          <tr>
                	<th>FACULTY NAME</th>
                    <th>SUBJECTS SECTION</th>
            		<th align="center"><center>STATUS </center>
                    <table>
                    	<td>M</td>
                    	<td>RM</td>
	                    <td>Web</td>
                        <td>GP</td>
                        <td>CP</td>
                        <td>AI</td>
                        <td>CT</td>
                        </table>
                    </th>
                </tr>
                	<?php
				$data = ExecuteNonQuery('select uid FROM section where semid='.$_SESSION["statusincsem"]." group by uid");
				while($info = mysqli_fetch_assoc($data)) 
					{
					$fnm=GetSingleField("select firstname from users where uid=".$info["uid"],"firstname")	;
					$lnm=GetSingleField("select lastname from users where uid=".$info["uid"],"lastname")	;
					?>
				
                <tr>
               		<td ><?php echo "<b>".$fnm." ".$lnm."</b>";?></td>
               <?php
			   $data1 = ExecuteNonQuery('select * FROM section where semid='.$_SESSION["statusincsem"]." and uid=".$info["uid"]." and active=1");
				while($info1 = mysqli_fetch_assoc($data1)) 
					{
					$section=GetSingleField("select sections from course_section where csid=".$info1["csid"],"sections");
					$cntrecords=CountRecords("select * from fcamm where csid=".$info1["csid"]." and semid=".$_SESSION["statusincsem"]." and uid=".$info["uid"]);
					if($cntrecords>0)
					{
					$sql="SELECT SUM(obj1) + SUM(obj2) + SUM(obj3)+ SUM(obj4)+ SUM(obj5)+ SUM(obj6)+ SUM(obj7)+ SUM(obj8)+ SUM(obj9)+ SUM(obj10) as 'Total'
FROM fcamm where csid=".$info1["csid"]." and semid=".$_SESSION["statusincsem"]." and uid=".$info["uid"];
					//echo $sql;
					$sfld=GetSingleField($sql,"Total");
						if($sfld!=0)
							$cntrecords=1;
						else
							{
								$cntrecords=0;
							}
					}
					else
					{
						$cntrecords=0;
					}
			   ?>
               <tr>
                   <td>&nbsp;</td>
            	    <td><?php echo $section;?></td>
                    <td>
					<table border="1">
                   
                    <tr>
                    	<td>
                        <?php if($cntrecords==1){ ?> 
                                     <img src="images/susscess.png">
                            <?php }else{ ?>         
                                     <img src="images/fail.png">
                            <?php }?>                     
                        </td>
                        <td>
							<?php if(!is_null($info1["reqmaterials"]) && trim($info1["reqmaterials"])!=""){ ?> 
                                     <img src="images/susscess.png">
                            <?php }else{ ?>         
                                     <img src="images/fail.png">
                            <?php }?>                     
                        </td>
                       
	                    <td>
							<?php if(!is_null($info1["website"]) && trim($info1["website"])!=""){ ?> 
                                     <img src="images/susscess.png">
                            <?php }else{ ?>         
                                     <img src="images/fail.png">
                            <?php }?>
                        
                        </td>
                        <td>
							<?php if(!is_null($info1["gradingpolicy"]) && trim($info1["gradingpolicy"])!=""){ 
							
										$gp= $info1["gradingpolicy"];
										//echo $gp;
										$sql="select camname from cams where csid=".$info1["csid"]." and uid=".$info1["uid"]." and semid=".$info1["semid"];
//										echo $sql;
										$data4=ExecuteNonQuery($sql);
										$flag=0;
										while($info4=mysqli_fetch_assoc($data4))
										{
											
											if (strpos($gp,'[NN%]') !== false) {
												$flag=1;
											}
										}
										if($flag==0)
										{
										?> 
        	                  			  <img src="images/susscess.png">
                                      <?php }else{?>
		                                     <img src="images/fail.png">  
                                      <?php }?>
                            <?php }else{ ?>         
                                     <img src="images/fail.png">
                            <?php }?>
                        </td>
                        <td>
							<?php if(!is_null($info1["coursepolicy"]) && trim($info1["coursepolicy"])!=""){ ?> 
                                     <img src="images/susscess.png">
                            <?php }else{ ?>         
                                     <img src="images/fail.png">
                            <?php }?>
                        </td>
                        <td>
							<?php if(!is_null($info1["attpolicy"]) && trim($info1["attpolicy"])!=""){ ?> 
                                     <img src="images/susscess.png">
                            <?php }else{ ?>         
                                     <img src="images/fail.png">
                            <?php }?>
                        </td>
                        <td>
							<?php if(!is_null($info1["coursetopics"]) && trim($info1["coursetopics"])!=""){ ?> 
                                     <img src="images/susscess.png">
                            <?php }else{ ?>         
                                     <img src="images/fail.png">
                            <?php }?>
                        </td>
                    </tr>
                    </table>
                    </td>
                 </tr>
       
               <?php
					}
			   ?>
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