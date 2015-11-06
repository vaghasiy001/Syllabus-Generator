<?php
			include("includes/DataAccess.php");  
	ini_set('display_errors','off');
	$q=$_GET["q"];
	$cntrec=CountRecords('select * FROM objectives where subid='.$q) or die(mysql_error()); 
	if($cntrec>0)
	{
	$data = ExecuteNonQuery('select * FROM objectives where subid='.$q) or die(mysql_error()); 
	 while($info = mysql_fetch_assoc( $data )) 
	{
?>
<table>
<tr>
            	<td class="namecol">Objective 1</td>
                <td>:</td>
                <td><input type="text" name="objective1" value="<?php echo $info["obj1"]; ?>" class="inputtext"></td>
            </tr>
            <tr>
            	<td class="namecol">Objective 2</td>
                <td>:</td>
                <td><input type="text" name="objective2" value="<?php echo $info["obj2"]; ?>" class="inputtext"></td>
            </tr>
            <tr>
            	<td class="namecol">Objective 3</td>
                <td>:</td>
                <td><input type="text" name="objective3" value="<?php echo $info["obj3"]; ?>" class="inputtext"></td>
            </tr>
            <tr>
            	<td class="namecol">Objective 4</td>
                <td>:</td>
                <td><input type="text" name="objective4" value="<?php echo $info["obj4"]; ?>" class="inputtext"></td>
            </tr>
            <tr>
            	<td class="namecol">Objective 5</td>
                <td>:</td>
                <td><input type="text" name="objective5" value="<?php echo $info["obj5"]; ?>" class="inputtext"></td>
            </tr>
    		<tr>
            	<td colspan="3" align="center"><input type="button" name="btnsubmit" value="Submit"></td>
            </tr>
            </table>
            <?php }?>
		<?php }
		else {
		?>
<table>
<tr>
            	<td class="namecol">Objective 1</td>
                <td>:</td>
                <td><input type="text" name="objective1" value="dsdf" class="inputtext"></td>
            </tr>
            <tr>
            	<td class="namecol">Objective 2</td>
                <td>:</td>
                <td><input type="text" name="objective2" value="fsdf" class="inputtext"></td>
            </tr>
            <tr>
            	<td class="namecol">Objective 3</td>
                <td>:</td>
                <td><input type="text" name="objective3" value="dfs" class="inputtext"></td>
            </tr>
            <tr>
            	<td class="namecol">Objective 4</td>
                <td>:</td>
                <td><input type="text" name="objective4" value="sdfsd" class="inputtext"></td>
            </tr>
            <tr>
            	<td class="namecol">Objective 5</td>
                <td>:</td>
                <td><input type="text" name="objective5" value="sdfsd" class="inputtext"></td>
            </tr>
    		<tr>
            	<td colspan="3" align="center"><input type="submit" name="btnsubmit" value="Submit"></td>
            </tr>
  </table>
            <?php }?>