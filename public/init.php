<?php

if (!defined("ROOT")) { die('File not found'); }

$post = POST;
$cache = CACHE;
$all_cache = "$cache/all";
if (!is_dir($cache)) {
	mkdir($cache, 0755);
}
if (!file_exists($all_cache) || $config->debug) {
	$all_data = `cd $post && find . -type f | sort`;
	$all_data = explode("\n", $all_data);
	$all_data = array_reverse($all_data);
	$all_data = implode("\n", $all_data);
	file_put_contents($all_cache, $all_data); 
}
