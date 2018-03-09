<?php
/**
 * iLyrics Fetcher Class
 *
 * @author Ethan Liu <ethan@creativecrap.com>
**/

require_once dirname(__FILE__) . "/controller.php";

class LyricsFetcher extends Controller {
	public $title = '';
	public $artist = '';
	public $album = '';
	public $lyricsId = 0;
	public $lyrics = '';
	public $lyricsSource = '';
	// public $cache = TRUE;

	private $plugins = array();
	private $_stripped = FALSE;
	private $_pages = 1;
	private $_limited = 10;
	private $_data = null;

	public function __construct($plugins) {
		parent::__construct();

		$this->plugins = $plugins;
		$this->db_connect();
	}

	// class interface

	public function output($error = '') {
		$data = array();
		//if (empty($this->_data)) {
		if (!is_array($this->_data)) {
			if (strlen($this->title.$this->artist.$this->album) > 0 || !empty($this->lyricsId)) {
				$data = array(array(
					'title' => $this->title,
					'artist' => $this->artist,
					'album' => $this->album,
					'lyrics' => $this->lyrics,
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

		//header("Content-type: application/json");
		print json_encode($result);
		exit;
	}

	public function artwork($source) {
		$this->stripStrings();

		if ($this->album == '' || $this->artist == '') {
			return '';
		}

		$url = $this->getArtwork();
		if (empty($url)) {
			// foreach ($this->plugins['artwork'] as $plugin) {
			// 	$url = $this->executePlugin('artwork', $plugin);
			// 	if (!empty($url)) {
			// 		$this->setArtwork($url);
			// 		break;
			// 	}
			// }
			$url = $this->executePlugin('artwork', $source);
			if (!empty($url)) {
				$this->setArtwork($url);
			}
		}
		return $url;
	}

	public function lyrics($source) {
		$this->lyricsSource = $source;
		$this->stripStrings();

		if ((empty($this->title) || strlen($this->artist . $this->album) <= 0) && empty($this->lyricsId)) {
			return '';
		}

		if (in_array($source, $this->plugins['lyrics'])) {
			// $this->lyrics = $this->getLyrics($source);
			$this->lyrics = $this->getLyrics();

			if (empty($this->lyrics) && empty($this->lyricsId)) {
				// foreach ($this->plugins['lyrics'][$source] as $plugin) {
				// 	$this->lyrics = $this->executePlugin('lyrics', $plugin);
				// 	if (!empty($this->lyrics)) {
				// 		$this->parsing();
				// 		$this->saveLyrics($this->lyrics);
				// 		break;
				// 	}
				// }
				$this->lyrics = $this->executePlugin('lyrics', $source);
				if (!empty($this->lyrics)) {
					$this->parsing();
					$this->saveLyrics($this->lyrics);
				}
			}
			else {
				$this->parsing();
			}
			//$this->output();
			return $this->lyrics;
		}
		else {
			// fb("Unknown source: {$source}");
		}

		return '';
	}

	public function search() {
		if (!$this->db) {
			return array();
		}

		$this->stripStrings();
		$result = array();

		$query = '';
		$query .= !empty($this->title) ? ' AND UPPER(l.title) LIKE UPPER(:title)' : '';
		$query .= !empty($this->artist) ? ' AND UPPER(l.artist) LIKE UPPER(:artist)' : '';
		$query .= !empty($this->album) ? ' AND UPPER(l.album) LIKE UPPER(:album)' : '';

		// atleast one condition
		if (empty($query)) {
			return $result;
		}
		$sql = "SELECT COUNT(*) AS total FROM lyrics AS l WHERE (1=1) " . $query;
		$stmt = $this->db_prepare($sql);

		if (!empty($this->title)) {
			$value = "%" . $this->title . "%";
			$stmt->bindValue(":title", $value, PDO::PARAM_STR);
		}
		if (!empty($this->artist)) {
			$value = "%" . $this->artist . "%";
			$stmt->bindValue("artist", $value, PDO::PARAM_STR);
		}
		if (!empty($this->album)) {
			$value = "%" . $this->album . "%";
			$stmt->bindValue("album", $value, PDO::PARAM_STR);
		}

		$total = $this->db_getOne($stmt);

		if (empty($total)) {
			$this->_pages = 0;
			$this->_data = $result;
			return $result;
		}

		$page = empty($_REQUEST['page']) ? 1 : intval($_REQUEST['page']);
		$offset = abs($page - 1) * $this->_limited;
		$pages = ceil($total / $this->_limited);


		$sql = "SELECT l.id, l.lang, l.title, l.artist, l.album, l.lyrics,
					(SELECT url FROM artworks AS a WHERE UPPER(a.artist) = UPPER(l.artist) AND UPPER(a.album) = UPPER(l.album) ORDER BY a.created DESC LIMIT 1) AS url
				FROM lyrics AS l
				WHERE (1=1) " . $query . " LIMIT {$this->_limited} OFFSET {$offset}";
		/*
		if (!empty($this->album) && !empty($this->artist)) {

			$query2 = '';
			$query2 .= !empty($this->artist) ? ' AND UPPER(artist) LIKE UPPER(:artist)' : '';
			$query2 .= !empty($this->album) ? ' AND UPPER(album) LIKE UPPER(:album)' : '';

			$sql = "SELECT id, lang, title, artist, album, lyrics,
					(SELECT url FROM artworks WHERE (1=1) " . $query2 . " ORDER BY id DESC LIMIT 1) AS url
					FROM lyrics AS l WHERE (1=1) " . $query . " LIMIT {$this->_limited} OFFSET {$offset}";
		}
		else {
			$sql = "SELECT id, lang, title, artist, album, lyrics FROM lyrics WHERE (1=1) " . $query . " LIMIT {$this->_limited} OFFSET {$offset}";
		}
		*/

		$stmt = $this->db_prepare($sql);
		if (!empty($this->title)) {
			$value = "%" . $this->title . "%";
			$stmt->bindValue(":title", $value);
		}
		if (!empty($this->artist)) {
			$value = "%" . $this->artist . "%";
			$stmt->bindValue(":artist", $value);
		}
		if (!empty($this->album)) {
			$value = "%" . $this->album . "%";
			$stmt->bindValue(":album", $value);
		}
		$result = $this->db_getAll($stmt);

		for ($i=0, $loop = count($result); $i < $loop; $i++) {
			$result[$i]['lyrics'] = str_replace("\n", " ", $result[$i]['lyrics']);
			$result[$i]['lyrics'] = substr($result[$i]['lyrics'], 0, 80) . '...';
		}

		//var_dump($sql);
		//var_dump($result);

		$this->_pages = $pages;
		$this->_data = $result;

		return $result;
	}

	// common methods

	private function getArtwork() {
		if (!$this->db || empty($this->album)) {
			return '';
		}

		$query = " AND UPPER(album) LIKE UPPER(:album)";
		$query .= !empty($this->artist) ? " AND UPPER(artist) LIKE UPPER(:artist)" : '';
		$random = ($this->provider == 'mysql') ? 'RAND()' : 'RANDOM()';

		$sql = "SELECT url FROM artworks WHERE (1=1) " . $query . " ORDER BY {$random} LIMIT 1";
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
		$query = "INSERT INTO artworks (created, artist, album, url) VALUES (:created, :artist, :album, :url)";
		$stmt = $this->db_prepare($query);
		$stmt->bindParam(":created", time());
		$stmt->bindParam(":artist", $this->artist);
		$stmt->bindParam(":album", $this->album);
		$stmt->bindParam(":url", $url);
		$this->db_execute($stmt);
	}

	private function getLyrics($source = '') {
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
			// if (!$this->cache) {
			// 	return '';
			// }
			$query = '';
			$query .= !empty($source) ? ' AND UPPER(lang) LIKE UPPER(:lang)' : '';
			$query .= !empty($this->title) ? ' AND UPPER(title) LIKE UPPER(:title)' : '';
			$query .= !empty($this->artist) ? ' AND UPPER(artist) LIKE UPPER(:artist)' : '';
			$query .= !empty($this->album) ? ' AND UPPER(album) LIKE UPPER(:album)' : '';

			$sql = "SELECT lang, title, artist, album, lyrics FROM lyrics WHERE (1=1) " . $query . " ORDER BY created DESC LIMIT 1";
			$stmt = $this->db_prepare($sql);
			// fb($sql);
			// fb($stmt);

			// $stmt->bindParam(":lang", $this->lyricsSource);
			if (!empty($source)) {
				$stmt->bindParam(":lang", $source);
			}
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

	// for migration
	public function saveLyrics($lyrics) {
		if (empty($lyrics)) {
			$this->lyrics = '';
			return;
		}

		$this->stripStrings();
		//$txt = $this->getLyrics();
		$this->title = stripslashes($this->title);
		$this->artist = stripslashes($this->artist);
		$this->album = stripslashes($this->album);

		if (empty($this->title) || empty($this->artist)) {
			return;
		}

		$query = ' lang LIKE "' . $this->lyricsSource . '"';
		if (strpos($this->database, 'sqlite') === false) {
			$query .= ' AND UPPER(CONCAT(title, artist)) LIKE :search';
		}
		else {
			$query .= ' AND UPPER(title || artist) LIKE :search';
		}
		$sql = "SELECT id FROM lyrics WHERE " . $query . " ORDER BY created DESC LIMIT 1";
		$stmt = $this->db_prepare($sql);
		$stmt->bindParam(":search", strtoupper($this->title . $this->artist));
		$result = $this->db_getOne($stmt);

		if (!empty($result)) {
			return;
		}

		/*
		$query = '';
		$query .= ' AND UPPER(lang) LIKE UPPER(:lang)';
		$query .= !empty($this->title) ? ' AND UPPER(title) LIKE UPPER(:title)' : '';
		$query .= !empty($this->artist) ? ' AND UPPER(artist) LIKE UPPER(:artist)' : '';
		$query .= !empty($this->album) ? ' AND UPPER(album) LIKE UPPER(:album)' : '';

		$sql = "SELECT id FROM lyrics WHERE (1=1) " . $query . " ORDER BY created DESC LIMIT 1";
		$stmt = $this->db_prepare($sql);
		$stmt->bindParam(":lang", $this->lyricsSource);

		if (!empty($this->title)) {
			$stmt->bindParam(":title", $this->title);
		}
		if (!empty($this->artist)) {
			$stmt->bindParam(":artist", $this->artist);
		}
		if (!empty($this->album)) {
			$stmt->bindParam(":album", $this->album);
		}
		$result = $this->db_getOne($stmt);

		if (!empty($result)) {
			return;
		}

		*/
		// $this->lyrics = $lyrics;
		// $this->parsing();
		$this->setLyrics();
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

	private function executePlugin($type = 'lyrics', $plugin) {
		$hook = "{$plugin}_{$type}_hook";
		$path = dirname(__FILE__) . "/../plugins/{$plugin}.php";
		$result = '';
		if (file_exists($path)) {
			require_once $path;
			if (function_exists($hook)) {
				$param = array(
					'title' => $this->title,
					'artist' => $this->artist,
					'album' => $this->album
				);
				$result = call_user_func($hook, $param);
			}
		}
		return $result;
	}

	private function stripStrings() {
		if (!$this->_stripped) {
			$this->title = $this->removeFeatureString($this->title);
			$this->album = $this->removeFeatureString($this->album);
			$this->artist = $this->removeFeatureString($this->artist);

			$this->title = str_replace('%', '\%', $this->title);
			$this->artist = str_replace('%', '\%', $this->artist);
			$this->album = str_replace('%', '\%', $this->album);

		}
		$this->_stripped = TRUE;
	}

	private function parsing() {
		$lyrics = html_entity_decode($this->lyrics);
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
		// $lyrics = preg_replace('#(\\r\\n|\\r|\\n)#', "\n", $lyrics);
		$lyrics = preg_replace("/(\r?\n)/", "\n", $lyrics);
		$rows = explode("\n", $lyrics);
		$loop = count($rows);

		// parsing, remove spam text
		for ($i=0; $i < $loop; $i++) {
			$row = trim($rows[$i]);
			$pattern = '/(document\.write|mojim|\.com|google|script|轉載來自|歌詞網 |動態歌詞|友站連結|提供歌詞|修正歌詞|http:\/\/)/i';
			preg_match($pattern, $row, $matches);
			if (empty($matches)) {
				$plaintext[] = $row;
			}
		}

		$lyrics = implode("\n", trim($plaintext));
		// remove multiple line breaks
		$lyrics = preg_replace("/(\r?\n){2,}/", "\n\n", $lyrics);
		$this->lyrics = trim($lyrics);
	}

	private function removeFeatureString($str) {
		$patterns = array();
		$replacements = array();

		$patterns[] = '/\.mp3|\.wav|\.m4a/i';
		$replacements[] = '';

		$patterns[] = '/ featuring .*| ft\. .*| f\. .*| feat .*| ft .*| feat\. .*|\/feat .*| f .*| \[feat\. .*\]| \(feat\. .*\)| \[ft. .*\]|\(.* feat .*\)/i';
		$replacements[] = '';

		$patterns[] = '/ \(.* version\)| \(.* edition\)| \(.* edit\)| ～.* version～/i';
		$replacements[] = '';

		$patterns[] = '/ \[.* version\]| \[.* edition\]| \[.* edit\]/i';
		$replacements[] = '';

		$patterns[] = '/ \(remix\)| \(.* remix\)| \(remixed by .*\)| \(acoustic\)| \(.* track\)| \(.* tracks\)/i';
		$replacements[] = '';

		$patterns[] = '/ \[remix\]| \[.* remix\]| \[remixed by .*\]| \[acoustic\]| \[.* track\]| \[.* tracks\]/i';
		$replacements[] = '';

		$patterns[] = '/ (live)| \[live\]| - live in .*| \(live in .*\)| \[live in .*\]| - live/i';
		$replacements[] = '';

		$patterns[] = '/^([0-9]{1,2})\.+|^([0-9]{1,2}) \- |^([0-9]{1,2}) /';
		$replacements[] = '';

		$patterns[] = '/\[EP\]|\[CD\/DVD\]|\(CD\/DVD\)|\[Disc.?[0-9]{1,}\]|\(Disc.?[0-9]{1,}\)|Disc.?[0-9]{1,}/i';
		$replacements[] = '';

		$patterns[] = '/\[soundtrack\]|\(soundtrack\)|\[Original Sound Track\]|\(Original Sound Track\)/i';
		$replacements[] = '';

		$patterns[] = '/\[instrumental\]|\(instrumental\)| .?instrumental.?| ～Instrumental～/i';
		$replacements[] = '';

		$patterns[] = '/\[初回盤\]|\(初回盤\)/';
		$replacements[] = '';

		$patterns[] = '/\/{1,}/';
		$replacements[] = '/';

		$patterns[] = '/^\//';
		$replacements[] = '';

		$patterns[] = '/\/$/';
		$replacements[] = '';

		$patterns[] = '/\s\s+/'; // remove multiple blank spaces,  always be last check
		$replacements[] = ' ';

		$patterns[] = '/(\(\))|(\[\])/'; // remove emtpy () or []
		$replacements[] = '';

		$str = preg_replace($patterns, $replacements, $str);
		$str = trim($str);
		return $str;
	}

}
