<?php
define('ROOT', realpath(__DIR__ . '/../'));
define('CTRL',  ROOT . '/controllers');
define('CACHE', ROOT . '/cached');
define('LIBRARIES', ROOT . '/libraries');


require_once LIBRARIES . '/Sample.php';
require_once LIBRARIES . '/Config.php';

unset($file);
unset($files);

global $config;
$config = new Config(json_decode(file_get_contents(ROOT.'/config.json')));

define('POST',  $config->get('post_dir', '/posts'));

if (isset($config->debug) && $config->debug) {
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

define('VIEW',  ROOT . "/themes/{$config->theme}");


include ROOT . '/vendor/autoload.php';
include __DIR__.'/functions.php';
include __DIR__.'/init.php';
