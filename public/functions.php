<?php

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
		'name'     => basename($post_path),
		'path'     => $post_path,
		'fullpath' => POST . '/' . $post_path,
	];

	$metadata = get_post_metadata($post_path);
	if ($metadata) {
		$post = array_merge($post, $metadata);
	}

	if (isset($post['date'])) {
		$post['date'] = $post['date'];
		list($post['year'], $post['month'], $post['day']) = explode("-", date("Y-m-d", strtotime($post['date'])));
	}else {
		$post['date'] = '';
	}

	$post_obj = new Post($post);
	$post_obj->link = get_permalink($post_obj);
	return $post_obj;
}

function get_post_metadata($post_path) {
	if (strpos($post_path, POST) === false) {
		$post_path = POST.$post_path;
	}
	$content = file_get_contents($post_path);
	$lines = explode("\n", $content);
	if ($lines[0] == '---') {
		$parts = explode("---", $content);
		$metadata = parse_toml($parts[1]);
		$metadata['content'] = $parts[2];
		if (isset($metadata['title'])) {
			$t = $metadata['title'];
		}
	}else {
		// get first line as title
		$metadata = [
			'title' => array_shift($content),
			'content' => implode("\n", $content),
		];
	}
	$metadata['title'] = trim($metadata['title'], "'\" \t\r\n");
	if (empty($metadata['title'])) {
		$metadata['title'] = preg_replace("/\.md/", ".html", $post_path);
	}else{
		$metadata['title'] = preg_replace("/\#\s*/", '', $metadata['title']);
	}

	return $metadata;
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

	if (!is_numeric($time)) {
		$time = strtotime($time);
	}

	if ($time <=0 ) {
		return '';
	}

	$format = isset($config->short_date) ? $config->short_date : "Y-m-d";
	return date($format, $time);
}

function full_date($time) {
	global $config;

	if (!is_numeric($time)) {
		$time = strtotime($time);
	}

	if ($time <=0 ) {
		return '';
	}
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

function file_get_lines($filepath, $n=1) {
	$result = "";
	$fn = fopen($filepath,"r");

	for ($i=0; $i<$n; $i++) {
		$result .= fgets($fn);
	}

	fclose($fn);
	return $result;
}

function parse_toml($lines) {
	$lines = trim($lines);
	if (is_string($lines)) {
		$lines = explode("\n", $lines);
		$header = [];
		foreach($lines as $line) {
			$line = explode(":", $line);
			$line[0] = trim(strtolower($line[0]));
			$line[1] = trim($line[1]);

			if (strpos($line[1], '[') !== false) {
				$line[1] = json_decode($line[1], true);
			}
			$header[$line[0]] = $line[1];
		}
	}
	return $header;
}


function get_permalink($post){
	global $config;
	static $df_permalink = "/{YEAR}-{MONTH}-{DAY}-{TITLE}.html";
	$permalink = $config->permalink;
	if (!preg_match_all('/{(\w+)}/', $permalink, $vars)){
		preg_match_all('/{(\w+)}/', $df_permalink, $vars);
	}
	$link = $permalink;
	foreach ($vars[1] as $key) {
		$property = strtolower($key);
		$link = str_replace("{{$key}}", strtolower($post->$property), $link);
	}
	$link = preg_replace("/\s+/", '-', $link);
	$link = preg_replace("/\-+/", '-', $link);
	return $link;
}
