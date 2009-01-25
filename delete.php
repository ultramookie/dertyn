<?php

include_once("header.php");
include_once("db.php");
include_once("dertyn.php");

$id = stripslashes($_GET['number']);
$type = stripslashes($_GET['type']);

if (!(stripslashes($_POST['checksubmit'])) &&  (checkCookie())) {
        showDelform($id,$type);
} else if ( (stripslashes($_POST['checksubmit'])) && (checkCookie()) ) {
	deleteEntry( stripslashes($_POST['id']), stripslashes($_POST['type']));
} else {
        echo "please <a href='login.php'>login</a> in order to delete entries!";
}	
?>

<?php
	include_once("footer.php");
?>

