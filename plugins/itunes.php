<?php
/**
 * Artworks search via iTunes Search API
 *
 * @author Ethan Liu <ethan@creativecrap.com>
 * @copyright Creativecrap.com, 14 April, 2016
 * @package plugin
 */

function itunes_artwork_hook($param) {
	$entity = 'album';
	$country = 'us';
	$search = urlencode($param['album'] . ' ' . $param['artist']);

	$url = 'http://ax.itunes.apple.com/WebObjects/MZStoreServices.woa/wa/wsSearch?term='.$search.'&country='.$country.'&entity='.$entity;

	$ch = curl_init(); 
	curl_setopt($ch, CURLOPT_URL, $url); 
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5); 
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$json = curl_exec($ch); 
	if ($errno = curl_errno($ch)) {
		curl_close($ch);
		return '';
	}
	curl_close($ch);

	$json = json_decode($json);
	// fb($json);
	
	if ($json->resultCount > 0) {
		$item = $json->results[0];
		return $item->artworkUrl100;
	}

	return '';
}
