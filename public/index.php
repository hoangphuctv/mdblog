<?php
opcache_reset();

define('ROOT', realpath(__DIR__ . '/../'));
define('POST', realpath(ROOT . '/posts'));
define('VIEW', realpath(ROOT . '/views'));
define('CTRL', realpath(ROOT . '/controllers'));
define('CACHE', realpath(ROOT . '/cached'));

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

global $config;
$config = json_decode(file_get_contents(ROOT.'/config.json'));

include ROOT . '/vendor/autoload.php';
include __DIR__.'/init.php';


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
