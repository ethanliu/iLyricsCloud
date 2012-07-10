<?php
/**
 * fetcher for lyricswiki from wikia.com
 *
 * @author Ethan Liu
 * @copyright , 25 December, 2011
 * @package plugin
 **/

function lyricswiki_lyrics_hook($param) {
	//http://lyrics.wikia.com/index.php?action=edit

	//http://lyrics.wikia.com/api.php?artist=Cake&song=Dime&fmt=xml
	//fmt: text,xml,html,fixXML,json

	$url = sprintf("http://lyrics.wikia.com/api.php?artist=%s&song=%s&fmt=xml", urlencode($param['artist']), urlencode($param['title']));
	$xml = simplexml_load_string(file_get_contents($url));
	//var_dump($xml);
	if ($xml->lyrics == 'Not found') {
		return '';
	}

	$html = file_get_contents($xml->url . '?action=edit');
	$html = phpQuery::newDocumentHTML($html)->find('textarea')->html();
	$html = str_replace('&lt;', '<', $html);
	$html = str_replace('&gt;', '>', $html);
	$html = phpQuery::newDocumentHTML($html)->find('lyrics')->html();

	//$html = str_replace('{{gracenote_takedown}}', '', $html);
	if (strpos('{{gracenote_takedown}}', $html) !== false) {
		return '';
	}

	//$html = str_replace('{{gracenote_takedown}}', '', $html);
	return $html;

	/*
	// since lyrics from api is lite version, we need to fetch from url again
	$html = file_get_contents($xml->url);
	$doc = phpQuery::newDocument($html)->find('.lyricbox');
	unset($xml);
	unset($html);
	if (empty($doc)) {
		return '';
	}

	// remove 2 divs
	pq('div')->remove();
	// remove comment block
	$html = strip_tags($doc->html(), '<br>');
	unset($doc);

	$html = str_replace("&amp;", "&", $html);
	$html = str_replace("&quot;", '"', $html);
	*/
	//return $html;
}