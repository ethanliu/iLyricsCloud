<?php
// Requried for AFNetwork Framework over iOS devices
header("Content-type: application/json");
require_once dirname(__FILE__) . "/config.php";
require_once dirname(__FILE__) . "/classes/ilyrics.php";

$action = strtolower(@trim($_REQUEST['action']));
$plugin = strtolower(@trim($_REQUEST['plugin']));
$version = trim(file_get_contents("./VERSION"));
// $token = @trim($_REQUEST['token']);

if (empty($action)) {
	die('{"error":"Not available"}');
}
else if ($action === 'plugins') {
	$name = strtolower(@trim($_REQUEST['name']));
	if (!empty($name) && isset($plugins[$name])) {
		echo json_encode($plugins[$name]);
		exit;
	}
	die('[]');
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
		if (empty($plugin) || !in_array($plugin, $plugins['lyrics'])) {
			foreach ($plugins['lyrics'] as $plugin) {
				$lyrics = $fetcher->lyrics($plugin);
				if (!empty($lyrics)) {
					break;
				}
			}
		}
		else {
			$lyrics = $fetcher->lyrics($plugin);
		}
		$fetcher->output();
		break;
	case 'artwork':
		$result = array(
			'error' => '',
			'url' => $url
		);
		foreach ($plugins['artworks'] as $plugin) {
			$url = $fetcher->artwork($plugin);
			if (!empty($url)) {
				$result['url'] = $url;
				break;
			}
		}
		echo json_encode($result);
		break;
}
