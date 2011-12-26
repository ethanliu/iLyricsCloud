<?php
/**
 * Fetcher for jpopasia.com
 *
 * @author Ethan Liu
 * @version $Id$
 * @copyright , 26 December, 2011
 * @package plugin
 **/

function jpopasia_lyrics_hook($param) {
	$artist = $param['artist'];
	$reserved = preg_quote('\/:*?"<>|', '/');
	$replacement = '';
	$artist = preg_replace("/([\\x00-\\x20\\x7f-\\xff{$reserved}])/", $replacement, $artist);
	$url = "http://www.jpopasia.com/group/{$artist}/discography/";
	
	$html = file_get_contents($url);
	if (empty($html)) {
		return '';
	}

	$html = phpQuery::newDocumentHTML($html)->find(".tracklist > ul");
	foreach (pq("a:contains(" . htmlspecialchars($param['title']) . ")") as $item) {
		$url = trim(pq($item)->attr('href'));
		if (!empty($url)) {
			break;
		}
	}

	$html = file_get_contents($url);
	if (empty($html)) {
		return '';
	}

	$html = phpQuery::newDocumentHTML($html);
	$result['romanzied'] = pq('li#lyricR-1')->html();
	$result['hangul'] = pq('li#lyricK-1')->html();
	$result['translation'] = pq('li#lyricT-1')->html();
	unset($html);

	// remove author
	$result['romanzied'] = preg_replace("#<div.*>.*</div>#i", "", $result['romanzied']);
	$result['hangul'] = preg_replace("#<div.*>.*</div>#i", "", $result['hangul']);
	$result['translation'] = preg_replace("#<div.*>.*</div>#i", "", $result['translation']);
	
	$lyrics = $result['hangul'];
	$lyrics .= !empty($result['romanzied']) ? "\n<br><br>\nRomanized<br><br>\n" . $result['romanzied'] : '';
	$lyrics .= !empty($result['translation']) ? "\n<br><br>\nTranslation<br><br>\n" . $result['translation'] : '';
	
	return $lyrics;
}

