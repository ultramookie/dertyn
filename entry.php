<?php

include_once("header.php");
include_once("db.php");
include_once("dertyn.php");

$sitename = getSiteName();

if ($rewriteCheck == 1) {
	printEntry($id,"single");
	$pid = getPid($id);
} else {
	printEntry($id);
	$pid = $id;
}

?>

<!-- insert disqus commenting code here if you want commenting -->

<?php
	include_once("footer.php");
?>

