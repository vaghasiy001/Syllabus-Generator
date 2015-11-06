<?php
// Database Constants
//define("DB_SERVER", "localhost");
//define("DB_USER", "root");
//define("DB_PASS", "root");
define("DB_SERVER", "localhost");
define("DB_USER", "root");
define("DB_PASS", "root");
define("DB_NAME", "sg");
// 1. Create a database connection
global $connection;
$connection = mysqli_connect(DB_SERVER,DB_USER,DB_PASS,DB_NAME);
if (!$connection) {
	die("Database connection failed: " . mysql_error());
}

// 2. Select a database to use 
$db_select = mysqli_select_db($connection,DB_NAME);
if (!$db_select) {
	die("Database selection failed: " . mysql_error());
}

?>