<?php
/**
 * fetcher for kkbox
 *
 * @author Ethan Liu <ethan@creativecrap.com>
 * @package plugin
 **/

function kkbox_artwork_hook($param) {
	$query = str_replace(' ', '+', ($param['title'] . ' ' . $param['artist']));
	$url = 'http://tw.kkbox.com/search.php?&search=song&search_lang=&word=' . $query;

	$html = @file_get_contents($url);
	$url = phpQuery::newDocument($html)->find('div.song-list > table tbody tr:first td:nth-child(3) a')->attr('href');
	if (empty($url)) {
		return '';
	}

	$url = 'http://tw.kkbox.com' . $url;
	$html = @file_get_contents($url);
	$url = phpQuery::newDocument($html)->find('.album-pic')->attr('src');

	return $url;
}

function kkbox_lyrics_hook($param) {
	$query = urlencode($param['title'] . ' ' . $param['artist']);
	$url = 'http://tw.kkbox.com/search.php?&search=lyrics&word=' . $query;

	$html = file_get_contents($url);
	$doc = phpQuery::newDocument($html);
	// fb($html);

	foreach (pq('.lyrics li') as $item) {
		$text = strtolower(pq($item)->find('h4')->text());
		if (strpos($text, strtolower($param['title'])) !== false && strpos($text, strtolower($param['artist'])) !== false) {
			$doc = pq($item)->find('.full-lyrics');
			pq('a')->remove();
			$html = strip_tags($doc->html(), '<br>');

			return $html;
		}
	}
	return '';
}