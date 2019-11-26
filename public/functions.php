<?php

function get_title_from_url($uri){
	global $config;
	$permalink = $config->permalink;
	$uris = explode("/", $uri);
	$uris = array_filter($uris);
	$permalinks = explode("/", $permalink);
	$permalinks = array_filter($permalinks);

	if (count($uris) != count($permalinks)) {
		show_404('Config url not match.');
	}
	$ret = array_combine($permalinks, $uris);
	if (empty($ret['{TITLE}'])) {
		show_404('Invalid config permalink.');
	}

	return urldecode($ret['{TITLE}']);
}

function show_404($msg=''){
	echo $msg ?:"File not found.";
	exit;
}

function find_post($link) {
	$link = urldecode($link);
	$all_cache_metadata = CACHE."/all_metadata";
	$metadata = file_get_contents($all_cache_metadata);
	$metadata = explode("\n", $metadata);
	foreach ($metadata as $line) {
		$post_meta = json_decode($line);
		if (empty($post_meta->link)) {continue;}
		if ($post_meta->link == $link) {
			return $post_meta;
		}
	}
	return null;
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
	if (empty($post_path)) { return; }
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
	$relative_path = $post_path;
	if (strpos($post_path, POST) === false) {
		$post_path = POST.$post_path;
	}else {
		$relative_path = str_replace($post_path, POST, '');
	}
	$content = file_get_contents($post_path);
	if (empty($content)) {
		return array();
	}

	$lines = explode("\n", $content);
	if ($lines[0] == '---') {
		$parts = explode("---", $content);
		$metadata = parse_toml($parts[1]);
		$metadata['content'] = $parts[2];
		if (isset($metadata['title'])) {
			$t = $metadata['title'];
		}else {
			$metadata['title'] = ltrim($relative_path, '/');
		}
	}else {
		// get first line as title
		$metadata = [
			'title' => array_shift($lines),
			'content' => implode("\n", $lines),
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
			$line = trim($line);
			if (empty($line)) {continue;}
			$line = explode(":", $line);
			$line[0] = trim(mb_strtolower($line[0]));
			$line[1] = trim($line[1]);

			if (strpos($line[1], '[') !== false) {
				$line[1] = json_decode($line[1], true);
			}
			$header[$line[0]] = $line[1];
		}
	}
	return $header;
}

function get_permalink($post) {
	return $post->path;
}

function get_permalink2($post){
	global $config;
	static $df_permalink = "/{YEAR}-{MONTH}-{DAY}-{TITLE}.html";
	$permalink = $config->permalink;
	if (!preg_match_all('/{(\w+)}/', $permalink, $vars)){
		preg_match_all('/{(\w+)}/', $df_permalink, $vars);
	}
	$link = $permalink;
	foreach ($vars[1] as $key) {
		$property = mb_strtolower($key);
		$link = str_replace("{{$key}}", mb_strtolower($post->$property), $link);
	}
	// character not support function mb_strtolower
	$link = str_replace(['Đ'], 'đ', $link);

	// $link = str_replace(['/'], '-', $link);
	$link = str_replace([
		'(', ')',
		'[', ']',
		'$', '|',
		"'", '"',
		".", ',',
		"<", '>',
		], '', $link);
	$link = preg_replace("/\s+/", '-', $link);
	$link = preg_replace("/\-+/", '-', $link);
	return $link;
}

function get_page_link($page) {
	if ($page == 1) {
		return BASE_URL;
	}
	return BASE_URL.'page-'.$page;
}