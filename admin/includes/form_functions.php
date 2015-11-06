<?php
function check_required_fields($required_array) {
	$field_errors = array();
	foreach($required_array as $fieldname) {
		if (!isset($_POST[$fieldname]) || (empty($_POST[$fieldname]) && !is_numeric($_POST[$fieldname]))) { 
			$field_errors[] = $fieldname; 
		}
	}
	return $field_errors;
}

function getip()
{
	return $_SERVER['REMOTE_ADDR'];	
}
function check_max_field_lengths($field_length_array) {
	$field_errors = array();
	foreach($field_length_array as $fieldname => $maxlength ) {
		if (strlen(trim(mysql_prep($_POST[$fieldname]))) > $maxlength) { $field_errors[] = $fieldname; }
	}
	return $field_errors;
}

function display_errors($error_array) {
	echo "<p class=\"errors\">";
	echo "Please review the following fields:<br />";
	echo "<ul style=color:red;>";
	foreach($error_array as $error) {
		echo "<li style=text-align:left;>" . $error . "</li>";
	}
	echo "</ul>";
	echo "</p>";
}
function display_errors1($error_array) {
	$str="Please correct below fields:\\n";
	foreach($error_array as $error) {
		$str=$str."-". $error . "\\n";
	}
	echo '<script type="text/javascript">alert("' . $str. '");</script>';
}
function show_alert($msg) 
{
		echo '<script type="text/javascript">alert("' . $msg. '");</script>';	
}
function single_errormessage($msg) {
	$str="Please correct below field:\\n";
	$str=$str.$msg;
	echo '<script type="text/javascript">alert("' . $str. '");</script>';	
}
?>