<?php
/**
 * Controller
 *
 * @author Ethan Liu <ethan@creativecrap.com>
 **/

require_once dirname(__FILE__) . "/../config.php";

class Controller {
	public $database = '';
	public $db;
	public $provider;

	public function __construct() {
		$this->database = (defined('DATABASE_DSN') ? DATABASE_DSN : '');
		$this->db_connect();
	}

	public function debug() {
		$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	// sqlite section
	public function db_connect() {
		if (!empty($this->database) && !$this->db) {
			$username = '';
			$password = '';

			if (preg_match('/user=([^;]*);/', $this->database, $matches)) {
				$username = $matches[1];
				$this->database = str_replace($matches[0], '', $this->database);
			}
			if (preg_match('/password=([^;]*);/', $this->database, $matches)) {
				$password = $matches[1];
				$this->database = str_replace($matches[0], '', $this->database);
			}

			if (preg_match('/(.*):/', $this->database, $matches)) {
				$this->provider = $matches[1];
			}

			$this->db = new PDO($this->database, $username, $password);
			if (!$this->db) {
				echo "Database access denied<br>";
				echo $this->database . "<br>";
				echo $username . "<br>";
				echo $password . "<br>";
				exit;
			}
			$this->db->query("SET NAMES 'utf8'");
		}
	}

	public function db_prepare($sql) {
		if ($this->db) {
			$stmt = $this->db->prepare($sql);
			if (!$stmt) {
				$error = $this->db->errorInfo();
				die("Error (1): " . $error[2]);
				return FALSE;
			}
			return $stmt;
		}
		return FALSE;

	}

	public function db_getOne($statement) {
		if ($this->db) {
			if (!$statement->execute()) {
				$error = $this->db->errorInfo();
				die("Error (2): " . $error[2]);
				return FALSE;
			}
			$result = $statement->fetch();
			return $result[0];
		}
		return FALSE;
	}

	public function db_getRow($statement) {
		if ($this->db) {
			if (!$statement->execute()) {
				$error = $this->db->errorInfo();
				die("Error (3): " . $error[2]);
				return FALSE;
			}
			$result = $statement->fetch(PDO::FETCH_ASSOC);
			return $result;
		}
		return FALSE;
	}

	public function db_getAll($statement) {
		if ($this->db) {
			if (!$statement->execute()) {
				$error = $this->db->errorInfo();
				die("Error (4): " . $error[2]);
				return FALSE;
			}
			$result = $statement->fetchAll(PDO::FETCH_ASSOC);
			return $result;
		}
		return FALSE;
	}

	public function db_execute($statement) {
		if ($this->db) {
			if (!$statement->execute()) {
				$error = $this->db->errorInfo();
				die("Error (5): " . $error[2]);
				return FALSE;
			}
			return TRUE;
		}
		return FALSE;
	}

}

