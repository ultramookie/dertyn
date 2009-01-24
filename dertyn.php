<?php

// dertyn main library
// steve "mookie" kong
// http://ultramookie.com
//
// licensed under gplv3
// http://www.gnu.org/licenses/gpl-3.0.html

error_reporting(E_ERROR | E_PARSE);

$sitename = getSiteName();
$siteurl = getSiteUrl();
$indexNum = getIndexNum();
$rssNum = getRssNum();
$numOfEntries = getNumEntries();

function showUpdateForm() {
	$ua = $_SERVER['HTTP_USER_AGENT'];
        echo "<form action=\"";
        echo $_SERVER['PHP_SELF'];
        echo "\"";
        echo " method=\"post\">";
	if (eregi("IEMobile|Windows\ CE|iPhone|Mobile",$ua)) {
	        echo "<input type=\"text\" name=\"subject\" />";
	        echo "<input type=\"text\" name=\"body\" />";
	} else {
	        echo "<input type=\"text\" name=\"subject\" />";
		echo "<textarea cols=\"70\" rows=\"24\" name=\"body\"></textarea>";
	}
        echo "<input type=\"hidden\" name=\"checksubmit\" value=\"1\">";
	echo "<br />";
        echo "<input type=\"submit\" name=\"submit\" value=\"post\" id=\"submitbutton1\">";
        echo "</form>";
}

function addEntry($subject,$body) {
	$subject = mysql_real_escape_string($subject);
	$body = mysql_real_escape_string($body);
	$lowersubject = strtolower($subject);
        $slugdashes = preg_replace("/\s/","-",$lowersubject);
        $slug = ereg_replace("[^a-zA-Z0-9-]","",$slugdashes);

	$query = "insert into main (subject,body,entrytime,slug) values ('$subject','$body',NOW(),'$slug')";
	$status = mysql_query($query);
}

function showEntriesIndex() {

	$num = getIndexNum();
        $query = "select id from main order by entrytime desc limit $num";
        $result = mysql_query($query);

        while ($row = mysql_fetch_array($result)) {
		printEntry($row['id']);
        }
}

function showEntriesArchive($num,$pnum) {

        if($pnum == 1) {
                $offset = 1;
        } else {
                $offset = ($pnum-1) * $num;
        }

        $query = "select id from main order by entrytime desc limit $offset,$num";
        $result = mysql_query($query);

        while ($row = mysql_fetch_array($result)) {
		printEntry($row['id']);
        }
}


function printEntry($id) {
       
	$query = "select subject,body,date_format(entrytime, '%b %e, %Y @ %h:%i %p') as date from main where id = '$id'";
        $result = mysql_query($query);
        $row = mysql_fetch_array($result);

	if (ereg(".*http.*",$row['body'])) {
		$text = makeLinks(nl2br($row['body']));
	} else {
		$text = nl2br($row['body']);
	}

	echo "\n";
        echo "<p class=\"subject\"><a href=\"entry.php?number=" . $id . "\">" . $row['subject'] . "</a></p>";
	echo "\n";
	echo "<p class=\"timedate\">" . $row['date'];
	if(checkCookie()) {
		echo "<a href=\"delete.php?number=" . $id ."\"><img src=\"page_delete.gif\" border=\"0\" /></a> ";
	}
	echo "</p>";
	echo "\n";
        echo "<p class=\"entry\">" . $text . " </p>";
	echo "\n";
	echo "<hr />";
}

function makeYouTube($in_url) {

	list($blah,$args) = split("\?",$in_url,2);

	if ($args) {
		$argsList = split("\&",$args);
		$num = count($argsList);
		for($i=0;$i<=$num;$i++) {
			list($key,$value) = split("=",$argsList[$i]);
			$$key = $value;
		}
		if ($v) {
			$youtube = "<object width=\"425\" height=\"344\"><param name=\"movie\" value=\"http://www.youtube.com/v/$v&hl=en&fs=1\"></param><param name=\"allowFullScreen\" value=\"true\"></param><embed src=\"http://www.youtube.com/v/$v&hl=en&fs=1\" type=\"application/x-shockwave-flash\" allowfullscreen=\"true\" width=\"425\" height=\"344\"></embed></object>";
		} else {
		$youtube = "<a href=\"$youtube_url\">$youtube_url</a>";
		}
	}

	return ($youtube);
}

