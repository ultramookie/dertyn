<?php

include_once("header.php");
include_once("db.php");
include_once("dertyn.php");

$patternid = $_GET['patternid'];

$pattern = mysql_real_escape_string($pattern);
if (checkCookie()) {
	deletePattern($patternid);
} else {
        echo "please <a href='login.php'>login</a> in order to delete entries!";
}	

include_once("footer.php");
?>

