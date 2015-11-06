<?php
ini_set('display_errors','off');
include_once('includes/connect.php');
include( 'includes/DataAccess.php');
?>
<html>
  <head>
    <script language="javascript" type="text/javascript" src="js/jquery-1.4.1.min.js"></script>
 <script type="text/javascript">
  function getdata1(id)
  {
	   $.ajax({                                      
      url: 'api.php',                  //the script to call to get data          
      data: "userid=" + id,                        //you can insert url argumnets here to pass to api.php
		                                 //for example "id=5&parent=6"
      dataType: 'json',                //data format      
      success: function(data)          //on recieve of reply
      {
        var id1 = data[0];              //get id
        var vname = data[1];           //get name
        //--------------------------------------------------------------------
        // 3) Update html content
        //--------------------------------------------------------------------
        $('#output').html("<b>id: </b>"+id1+"<b> name: </b>"+vname); //Set output element html
        //recommend reading up on jquery selectors they are awesome 
        // http://api.jquery.com/category/selectors/
      } 
    });
  }
  </script>
    </head>
  <body>
<form name="form">
  <!-------------------------------------------------------------------------
  1) Create some html content that can be accessed by jquery
  -------------------------------------------------------------------------->
  <h2> Client example </h2>
  <h3>Output: </h3>
  <div id="output">this element will be accessed by jquery and this text replaced</div>
<?php $data = ExecuteNonQuery('select * FROM users') or die(mysql_error()); 
				// $info = mysql_fetch_array($data);
				  ?>
            		<select id="ddlsub"  name="ddlsub" style="text-transform:uppercase;" onclick="getdata1('ddlsub',this);">
						<option value="">Select</option>					
					<?php 	
						 while($info = mysql_fetch_assoc( $data )) 
							 {?> 
                    <option value="<?php echo $info['userid']; ?>"><?php echo $info['firstname']; ?></option>
                    <?php } ?>
                    </select>
  </form>
 
  </body>
</html>