<?php

include_once("header.php");
include_once("db.php");
include_once("dertyn.php");

$id = $_GET['number'];

printEntry($id);

?>

<?php
	include_once("footer.php");
?>

