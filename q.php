<?php
// Requried for AFNetwork Framework over iOS devices
header("Content-type: application/json");
require_once dirname(__FILE__) . "/classes/ilyrics.php";

$action = strtolower(@trim($_REQUEST['action']));
$lang = strtolower(@trim($_REQUEST['lang']));
// $token = @trim($_REQUEST['token']);

if (empty($action)) {
	die('{"error":"Not available"}');
}
if (empty($lang) && !in_array($action, array('artwork'))) {
	die('{"error":"Not available"}');
}

$fetcher = new LyricsFetcher($plugins);
$fetcher->cache = false;
$fetcher->lyricsId = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
$fetcher->title = isset($_REQUEST['title']) ? $_REQUEST['title'] : '';
$fetcher->artist = isset($_REQUEST['artist']) ? $_REQUEST['artist'] : '';
$fetcher->album = isset($_REQUEST['album']) ? $_REQUEST['album'] : '';

switch ($action) {
	case 'search':
		$result = $fetcher->search();
		$fetcher->output();
		break;
	case 'lyrics':
		$result = $fetcher->lyrics($lang);
		$fetcher->output();
		break;
	case 'artwork':
		$url = $fetcher->artwork();
		$result = array(
			'error' => '',
			'url' => $url
		);
		echo json_encode($result);
		break;
}
