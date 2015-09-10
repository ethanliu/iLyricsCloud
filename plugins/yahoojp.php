<?php
/**
 * fetcher for http://music.yahoo.co.jp/lyrics/
 *
 * @author Ethan Liu <ethan@creativecrap.com>
 * @package plugin
 **/

function yahoojp_lyrics_hook($param) {
	// n1=[,l,t,a]; empty for all, l for lyrics, t for title, a for artist
	//$url = "http://search.music.yahoo.co.jp/musicsearch?x=48&y=14&cc=ls&n1=&cp=" . $param['title'];

	// r1, sort
	$url = "http://search.music.yahoo.co.jp/musicsearch?cc=ls&r1=-pv&d=11110&cp=" . urlencode($param['title'] . ' ' . $param['artist']);

	$html = @file_get_contents($url);
	if (empty($html)) {
		return '';
	}

	$doc = phpQuery::newDocument($html);

	if (empty($doc)) {
		return '';
	}

	foreach (pq('.kashi02 td.lft dt a') as $key => $item) {
		if (pq($item)->text() == $param['title']) {
			break;
		}
	}
	$url = pq($item)->attr('href');
	if (empty($url)) {
		return '';
	}

	$uri = explode('/', $url);
	$url = sprintf("http://music.yahooapis.jp/YjmDirService/V2/lyricsSearch?appid=Eto7LbGxg64FmiFnpkRBChVClad1MY4zoG4mjrH9FZ2wsItBLYywP3zQRBRL4ECX2Eo-&aid=%s&lyid=%s&d=11010&results=1", $uri[6], $uri[5]);
	$xml = simplexml_load_string(file_get_contents($url));

	return $xml->Result->AllLyrics;
}