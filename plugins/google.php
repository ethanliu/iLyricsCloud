<?php
/**
 * Fetcher for goole.com
 *
 * @author Ethan Liu <ethan@creativecrap.com>
 * @package plugin
 **/

function google_artwork_hook($param) {
	$image_size = "large"; //can be 'icon' 'small' 'medium' 'large'
	$limit_mode = "e"; // i for include as_sitesearch, e for exclude
	$limit_to_domain = "imageshack.us"; //"amazon.com"; // 'amazon.com' or 'artistdirect.com'

	$query = urlencode($param['album']) . '%20' . urlencode($param['artist']);
	$url = "http://images.google.com/images?ie=utf-8&hl=en&btnG=Google+Search";
	$url .= "&imgsz={$image_size}&as_dt={$limit_mode}&as_sitesearch={$limit_to_domain}&q=" . $query;

	$html = file_get_contents($url);
	if (empty($html)) {
		return '';
	}

	$url = phpQuery::newDocument($html)->find('table.images_table a:first')->attr('href');
	$url = 'http://images.google.com' . $url; // openshift version parse_url need valid full url
	parse_str(parse_url($url, PHP_URL_QUERY), $args);
	if (empty($args['imgurl'])) {
		return '';
	}

	if (strpos($args['imgurl'], $limit_to_domain) !== false) {
		return '';
	}

	return $args['imgurl'];
}