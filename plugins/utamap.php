<?php
/**
 * fetcher for utamap.com
 *
 * @author Ethan Liu <ethan@creativecrap.com>
 * @package plugin
 **/

function utamap_lyrics_hook($param) {
	$url = "http://www.utamap.com/searchkasi.php?searchname=title&sortname=1&pattern=1&act=search&word=" . $param['title'];

	$html = @file_get_contents($url);
	if (empty($html)) {
		return '';
	}

	$html = @mb_convert_encoding($html, "UTF-8", "SJIS");
	$html = str_replace("charset=Shift_JIS", "charset=utf8", $html);
	$doc = phpQuery::newDocument($html)->find('table');

	if (empty($doc)) {
		return '';
	}

	$url = '';
	foreach (pq('td') as $item) {
		$text = trim(pq($item)->text());
		if (strtolower($text) == strtolower($param['artist'])) {
			$url = trim(pq($item)->prev('td')->find('a')->attr('href'));
			break;
		}
	}

	if (empty($url)) {
		return '';
	}
	
	// $url = "./showkasi.php?surl=B30135";
	
	$query = explode('=', parse_url($url, PHP_URL_QUERY));
	$url = "http://www.utamap.com/phpflash/flashfalsephp.php?unum=" . $query[1];

	$html = @file_get_contents($url);
	if (empty($html)) {
		return '';
	}
	$doc = phpQuery::newDocument($html)->html();
	$doc = explode('=', $doc);
	$doc = $doc[count($doc)-1];
	$doc = trim($doc);
	if (empty($doc)) {
		return '';
	}
	return $doc;
}