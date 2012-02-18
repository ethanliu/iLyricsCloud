<?php
/**
 * iLyrics Fetcher Class
 *
 * @author Ethan Liu
 * @copyright , 25 December, 2011
 * @package class
**/

require_once dirname(__FILE__) . "/controller.php";

class LyricsFetcher extends Controller {
	public $title = '';
	public $artist = '';
	public $album = '';
	public $lyricsId = 0;
	public $lyrics = '';
	public $lyricsSource = '';
	private $plugins = array(
		'en' => 'lyricswiki:lyrics',
		'zh' => 'mojim:lyrics',
		'jp' => 'yahoojp:lyrics|jpopasia:lyrics',//'jpopasia:lyrics|utamap:lyrics',
		'artwork' => 'google:artwork|kkbox:artwork',
	);

	private $_stripped = FALSE;
	private $_pages = 1;
	private $_limited = 10;
	private $_data = null;

	public function __construct() {
		parent::__construct();
		//$this->database = (defined('DATABASE_DNS') ? DATABASE_DNS : '');
		if (!empty($this->database) && !INSTALLED) {
			$this->output("Run install first.");
		}
		
		$this->db_connect();
	}
	
	// class interface
	
	public function output($error = '') {
		$gaImageUrl = $this->googleAnalyticsGetImageUrl();
		$data = array();
		//if (empty($this->_data)) {
		if (!is_array($this->_data)) {
			if (strlen($this->title.$this->artist.$this->album) > 0) {
				$data = array(array(
					'title' => $this->title,
					'artist' => $this->artist,
					'album' => $this->album,
					'lyrics' => $this->lyrics,
					'ga' => $gaImageUrl
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
	
	public function artwork() {
		$this->stripStrings();
		
		if ($this->album == '' || $this->artist == '') {
			return '';
		}
		
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
		
		if (empty($this->title) || strlen($this->artist . $this->album) <= 0) {
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
	
	public function news() {
		$sql = "SELECT created AS id, news FROM news ORDER BY created DESC LIMIT 1";
		$stmt = $this->db_prepare($sql);
		$result = $this->db_getRow($stmt);
		return $result;
	}
	
	public function search() {
		if (!$this->db) {
			return array();
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
		
		$sql = "SELECT COUNT(*) AS total FROM lyrics AS l WHERE (1=1) " . $query;
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
		
		//$sql = "SELECT id, lang, title, artist, album FROM lyrics WHERE (1=1) " . $query . " LIMIT {$offset}, {$this->_limited}";
		$sql = "SELECT id, lang, title, artist, album, lyrics,
				(SELECT url FROM artworks WHERE (1=1) " . $query . " ORDER BY id DESC LIMIT 1) AS url
				FROM lyrics AS l WHERE (1=1) " . $query;
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
		
		$query = " AND album LIKE :album";
		$query .= !empty($this->artist) ? " AND artist LIKE :artist" : '';
		$sql = "SELECT url FROM artworks WHERE (1=1) " . $query . " ORDER BY RANDOM() LIMIT 1";
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
			$query .= ' AND lang LIKE :lang';
			$query .= !empty($this->title) ? ' AND title LIKE :title' : '';
			$query .= !empty($this->artist) ? ' AND artist LIKE :artist' : '';
			$query .= !empty($this->album) ? ' AND album LIKE :album' : '';
		
			$sql = "SELECT lang, title, artist, album, lyrics FROM lyrics WHERE (1=1) " . $query . " ORDER BY created DESC LIMIT 1";
			//var_dump($sql);
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
		$path = dirname(__FILE__) . "/../plugins/{$plugin}.php";
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
