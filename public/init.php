<?php

if (!defined("ROOT")) { die('File not found'); }

$post = POST;
$cache = CACHE;
$all_cache = "$cache/all";
if (!file_exists($all_cache) || $config->debug) {
	$all_data = `cd $post && find . -type f | sort`;
	$all_data = explode("\n", $all_data);
	$all_data = array_reverse($all_data);
	$all_data = implode("\n", $all_data);
	file_put_contents($all_cache, $all_data); 
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
	$content = file_get_contents($file);
	$lines = explode("\n", $content);

	if ($lines[0] == '---') {
		$parts = explode("---", $content);
		$metadata = parse_toml($parts[1]);
		if (isset($metadata['title'])) {
			return $metadata['title'];
		}
		$t = $content[0];
		$t = trim($t);
		if (empty($t)) {
			$t = str_replace(".md", ".html", $post_path);
		}else{
			$t = preg_replace("/\#\s*/", '', $t);
		}
	}
	return $t;
}

function get_next_posts($current_post, $n=1) {
	$all = CACHE ."/all";
	$posts = `awk '$0 == ".$current_post" {i=1;next};i && i++ <= $n' $all`;
	$posts = trim($posts);
	if (empty($posts)) {
		$posts = `tail -n $n $all`;
		$posts = trim($posts);
	}
	return explode("\n", $posts);
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

function current_url() {
    $protocol = 'http';
    if ($_SERVER['SERVER_PORT'] == 443 || (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')) {
        $protocol .= 's';
        $protocol_port = $_SERVER['SERVER_PORT'];
    } else {
        $protocol_port = 80;
    }

    $host = $_SERVER['HTTP_HOST'];
    $port = $_SERVER['SERVER_PORT'];
    $request = $_SERVER['REQUEST_URI'];
    $query = isset($_SERVER['argv']) ? substr($_SERVER['argv'][0], strpos($_SERVER['argv'][0], ';') + 1) : '';

    $toret = $protocol . '://' . $host . $request . (empty($query) ? '' : '?' . $query);
    return $toret;
}
