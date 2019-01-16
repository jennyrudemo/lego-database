<?php

session_start();

	if(isset($_GET['searchorder'])) {
		$button = $_GET['searchorder'];
		$_SESSION['searchorder'] = $button;
		setcookie('searchorder', $button, time() + (3600 * 24 * 30));
	} else if(isset($_SESSION['searchorder'])) {
		$button = $_SESSION['searchorder'];
	} else if(isset($_COOKIE['searchorder'])) {
		$button = $_COOKIE['searchorder'];
	} else {
		$button = 'sbid';
	}
 	
 	$lang_file = 'searchorder.'.$button.'.php';

	include_once $lang_file;

	?>