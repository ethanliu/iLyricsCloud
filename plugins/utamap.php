<?php
/**
 * fetcher for utamap.com
 *
 * @author Ethan Liu
 * @copyright , 25 December, 2011
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
	$doc = phpQuery::newDocument($html)->find('table table');

	if (empty($doc)) {
		return '';
	}
	foreach (pq('td') as $key => $item) {
		if (pq($item)->text() == $param['artist']) {
			break;
		}
		$td = $item;
	}
	$doc = explode('=', pq($td)->find('a')->attr('href'));
	if (empty($doc)||empty($doc[1])) {
		return '';
	}

	$url = "http://www.utamap.com/phpflash/flashfalsephp.php?unum=" . $doc[1];
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