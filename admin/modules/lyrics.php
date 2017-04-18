<?php
/**
 * Lyrics module
 *
 * @author Ethan Liu <ethan@creativecrap.com>
 **/

require_once dirname(__FILE__) . "/../common.php";
require_once dirname(__FILE__) . "/../../classes/controller.php";

class LyricsModule extends Controller {
	public $numberOfRowsPerPage = 25;
	public $numberOfPages = -1;
	public $numberOfRecords = -1;

	public function __construct() {
		parent::__construct();
		$this->db_connect();
	}

	public function upgrade($version = '') {
		$version = trim(file_get_contents(dirname(__FILE__) . "/../../VERSION"));
		$upgrade = '';

		if ($version === '2.1.0') {
			$sql = "SELECT COUNT(*) AS total FROM lyrics WHERE lang = 'en' OR lang = 'jp' OR lang = 'zh' GROUP BY lang";
			$stmt = $this->db_prepare($sql);
			$total = $this->db_getOne($stmt);
			if ($total) {
				$upgrade = $version;
			}
		}

		return $upgrade;
	}

	public function records($page = 1) {
		$pages = $this->numberOfPages;
		$page = ($page > $pages) ? $pages : (($page < 1) ? 1 : $page);
		$offset = ($page - 1 < 0) ? 0 : ($page - 1) * $this->numberOfRowsPerPage;

		$query = '';
		$search = !empty($_REQUEST['search']) ? trim($_REQUEST['search']) : '';
		if ($search) {
			//$query .= " AND ( UPPER(title) LIKE UPPER(CONCAT('%', :search, '%'))";
			//$query .= " OR UPPER(artist) LIKE UPPER(CONCAT('%', :search, '%'))";
			//$query .= " OR UPPER(album) LIKE UPPER(CONCAT('%', :search, '%')) )";
			$query .= " AND ( UPPER(title) LIKE UPPER(:search)";
			$query .= " OR UPPER(artist) LIKE UPPER(:search)";
			$query .= " OR UPPER(album) LIKE UPPER(:search) )";
		}

		$sql = "SELECT * FROM lyrics WHERE (1=1) " . $query . " ORDER BY created DESC LIMIT {$this->numberOfRowsPerPage} OFFSET {$offset}";
		$stmt = $this->db_prepare($sql);

		if (!empty($query)) {
			$stmt->bindParam(":search", $search);
		}

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
		$query = '';
		$search = !empty($_REQUEST['search']) ? trim($_REQUEST['search']) : '';
		if ($search) {
			//$query .= " AND ( UPPER(title) LIKE UPPER(CONCAT('%', :search, '%'))";
			//$query .= " OR UPPER(artist) LIKE UPPER(CONCAT('%', :search, '%'))";
			//$query .= " OR UPPER(album) LIKE UPPER(CONCAT('%', :search, '%')) )";
			$query .= " AND ( UPPER(title) LIKE UPPER(:search)";
			$query .= " OR UPPER(artist) LIKE UPPER(:search)";
			$query .= " OR UPPER(album) LIKE UPPER(:search) )";
		}

		$sql = "SELECT COUNT(*) AS total FROM lyrics WHERE (1=1) " . $query;
		$stmt = $this->db_prepare($sql);

		if (!empty($query)) {
			$stmt->bindParam(":search", $search);
		}

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
