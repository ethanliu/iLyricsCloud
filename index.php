<?php
require dirname(__FILE__) . "/class.ilyrics.php";

$test = false;
//$_REQUEST['action'] = 'lyrics';
$_REQUEST['key'] = 'web';

$action = strtolower(@trim($_REQUEST['action']));
$platform = strtolower(@trim($_REQUEST['key']));
$lang = strtolower(@trim($_REQUEST['lang']));

if (empty($action)) {
	die('{"error":"Not available"}');
}
if (empty($platform)) {
	die('{"error":"Not available"}');
}
if (empty($lang)) {
	die('{"error":"Not available"}');
}

$fetcher = new LyricsFetcher();
if ($test) {
	$fetcher->title = "I Can't Lie ft. jayz";
	$fetcher->artist = "Maroon 5";
	$fetcher->album = "Hands All Over";
	//$fetcher->lyricsId = 9;
}
else {
	$fetcher->title = isset($_REQUEST['title']) ? $_REQUEST['title'] : '';
	$fetcher->artist = isset($_REQUEST['artist']) ? $_REQUEST['artist'] : '';
	$fetcher->album = isset($_REQUEST['album']) ? $_REQUEST['album'] : '';
}

switch ($action) {
	case 'search':
		$result = $fetcher->search();
		//var_dump($result);
		$fetcher->output();
		break;
	case 'lyrics':
		$result = $fetcher->lyrics($lang);
		$fetcher->output();
		break;

	case 'artwork':
		echo $fetcher->artwork();
		break;
}
