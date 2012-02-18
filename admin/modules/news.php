<?php
/**
 * 
 *
 * @author Ethan Liu
 * @copyright , 19 February, 2012
 **/

require_once dirname(__FILE__) . "/../common.php";
require_once dirname(__FILE__) . "/../../classes/controller.php";

class NewsModule extends Controller {
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

		$sql = "SELECT * FROM news ORDER BY created DESC LIMIT {$this->numberOfRowsPerPage} OFFSET {$offset}";
		$stmt = $this->db_prepare($sql);
		$result = $this->db_getAll($stmt);
		return $result;
	}
	
	public function edit($id) {
		if (!empty($id)) {
			$sql = "SELECT * FROM news WHERE id = {$id}";
			$stmt = $this->db_prepare($sql);
			$result = $this->db_getRow($stmt);
			return $result;
		}
		return null;
	}
	
	public function update() {
		$id = intval($_POST['id']);
		$created = strtotime(trim($_POST['created']));
		$news = trim($_POST['news']);

		if (empty($id)) {
			$sql = "INSERT INTO news (created, news) VALUES (:created, :news)";
			$stmt = $this->db_prepare($sql);
			$stmt->bindParam(":created", $created);
			$stmt->bindParam(":news", $news);
			$result = $this->db_execute($stmt);
		}
		else {
			$sql = "UPDATE news SET created = :created, news = :news WHERE id = :id";
			$stmt = $this->db_prepare($sql);
			$stmt->bindParam(":created", $created);
			$stmt->bindParam(":news", $news);
			$stmt->bindParam(":id", $id);
			$result = $this->db_execute($stmt);
		}
		return $result;
	}
	
	public function numberOfRecords() {
		$sql = "SELECT COUNT(*) AS total FROM news";
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
	
}
