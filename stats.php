<?php
/**
 * Statics
 *
 * @author Ethan Liu
 * @version $Id$
 * @copyright , 27 December, 2011
 * @package class
 **/

require_once dirname(__FILE__) . "/classes/stats.php";
$stats = new Stats();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>iLyrics report</title>
	<meta name="title" content="" />
	<meta name="Description" content="" />
	<meta name="Keywords" content="" />
	<meta name="robots" content="index,follow,archive" />
	<meta name="Copyright" content="(c) 2011 Creativecrap.com" />
	<meta name="Author" content="Ethan" />
	<meta http-equiv="X-UA-Compatible" content="IE=8" />
	<meta http-equiv="X-UA-Compatible" content="chrome=1" />
	<meta http-equiv="imagetoolbar" content="no" />
	<style>
	body {padding:20px; font: 75% "Lucida Grande", "Trebuchet MS", Verdana, sans-serif;}
	h1 {margin-top:5px; margin-bottom:5px;}
	img {margin:10px;}
	</style>
</head>
<body>

<h1>Stats</h1>

<div>
<?php
$result = $stats->numberOfLyrics();
printf("%s lyrics, ", number_format($result));
$result = $stats->numberOfArtworks();
printf("%s artworks.", number_format($result));
?>
</div>

<h2>First Lyrics</h2>
<ul>
<?php
$result = $stats->firstLyrics();
echo "<li>" . $result['id'] . "</li>";
echo "<li>" . date("Y-m-d H:i:s", $result['created']) . "</li>";
echo "<li>" . $result['title'] . "</li>";
echo "<li>" . $result['artist'] . "</li>";
echo "<li>" . $result['album'] . "</li>";
echo "<li>" . $result['lyrics'] . "</li>";
?>
</ul>

<h2>First Lyrics</h2>
<ul>
<?php
$result = $stats->lastLyrics();
echo "<li>" . $result['id'] . "</li>";
echo "<li>" . date("Y-m-d H:i:s", $result['created']) . "</li>";
echo "<li>" . $result['title'] . "</li>";
echo "<li>" . $result['artist'] . "</li>";
echo "<li>" . $result['album'] . "</li>";
echo "<li>" . $result['lyrics'] . "</li>";
?>
</ul>

<h2>First Artwork</h2>
<ul>
<?php
$result = $stats->firstArtwork();
echo "<li>" . $result['id'] . "</li>";
echo "<li>" . date("Y-m-d H:i:s", $result['created']) . "</li>";
echo "<li>" . $result['artist'] . "</li>";
echo "<li>" . $result['album'] . "</li>";
echo "<li>" . $result['url'] . "</li>";
echo "<img src='" . $result['url'] . "'>";
?>
</ul>

<h2>Last Artwork</h2>
<ul>
<?php
$result = $stats->lastArtwork();
echo "<li>" . $result['id'] . "</li>";
echo "<li>" . date("Y-m-d H:i:s", $result['created']) . "</li>";
echo "<li>" . $result['artist'] . "</li>";
echo "<li>" . $result['album'] . "</li>";
echo "<li>" . $result['url'] . "</li>";
echo "<img src='" . $result['url'] . "'>";
?>
</ul>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
</body>
</html>
