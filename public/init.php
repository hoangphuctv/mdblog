<?php

if (!defined("ROOT")) { die('File not found'); }

$post = POST;
$cache = CACHE;
$all_cache = "$cache/all";

if (!is_file($all_cache)) {
	`cd $post && find . -type f > $all_cache`;
}


function find_posts($offset, $limit){
	$head = $offset + $limit;

	$all_cache = CACHE."/all";
	
	$posts = `head -n $head $all_cache | tail -n $limit`;
	$posts = explode("\n", trim($posts));

	$total = intval(`wc -l $all_cache`);
	return [$posts, $total];
}

function parse_post($post_path){

	$post_path = str_replace("./", '/', $post_path);
	$post = [
		'title'    => get_post_title($post_path),
		'name'     => basename($post_path),
		'path'     => $post_path,
		'fullpath' => POST . '/' . $post_path,
	];
	$post['mod_date'] = full_date(filemtime($post['fullpath']));
	$post['create_date'] = full_date(filectime($post['fullpath']));
	if (empty($post['mod_date'])) {
		$post['mod_date'] = $post['create_date'];
	}
	return $post;
}

function get_post_title($post_path) {
	$file = POST.$post_path;
	$t = `head $file -n 1`;
	$t = trim($t);
	if (empty($t)) {
		$t = str_replace(".md", ".html", $post_path);
	}else{
		$t = preg_replace("/\#\s*/", '', $t);
	}
	return $t;
}

function get_next_posts($current_post, $n=1) {
	$all = CACHE ."/all";
	$posts = `awk '$0 == ".$current_post" {i=1;next};i && i++ <= $n' $all`;
	return explode("\n", trim($posts));
}

function short_date($time) {
	global $config;
	$format = isset($config->short_date) ? $config->short_date : "Y-m-d";
	return date ($format, $time);
} 

function full_date($time) {
	global $config;
	$format = isset($config->full_date) ? $config->full_date : "Y-m-d H:i:s";

	$today = date("Y-m-d");
	$date = str_replace($today, '', date ($format, $time));
	return $date;
} 
