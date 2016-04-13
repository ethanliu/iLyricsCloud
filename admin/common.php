<?php
@session_start();
include "./includes/functions.php";
include "./includes/pager.php";

if (!isset($_SESSION['login_id'])) {
	header("location:login.php");
	exit;
}
