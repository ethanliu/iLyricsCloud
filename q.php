<?php
require dirname(__FILE__) . "/classes/ilyrics.php";

$action = strtolower(@trim($_REQUEST['action']));
$lang = strtolower(@trim($_REQUEST['lang']));
$key = @trim($_REQUEST['key']);

if (empty($action)) {
	die('{"error":"Not available"}');
}
if (empty($lang) && !in_array($action, array('artwork', 'news'))) {
	die('{"error":"Not available"}');
}
switch ($key) {
	case 'Y3JlYXRpdmVjcmFwMjU2N2E1ZWM5NzA1ZWI3YWMyYzk4NDAzM2UwNjE4OWQ':
		$platform = 'web';
		break;
	case 'Y3JlYXRpdmVjcmFwMGIzZjQ1YjI2NmE5N2Q3MDI5ZGRlN2MyYmEzNzIwOTM':
		$platform = 'iphone';
		break;
	case 'Y3JlYXRpdmVjcmFwMDk0MDFmZGVkNDMzYzM0NzA5ZmQxZjE4NzI3MjgxNjI':
		$platform = 'ipad';
		break;
	case 'Y3JlYXRpdmVjcmFwOWQyYjFhZDViYmMxNmM0NGQ0OTExNmRjMjEzYzUzZjI':
		$platform = 'widget';
		break;
	default:
		$platform = '';
		die('{"error":"Not available"}');
		break;
}

$plugins = array();
$plugins['en'] = array('metrolyrics:lyrics', 'lyricscom:lyrics', 'lyricswiki:lyrics');
$plugins['zh'] = array('mojim:lyrics');
$plugins['jp'] = array('yahoojp:lyrics', 'jpopasia:lyrics', 'utamap:lyrics');
$plugins['artwork'] = array('google:artwork', 'kkbox:artwork');

$fetcher = new LyricsFetcher($plugins);
$fetcher->cache = false;
$fetcher->lyricsId = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
$fetcher->title = isset($_REQUEST['title']) ? $_REQUEST['title'] : '';
$fetcher->artist = isset($_REQUEST['artist']) ? $_REQUEST['artist'] : '';
$fetcher->album = isset($_REQUEST['album']) ? $_REQUEST['album'] : '';

// Requried for AFNetwork Framework over iOS devices
header("Content-type: application/json");
switch ($action) {
	case 'search':
		$result = $fetcher->search();
		$fetcher->output();
		break;
	case 'lyrics':
		$result = $fetcher->lyrics($lang);
		$fetcher->output();
		break;
	case 'news':
		$category = @trim($_REQUEST['cate']);
		$result = $fetcher->news($category);
		echo json_encode($result);
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
