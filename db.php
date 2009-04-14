<?php

include_once("config.php");

$link = mysql_connect("$dbhost","$dbuser","$dbpass")
    or die('Could not connect: ' . mysql_error());

mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $link);

 mysql_select_db("$db") or die('Could not select database');

?>
