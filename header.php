<?php 
include_once("db.php");
include_once("dertyn.php");

$siteurl = getSiteUrl();
$tagline = getTagline();
$rewriteCheck = getrewriteCheck();

$id = $_GET['id'];

$numEntries = getIndexNum();
$pagenum = 1;

	if ($rewriteCheck == 1) {
		$pid = getPid($id);
	}  else {
		$pid = $id;
	}

	if($id) {
		$subject = getSubject($pid);
		$title = "$subject - $sitename";
                $description = getArticleDesc($pid);
	} else {
		$title = $sitename;
	}
						
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title><?php echo "$title"; ?> </title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo "$siteurl"; ?>/style.css" />
<link rel="alternate" type="application/rss+xml" title="<?php echo "$sitename"; ?> (RSS 2.0)" href="<?php echo "$siteurl"; ?>/rss.php"  />
<link rel="alternate" type="application/atom+xml" title="<?php echo "$sitename"; ?> (Atom 1.0)" href="<?php echo "$siteurl"; ?>/atom.php" />
<meta name="generator" content="Dertyn <?php echo "$version"; ?>" />

<!-- YUI for Editor -->
<!-- Skin CSS file -->
<!-- Only need when user is logged in -->
<?php
if(checkCookie()) {
	echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"$siteurl/yui/build/assets/skins/sam/skin.css\">";
}
?>
<!-- End YUI -->

</head>
<body class="yui-skin-sam">
<div id="wrap">

<div id="header">
<h2><a href="<? echo "$siteurl"; ?>"><?php echo "$sitename"; ?></a></h2>
<p><?php echo "$tagline"; ?></p>
</div>

<?php include_once("sidebar.php"); ?>

<div id="main">
