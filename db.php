<?php

include_once("config.php");

$link = mysql_connect("$dbhost","$dbuser","$dbpass")
    or die('Could not connect: ' . mysql_error());

mysql_set_charset('utf8',$link);

mysql_select_db("$db") or die('Could not select database');

?>
