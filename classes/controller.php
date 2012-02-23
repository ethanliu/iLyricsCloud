<?php
/**
 * Controller template
 *
 * @author Ethan Liu
 * @version $Id$
 * @copyright , 27 December, 2011
 * @package class
 **/

require_once dirname(__FILE__) . "/../config.php";

class Controller {
	public $database = '';
	public $db;

	public function __construct() {
		$this->database = (defined('DATABASE_DNS') ? DATABASE_DNS : '');
		$this->db_connect();
	}
	
	public function debug() {
		$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	// google analytics section
	public function googleAnalyticsGetImageUrl() {
	    $url = "";
		if (!defined('GA_PIXEL') || !defined('GA_ACCOUNT')) {
			return $url;
		}
		
		$url .= GA_PIXEL . "?";
		$url .= "utmac=" . GA_ACCOUNT;
		$url .= "&utmn=" . rand(0, 0x7fffffff);

		$referer = @$_SERVER["HTTP_REFERER"];
		//$query = $_SERVER["QUERY_STRING"];
		//$path = $_SERVER["REQUEST_URI"];
		$path = @$_SERVER["PHP_SELF"];
	
		$param = array();
		foreach ($_REQUEST as $key => $value) {
			if (!($key == 'key' || $key == 'time')) {
				$param[] = $key . '=' . $value;
			}
		}
		$path = $path . '?' . implode('&', $param);
		
		if (empty($referer)) {
			$referer = "-";
		}
		$url .= "&utmr=" . urlencode($referer);
		if (!empty($path)) {
			$url .= "&utmp=" . urlencode($path);
		}
		$url .= "&guid=ON";
		return str_replace("&", "&amp;", $url);		
	}

	// sqlite section
	public function db_connect() {
		if (!empty($this->database) && !$this->db) {
			$this->db = new PDO($this->database);
		}
	}

	public function db_prepare($sql) {
		if ($this->db) {
			$stmt = $this->db->prepare($sql);
			if (!$stmt) {
				$error = $this->db->errorInfo();
				die("Error: " . $error[2]);
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
				die("Statement Error: " . $error[2]);
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
				die("Error: " . $error[2]);
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
				die("Error: " . $error[2]);
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
				die("Error: " . $error[2]);
				return FALSE;
			}
			return TRUE;
		}
		return FALSE;
	}

}

