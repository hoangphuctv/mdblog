<?php
include __DIR__.'/bootstrap.php';

$uri = str_replace('..', '', explode("?", $_SERVER['REQUEST_URI'])[0]);
$uri = str_replace('.html', '.md', $uri);

$md_origin = $uri;
$mdfile = POST. '/'. $uri;

if ($uri == "/") {
	include CTRL . '/home.php';
}else {
	if (!is_file($mdfile)) {
		echo 'file not found';exit;
	}else {
		include CTRL . '/single.php';
	}
}
