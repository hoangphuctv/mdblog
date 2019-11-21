<?php
include __DIR__.'/bootstrap.php';

$uri = str_replace('..', '', explode("?", $_SERVER['REQUEST_URI'])[0]);
$uri = str_replace('.html', '.md', $uri);

$md_origin = $uri;
$mdfile = POST. '/'. $uri;

if ($uri == "/" || preg_match("/\/page-(\d+)/", $uri, $match_page)) {
	if (!empty($match_page[1])) {
		$_GET['page'] = (int)$match_page[1];
	}
	include CTRL . '/home.php';
}else {
	$post = find_post($uri);
	if (empty($post)) {
		show_404();
	}else {
		include CTRL . '/single.php';
	}
}
