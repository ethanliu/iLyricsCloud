<?php
/**
 * fetcher for mojim.com
 *
 * @author Ethan Liu
 * @copyright , 25 December, 2011
 * @package plugin
 **/
 
require_once dirname(__FILE__) . '/../class.curl.php';
function mojim_lyrics_hook($param) {
	$curl = new CURL();
	// t2 for album
	//$url = sprintf("http://mojim.com/%s.html?t2", $param['album']);
	// t3 for song
	$url = sprintf("http://mojim.com/%s.html?t3", urlencode($param['title']));
	$html = $curl->get($url);
	if (empty($html)) {
		return '';
	}

	// get url of this song
	$url = '';
	$html = phpQuery::newDocumentHTML($html)->find('table.iB td');
	foreach (pq("a:contains(" . htmlspecialchars($param['title']) . ")") as $item) {
		// only get first result, when it usually be right
		$url = pq($item)->attr('href');
		if (!empty($url)) {
			break;
		}
	}
	
	if (empty($url)) {
		// try cn version
		$url = sprintf("http://mojim.com/%s.html?g3", urlencode($param['title']));
		$html = $curl->get($url);
		if (empty($html)) {
			return '';
		}
		
		// get url of this song
		$url = '';
		$html = phpQuery::newDocumentHTML($html)->find('table.iB td');
		foreach (pq("a:contains(" . htmlspecialchars($param['title']) . ")") as $item) {
			// only get first result, when it usually be right
			$url = pq($item)->attr('href');
			if (!empty($url)) {
				break;
			}
		}

		if (empty($url)) {
			return '';
		}
	}
	
	$url = sprintf("http://mojim.com%s", $url);
	$html = $curl->get($url);
	if (empty($html)) {
		return '';
	}
	
	$doc = phpQuery::newDocumentHTML($html)->find('dl:last');
	//$html = $doc->find('a[title="歌詞'.$param['title'].'"]')->parent()->next('dd');
	//$html = pq('a[title="歌詞'.$param['title'].'"]')->parent()->next('dd')->html();
	//$html = $doc->find("a:contains(" . htmlspecialchars($param['title']) . ")")->parent()->next('dd');
	$html = pq("a:contains(" . htmlspecialchars($param['title']) . ")")->parent()->next('dd')->html();
	$doc = phpQuery::newDocumentHTML($html);
	//pq('a')->remove();
	pq('script')->remove();
	//pq('hr')->remove();
	$html = $doc->html();
	unset($doc);

	$html = strip_tags($html, '<br>');
	return $html;
}

