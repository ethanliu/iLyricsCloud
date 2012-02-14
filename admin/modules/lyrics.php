<?php
/**
 * 
 *
 * @author Ethan Liu
 * @copyright , 14 February, 2012
 **/

require_once dirname(__FILE__) . "/../common.php";
require_once dirname(__FILE__) . "/../../classes/controller.php";

class LyricsModule extends Controller {
	public $numberOfRowsPerPage = 25;
	public $numberOfPages = -1;
	public $numberOfRecords = -1;
	
	public function __construct() {
		parent::__construct();
		if (!empty($this->database) && !INSTALLED) {
			$dsn = explode(':', $this->database);
			switch ($dsn[0]) {
				case 'sqlite':
					$this->install_sqlite();
					break;
				case 'pgsql':
					$this->install_postgres();
					break;
			}
			die("Installed success, please update INSTALLED.");
		}
		
		$this->db_connect();
	}
	
	public function records($page = 1) {
		$pages = $this->numberOfPages;
		$page = ($page > $pages) ? $pages : (($page < 1) ? 1 : $page);
		
		$offset = ($page - 1) * $this->numberOfRowsPerPage;

		$sql = "SELECT * FROM lyrics ORDER BY created DESC LIMIT {$offset}, {$this->numberOfRowsPerPage}";
		$stmt = $this->db_prepare($sql);
		$result = $this->db_getAll($stmt);
		return $result;
	}

	public function edit($id) {
		$sql = "SELECT * FROM lyrics WHERE id = {$id}";
		$stmt = $this->db_prepare($sql);
		$result = $this->db_getRow($stmt);
		return $result;
	}
	
	public function update() {
		$id = intval($_POST['id']);
		$delete = @intval($_POST['delete']);
		$lang = trim($_POST['lang']);
		$title = trim($_POST['title']);
		$artist = trim($_POST['artist']);
		$album = trim($_POST['album']);
		$lyrics = trim($_POST['lyrics']);
		
		if ($delete) {
			$result = $this->delete($id);
		}
		else {
			$sql = "UPDATE lyrics SET created = :created, lang = :lang, title = :title, artist = :artist, album = :album, lyrics = :lyrics WHERE id = :id";
			$stmt = $this->db_prepare($sql);
			$stmt->bindParam(":created", time());
			$stmt->bindParam(":lang", $lang);
			$stmt->bindParam(":title", $title);
			$stmt->bindParam(":artist", $artist);
			$stmt->bindParam(":album", $album);
			$stmt->bindParam(":lyrics", $lyrics);
			$stmt->bindParam(":id", $id);
			$result = $this->db_execute($stmt);
		}
		
		return $result;
	}
	
	public function numberOfRecords() {
		$sql = "SELECT COUNT(*) AS total FROM lyrics";
		$stmt = $this->db_prepare($sql);
		$this->numberOfRecords = $this->db_getOne($stmt);
		return $this->numberOfRecords;
	}
	
	public function numberOfPages() {
		if ($this->numberOfRecords < 0) {
			$foo = $this->numberOfRecords();
		}
		$this->numberOfPages = ceil($this->numberOfRecords / $this->numberOfRowsPerPage);
		return $this->numberOfPages;
	}
	
	private function delete($id) {
		$sql = "DELETE FROM lyrics WHERE id = :id";
		$stmt = $this->db_prepare($sql);
		$stmt->bindParam(":id", $id);
		$result = $this->db_execute($stmt);
		return $result;
	}
}
