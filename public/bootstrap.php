<?php
define('ROOT', realpath(__DIR__ . '/../'));
define('VIEW',  ROOT . '/views');
define('CTRL',  ROOT . '/controllers');
define('CACHE', ROOT . '/cached');

global $config;
$config = json_decode(file_get_contents(ROOT.'/config.json'));
define('POST',  !empty($config->post_dir) ? $config->post_dir : ROOT . '/posts');

if (isset($config->debug) && $config->debug) {
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

include ROOT . '/vendor/autoload.php';
include __DIR__.'/functions.php';
include __DIR__.'/init.php';
