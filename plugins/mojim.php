<?php
/**
 * fetcher for mojim.com
 *
 * @author Ethan Liu <ethan@creativecrap.com>
 * @package plugin
 **/

require_once dirname(__FILE__) . '/../classes/curl.php';

function mojim_lyrics_hook($param) {
	$url = '';
	$curl = new CURL();

	if ($param['album'] != '') {
		// t2 for album
		$url = sprintf("http://mojim.com/%s.html?t2", urlencode($param['album']));
		// fb($url);
		$html = $curl->get($url);
		if (empty($html)) {
			return '';
		}

		// .mxsh_sse a:contains($param['artist'])

		$doc = phpQuery::newDocumentHTML($html);
		foreach (pq('.mxsh_ss3 a') as $item) {
			$attrTitle = trim(pq($item)->attr('title'));
			if (strpos($attrTitle, $param['artist']) !== false) {
				$url = pq($item)->attr('href');
				break;
			}
		}

	}
	else {
		// t3 for song
		$url = sprintf("http://mojim.com/%s.html?t3", urlencode($param['title']));
		$html = $curl->get($url);
		if (empty($html)) {
			return '';
		}
		// get url of this song
		$url = '';
		$doc = phpQuery::newDocumentHTML($html)->find("dl");
		if (!$doc) {
			return '';
		}

		// [title*=artist]
		foreach (pq("a") as $item) {
			$title = pq($item)->attr('title');
			if (strpos($title, $param['artist']) !== false) {
				$url = pq($item)->attr('href');
				break;
			}
		}

		if (!empty($url)) {
			$url = sprintf("http://mojim.com%s", $url);
			$html = $curl->get($url);
			$html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
			if (empty($html)) {
				return '';
			}

			$doc = phpQuery::newDocumentHTML($html)->find("td");
			if (!$doc) {
				return '';
			}

			// get url of this song
			foreach (pq("a") as $item) {
				$title = pq($item)->attr('title');
				if (strpos($title, $param['title']) !== false) {
					$url = pq($item)->attr('href');
					break;
				}
			}
		}
	}

	if (empty($url)) {
		// try cn version

		if ($param['album'] != '') {
			$url = sprintf("http://mojim.com/%s.html?g2", urlencode($param['album']));
			$html = $curl->get($url);
			if (empty($html)) {
				return '';
			}

			$doc = phpQuery::newDocumentHTML($html);
			foreach (pq('.mxsh_ss3 a') as $item) {
				$attrTitle = trim(pq($item)->attr('title'));
				if (strpos($attrTitle, $param['artist']) !== false) {
					$url = pq($item)->attr('href');
					break;
				}
			}
		}
		else {
			$url = sprintf("http://mojim.com/%s.html?g3", urlencode($param['title']));
			$html = $curl->get($url);
			if (empty($html)) {
				return '';
			}

			// get url of this song
			foreach (pq("a") as $item) {
				$title = pq($item)->attr('title');
				if (strpos($title, $param['artist']) !== false) {
					$url = pq($item)->attr('href');
					break;
				}
			}
		}

	}

	if (empty($url)) {
		return '';
	}

	$url = sprintf("http://mojim.com%s", $url);
	$html = $curl->get($url);
	if (empty($html)) {
		return '';
	}

	// $doc = phpQuery::newDocumentHTML($html)->find('dl dt a:contains('.$param['title'].')')->parent('dt')->next('dd');
	$doc = phpQuery::newDocumentHTML($html)->find('#fsZx3');
	if (!$doc) {
		return '';
	}

	// fb($doc->html());

	//$html = $doc->find('a[title="歌詞'.$param['title'].'"]')->parent()->next('dd');
	//$html = pq('a[title="歌詞'.$param['title'].'"]')->parent()->next('dd')->html();
	//$html = $doc->find("a:contains(" . htmlspecialchars($param['title']) . ")")->parent()->next('dd');
	// $html = pq("a:contains(" . htmlspecialchars($param['title']) . ")")->parent()->next('dd')->html();
	// $doc = phpQuery::newDocumentHTML($html);
	//pq('a')->remove();
	pq('script')->remove();
	//pq('hr')->remove();
	$html = $doc->html();
	unset($doc);

	$html = strip_tags($html, '<br>');
	return $html;
}