function makeFlickr($in_url) {

	$appkey = "260422cecc98a0ef5233856d6b7ffc05";

	list($http,$blah,$base,$photos,$user,$photoid) = split("/",$in_url,7);

	if ($photoid) {
		$url = "http://api.flickr.com/services/rest/";

		$session = curl_init();
		curl_setopt ( $session, CURLOPT_URL, $url );
		curl_setopt ( $session, CURLOPT_HEADER, false );
		curl_setopt ( $session, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $session, CURLOPT_POST, 1);
		curl_setopt ( $session, CURLOPT_POSTFIELDS,"method=flickr.photos.getSizes" . "&photo_id=" . $photoid . "&api_key=" . $appkey);
		$result = curl_exec ( $session );
		curl_close( $session );

		$xml = simplexml_load_string($result);

		if ($xml->attributes() == "ok") {
			foreach ($xml->sizes->size[3]->attributes() as $key => $value) {
				$$key = $value;
			}

			$flickr = "<a href=\"$in_url\"><img src=\"$source\" width=\"$width\" height=\"$height\" border=\"0\" /></a>";
		} else {
			$flickr = "<a href=\"$in_url\">$in_url</a>";
		}

	} else {
		$flickr = "<a href=\"$in_url\">$in_url</a>";
	}

	return($flickr);
}

function makeLinks($text) {
	$chunk = preg_split("/[\s,]+/", $text);
	$size = count($chunk);

	for($i=0;$i<$size;$i++) {
		if(ereg("^http.*youtube\.com.*watch",$chunk[$i])) {
			$embed = makeYouTube($chunk[$i]);
			$total = $total . "<br /><br />" . $embed . "<br /><br />";
		} else if(ereg("^http.*flickr\.com.*photos",$chunk[$i])) {
			$embed = makeFlickr($chunk[$i]);
			$total = $total . "<br /><br />" . $embed . "<br /><br />";
		} else if(ereg("^http",$chunk[$i])) {
			$url = $chunk[$i];
			$new = "<a href=\"$url\" rel=\"nofollow\" target=\"blank\">$url</a>";
			$total = $total . " " . $new;
		} else {
			$total = $total . " " . $chunk[$i];
		}
	}

	return $total;
}

function printRSS($num) {
        $query = "select id,subject,body,date_format(entrytime, '%a, %d %b %Y %H:%i:%s') as date from main order by entrytime desc limit $num";
        $result = mysql_query($query);

	$siteurl = getSiteUrl();

        while ($row = mysql_fetch_array($result)) {
		echo "\t<item>\n";
		echo "\t\t<title>" . $row['subject'] . "</title>\n";
		echo "\t\t<pubDate>" . $row['date'] . " PST</pubDate>\n";
		echo "\t\t<guid>" . $siteurl . "/entry.php?number=" . $row['id'] . "</guid>\n";
		echo "\t\t<link>" . $siteurl . "/entry.php?number=" . $row['id'] . "</link>\n";
		echo "\t</item>\n";
        }
}

function showLoginForm() {
	echo "<form action=\"";
	echo $_SERVER['PHP_SELF'];
	echo "\"";
	echo " method=\"post\">";
	echo "user: <input type=\"text\" name=\"user\"><br />";
	echo "pass: <input type=\"password\" name=\"pass\"><br />";
	echo "<input type=\"hidden\" name=\"checksubmit\" value=\"1\">";
	echo "<input type=\"submit\" name=\"submit\" value=\"login\">";
	echo "</form>";
	echo "<hr />";
	echo "<a href='forgot.php'>forgot password</a>";
}

function getSecret() {
        $query = "select secret from user limit 1";
        $result = mysql_query($query);

        $row = mysql_fetch_array($result);

        return($row['secret']);
}

function getCookie() {
        $query = "select cookie from user limit 1";
        $result = mysql_query($query);

        $row = mysql_fetch_array($result);

        return($row['cookie']);
}

function checkCookie() {
	$secret = getSecret();
	$cookie = $_COOKIE['dertyn'];
	$user = $_COOKIE['user'];
	$storedcookie = getCookie();

	$loggedin = 0;

	$test = sha1($user . $secret);

	if ( (strlen($cookie) > 0) && ($cookie == $storedcookie) && ($cookie == $test) ) {
		$loggedin = 1;
	}

	return $loggedin;
}

function getUserName() {
	if(checkCookie()) {
		$name = $_COOKIE['user'];
	} else {
		$name = "not logged in";
	}
	return $name;
}

function getNumEntries() {
	$query = "select count(id) from main";
	$result = mysql_query($query);

	$row = mysql_fetch_array($result);

        return($row['count(id)']);
}

function getEmail() {
        $query = "select email from user limit 1";
        $result = mysql_query($query);

        $row = mysql_fetch_array($result);

        return($row['email']);
}

function getUser() {
        $query = "select name from user limit 1";
        $result = mysql_query($query);

        $row = mysql_fetch_array($result);

        return($row['name']);
}

function getSiteName() {
	$query = "select name from site limit 1";
	$result = mysql_query($query);

	$row = mysql_fetch_array($result);

	return($row['name']);
}

