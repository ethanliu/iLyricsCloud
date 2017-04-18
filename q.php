<?php
// Requried for AFNetwork Framework over iOS devices
header("Content-type: application/json");
require_once dirname(__FILE__) . "/classes/ilyrics.php";

$action = strtolower(@trim($_REQUEST['action']));
$source = strtolower(@trim($_REQUEST['source']));
$version = trim(file_get_contents("./VERSION"));
// $token = @trim($_REQUEST['token']);

if (empty($action)) {
	die('{"error":"Not available"}');
}
// if (empty($lang) && !in_array($action, array('artwork'))) {
// 	die('{"error":"Not available"}');
// }

$fetcher = new LyricsFetcher($plugins);
$fetcher->cache = false;
$fetcher->lyricsId = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
$fetcher->title = isset($_REQUEST['title']) ? $_REQUEST['title'] : '';
$fetcher->artist = isset($_REQUEST['artist']) ? $_REQUEST['artist'] : '';
$fetcher->album = isset($_REQUEST['album']) ? $_REQUEST['album'] : '';

$fetcher->sendTraffic('event', array('category' => 'iLyricsCloud ' . $version, 'action' => $action, 'label' => '', 'value' => ''));

switch ($action) {
	case 'search':
		$result = $fetcher->search();
		$fetcher->output();
		break;
	case 'lyrics':
		if (empty($source) || !in_array($source, $plugins['lyrics'])) {
			foreach ($plugins['lyrics'] as $source) {
				$lyrics = $fetcher->lyrics($source);
				if (!empty($lyrics)) {
					break;
				}
			}
		}
		else {
			$lyrics = $fetcher->lyrics($source);
		}
		$fetcher->output();
		break;
	case 'artwork':
		$result = array(
			'error' => '',
			'url' => $url
		);
		foreach ($plugins['artworks'] as $source) {
			$url = $fetcher->artwork($source);
			if (!empty($url)) {
				$result['url'] = $url;
				break;
			}
		}
		echo json_encode($result);
		break;
}
