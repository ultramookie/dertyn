<?php

include_once("header.php");
include_once("db.php");
include_once("dertyn.php");

$id = $_GET['number'];
$type = $_GET['type'];

if (!($_POST['checksubmit']) &&  (checkCookie())) {
        showDelform($id,$type);
} else if ( ($_POST['checksubmit']) && (checkCookie()) ) {
	deleteEntry( $_POST['id'], $_POST['type']);
} else {
        echo "please <a href='login.php'>login</a> in order to delete entries!";
}	
?>

<?php
	include_once("footer.php");
?>

