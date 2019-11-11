<?php
define('ROOT', realpath(__DIR__ . '/../'));
define('CTRL',  ROOT . '/controllers');
define('CACHE', ROOT . '/cached');
define('LIBRARIES', ROOT . '/libraries');


require_once LIBRARIES . '/sample.php';
require_once LIBRARIES . '/config.php';
require_once LIBRARIES . '/post.php';

unset($file);
unset($files);

global $config;
$cf = json_decode(file_get_contents(ROOT.'/config.json'), true);
if ($cf === null) {
	echo "Invalid config format<br/>";
	readfile(ROOT.'/config.json');
	exit;
}
$config = new Config($cf);
unset($cf);

define('POST',  $config->get('post_dir', '/posts'));

if (!is_dir(POST)) {
	echo "Post dir: ", POST, " not found";exit;
}
if (isset($config->debug) && $config->debug) {
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

define('VIEW',  ROOT . "/themes/{$config->theme}");


include ROOT . '/vendor/autoload.php';
include __DIR__.'/functions.php';
include __DIR__.'/init.php';
