<?php
header('Content-type: application/atom+xml');

include_once("db.php");
include_once("dertyn.php");

$numRss = getRssNum();
$realname = getRealName();

echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>";
?>

<feed xmlns="http://www.w3.org/2005/Atom"
      xmlns:thr="http://purl.org/syndication/thread/1.0"
      xml:lang="en-us"  
      xml:base="<?php echo $siteurl; ?>/atom.php">
	<title type="text"><?php echo $sitename; ?></title>
	<subtitle type="text"><?php echo $tagline; ?></subtitle>
	<updated><?php getLastUpdatedAtom(); ?></updated>
	<generator uri="http://dertyn.com/" version="<?php echo $version; ?>">dertyn</generator>

	<link rel="alternate" type="text/html" href="<?php echo $siteurl; ?>" />
	<id><?php echo $siteurl; ?>/atom.php</id>
	<link rel="self" type="application/atom+xml" href="<?php echo $siteurl; ?>/atom.php" />
	<author>
		<name><?php echo $realname; ?></name>
	</author>

<?php
	printAtom($numRss);
?>

</feed>

