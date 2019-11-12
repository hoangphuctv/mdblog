<?php
include_once __DIR__ . '/bootstrap.php';

// init server variables

$_SERVER['HTTP_HOST'] = parse_url($config->base_url, PHP_URL_HOST);
$_SERVER['SERVER_PORT'] = parse_url($config->base_url, PHP_URL_PORT);

$all_cache = CACHE."/all";
$lines = file_get_contents($all_cache);
$lines = explode("\n", $lines);
foreach ($lines as $md_origin) {
	$_SERVER['REQUEST_URI'] = $md_origin;
	if (empty($md_origin)) { continue; }
	$post = parse_post($md_origin);
	$mdfile = POST. '/'. $md_origin;

	ob_start();
	include CTRL . '/single.php';
	$content = ob_get_contents();
	ob_clean();
	$htmlfile = $post->link;
	if ($post->draft == 'true') {
		continue;
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
	file_put_contents($dirname . '/' . $basename, $content);
}
println("Done");