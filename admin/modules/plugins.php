<?php
/**
 * Plugins module
 *
 * @author Ethan Liu <ethan@creativecrap.com>
 **/

require_once dirname(__FILE__) . "/../common.php";
require_once dirname(__FILE__) . "/../../classes/controller.php";

class PluginsModule extends Controller {
	public $numberOfRowsPerPage = 25;
	public $numberOfPages = -1;
	public $numberOfRecords = -1;

	public function __construct() {
		parent::__construct();
		$this->db_connect();
	}

	public function set() {
		$target = strtolower(trim($_POST['target']));
		$values = trim($_POST['value']);

		if ($target === 'enable') {
			list($plugin, $enabled) = explode('|', $values);
			$sql = "UPDATE plugins SET enabled = :enabled WHERE path = :plugin";
			$stmt = $this->db_prepare($sql);
			$stmt->bindParam(":plugin", $plugin);
			$stmt->bindParam(":enabled", intval($enabled));
			$result = $this->db_execute($stmt);
		}

		if ($target === 'ordering') {
			$plugins = explode(',', $values);
			$ordering = 1;
			foreach ($plugins as $plugin) {
				if (empty($plugin)) {
					continue;
				}

				$sql = "UPDATE plugins SET ordering = :ordering WHERE path = :plugin";
				$stmt = $this->db_prepare($sql);
				$stmt->bindParam(":plugin", $plugin);
				$stmt->bindParam(":ordering", $ordering);
				$result = $this->db_execute($stmt);
				$ordering++;
			}
		}
		return json_encode(array('success' => true));
	}

	public function records($page = 1) {
		$pugins_path = realpath(dirname(__FILE__) . '/../../plugins/');

		$sql = "SELECT * FROM plugins ORDER BY ordering ASC";
		$stmt = $this->db_prepare($sql);
		$plugins = $this->db_getAll($stmt);

		$files = array();
		if ($d = opendir($pugins_path)) {
			while (false !== ($entry = readdir($d))) {
				if (strpos($entry, ".php") !== false) {
					$files[] = $entry;
				}
			}
			closedir($d);
		}

		// check new plugin
		foreach ($plugins as $row) {
			if (in_array($row['path'], $files)) {
				$key = array_search($row['path'], $files);
				unset($files[$key]);
			}
		}
		if ($files) {
			$ordering = count($plugins) + 1;
			foreach ($files as $file) {
				$sql = "INSERT INTO plugins (path, ordering, enabled, created) VALUES (:path, :ordering, :enabled, :created)";
				$stmt = $this->db_prepare($sql);
				$stmt->bindParam(":path", $file);
				$stmt->bindParam(":ordering", $ordering);
				$stmt->bindValue(":enabled", 0);
				$stmt->bindParam(":created", time());
				$this->db_execute($stmt);
				$ordering++;
			}

			$sql = "SELECT * FROM plugins ORDER BY ordering ASC";
			$stmt = $this->db_prepare($sql);
			$plugins = $this->db_getAll($stmt);
		}


		return $plugins;
	}

	public function numberOfRecords() {
		if ($this->numberOfRecords === -1) {
			$sql = "SELECT COUNT(*) AS total FROM plugins";
			$stmt = $this->db_prepare($sql);
			$this->numberOfRecords = $this->db_getOne($stmt);
		}
		return $this->numberOfRecords;
	}
	public function numberOfPages() {
		return 1;
	}

}