function getSiteUrl() {
	$query = "select url from site limit 1";
	$result = mysql_query($query);

	$row = mysql_fetch_array($result);

	return("http://" . $row['url']);
}

function getRawSiteURl() {
	$query = "select url from site limit 1";
	$result = mysql_query($query);

	$row = mysql_fetch_array($result);

	return($row['url']);
}

function getIndexNum() {
        $query = "select indexNum from site limit 1";
        $result = mysql_query($query);

        $row = mysql_fetch_array($result);

        return($row['indexNum']);
}

function getRssNum() {
        $query = "select rssNum from site limit 1";
        $result = mysql_query($query);

        $row = mysql_fetch_array($result);

        return($row['rssNum']);
}

function setLoginCookie($user) {
		$secret = getSecret();
                $login = sha1($user . $secret);
                $expiry = time()+60*60*24*30;
		setcookie('user',$user,"$expiry");
                setcookie('dertyn',$login,"$expiry");

	        $query = "update user set cookie='$login' where name like '$user'";
        	$result = mysql_query($query);
}

function killCookie() {
	if(checkCookie()) {
		$expiry = time() - 4800;
		setcookie('user','',"$expiry");
		setcookie('dertyn','',"$expiry");
	}
}

function checkLogin($user,$pass) {
        $salt = substr("$user",0,2);
        $epass = crypt($pass,$salt);

	$query = "select * from user where name like '$user' and pass like '$epass'";
	$result = mysql_query($query);

	if (mysql_num_rows($result)==1) {
		return 0;
	} else {
		return 1;
	}
}

function showAddform() {
	echo "<form action=\"";
	echo $_SERVER['PHP_SELF'];
	echo "\"";
	echo " method=\"post\">";
	echo "user: <input type=\"text\" name=\"user\"><br />";
	echo "email: <input type=\"text\" name=\"email\"><br />";
	echo "password: <input type=\"password\" name=\"pass1\"><br />";
	echo "password (again): <input type=\"password\" name=\"pass2\"><br />";
	echo "name of site: <input type=\"text\" name=\"site\"><br />";
	echo "base url (without http://): <input type=\"text\" name=\"url\"><br />";
	echo "<input type=\"hidden\" name=\"checksubmit\" value=\"1\">";
	echo "<input type=\"submit\" name=\"submit\" value=\"install\">";
	echo "</form>";
}

function showSettingsform() {
	$sitename = getSiteName();
	$rawsiteurl = getRawSiteUrl();
	$indexNum = getIndexNum();
	$rssNum = getRssNum();
	
	echo "<p><b>general site settings:</b></p>";
	echo "<form action=\"";
	echo $_SERVER['PHP_SELF'];
	echo "\"";
	echo " method=\"post\">";
        echo "user: <input type=\"text\" name=\"user\"><br />";
        echo "pass: <input type=\"password\" name=\"pass\"><br />";
	echo "name of site: <input type=\"text\" name=\"site\" value=\"" . $sitename . "\"><br />";
	echo "base url (without http://): <input type=\"text\" name=\"url\" value=\"" . $rawsiteurl . "\"><br />";
	echo "number of entries to display per page: <input type=\"text\" name=\"index\" value=\"" . $indexNum . "\"><br />";
	echo "number of entries to display in rss feed: <input type=\"text\" name=\"rss\" value=\"" . $rssNum . "\"><br />";
	echo "<input type=\"hidden\" name=\"checksubmit\" value=\"1\">";
	echo "<input type=\"submit\" name=\"submit\" value=\"update\">";
	echo "</form>";


}

function showDelform($id,$secret) {
	echo "hey! are you SURE you want to delete this entry?";
	$siteurl = getSiteUrl();
        echo "<form action=\"";
        echo $_SERVER['PHP_SELF'];
        echo "\"";
        echo " method=\"post\">";
        echo "<input type=\"hidden\" name=\"checksubmit\" value=\"1\">";
        echo "<input type=\"hidden\" name=\"id\" value=\"$id\">";
        echo "<input type=\"submit\" name=\"submit\" value=\"YES\">";
	echo " <a href=\"$siteurl\">no</a>";
        echo "</form>";
}

function showForgotform() {
        echo "Please enter the following information to reset your password: <br />";
        echo "<form action=\"";
        echo $_SERVER['PHP_SELF'];
        echo "\"";
        echo " method=\"post\">";
        echo "user: <input type=\"text\" name=\"user\"><br />";
        echo "email: <input type=\"text\" name=\"email\"><br />";
        echo "<input type=\"hidden\" name=\"checksubmit\" value=\"1\">";
        echo "<input type=\"submit\" name=\"submit\" value=\"Reset Password\">";
        echo "</form>";
}


