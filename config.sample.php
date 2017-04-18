<?php
/**
 * Configuration
 *
 * @author Ethan Liu <ethan@creativecrap.com>
 **/

require_once dirname(__FILE__) . '/libs/phpQuery.php';

//error_reporting(E_ALL&~E_NOTICE&~E_WARNING);
// error_reporting(E_ALL);
ini_set("display_errors", 0);

set_time_limit(180);
@mb_internal_encoding("UTF-8");

define('GA_ACCOUNT', 'UA-402625-5');

define('ADMIN_USER', 'admin');
define('ADMIN_PASS', 'admin');

define('DATABASE_DSN', 'sqlite:' . dirname(__FILE__) . '/cache/cache.db');
// define('DATABASE_DSN', 'mysql:host=127.0.0.1;port=3306;dbname=;user=;password=;');

// OpenShift
// $host = getenv('OPENSHIFT_MYSQL_DB_HOST');
// $port = getenv('OPENSHIFT_MYSQL_DB_PORT');
// $dbname = getenv('OPENSHIFT_APP_NAME');
// $user = getenv('OPENSHIFT_MYSQL_DB_USERNAME');
// $passwd = getenv('OPENSHIFT_MYSQL_DB_PASSWORD');
// define('DATABASE_DSN', "mysql:host={$host};port={$port};dbname={$dbname};user={$user};password={$passwd};");

$plugins = array();
$plugins['lyrics'] = array(
	'metrolyrics',
	'lyricscom',
	'lyricswiki',
	'mojim',
	'kkbox',
	// 'yahoojp',
	'utamap',
	'naver',
	// 'jpopasia',
);
$plugins['artworks'] = array(
	// 'google',
	'itunes',
	'kkbox',
);
