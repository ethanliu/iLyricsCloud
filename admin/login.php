<?php
/**
 * Login
 *
 * @author Ethan Liu <ethan@creativecrap.com>
 **/

@session_start();
require_once dirname(__FILE__) . "/../config.php";
include "./header.php";

if (!empty($_POST['username']) && !empty($_POST['password'])) {

	$username = trim($_POST['username']);
	$password = trim($_POST['password']);

	if ($username == ADMIN_USER && $password == ADMIN_PASS) {
		$_SESSION['login_id'] = 1;
		header("Location:index.php");
		exit;
	}
}


if (!isset($_SESSION['login_id'])) {
	include "./views/login-view.php";
	// exit;
}

include "./footer.php";
