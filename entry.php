<?php

include_once("header.php");
include_once("db.php");
include_once("dertyn.php");

$sitename = getSiteName();

if(stripslashes($_POST['checksubmit'])) {
	$captcha = strip_tags($_POST['captcha']);
	$pid = strip_tags($_POST['pid']);
	$name = strip_tags($_POST['name']);
	$url = strip_tags($_POST['url']);
	$comment = strip_tags($_POST['comment'],"<p><a><i><b><img><br><ul><li><pre>");
	$ipaddy = strip_tags($_POST['ipaddy']);
	$id = $pid;
	$site = trim(strip_tags($_POST['site']));

	if (strlen($captcha) > 0) {
		echo "<br /><b>go away spammer!</b>";
	} else if (strlen($name) < 1) {
		echo "<br /><b>need to enter a name please</b>";
		$commented = 1;
	} else if (strlen($comment) < 1) {
		echo "<br /><b>if you got nothing to say...</b>";
		$commented = 1;
	} else if (strcmp($site,$sitename) != 0) {
		echo "<br /><b>you failed to type in the site name...</b>";
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
echo "<p>Basic XHTML (including links) is allowed, just don't try anything fishy. Your comment will be auto-formatted.</p>\n";

printCommentForm($pid);

printComments($pid);

?>

<?php
	include_once("footer.php");
?>

