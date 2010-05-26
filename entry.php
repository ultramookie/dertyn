<?php

include_once("header.php");
include_once("db.php");
include_once("dertyn.php");

$sitename = getSiteName();

if($_POST['checksubmit']) {
	$captcha = strip_tags($_POST['captcha']);
	$name = strip_tags($_POST['name']);
	$url = strip_tags($_POST['url']);
	$comment = strip_tags($_POST['comment']);
	$pid = strip_tags($_POST['pid']);
	$ipaddy = strip_tags($_POST['ipaddy']);
	$key = strip_tags($_POST['key']);
	$sig = strip_tags($_POST['sig']);
	$time = strip_tags($_POST['time']);
	$mynum = strip_tags($_POST['mynum']);
	$id = $pid;
	$nowtime = time();

	$realkey = crypt($mynum,$_SERVER['REMOTE_ADDR']);
	$realsig = crypt($id,$time);

	$timediff = $nowtime - $time;

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
	} else if ($key != $realkey) {
		echo "<br /><b>try your addition again.</b>";
		logerr("addition was wrong " . $errmsg, "entry");
		$commented = 1;
	} else if ( ($sig != $realsig) || ($timediff < 20) ) {
		echo "<br /><b>there's something wrong with the time. most likely, you're a bot and you submited this in less than 20 seconds. c'mon!</b>";
		logerr("bad time " . $errmsg, "entry");
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

echo "<p class=\"subject\" id=\"comments\">Comments</a></p>";

printComments($pid);

printCommentForm($pid,$name,$url,$comment);
?>

<?php
	include_once("footer.php");
?>

