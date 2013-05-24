<?php
/**
 * fetcher for metrolyrics.com
 *
 * @author Ethan Liu <ethan@creativecrap.com>
 * @package plugin
 **/

function metrolyrics_lyrics_hook($param) {
	$patterns = array('/ {1,}/', '/[^\w-]/', '/-{1,}/');
	$replacements = array('-', '', '-');
	$artist = preg_replace($patterns, $replacements, strtolower($param['artist']));

	$patterns = array('/ /', '/[^\w-]/', '/-{2,}/');
	$replacements = array('-', '', '-');
	$title = preg_replace($patterns, $replacements, strtolower($param['title']));

	if (strlen($title . $artist) <= 2) {
		return '';
	}

	$url = sprintf("http://www.metrolyrics.com/%s-lyrics-%s.html", $title, $artist);
	//echo $url . '<hr>';exit;
	$html = file_get_contents($url);
	if (empty($html)) {
		return '';
	}

	$doc = phpQuery::newDocumentHTML($html)->find('div#lyrics-body');
	pq('span')->after('<br>');
	$html = strip_tags($doc->html(), '<br>');

	if (strpos($html, "Unfortunately, we don't have the lyrics") !== false) {
		return '';
	}


	return $html;
}
