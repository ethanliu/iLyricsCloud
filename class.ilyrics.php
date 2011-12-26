<?php
/**
 * iLyrics Fetcher Class
 *
 * @author Ethan Liu
 * @copyright , 25 December, 2011
 * @package class
 **/

//error_reporting(E_ALL&~E_NOTICE&~E_WARNING);
error_reporting(E_ALL);
ini_set("display_errors", 1);

ini_set('magic_quotes_runtime', 0);
ini_set('magic_quotes_gpc', 0);
ini_set('magic_quotes_sybase', 0);
ini_set('url_rewriter.tags', '');
ini_set('auto_detect_line_endings','1');

set_time_limit(180);
mb_internal_encoding("UTF-8"); 

require_once 'phpQuery.php';

class LyricsFetcher {
	public $title = '';
	public $artist = '';
	public $album = '';
	public $lyricsId = 0;
	public $lyrics = '';
	public $lyricsSource = '';
	private $plugins = array(
		'en' => 'lyricswiki:lyrics',
		'ch' => 'mojim:lyrics',
		'jp' => 'jpopasia:lyrics|utamap:lyrics',
		'artwork' => 'google:artwork|kkbox:artwork',
	);
	private $database = 'cache/cache.db';
	private $db;
	private $_stripped = FALSE;
	private $_pages = 1;
	private $_limited = 10;
	private $_data = array();

