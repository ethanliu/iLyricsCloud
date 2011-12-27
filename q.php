<?php
require dirname(__FILE__) . "/classes/ilyrics.php";

$action = strtolower(@trim($_REQUEST['action']));
$lang = strtolower(@trim($_REQUEST['lang']));
$key = strtolower(@trim($_REQUEST['key']));

if (empty($action)) {
	die('{"error":"Not available"}');
}
if (empty($lang)) {
	die('{"error":"Not available"}');
}
switch ($key) {
	case 'Y3JlYXRpdmVjcmFwMjU2N2E1ZWM5NzA1ZWI3YWMyYzk4NDAzM2UwNjE4OWQ=':
		$platform = 'web';
		break;
	case 'Y3JlYXRpdmVjcmFwMGIzZjQ1YjI2NmE5N2Q3MDI5ZGRlN2MyYmEzNzIwOTM=':
		$platform = 'iphone';
		break;
	case 'Y3JlYXRpdmVjcmFwMDk0MDFmZGVkNDMzYzM0NzA5ZmQxZjE4NzI3MjgxNjI=':
		$platform = 'ipad';
		break;
	case 'Y3JlYXRpdmVjcmFwOWQyYjFhZDViYmMxNmM0NGQ0OTExNmRjMjEzYzUzZjI=':
		$platform = 'widget';
		break;
	default:
		$platform = '';
		die('{"error":"Not available"}');
		break;
}

$fetcher = new LyricsFetcher();
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
		echo $fetcher->artwork();
		break;
}
