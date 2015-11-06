<?php
ini_set('display_errors','off');
		require_once("includes/functions.php");
		require_once("includes/session.php");
		require_once("includes/connect.php");
		include("includes/DataAccess.php");  

?>
<?php
 
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>

<?php 			$q = intval($_POST['q']); ?>
<form method="post" action="facultyobjectives.php?q="<?php echo $q; ?>">
<tr>
            	<td class="namecol">Select Subject</td>
                <td>:</td>
		
		 <?php
		 
				$data = ExecuteNonQuery('select * FROM subject where userid='.$q) or die(mysql_error()); 
				// $info = mysql_fetch_array($data);
				  ?>
            		<td><select id="ddlsub"  name="ddlsub" style="text-transform:uppercase;">
						<option value="">Select</option>					
					<?php 	
						 while($info = mysql_fetch_assoc( $data )) 
							 {?> 
                    <option value="<?php echo $info['subid']; ?>"><?php echo $info['subname']; ?></option>
                    <?php } ?>
                    </select>
                  <span><input type="submit" value="Get Objectives"></span>                   
                    </td>

	</tr>
 <tr>
          		<td class="namecol">Objective 1</td>
                <td>:</td>
                <td><input type="text" name="objective1" value="<?php echo $_POST["obj1"]; ?>" class="inputtext"></td>
            </tr>
            <tr>
            	<td class="namecol">Objective 2</td>
                <td>:</td>
                <td><input type="text" name="objective2" value="<?php echo $_POST["obj2"]; ?>" class="inputtext"></td>
            </tr>
            <tr>
            	<td class="namecol">Objective 3</td>
                <td>:</td>
                <td><input type="text" name="objective3" value="<?php echo $_POST["obj3"]; ?>" class="inputtext"></td>
            </tr>
            <tr>
            	<td class="namecol">Objective 4</td>
                <td>:</td>
                <td><input type="text" name="objective4" value="<?php echo $_POST["obj4"]; ?>" class="inputtext"></td>
            </tr>
            <tr>
            	<td class="namecol">Objective 5</td>
                <td>:</td>
                <td><input type="text" name="objective5" value="<?php echo $_POST["obj5"]; ?>" class="inputtext"></td>
            </tr>
    		<tr>
            	<td colspan="3" align="center"><input type="button" name="btnsubmit" value="Submit"></td>
            </tr>        
</form>
</body>
</html>