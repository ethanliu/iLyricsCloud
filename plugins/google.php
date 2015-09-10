<?php
/**
 * Fetcher for goole.com
 *
 * @author Ethan Liu <ethan@creativecrap.com>
 * @package plugin
 **/

function google_artwork_hook($param) {
	$query = urlencode($param['album']) . '%20' . urlencode($param['artist']);
	$url = 'https://ajax.googleapis.com/ajax/services/search/images?v=1.0';
	$url .= "&as_rights=cc_publicdomain&imgsz=large&rsz=1";
	$url .= '&q=artwork%20' . $query;
	
	// sendRequest
	// note how referer is set manually
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	// curl_setopt($ch, CURLOPT_REFERER, /* Enter the URL of your site here */);
	$body = curl_exec($ch);
	curl_close($ch);

	// now, process the JSON string
	$json = json_decode($body);
	$link = empty($json->responseData->results[0]->url) ? '' : $json->responseData->results[0]->url;
	return $link;
}