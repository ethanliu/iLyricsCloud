<?php
@session_start();
include "functions.php";

if (!isset($_SESSION['login_id'])) {
	header("location:login.php");
	exit;
}
