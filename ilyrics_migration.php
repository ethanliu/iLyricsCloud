<?php
require dirname(__FILE__) . "/classes/ilyrics.php";
$data = file_get_contents('http://aragon.localhost/ilyricsm.php');
$data = json_decode($data);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>iLyrics migration</title>
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
	form label {display:inline-block; width:50px;}
	input, select, button {margin-bottom:5px;}
	#result-wrapper {position:absolute; top:20px; left:300px; width:60%;}
	</style>
</head>
<body>
<?php

for ($i=0, $loop = count($data); $i < $loop; $i++) { 
	$fetcher = new LyricsFetcher();

	$fetcher->lyricsSource = 'en';
	$lyrics = $data[$i]->lyrics;

	$pattern = '/([\x{3040}-\x{30FF}])/ui';
	if (preg_match($pattern, $lyrics)) {
		$fetcher->lyricsSource = 'jp';
	}
	else {
		$pattern = '/([\x{4e00}-\x{9fa5}])/ui';
		if (preg_match($pattern, $lyrics)) {
			$fetcher->lyricsSource = 'zh';
		}
	}

	$fetcher->title = trim($data[$i]->title);
	$fetcher->artist = trim($data[$i]->artist);
	$fetcher->album = trim($data[$i]->album);

	//$lyrics = strip_tags($data[$i]->lyrics, '<br>');
	$fetcher->saveLyrics($lyrics);
	
	echo $fetcher->lyricsSource . "<br>";
	echo $fetcher->title . "<br>";
	echo $fetcher->artist . "<br>";
	echo $fetcher->album . "<br>";
	//echo nl2br($fetcher->lyrics);
	echo "<hr>";
	unset($fetcher);
}

?>
</body>
</html>
