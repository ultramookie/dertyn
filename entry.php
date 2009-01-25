<?php

include_once("header.php");
include_once("db.php");
include_once("dertyn.php");

$rewriteCheck = getrewriteCheck();

$id = $_GET['id'];

if(stripslashes($_POST['checksubmit'])) {
	$captcha = strip_tags($_POST['captcha']);
	$pid = strip_tags($_POST['pid']);
	$name = strip_tags($_POST['name']);
	$url = strip_tags($_POST['url']);
	$comment = strip_tags($_POST['comment'],"<p><a><i><b><img><br><ul><li><pre>");
	$ipaddy = strip_tags($_POST['ipaddy']);
	$id = $pid;

	if (strlen($captcha) > 0) {
		echo "go away spammer!";
	} else if (strlen($name) < 1) {
		echo "need to enter a name please";
		$commented = 1;
	} else if (strlen($comment) < 1) {
		echo "if you got nothing to say...";
		$commented = 1;
	} else {
		$commented = 1;
		addComment($name,$url,$comment,$ipaddy,$pid);
	}

}

if (($rewriteCheck == 1) && ($commented != 1)) {
	stripslashes($_POST['id']);
	printEntry($id,"single");
	$pid = getPid($id);
} else {
	stripslashes($_POST['id']);
	printEntry($id);
	$pid = $id;
}

echo "<p class=\"subject\"><a name=\"comments\">Comments</a></p>";

printCommentForm($pid);

printComments($pid);

?>

<?php
	include_once("footer.php");
?>

