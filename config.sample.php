<?php
/**
 * Glboal configuration
 *
 * @author Ethan Liu <ethan@creativecrap.com>
 **/

require_once dirname(__FILE__) . '/libs/phpQuery.php';

//error_reporting(E_ALL&~E_NOTICE&~E_WARNING);
error_reporting(E_ALL);
ini_set("display_errors", 1);

ini_set('magic_quotes_runtime', 0);
ini_set('magic_quotes_gpc', 0);
ini_set('magic_quotes_sybase', 0);
ini_set('url_rewriter.tags', '');
ini_set('auto_detect_line_endings','1');

set_time_limit(180);
@mb_internal_encoding("UTF-8");

define('ADMIN_USER', 'admin');
define('ADMIN_PASS', 'admin');

define('INSTALLED', true);

//define('DATABASE_DSN', 'sqlite:' . dirname(__FILE__) . '/cache/cache.db');
define('DATABASE_DSN', 'mysql:host=127.0.0.1;port=3306;dbname=cloudapp;user=root;password=root;');

$plugins = array();
$plugins['en'] = array(
	'metrolyrics:lyrics',
	'lyricscom:lyrics',
	'lyricswiki:lyrics',
);
$plugins['zh'] = array(
	'mojim:lyrics',
);
$plugins['jp'] = array(
	'yahoojp:lyrics',
	'jpopasia:lyrics',
	'utamap:lyrics',
);
$plugins['artwork'] = array(
	'google:artwork',
	'kkbox:artwork',
);