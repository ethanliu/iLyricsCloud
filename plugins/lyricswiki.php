<?php
/**
 * fetcher for lyricswiki from wikia.com
 *
 * @author Ethan Liu <ethan@creativecrap.com>
 * @package plugin
 **/

function lyricswiki_lyrics_hook($param) {

	$artist = str_replace(' ', '_', $param['artist']);
	$title = str_replace(' ', '_', $param['title']);

	$url = sprintf("http://lyrics.wikia.com/wiki/%s:%s", $artist, $title);
	$html = file_get_contents($url);
	if (empty($html)) {
		return '';
	}

	$doc = phpQuery::newDocumentHTML($html)->find('.lyricbox');
	$html = strip_tags($doc->html(), '<br>');

	return $html;

	// 	api is out of service

	//http://lyrics.wikia.com/index.php?action=edit
	//http://lyrics.wikia.com/api.php?artist=Cake&song=Dime&fmt=xml
	//fmt: text,xml,html,fixXML,json
	
	// $url = sprintf("http://lyrics.wikia.com/api.php?artist=%s&song=%s&fmt=xml", urlencode($param['artist']), urlencode($param['title']));
	// $xml = simplexml_load_string(file_get_contents($url));
	// //var_dump($xml);
	// if ($xml->lyrics == 'Not found') {
	// 	return '';
	// }
	//
	// $html = file_get_contents($xml->url . '?action=edit');
	// $html = phpQuery::newDocumentHTML($html)->find('textarea')->html();
	// $html = str_replace('&lt;', '<', $html);
	// $html = str_replace('&gt;', '>', $html);
	// $html = phpQuery::newDocumentHTML($html)->find('lyrics')->html();
	//
	// //$html = str_replace('{{gracenote_takedown}}', '', $html);
	// if (strpos($html, '{{gracenote_takedown}}') !== false) {
	// 	return '';
	// }
	//
	// //$html = str_replace('{{gracenote_takedown}}', '', $html);
	// return $html;
}