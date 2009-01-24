<?php

include_once("header.php");
include_once("db.php");
include_once("dertyn.php");

$rewriteCheck = getrewriteCheck();

$id = $_GET['id'];

if ($rewriteCheck == 1) {
	stripslashes($_POST['id']);
	printEntry($id,"single");
} else {
	stripslashes($_POST['id']);
	printEntry($id);
}

?>

<?php
	include_once("footer.php");
?>

