<?php

include_once("header.php");
include_once("db.php");
include_once("dertyn.php");

$sitename = getSiteName();

// Check that a post exists.  Else, exit.
checkPostExists($id);

if($_POST['checksubmit']) {
	$captcha = strip_tags($_POST['captcha']);
	$name = strip_tags($_POST['name']);
	$url = strip_tags($_POST['url']);
	$comment = strip_tags($_POST['comment']);
	$pid = strip_tags($_POST['pid']);
	$ipaddy = strip_tags($_POST['ipaddy']);
	$site = trim(strip_tags($_POST['site']));
	$id = $pid;

	$errmsg = "name " . $name . ", url " . $url . ", comment " . $comment . ", captcha " . $captcha . ", pid " . $pid;

	if (strlen($captcha) > 0) {
		echo "<br /><b>go away spammer!</b>";
		$name = $comment = "i am a stupid spammer from ip address $ipaddy.";
		logerr("spammer " . $errmsg, "entry");
	} else if (strlen($name) < 1) {
		echo "<br /><b>need to enter a name please</b>";
		logerr("no name " . $errmsg, "entry");
		$commented = 1;
	} else if (strlen($comment) < 1) {
		echo "<br /><b>if you got nothing to say...</b>";
		logerr("empty comment " . $errmsg, "entry");
		$commented = 1;
	} else if (strcmp($site,$sitename) != 0) {
		echo "<br /><b>you failed to remove stuff from a field...</b>";
		logerr("no sitename " . $errmsg, "entry");
		$commented = 1;
	} else {
		$commented = 1;
		addComment($name,$url,$comment,$ipaddy,$pid);
		$name = $url = $comment = "";
	}

}

if (($rewriteCheck == 1) && ($commented != 1)) {
	printEntry($id,"single");
	$pid = getPid($id);
} else {
	printEntry($id);
	$pid = $id;
}

echo "<p class=\"subject\"><a name=\"comments\">Comments</a></p>";
echo "<p>Your comment will be auto-formatted.</p>\n";

printCommentForm($pid,$name,$url,$comment);

printComments($pid);

?>

<?php
	include_once("footer.php");
?>

