<?php
include_once __DIR__ . '/bootstrap.php';

// init server variables
$_SERVER['HTTP_HOST'] = parse_url($config->base_url, PHP_URL_HOST);
$_SERVER['SERVER_PORT'] = parse_url($config->base_url, PHP_URL_PORT);

define('BASE_URL', $config->base_url);

$all_cache = CACHE."/all";
$lines = file_get_contents($all_cache);
$lines = explode("\n", $lines);

// generate post detail
foreach ($lines as $md_origin) {
	$_SERVER['REQUEST_URI'] = $md_origin;
	if (empty($md_origin)) { continue; }
	$post = parse_post($md_origin);
	if (empty($post)) { continue; }
	$mdfile = POST. '/'. $md_origin;

	ob_start();
	include CTRL . '/single.php';
	$html = ob_get_contents();
	ob_clean();
	write_post_file($post, $html);
}

// generate index and pages
$page_number = ceil(count($lines) / $config->post_per_page);
for ($page = 1; $page <= $page_number; $page++) {
	$_GET['page'] = $page;
	ob_start();
	include CTRL . '/home.php';
	$html = ob_get_contents();
	ob_clean();
	if ($page == 1) {
		// write home page
		write_page_file(0, $html);
	}
	write_page_file($page, $html);
}
println("Done");

function write_post_file($post, $html) {
	$htmlfile = $post->link;
	if ($post->draft == 'true') {
		return false;
	}
	if (!preg_match('/\/(.+)\.(\w+)$/', $htmlfile, $m)) {
		$htmlfile .= 'index.html';
	}

	$fullpath =  STATICPATH . $htmlfile;
	$dirname = STATICPATH . dirname($htmlfile);
	$basename = basename($htmlfile);

	if (!is_dir($dirname)) {
		mkdir($dirname, 0775, true);
	}
	println('Writing file', $htmlfile);
	file_put_contents($dirname . '/' . $basename, $html);
	return true;
}

function write_page_file($page, $html) {
	if ($page == 0) {
		$htmlfile = "/index.html";
	}else {
		$htmlfile = "/page-{$page}/index.html";
	}
	$fullpath =  STATICPATH . $htmlfile;
	$dirname = STATICPATH . dirname($htmlfile);
	$basename = basename($htmlfile);

	if (!is_dir($dirname)) {
		mkdir($dirname, 0775, true);
	}
	println('Writing file', $htmlfile);
	file_put_contents($dirname . '/' . $basename, $html);
}
