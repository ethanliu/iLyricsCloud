<?php
/**
 * 
 *
 * @author Ethan Liu
 * @copyright , 14 February, 2012
 **/

require_once dirname(__FILE__) . "/../common.php";
require_once dirname(__FILE__) . "/../../classes/controller.php";

class ArtworksModule extends Controller {
	public $numberOfRowsPerPage = 25;
	public $numberOfPages = -1;
	public $numberOfRecords = -1;
	
	public function __construct() {
		parent::__construct();
		if (!empty($this->database) && !INSTALLED) {
			die('Run <a href="?q=install">install</a> database first.');
		}
		
		$this->db_connect();
	}
	
	public function records($page = 1) {
		$pages = $this->numberOfPages;
		$page = ($page > $pages) ? $pages : (($page < 1) ? 1 : $page);
		
		$offset = ($page - 1) * $this->numberOfRowsPerPage;

		$sql = "SELECT * FROM artworks ORDER BY created DESC LIMIT {$this->numberOfRowsPerPage} OFFSET {$offset}";
		$stmt = $this->db_prepare($sql);
		$result = $this->db_getAll($stmt);
		return $result;
	}

	public function edit($id) {
		$sql = "SELECT * FROM artworks WHERE id = {$id}";
		$stmt = $this->db_prepare($sql);
		$result = $this->db_getRow($stmt);
		return $result;
	}
	
	public function update() {
		$id = intval($_POST['id']);
		$delete = @intval($_POST['delete']);
		$artist = trim($_POST['artist']);
		$album = trim($_POST['album']);
		$url = trim($_POST['url']);
		
		if ($delete) {
			$result = $this->delete($id);
		}
		else {
			$sql = "UPDATE artworks SET created = :created, artist = :artist, album = :album, url = :url WHERE id = :id";
			$stmt = $this->db_prepare($sql);
			$stmt->bindParam(":created", time());
			$stmt->bindParam(":artist", $artist);
			$stmt->bindParam(":album", $album);
			$stmt->bindParam(":url", $url);
			$stmt->bindParam(":id", $id);
			$result = $this->db_execute($stmt);
		}
		
		return $result;
	}
	
	public function numberOfRecords() {
		$sql = "SELECT COUNT(*) AS total FROM artworks";
		$stmt = $this->db_prepare($sql);
		$this->numberOfRecords = $this->db_getOne($stmt);
		return $this->numberOfRecords;
	}
	
	public function numberOfPages() {
		if ($this->numberOfRecords < 0) {
			$foo = $this->numberOfLyrics();
		}
		$this->numberOfPages = ceil($this->numberOfRecords / $this->numberOfRowsPerPage);
		return $this->numberOfPages;
	}
	
	private function delete($id) {
		$sql = "DELETE FROM artworks WHERE id = :id";
		$stmt = $this->db_prepare($sql);
		$stmt->bindParam(":id", $id);
		$result = $this->db_execute($stmt);
		return $result;
	}
}