function deleteEntry($id) {
	$query = "delete from main where id='$id'";
	$result = mysql_query($query);

	echo "entry " . $id . " deleted!";
}

function generateCode($length=16) {
	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
        $code = "";
        $clen = strlen($chars) - 1;
        while (strlen($code) < $length) {
		$code .= $chars[mt_rand(0,$clen)];
        }
        return $code;
}

function showPasswordChangeform() {
	$username = getUserName();
	echo "changing password for ";
	echo $username;
	echo "<form action=\"";
	echo $_SERVER['PHP_SELF'];
	echo "\"";
	echo " method=\"post\">";
	echo "old pass: <input type=\"password\" name=\"oldpass\"><br />";
	echo "new pass: <input type=\"password\" name=\"newpass1\"><br />";
	echo "new pass (again): <input type=\"password\" name=\"newpass2\"><br />";
	echo "<input type=\"hidden\" name=\"checksubmit\" value=\"1\">";
	echo "<input type=\"submit\" name=\"submit\">";
	echo "</form>";
}

function changePass($user,$pass) {
	$email = getEmail();
        $salt = substr("$email",0,2);
        $epass = crypt($pass,$salt);

	$query = "update user set pass='$epass' where name like '$user'";
	$result = mysql_query($query);

	echo " <img src=\"icon_accept.gif\" border=\"0\" /> password has been updated!";
}

function changeSettings($site,$url,$numberIndex,$numberRSS) {

        $site = mysql_real_escape_string($site);
        $url = mysql_real_escape_string($url);
        $numberIndex = mysql_real_escape_string($numberIndex);
        $numberRSS = mysql_real_escape_string($numberRSS);

	$query = "update site set name='$site', url='$url', indexNum='$numberIndex', rssNum='$numberRSS' limit 1";
	$result = mysql_query($query);

	echo "your settings have been updated!";

}

function addUser($user,$email,$pass,$site,$url) {
        $salt = substr("$email",0,2);
        $epass = crypt($pass,$salt);

	$query = "select * from user";
	$status = mysql_query($query);

	if (mysql_num_rows($status) >= 1) {
		echo "already installed!";
	} else {
		$user = mysql_real_escape_string($user);
		$email = mysql_real_escape_string($email);
		$pass = mysql_real_escape_string($pass);
		$site = mysql_real_escape_string($site);
		$url = mysql_real_escape_string($url);
		
		$query = "create table user ( name varchar(30) NOT NULL, email varchar(30) NOT NULL, pass varchar(30) NOT NULL, secret varchar(6), cookie varchar(300) )";
		$status = mysql_query($query);

		$query = "create table main ( id int NOT NULL AUTO_INCREMENT, entrytime DATETIME NOT NULL, subject varchar(160) NOT NULL, body MEDIUMTEXT, slug varchar(160), PRIMARY KEY (id), FULLTEXT(subject,body)); ";
		$status = mysql_query($query);
		
		$query = "create table comments ( cid int NOT NULL AUTO_INCREMENT, pid int NOT NULL, commenttime DATETIME NOT NULL, ip varchar(16), name varchar(40), url varchar(100), comment MEDIUMTEXT, PRIMARY KEY (cid)); ";
		$status = mysql_query($query);
		
		$query = "create table site ( name varchar(160) NOT NULL, url varchar(160) NOT NULL, indexNum int NOT NULL, rssNum int NOT NULL ); ";
		$status = mysql_query($query);
	
		$secret = generateCode();
	
		$query = "insert into user (name,email,pass,secret) values ('$user','$email','$epass','$secret')";
		$status = mysql_query($query);
	
		$query = "insert into site (name,url,indexNum,rssNum) values ('$site','$url','10','10')";
		$status = mysql_query($query);

		echo "dertyn installed!  thanks!";
	}
}

function sendRandomPass($email,$func) {
        $pass = generateCode();
	$salt = substr("$email",0,2);
	$epass = crypt($pass,$salt);

	$email = mysql_real_escape_string($email);
	
	$to = "$email";
	$from = "From: webmaster@ultramookie.com";
	$subject = "password";
	$body = "hi, your password is $pass. please login using your email address and the password.  feel free to change your password at anytime.";
	if (mail($to, $subject, $body, $from)) {
		if ((strcmp($func,"new")) == 0) {
			$query = "insert into user (email,pass) values ('$email','$epass')";
			$status = mysql_query($query);
		} else if ((strcmp($func,"lost")) == 0) {
			$query = "update user set pass='$epass' where email like '$email'";
			$status = mysql_query($query);
		} else {
			echo "nothing to do!";
		}

		echo "<p>Your new password has been sent!  <a href='login.php'>login</a> after you receive your password.</p>";
	} else {
		echo("<p>Message delivery failed...</p>");
	}
}
