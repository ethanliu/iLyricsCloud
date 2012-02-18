<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>iLyrics Cloud Admin</title>
	<meta name="title" content="" />
	<meta name="Description" content="" />
	<meta name="Keywords" content="" />
	<meta name="robots" content="index,follow,archive" />
	<meta name="Copyright" content="(c) 2011 Creativecrap.com" />
	<meta name="Author" content="Ethan" />
	<meta http-equiv="X-UA-Compatible" content="IE=8" />
	<meta http-equiv="X-UA-Compatible" content="chrome=1" />
	<meta http-equiv="imagetoolbar" content="no" />
	<link rel="stylesheet" href="css/master.css" />
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
</head>
<body>

<div class="topbar">
	<div class="fill">
		<div class="container">
			<a class="brand" href="./">iLyrics Cloud Admin</a>
			<?php if (isset($_SESSION['login_id']) && $_SESSION['login_id']):?>
			<ul id="main-nav" class="nav">
				<li><a href="./?q=artworks">Artworks</a></li>
				<li><a href="./?q=lyrics">Lyrics</a></li>
				<li><a href="./?q=news">News</a></li>
				<li><a href="../demo.html">Demo</a></li>
			</ul>
			<form class="pull-left">
				<input type="search" name="search" value="" placeholder="Search">
			</form>
			<p class="pull-right"><a href="./login.php?q=logout">Logout</a></p>
			<?php endif;?>
		</div>
	</div>
</div>

<div class="container">
	<div id="content" class="content">
