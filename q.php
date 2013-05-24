<?php
// Requried for AFNetwork Framework over iOS devices
header("Content-type: application/json");

define('GA_ACCOUNT', 'MO-402625-5');
define('GA_DOMAIN', 'creativecrap.com');

require_once dirname(__FILE__) . "/classes/ilyrics.php";
require_once dirname(__FILE__) . "/classes/ga.php";

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

$ga = new GoogleAnalyticsTracker(GA_ACCOUNT, GA_DOMAIN);
$ga->addPageView('/ilyrics-widget');


switch ($action) {
	case 'search':
		$ga->addEvent('iLyrics Widget', 'Search', $fetcher->title . ' - ' . $fetcher->artist);
		$result = $fetcher->search();
		$fetcher->output();
		break;
	case 'lyrics':
		$ga->addEvent('iLyrics Widget', 'Lyrics', $fetcher->title . ' - ' . $fetcher->artist, $lang);
		$result = $fetcher->lyrics($lang);
		$fetcher->output();
		break;
	case 'artwork':
		$ga->addEvent('iLyrics Widget', 'Artwork', $fetcher->album);
		$url = $fetcher->artwork();
		$result = array(
			'error' => '',
			'url' => $url
		);
		echo json_encode($result);
		break;
}
