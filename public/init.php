<?php

if (!defined("ROOT")) { die('File not found'); }

$post = POST;
$cache = CACHE;
$all_cache = "$cache/all";
$all_cache_metadata = "$cache/all_metadata";
if (!is_dir($cache)) {
	mkdir($cache, 0755);
}
if (!file_exists($all_cache) || $config->debug || PHP_SAPI === 'cli') {
	// reset or create cache
	$all_data = [];
	if (is_dir($post)) {
		$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($post));
		foreach ($iterator as $file) {
			if ($file->isFile() && $file->getExtension() === 'md' && !preg_match('/\/\./', $file->getPathname())) {
				$all_data[] = './' . substr($file->getPathname(), strlen($post) + 1);
			}
		}
		sort($all_data);
		$all_data = array_reverse($all_data);
	}

	$public_posts = array();
	foreach ($all_data as $post_path) {
		$_post = parse_post($post_path);
		if (empty($_post)) {
			continue;
		}

		if (isset($_post->draft) && $_post->draft == true) {
			continue;
		}

		$public_posts[] = $post_path;
	}
	file_put_contents($all_cache, implode("\n", $public_posts));

	// truncate and init file meta
	file_put_contents($all_cache_metadata, '');

	foreach($all_data as $line) {
		$post_meta = parse_post($line);
		if ($post_meta) {
			file_put_contents($all_cache_metadata, json_encode($post_meta)."\n", FILE_APPEND);
		}
	}
}