	public function __construct($data = array()) {
		if (!empty($this->database) && !file_exists($this->database)) {
			$this->db = new PDO('sqlite:' . $this->database);
			if ($this->db) {
				// create lyrics table
				$query = 'CREATE TABLE lyrics (
					id INTEGER PRIMARY KEY AUTOINCREMENT,
					created INTEGER NOT NULL DEFAULT 0,
					"lang" VARCHAR,
					"title" VARCHAR,
					"artist" VARCHAR,
					"album" VARCHAR,
					"lyrics" TEXT);';
				$stmt = $this->db_prepare($query);
				$stmt->execute();

				// create artowrks table
				$query = 'CREATE TABLE artworks (
					id INTEGER PRIMARY KEY AUTOINCREMENT,
					"artist" VARCHAR,
					"album" VARCHAR,
					"url" TEXT);';
				$stmt = $this->db_prepare($query);
				$stmt->execute();
			}
			else {
				die("Unable to create database");
			}
		}
		if (!empty($this->database) && !$this->db) {
			$this->db = new PDO('sqlite:' . $this->database);
		}
	}
	
	// class interface
	
	public function output($error = '') {
		
		$data = array();
		if (empty($this->_data)) {
			if (strlen($this->title.$this->artist.$this->album) > 0) {
				$data = array(array(
					'title' => $this->title,
					'artist' => $this->artist,
					'album' => $this->album,
					'lyrics' => $this->lyrics
				));
			}
		}
		else {
			$data = $this->_data;
		}

		$result = array(
			'error' => $error,
			'source' => $this->lyricsSource,
			'pages' => $this->_pages,
			'result' => $data
		);

		/*
		$result = array(
			'error' => $error,
			'source' => $this->lyricsSource,
			'title' => $this->title,
			'artist' => $this->artist,
			'album' => $this->album,
			'lyrics' => $this->lyrics
		);
		*/
		
		header("Content-type: application/json");
		print json_encode($result);
		exit;
	}
	
	public function artwork() {
		$this->stripStrings();
		$url = $this->getArtwork();
		if (empty($url)) {
			$plugins = explode('|', $this->plugins['artwork']);
			foreach ($plugins as $plugin) {
				$url = $this->executePlugin($plugin);
				if (!empty($url)) {
					$this->setArtwork($url);
					break;
				}
			}
			//$url = $this->executePlugin($this->plugins['artwork']);
			//$this->setArtwork($url);
		}
		return $url;
	}
	
	public function lyrics($source) {
		$this->lyricsSource = $source;
		$this->stripStrings();
		
		if (empty($this->title) || strlen($this->title.$this->artist.$this->album) <= 0) {
			return;
		}

		if (!empty($this->plugins[$source])) {
			$this->lyrics = $this->getLyrics();
			if (empty($this->lyrics)) {
				$plugins = explode('|', $this->plugins[$source]);
				foreach ($plugins as $plugin) {
					$this->lyrics = $this->executePlugin($plugin);
					if (!empty($this->lyrics)) {
						$this->parsing();
						$this->setLyrics();
						break;
					}
				}
				//$this->lyrics = $this->executePlugin($this->plugins[$source]);
				//if (!empty($this->lyrics)) {
				//	$this->parsing();
				//	$this->setLyrics();
				//}
			}
			else {
				$this->parsing();
			}
			//$this->output();
			return $this->lyrics;
		}
	}
	
	// common methods
	
	public function search() {
		if (!$this->db) {
			return '';
		}
		
		$this->stripStrings();
		$result = array();
		
		$query = '';
		$query .= !empty($this->title) ? ' AND title LIKE :title' : '';
		$query .= !empty($this->artist) ? ' AND artist LIKE :artist' : '';
		$query .= !empty($this->album) ? ' AND album LIKE :album' : '';
		
		// atleast one condition
		if (empty($query)) {
			return $result;
		}
		
		$sql = "SELECT COUNT(*) AS total FROM lyrics AS l WHERE 1" . $query;
		$stmt = $this->db_prepare($sql);
		if (!empty($this->title)) {
			$stmt->bindParam(":title", $this->title);
		}
		if (!empty($this->artist)) {
			$stmt->bindParam(":artist", $this->artist);
		}
		if (!empty($this->album)) {
			$stmt->bindParam(":album", $this->album);
		}
		$total = $this->db_getOne($stmt);
		
		$page = empty($_REQUEST['page']) ? 1 : intval($_REQUEST['page']);
		$offset = abs($page - 1) * $this->_limited;
		$pages = ceil($total / $this->_limited);
		
		//$sql = "SELECT id, lang, title, artist, album FROM lyrics WHERE 1" . $query . " LIMIT {$offset}, {$this->_limited}";
		$sql = "SELECT id, lang, title, artist, album
				, (SELECT url FROM artworks WHERE 1 " . $query . " ORDER BY id DESC LIMIT 1) AS url
				FROM lyrics AS l WHERE 1" . $query;
		$stmt = $this->db_prepare($sql);
		if (!empty($this->title)) {
			$stmt->bindParam(":title", $this->title);
		}
		if (!empty($this->artist)) {
			$stmt->bindParam(":artist", $this->artist);
		}
		if (!empty($this->album)) {
			$stmt->bindParam(":album", $this->album);
		}
		$result = $this->db_getAll($stmt);
		//var_dump($sql);
		//var_dump($result);
		
		$this->_pages = $pages;
		$this->_data = $result;
		
		return $result;
	}
	
	private function getArtwork() {
		if (!$this->db || empty($this->album)) {
			return '';
		}
		
		$query = " AND album LIKE :album";
		$query .= !empty($this->artist) ? " AND artist LIKE :artist" : '';
		$sql = "SELECT url FROM artworks WHERE 1" . $query . " ORDER BY RANDOM() LIMIT 1";
		$stmt = $this->db_prepare($sql);
		$stmt->bindParam(":album", $this->album);
		if (!empty($this->artist)) {
			$stmt->bindParam(":artist", $this->artist);
		}
		$result = $this->db_getOne($stmt);
		return $result;
	}

	private function setArtwork($url = '') {
		if (!$this->db || empty($url) || empty($this->album)) {
			return;
		}
		$query = "INSERT INTO artworks (artist, album, url) VALUES (:artist, :album, :url)";
		$stmt = $this->db_prepare($query);
		$stmt->bindParam(":artist", $this->artist);
		$stmt->bindParam(":album", $this->album);
		$stmt->bindParam(":url", $url);
		$this->db_execute($stmt);
	}

	private function getLyrics() {
		if (!$this->db) {
			return '';
		}
		
		$result = array();
		if ($this->lyricsId) {
			$query = "SELECT lyrics FROM lyrics WHERE id = :id ORDER BY created DESC LIMIT 1";
			$stmt = $this->db_prepare($query);
			$stmt->bindParam(":id", $this->lyricsId);
			$result = $this->db_getRow($stmt);
		}
		else {
			
			$query = '';
			//$query .= ' AND lang LIKE :lang';
			$query .= !empty($this->title) ? ' AND title LIKE :title' : '';
			$query .= !empty($this->artist) ? ' AND artist LIKE :artist' : '';
			$query .= !empty($this->album) ? ' AND album LIKE :album' : '';
		
			$sql = "SELECT lang, title, artist, album, lyrics FROM lyrics WHERE 1 " . $query . " ORDER BY created DESC LIMIT 1";
			//var_dump($sql);
			$stmt = $this->db_prepare($sql);
			//$stmt->bindParam(":lang", $this->lyricsSource);
			
			if (!empty($this->title)) {
				$stmt->bindParam(":title", $this->title);
			}
			if (!empty($this->artist)) {
				$stmt->bindParam(":artist", $this->artist);
			}
			if (!empty($this->album)) {
				$stmt->bindParam(":album", $this->album);
			}
			$result = $this->db_getRow($stmt);
			
			if (!empty($result['lyrics'])) {
				if (empty($this->title)) {
					$this->title = $result['title'];
				}
				if (empty($this->artist)) {
					$this->artist = $result['artist'];
				}
				if (empty($this->album)) {
					$this->album = $result['album'];
				}
			}
		}
		
		return $result['lyrics'];
	}
	
	private function setLyrics() {
		if (!$this->db) {
			return '';
		}
		$query = "INSERT INTO lyrics (created, lang, title, artist, album, lyrics) VALUES (:created, :lang, :title, :artist, :album, :lyrics)";
		$stmt = $this->db_prepare($query);
		$stmt->bindParam(":created", time());
		$stmt->bindParam(":lang", $this->lyricsSource);
		$stmt->bindParam(":title", $this->title);
		$stmt->bindParam(":artist", $this->artist);
		$stmt->bindParam(":album", $this->album);
		$stmt->bindParam(":lyrics", $this->lyrics);
		$this->db_execute($stmt);
	}

	private function executePlugin($namespace) {
		$namespace = explode(':', $namespace);
		$plugin = $namespace[0];
		$hook = "_{$namespace[1]}_hook";
		$path = dirname(__FILE__) . "/plugins/{$plugin}.php";
		$result = '';
		if (file_exists($path)) {
			require_once $path;
			if (function_exists($plugin . $hook)) {
				$param = array(
					'title' => $this->title,
					'artist' => $this->artist,
					'album' => $this->album);
				$result = call_user_func($plugin . $hook, $param);
			}
		}
		return $result;
	}
	
	private function stripStrings() {
		if (!$this->_stripped) {
			$this->title = $this->removeFeatureString($this->title);
			$this->album = $this->removeFeatureString($this->album);
			$this->artist = $this->removeFeatureString($this->artist);
		}
		$this->_stripped = TRUE;
	}
	
	private function parsing() {
		$lyrics = $this->lyrics;
		$plaintext = array();
	
		// first check, remove <br>\n
		$pattern = "#<br\s*/?>\n#i";
		preg_match($pattern, $lyrics, $matches);
		if (!empty($matches)) {
			$lyrics = preg_replace("#<br\s*/?>\n#i", "\n", $lyrics);
		}

		// second check, if there is still <br>
		$pattern = "#<br\s*/?>#i";
		preg_match($pattern, $lyrics, $matches);
		if (!empty($matches)) {
			$lyrics = preg_replace("#<br\s*/?>#i", "\n", $lyrics);
		}
	
		// uniform line-break
		$lyrics = preg_replace('#(\\r\\n|\\r|\\n)#', "\n", $lyrics);
		$rows = explode("\n", $lyrics);
		$loop = count($rows);
	
		// parsing, remove spam text
		for ($i=0; $i < $loop; $i++) { 
			$row = trim($rows[$i]);
			$pattern = '/(document\.write|mojim|\.com|google|script|動態歌詞|友站連結|http:\/\/)/i';
			preg_match($pattern, $row, $matches);
			if (empty($matches)) {
				$plaintext[] = $row;
			}
		}
	
		$lyrics = implode("\n", $plaintext);
		$this->lyrics = trim($lyrics);
	}
	
	private function removeFeatureString($str) {
		$patterns = array();
		
		$patterns[] = '/\.mp3|\.wav|\.m4a/i';
		$patterns[] = '/ featuring .*| ft\. .*| f\. .*| feat .*| ft .*| feat\. .*|\/feat .*| f .*| \[feat\. .*\]| \(feat\. .*\)| \[ft. .*\]/i';
		$patterns[] = '/ \(.* version\)| \(.* edit\)/i';
		$patterns[] = '/ (remix)| \(.* remix\)| \(remixed by .*\)| \(acoustic\)| \(.* track\)/i';
		$patterns[] = '/ (live)| \[live\]| - live in .*| \(live in .*\)| \[live in .*\]| - live/i';
		$patterns[] = '/([0-9]{2})\. |([0-9]{2}) \- |([0-9]{2}) /';

		$patterns[] = '/\/{1,}/';
		$patterns[] = '/^\//';
		$patterns[] = '/\/$/';

		$patterns[] = '/\s\s+/'; // remove multiple blank spaces,  always be last check

		$replacements = array();
		$replacements[] = '';
		$replacements[] = '';
		$replacements[] = '';
		$replacements[] = '';
		$replacements[] = '';
		$replacements[] = '';

		$replacements[] = '/';
		$replacements[] = '';
		$replacements[] = '';

		$replacements[] = ' ';

		$str = preg_replace($patterns, $replacements, $str);
		$str = trim($str);
		return $str;
	}
	
	// database interface
	private function db_prepare($sql) {
		if ($this->db) {
			$stmt = $this->db->prepare($sql);
			if (!$stmt) {
				$error = $this->db->errorInfo();
				die($error[2]);
				return FALSE;
			}
			return $stmt;
		}
		return FALSE;
		
	}
	
	private function db_getOne($statement) {
		if ($this->db) {
			if (!$statement->execute()) {
				$error = $this->db->errorInfo();
				die($error[2]);
				return FALSE;
			}
			$result = $statement->fetch();
			return $result[0];
		}
		return FALSE;
	}

	private function db_getRow($statement) {
		if ($this->db) {
			if (!$statement->execute()) {
				$error = $this->db->errorInfo();
				die($error[2]);
				return FALSE;
			}
			$result = $statement->fetch(PDO::FETCH_ASSOC);
			return $result;
		}
		return FALSE;
	}

	private function db_getAll($statement) {
		if ($this->db) {
			if (!$statement->execute()) {
				$error = $this->db->errorInfo();
				die($error[2]);
				return FALSE;
			}
			$result = $statement->fetchAll(PDO::FETCH_ASSOC);
			return $result;
		}
		return FALSE;
	}
	
	private function db_execute($statement) {
		if ($this->db) {
			if (!$statement->execute()) {
				$error = $this->db->errorInfo();
				die($error[2]);
				return FALSE;
			}
			return TRUE;
		}
		return FALSE;
	}
	
}


