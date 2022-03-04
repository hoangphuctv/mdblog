<?php
define('PROJECT_ROOT',       getcwd());
define('ROOT',       realpath(__DIR__ . '/..'));
define('CTRL',       ROOT . '/controllers');
define('CACHE',      ROOT . '/cached');
define('LIBRARIES',  ROOT . '/libraries');
define('PUBLICPATH',     ROOT . '/public');
define('STATICPATH',     ROOT . '/static');

check_system();

require_once LIBRARIES . '/sample.php';
require_once LIBRARIES . '/config.php';
require_once LIBRARIES . '/post.php';

unset($file);
unset($files);

global $config;
$cf = json_decode(file_get_contents(PROJECT_ROOT.'/config.json'), true);
if ($cf === null) {
	echo "Invalid config format<br/>";
	readfile(PROJECT_ROOT.'/config.json');
	exit;
}
$config = new Config($cf);
unset($cf);

define('POST',  PROJECT_ROOT);

if (!is_dir(POST)) {
	echo "Post dir: ", POST, " not found";exit;
}
if (isset($config->debug) && $config->debug) {
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

define('VIEW',  ROOT . "/themes/{$config->theme}");


include_once ROOT . '/vendor/autoload.php';
include_once __DIR__.'/functions.php';
include_once __DIR__.'/init.php';


function check_system() {
	if (!function_exists('mb_strtolower')) {
		echo 'mb_strtolower function not found, please install extendsion mbstring' . PHP_EOL;
		exit;
	}
}